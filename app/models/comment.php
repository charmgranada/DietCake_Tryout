<?php
class Comment extends AppModel
{
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
     *FILTER FUNCTION FOR COMMENTS
     *@param $filter, $user_id
     */
    public function filter($filter, $user_id)
    {
        $where = 'WHERE c.thread_id = ?';
        $where_params = array($this->thread_id, $user_id);
        switch($filter) {
            case 'My Comments':
                $where .= ' AND u.user_id = ?';
                break;
            case 'Other people\'s Comments':
                $where .= ' AND u.user_id != ?';
                break;
            default:
                $where_params = array($this->thread_id);
                break;
        }
        return array($where, $where_params);
    }

    /**
     *RETURNS ALL COMMENTS OF A THREAD
     *@param $limit, $filter, $user_id
     */
    public function getAll($limit, $filter, $user_id)
    {
        $comments = array();
        $db = DB::conn();
        list($where, $where_params) = $this->filter($filter, $user_id);
        $rows = $db->rows("SELECT c.*, u.* FROM comments c 
            INNER JOIN users u ON c.user_id = u.user_id 
            {$where} ORDER BY created DESC LIMIT {$limit}", $where_params);
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
        $row = $db->row('SELECT * FROM comments WHERE comment_id = ?', array($comment_id));
        return new self ($row);
    }

    /**
     *RETURNS TOTAL NUMBER OF COMMENTS
     *@param $filter, $user_id
     */
    public function count($filter, $user_id)
    {
        $db = DB::conn();    
        list($where, $where_params) = $this->filter($filter, $user_id);
        $count = $db->value("SELECT COUNT(*) FROM comments c 
            INNER JOIN users u ON c.user_id = u.user_id 
            {$where}", $where_params);
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
        $db->update('comments', array('body' => $this->body, 'updated' => date('Y-m-d H:i:s')), 
            array('comment_id' => $this->comment_id));
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
        $db->insert('comments', array(
            'thread_id' => $this->thread_id, 
            'user_id' => $this->user_id, 
            'body' => $this->body
        ));
    }
    
    /**
     *DELETES A COMMENT
     */
    public function delete()
    {
        $db = DB::conn();
        $db->query('DELETE FROM comments WHERE comment_id = ?', array($this->comment_id));
    }
}