<?php
class Thread extends AppModel
{
    const THREAD_TABLE = 'threads';

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
        $select = 'SELECT t.*, u.username FROM '.self::THREAD_TABLE.' t INNER JOIN '.User::USERS_TABLE.' u
        ON t.user_id = u.user_id';
        $where = null;
        $order_limit = "ORDER BY {$order} LIMIT {$limit}";
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
