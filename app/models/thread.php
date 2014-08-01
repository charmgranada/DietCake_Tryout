<?php
class Thread extends AppModel
{
    // FOR FILTERING THREADS
    const ALL_THREADS = 'All Threads';
    const MY_THREADS = 'My Threads';
    const COMMENTED_THREADS = 'Threads I commented';
    const THEIR_THREADS = 'Other people\'s Threads';
    // FOR SORTING THREADS
    const LATEST_FIRST = 'Latest First';
    const OLDEST_FIRST = 'Oldest First';
    const MOST_COMMENTS = 'Most Comments';
    const LEAST_COMMENTS = 'Least Comments';
    const MOST_LIKES = 'Most Likes';
    const LEAST_LIKES = 'Least Likes';
    // FOR CHOOSING A SEARCH OPTION
    const SEARCH_BY_THREAD = 'Thread';
    const SEARCH_BY_USER = 'User';

    public $validation = array(
        'title' => array(
            'length' => array(
                'validate_between', MIN_LENGTH, MAX_LENGTH,
            ),
            'format' => array(
                'is_valid_num_spaces'
            )
        ),
        'description' => array(
            'length' => array(
                'validate_between', MIN_LENGTH, MAX_TEXT_LENGTH,
            ),
            'format' => array(
                'is_valid_num_spaces'
            ),
        )
    );

