<?php
class Comment extends AppModel
{
    const COMMENT_TABLE = 'comments';
    
    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between' , MIN_LENGTH, MAX_TEXT_LENGTH
            ),
            'format' => array(
                'have_spaces'
            )
        )
    );

    /**
     *RETURNS ALL COMMENTS OF A THREAD
     *@param $limit
     */
    public function getAll($limit, $filter, $user_id)
    {
        $comments = array();
        $db = DB::conn();
        $select = 'SELECT c.*, u.* FROM '.self::COMMENT_TABLE.' c INNER JOIN '.User::USERS_TABLE.' u ON 
            c.user_id = u.user_id';
        $where = 'WHERE c.thread_id = ?';
        $where_params = array($this->thread_id);
        $order_limit = 'ORDER BY created DESC LIMIT '.$limit;
        switch ($filter) {
            case 'My Comments':
                $where .= ' AND u.user_id = ?';
                $where_params = array($this->thread_id, $user_id);
                break;
            case 'Other people\'s Comments':
                $where .= ' AND u.user_id != ?';
                $where_params = array($this->thread_id, $user_id);
                break;
            default:
                break;
        }
        $query = "{$select} {$where} {$order_limit}";       
        $rows = $db->rows($query, $where_params);
        foreach ($rows as $row) {
            $comments[] = new self($row);
        }
        return $comments;
    }

    /**
     *RETURNS A SPECIFIC COMMENT
     *@param $comment_id
     */
    public static function get($comment_id)
    {
        $comments = array();
        $db = DB::conn();
        $query = 'SELECT * FROM '.self::COMMENT_TABLE.' WHERE comment_id = ?';
        $where_params = array($comment_id);
        $row = $db->row($query, $where_params);
        return new self ($row);
    }

    /**
     *RETURNS TOTAL NUMBER OF COMMENTS
     */
    public function count($filter, $user_id)
    {
        $db = DB::conn();
        $select = 'SELECT COUNT(*) FROM '.self::COMMENT_TABLE;        
        $where = 'WHERE thread_id = ?';
        $where_params = array($this->thread_id);
        switch ($filter) {
            case 'My Comments':
                $where .= ' AND u.user_id = ?';
                $where_params = array($this->thread_id, $user_id);
                break;
            case 'Other people\'s Comments':
                $where .= ' AND u.user_id != ?';
                $where_params = array($this->thread_id, $user_id);
                break;
            default:
                break;
        }
        $query = "{$select} {$where}";
        $count = $db->value($query, $where_params);
        return $count;            
    }

    /**
     *SAVE CHANGES MADE TO A COMMENT
     *@throws ValidationException
     */
    public function update()
    {
        $this->validation['body']['format'][] = $this->body;
        if (!$this->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        $set_params = array('body' => $this->body, 'updated' => date('Y-m-d H:i:s'));
        $where_params = array('comment_id' => $this->comment_id);
        $db->update(self::COMMENT_TABLE, $set_params, $where_params);
    }

    /**
     *CREATES A NEW COMMENT
     *@throws ValidationException
     */
    public function create()
    {
        $this->validation['body']['format'][] = $this->body;
        if (!$this->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        $set_params = array(
            'thread_id' => $this->thread_id, 
            'user_id' => $this->user_id, 
            'body' => $this->body
        );
        $db->insert(self::COMMENT_TABLE, $set_params);
    }
    
    /**
     *DELETES A COMMENT
     */
    public function delete()
    {
        $db = DB::conn();
        $query = 'DELETE FROM '.self::COMMENT_TABLE.' WHERE comment_id = ?';
        $where_params = array($this->comment_id);
        $db->query($query, $where_params);
    }
}