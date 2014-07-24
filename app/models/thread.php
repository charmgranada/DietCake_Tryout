<?php
class Thread extends AppModel
{
    const THREAD_TABLE = 'threads';
    const THREAD_LIKES_TABLE = 'thread_likes';

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
                $order = 'like_ctr ASC';
                break;
            case 'Most Likes':
                $order = 'like_ctr DESC';
                break;
            case 'Least Comments':
                $order = 'comment_ctr ASC';
                break;
            case 'Most Comments':
                $order = 'comment_ctr DESC';
                break;
            case 'Oldest First':
                $order = 'created ASC';
                break;            
            default:
                $order = 'created DESC';
                break;
        }
        $db = DB::conn();
        $threads = array();
        $comments_tbl_join = Comment::COMMENT_TABLE.' c RIGHT JOIN '.self::THREAD_TABLE.' t ON 
            t.thread_id = c.thread_id';
        $users_tbl_join = 'INNER JOIN '.User::USERS_TABLE.' u ON t.user_id = u.user_id';
        $thread_likes_tbl_join = 'LEFT OUTER JOIN '.self::THREAD_LIKES_TABLE.' l ON l.thread_id = t.thread_id AND 
            l.user_id = u.user_id AND l.like_status = 1';
        $select = "SELECT  DISTINCT t.*, u.username, COUNT(c.comment_id) AS comment_ctr, COUNT(l.thread_id) AS 
                    like_ctr FROM {$comments_tbl_join} {$users_tbl_join} {$thread_likes_tbl_join}";
        $where = null;
        $order_limit = "GROUP BY thread_id ORDER BY {$order} LIMIT {$limit}";
        if ($search) {
            $where = "WHERE {$search}";
        }
        if ($filter != 'All Threads'){
            if ($filter == 'My Threads') {
                $where .= ' AND u.user_id = ?';
            } elseif ($filter == 'Threads I commented') {
                $where .= ' AND t.thread_id = ANY (SELECT DISTINCT t.thread_id FROM ' .self::THREAD_TABLE. ' t 
                    INNER JOIN ' .Comment::COMMENT_TABLE. ' c ON t.thread_id = c.thread_id WHERE c.user_id = ?)';
            } elseif ($filter == 'Other people\'s Threads') {
                $where .= ' AND u.user_id != ?';
            }
        }
        $query = "{$select} {$where} {$order_limit}";
        $rows = $db->rows($query, array($user_id));
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
        $query = 'SELECT * FROM ' .self::THREAD_TABLE. ' WHERE thread_id = ?';
        $where_params = array($thread_id);
        $row = $db->row($query, $where_params);
        return new self($row);
    }

    /**
     *RETURNS TOTAL NUMBER OF THREADS
     */
    public static function getNumRows($search, $filter, $user_id)
    {
        $db = DB::conn();
        $select = 'SELECT COUNT(*) FROM ' .self::THREAD_TABLE. ' t INNER JOIN ' .User::USERS_TABLE. ' u ON 
            t.user_id = u.user_id';
        $query = $select;
        if ($search) {            
            $query = "{$select} WHERE {$search}";
        }
        if ($filter != 'All Threads'){
            if ($filter == 'My Threads') {
                $where = 'WHERE u.user_id = ?';
            } elseif ($filter == 'Threads I commented') {
                $where = 'WHERE t.thread_id = ANY (SELECT DISTINCT t.thread_id FROM ' .self::THREAD_TABLE. ' t 
                    INNER JOIN ' .Comment::COMMENT_TABLE. ' c ON t.thread_id = c.thread_id WHERE c.user_id = ?)';
            } elseif ($filter == 'Other people\'s Threads') {
                $where = 'WHERE u.user_id != ?';
            }
            $query = "{$select} {$where} AND {$search}";
        }
        $count = $db->value($query, array($user_id));
        return $count;            
    }
    
    /**
     *ADD A LIKE OR DISLIKE FOR A THREAD
     */
    public function addLikeDislike($like_status)
    {
        $like_status = mysql_real_escape_string($like_status);
        $db = DB::conn();
        $db->begin();
        $query = 'SELECT * FROM ' .self::THREAD_LIKES_TABLE. ' WHERE thread_id = ? AND user_id = ?';
        $where_params = array($this->thread_id, $this->user_id);
        $user_has_record = $db->row($query, $where_params); 
        if ($user_has_record) {
            if ($user_has_record['like_status'] != $like_status) {
                $set_params = array (
                    'like_status' => $like_status
                );
                $where_params = array (
                    'thread_id' => $this->thread_id,
                    'user_id' => $this->user_id
                );
                $db->update(self::THREAD_LIKES_TABLE, $set_params, $where_params);
            } else {
                $query = 'DELETE FROM ' .self::THREAD_LIKES_TABLE. ' WHERE thread_id = ? AND user_id = ? 
                    AND like_status = ?';
                $where_params = array($this->thread_id, $this->user_id, $like_status);
                $db->query($query, $where_params);                
            }
        } else {
            $set_params = array (
                'thread_id' => $this->thread_id,
                'user_id' => $this->user_id,
                'like_status' => $like_status
            );
            $db->insert(self::THREAD_LIKES_TABLE, $set_params);
        }   
        $db->commit();
    }
    
    /**
     *GET A THREAD'S NUMBER OF LIKES 
     */
    public function getLikes()
    {
        $db = DB::conn();
        $query = 'SELECT COUNT(*) FROM ' .self::THREAD_LIKES_TABLE. ' WHERE thread_id = ? AND like_status = 1';
        $where_params = array($this->thread_id);
        $likes = $db->value($query, $where_params);    
        return $likes;
    }
    
    /**
     *GET A THREAD'S NUMBER OF DISLIKES 
     */
    public function getDislikes()
    {
        $db = DB::conn();
        $query = 'SELECT COUNT(*) FROM ' .self::THREAD_LIKES_TABLE. ' WHERE thread_id = ? AND like_status = 0';
        $where_params = array($this->thread_id);
        $dislikes = $db->value($query, $where_params);    
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
        $set_params = array(
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description
        );
        $db->insert(self::THREAD_TABLE, $set_params);
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
        $set_params = array('title' => $this->title, 'description' => $this->description);
        $where_params = array('thread_id' => $this->thread_id);
        $db->update(self::THREAD_TABLE, $set_params, $where_params);
    }
    
    /**
     *DELETES A THREAD AND ALL OF IT'S COMMENTS 
     */
    public function delete(){
        $db = DB::conn();
        $thread_query = 'DELETE FROM ' .self::THREAD_TABLE. ' WHERE thread_id = ?';
        $comment_query = 'DELETE FROM ' .Comment::COMMENT_TABLE. ' WHERE thread_id = ?';
        $where_params = array($this->thread_id);
        $db->query($thread_query, $where_params);    
        $db->query($comment_query, $where_params);    
    }
}