    /**
     *FILTER FUNCTION FOR THREADS
     *@param $filter, $title, $user_id
     */
    public static function filter($filter, $title, $user_id)
    {
        $where = "WHERE t.title LIKE ?";
        $where_params = array("%{$title}%", $user_id);
        switch($filter) {
            case self::MY_THREADS:
                $where .= ' AND u.user_id = ?';
                break;
            case self::COMMENTED_THREADS:
                $where .= ' AND t.thread_id = ANY (SELECT DISTINCT t.thread_id FROM threads t 
                    INNER JOIN comments c ON t.thread_id = c.thread_id WHERE c.user_id = ?)';
                break;
            case self::THEIR_THREADS:
                $where .= ' AND u.user_id != ?';
                break;
            default:
                $where_params = array("%{$title}%");
                break;
        }
        return array($where, $where_params);
    }

    /**
     *RETURNS ALL THREADS IN DATABASE
     *@param $limit, $search, $filter, $order, $user_id
     */
    public static function getAll($limit, $search, $filter, $order, $user_id)
    {
        switch ($order) {
            case self::LEAST_LIKES:
                $order = 'l.like_ctr';
                break;
            case self::MOST_LIKES:
                $order = 'l.like_ctr DESC';
                break;
            case self::LEAST_COMMENTS:
                $order = 'comment_ctr';
                break;
            case self::MOST_COMMENTS:
                $order = 'comment_ctr DESC';
                break;
            case self::OLDEST_FIRST:
                $order = 'updated';
                break;            
            default:
                $order = 'updated DESC';
                break;
        }
        $db = DB::conn();
        $select = "SELECT t.*, u.username, COUNT(c.thread_id) AS comment_ctr, l.like_ctr
            FROM threads t
            INNER JOIN users u ON t.user_id = u.user_id
            LEFT OUTER JOIN comments c ON c.thread_id = t.thread_id
            LEFT OUTER JOIN 
                (SELECT t.thread_id, COUNT(*) AS like_ctr FROM threads t 
                    INNER JOIN thread_likes l ON l.thread_id = t.thread_id 
                    WHERE l.like_status=1 GROUP BY t.thread_id) l ON l.thread_id = t.thread_id";
        list($where, $where_params) = self::filter($filter, $search, $user_id);
        $order_limit = "GROUP BY t.thread_id ORDER BY {$order} LIMIT {$limit}";
        $rows = $db->rows("{$select} {$where} {$order_limit}", $where_params);
        $threads = array();
        foreach ($rows as $row) {
            $threads[] = new self($row);
        }
        return $threads;
    }

    /**
     *RETURNS A SPECIFIC THREAD
     *@param $thread_id
     */
    public static function get($thread_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM threads WHERE thread_id = ?', array($thread_id));
        return new self($row);
    }

    /**
     *RETURNS TOTAL NUMBER OF THREADS
     *@param $search, $filter, $user_id
     */
    public static function count($search, $filter, $user_id)
    {
        $db = DB::conn();
        list($where, $where_params) = self::filter($filter, $search, $user_id);
        $count = $db->value("SELECT COUNT(*) FROM threads t 
            INNER JOIN users u ON t.user_id = u.user_id 
            {$where}", $where_params);
        return $count;            
    }
    
    /**
     *ADD A LIKE OR DISLIKE FOR A THREAD
     *@param $like_status
     */
    public function addLikeDislike($like_status)
    {
        $db = DB::conn();
        $db->begin();
        $user_has_record = $db->row('SELECT * FROM thread_likes WHERE thread_id = ? AND user_id = ?', 
            array($this->thread_id, $this->user_id)); 
        if ($user_has_record) {
            // CHANGE LIKE STATUS IF USER'S PREVIOUS LIKE STATUS IS DIFFERENT
            // DELETE LIKE STATUS IF USER'S PREVIOUS LIKE STATUS IS THE SAME
            if ($user_has_record['like_status'] != $like_status) {
                $db->update('thread_likes', array('like_status' => $like_status), 
                    array('thread_id' => $this->thread_id, 'user_id' => $this->user_id));
            } else {
                $db->query('DELETE FROM thread_likes WHERE thread_id = ? AND user_id = ? AND like_status = ?', 
                    array($this->thread_id, $this->user_id, $like_status));                
            }
        } else {
            $params = array(
                'thread_id' => $this->thread_id,
                'user_id' => $this->user_id,
                'like_status' => $like_status
            );
            $db->insert('thread_likes', $params);
        }   
        $db->commit();
    }
    
    /**
     *GET A THREAD'S NUMBER OF LIKES 
     */
    public function countLikes()
    {
        $db = DB::conn();
        $likes = $db->value('SELECT COUNT(*) FROM thread_likes WHERE thread_id = ? AND like_status = 1', 
            array($this->thread_id));    
        return $likes;
    }
    
    /**
     *GET A THREAD'S NUMBER OF DISLIKES 
     */
    public function countDislikes()
    {
        $db = DB::conn();
        $dislikes = $db->value('SELECT COUNT(*) FROM thread_likes WHERE thread_id = ? AND like_status = 0', 
            array($this->thread_id));    
        return $dislikes;
    }

    /**
     *CREATES A NEW THREAD
     *@throws ValidationException
     */
    public function create()
    {
        $this->validation['title']['format'][] = $this->title;
        $this->validation['description']['format'][] = $this->description;
        if (!$this->validate()) {
            throw new ValidationException('invalid title or description');
        }
        $db = DB::conn();
        $params = array(
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'updated' => date('Y-m-d H:i:s')
        );
        $db->insert('threads', $params);
        return $db->lastInsertId();
    }

    /**
     *EDIT A THREAD
     *@throws ValidationException
     */
    public function update()
    {
        $this->validation['title']['format'][] = $this->title;
        $this->validation['description']['format'][] = $this->description;
        if (!$this->validate()) {
            throw new ValidationException('invalid thread or comment');
        }
        $db = DB::conn();
        $set_params = array(
            'title' => $this->title, 
            'description' => $this->description, 
            'updated' => date('Y-m-d H:i:s')
            );
        $db->update('threads', $set_params, array('thread_id' => $this->thread_id));
    }
    
    /**
     *DELETES A THREAD AND ALL OF IT'S COMMENTS 
     */
    public function delete(){
        $db = DB::conn();
        $db->begin();
        $db->query('DELETE FROM threads WHERE thread_id = ?', array($this->thread_id));    
        $db->query('DELETE FROM comments WHERE thread_id = ?', array($this->thread_id));  
        $db->commit();  
    }
}
