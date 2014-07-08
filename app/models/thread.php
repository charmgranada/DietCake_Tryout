<?php
    class Thread extends AppModel
    {
        public $validation = array(
            'title' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_LENGTH,
                ),
            )
        );

        public static function getAll()
        {
            $threads = array();
            $db = DB::conn();
            $rows = $db->rows('SELECT * FROM thread');
            foreach ($rows as $row) {
                $threads[] = new self($row);
            }
            return $threads;
        }
        public static function get($id)
        {
            $db = DB::conn();
            $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
            return new self($row);
        }
        public function create(Comment $comment)
        {
            $this->validate();
            $comment->validate();
            if ($this->hasError() || $comment->hasError()) {
                throw new ValidationException('invalid thread or comment');
            }
            $db = DB::conn();
            $db->begin();
                $params = array(
                    'title' => $this->title, 
                    'user_created' => $this->user_id,
                    'created' => 'NOW()'
                    );
                $db->insert('thread',$params);
                $newID = $db->lastInsertId();
                $this->id = $newID;
                $comment->thread_id = $newID;
                // write first comment at the same time
                $comment->write($comment);
            $db->commit();
        }
        public function edit(Comment $comment)
        {
            $this->validate();
            $comment->validate();
            if ($this->hasError() || $comment->hasError()) {
                throw new ValidationException('invalid thread or comment');
            }
            $db = DB::conn();
            $db->begin();
            $db->query('UPDATE thread SET title = ? WHERE id = ?', 
                array($this->title, $this->id));
            $comment->thread_id = $this->id;
            // write first comment at the same time
            $comment->write($comment);
            $db->commit();
        }
        public static function delete($id){
            $db = DB::conn();
            $db->query(
            'DELETE FROM thread WHERE id = ?',
            array($id)
            );    
            $db->query(
            'DELETE FROM comment WHERE thread_id = ?',
            array($id)
            );    
        }
    }
?>
