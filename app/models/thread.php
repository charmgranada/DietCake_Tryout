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

        /**
         *RETURNS ALL THREADS IN DATABASE
         */
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

        /**
         *REGISTERS A SPECIFIC THREAD
         *@param $id
         */
        public static function get($id)
        {
            $db = DB::conn();
            $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
            return new self($row);
        }

        /**
         *CREATES A NEW THREAD WITH A COMMENT
         *@param $comment
         *@throws ValidationException
         */
        public function create(Comment $comment)
        {
            $this->validate();
            $comment->validate();
            if ($this->hasError() || $comment->hasError()) {
                throw new ValidationException('invalid thread or comment');
            }
            $db = DB::conn();
            $db->begin();
                $query = 'INSERT INTO thread SET title = ?, user_created = ?, created = NOW()';
                $params = array(
                    $this->title, 
                    $this->user_id
                    );
                $db->query($query, $params);
                $newID = $db->lastInsertId();
                $this->id = $newID;
                $comment->thread_id = $newID;
                // write first comment at the same time
                $comment->write($comment);
            $db->commit();
        }

        /**
         *EDITS A THREAD AND ADDS A COMMENT FOR IT
         *@param $comment
         *@throws ValidationException
         */
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
        
        /**
         *DELETES A THREAD AND ALL OF IT'S COMMENTS 
         *@param $id
         */
        public function delete(){
            $db = DB::conn();
            $db->query(
            'DELETE FROM thread WHERE id = ?',
            array($this->id)
            );    
            $db->query(
            'DELETE FROM comment WHERE thread_id = ?',
            array($this->id)
            );    
        }
    }
?>
