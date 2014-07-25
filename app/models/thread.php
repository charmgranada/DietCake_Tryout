<?php
class Thread extends AppModel
{
    public $validation = array(
        'title' => array(
            'length' => array(
                'validate_between', MIN_LENGTH, MAX_LENGTH,
            )
        ),
        'description' => array(
            'length' => array(
                'validate_between', MIN_LENGTH, MAX_TEXT_LENGTH,
            ),
            'format' => array(
                'have_spaces'
            )
        )
    );

    /**
     *RETURNS ALL THREADS IN DATABASE
     *@param $limit
     */
    public static function getAll($limit, $search, $filter, $order, $user_id)
    {
        switch ($order) {
            case 'Least Likes':
                $order = 'like_ctr';
                break;
            case 'Most Likes':
                $order = 'like_ctr DESC';
                break;
            case 'Least Comments':
                $order = 'comment_ctr';
                break;
            case 'Most Comments':
                $order = 'comment_ctr DESC';
                break;
            case 'Oldest First':
                $order = 'created';
                break;            
            default:
                $order = 'created DESC';
                break;
        }
        $db = DB::conn();
        $threads = array();
        // TABLE JOINS
        $comments_tbl_join = 'comments c RIGHT JOIN threads t ON t.thread_id = c.thread_id';
        $users_tbl_join = 'INNER JOIN users u ON t.user_id = u.user_id';
        $thread_likes_tbl_join = 'LEFT OUTER JOIN thread_likes l ON l.thread_id = t.thread_id 
            AND l.like_status = 1';
        // END OF TABLE JOINS
        $select = "SELECT  t.*, u.username, COUNT(c.comment_id) AS comment_ctr, COUNT(l.user_id) AS 
            like_ctr FROM {$comments_tbl_join} {$users_tbl_join} {$thread_likes_tbl_join}";
        $where = null;
        $order_limit = "GROUP BY user_id ORDER BY {$order} LIMIT {$limit}";
        if ($search) {
            $where = "WHERE {$search}";
        }
        switch ($filter) {
            case 'My Threads':
                $where .= ' AND u.user_id = ?';
                break;
            case 'Threads I commented':
                $where .= ' AND t.thread_id = ANY (SELECT DISTINCT t.thread_id FROM threads t 
                    INNER JOIN comments c ON t.thread_id = c.thread_id WHERE c.user_id = ?)';
                break;
            case 'Other people\'s Threads':
                $where .= ' AND u.user_id != ?';
                break;
            default:
                break;
        }
        $rows = $db->rows("{$select} {$where} {$order_limit}", array($user_id));
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
     */
    public static function count($search, $filter, $user_id)
    {
        $db = DB::conn();
        $select = 'SELECT COUNT(*) FROM threads t INNER JOIN users u ON t.user_id = u.user_id';
        $where = null;
        switch ($filter) {
            case 'My Threads':
                $where = 'WHERE u.user_id = ?';
                break;
            case 'Threads I commented':
                $where = 'WHERE t.thread_id = ANY (SELECT DISTINCT t.thread_id FROM threads t 
                    INNER JOIN comments c ON t.thread_id = c.thread_id WHERE c.user_id = ?)';
                break;
            case 'Other people\'s Threads':
                $where = 'WHERE u.user_id != ?';
                break;
            default:
                break;
        }
        if ($search) {            
            $search = "AND {$search}";
        }
        $count = $db->value("{$select} {$where} {$search}", array($user_id));
        return $count;            
    }
    
    /**
     *ADD A LIKE OR DISLIKE FOR A THREAD
     *@param $like_status
     */
    public function addLikeDislike($like_status)
    {
        $like_status = mysql_real_escape_string($like_status);
        $db = DB::conn();
        $db->begin();
        $user_has_record = $db->row('SELECT * FROM thread_likes WHERE thread_id = ? AND user_id = ?', 
            array($this->thread_id, $this->user_id)); 
        if ($user_has_record) {
            if ($user_has_record['like_status'] != $like_status) {
                // CHANGE LIKE STATUS IF USERS PREVIOUS LIKE STATUS IS DIFFERENT
                $db->update('thread_likes', array('like_status' => $like_status), 
                    array('thread_id' => $this->thread_id, 'user_id' => $this->user_id));
            } else {
                // DELETE LIKE STATUS IF USERS PREVIOUS LIKE STATUS IS THE SAME
                $db->query('DELETE FROM thread_likes WHERE thread_id = ? AND user_id = ? AND like_status = ?', 
                    array($this->thread_id, $this->user_id, $like_status));                
            }
        } else {
            $db->insert('thread_likes', array(
                'thread_id' => $this->thread_id,
                'user_id' => $this->user_id,
                'like_status' => $like_status
            ));
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
     *CREATES A NEW THREAD WITH A COMMENT
     *@throws ValidationException
     */
    public function create()
    {
        $this->validation['description']['format'][] = $this->description;
        if (!$this->validate()) {
            throw new ValidationException('invalid title or description');
        }
        $db = DB::conn();
        $db->insert('threads', array(
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description
        ));
        return $db->lastInsertId();
    }

    /**
     *EDITS A THREAD AND ADDS A COMMENT FOR IT
     *@param $comment
     *@throws ValidationException
     */
    public function update()
    {
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
        $db->query('DELETE FROM threads WHERE thread_id = ?', array($this->thread_id));    
        $db->query('DELETE FROM comments WHERE thread_id = ?', array($this->thread_id));    
    }
}
