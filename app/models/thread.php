<?php
class Thread extends AppModel
{
    const THREAD_TABLE = "thread";

    public $validation = array(
        "title" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH,
            ),
        )
    );

    /**
     *RETURNS ALL THREADS IN DATABASE
     *@param $limit
     */
    public static function getAll($limit)
    {
        $db = DB::conn();
        $threads = array();
        $query = "SELECT t.*, c.uname FROM " . self::THREAD_TABLE . " t 
        INNER JOIN " . User::USERS_TABLE . " c WHERE t.user_created = c.id ORDER BY created DESC LIMIT " . $limit;
        $rows = $db->rows($query);
        foreach ($rows as $row) {
            $threads[] = new self($row);
        }
        return $threads;
    }

    /**
     *REGISTERS A SPECIFIC THREAD
     *@param $thread_id
     */
    public static function get($thread_id)
    {
        $db = DB::conn();
        $query = "SELECT * FROM " . self::THREAD_TABLE . " WHERE id = ?";
        $where_params = array($thread_id);
        $row = $db->row($query, $where_params);
        return new self($row);
    }

    /**
     *RETURNS TOTAL NUMBER OF THREADS
     */
    public static function getNumRows()
    {
        $db = DB::conn();
        $query = "SELECT COUNT(*) FROM " . self::THREAD_TABLE;
        $count = $db->value($query);
        return $count;            
    }

    /**
     *CREATES A NEW THREAD WITH A COMMENT
     *@param $comment
     *@throws ValidationException
     */
    public function create(Comment $comment)
    {
        if (!$this->validate() || !$comment->validate()) {
            throw new ValidationException("invalid thread or comment");
        }
        $db = DB::conn();
        $db->begin();
            $set_params = array(
                "title" => $this->title, 
                "user_created" => $this->user_id
                );
            $db->insert(self::THREAD_TABLE, $set_params);
            $this->id = $db->lastInsertId();
            $comment->thread_id = $this->id;
            // write first comment at the same time
            $comment->create();
        $db->commit();
        return $this->id;
    }

    /**
     *EDITS A THREAD AND ADDS A COMMENT FOR IT
     *@param $comment
     *@throws ValidationException
     */
    public function update(Comment $comment)
    {
        if (!$this->validate() || !$comment->validate()) {
            throw new ValidationException("invalid thread or comment");
        }
        $db = DB::conn();
        $db->begin();
        $set_params = array("title" => $this->title);
        $where_params = array("id" => $this->id);
        $db->update(self::THREAD_TABLE, $set_params, $where_params);
        $comment->thread_id = $this->id;
        // write first comment at the same time
        $comment->create();
        $db->commit();
    }
    
    /**
     *DELETES A THREAD AND ALL OF IT"S COMMENTS 
     */
    public function delete(){
        $db = DB::conn();
        $thread_query = "DELETE FROM " . self::THREAD_TABLE . " WHERE id = ?";
        $comment_query = "DELETE FROM " . Comment::COMMENT_TABLE . " WHERE thread_id = ?";
        $where_params = array($this->id);
        $db->query($thread_query, $where_params);    
        $db->query($comment_query, $where_params);    
    }
}
