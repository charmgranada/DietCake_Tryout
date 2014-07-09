<?php
    class Thread extends AppModel
    {
        const table = 'thread';
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
            $db = DB::conn();
            $threads = array();
            $query = 'SELECT * FROM thread';
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
            $query = 'SELECT * FROM thread WHERE id = ?';
            $where_params = array($thread_id);
            $row = $db->row($query, $where_params);
            return new self($row);
        }

        /**
         *CREATES A NEW THREAD WITH A COMMENT
         *@param $comment
         *@throws ValidationException
         */
        public function createNew(Comment $comment)
        {
            if (!$this->validate() || !$comment->validate()) {
                throw new ValidationException('invalid thread or comment');
            }
            $db = DB::conn();
            $db->begin();
                $set_params = array(
                    'title' => $this->title, 
                    'user_created' => $this->user_id,
                    'created' => date('Y-m-d h:i:s')
                    );
                $db->insert(self::table, $set_params);
                $this->id = $db->lastInsertId();
                $comment->thread_id = $this->id;
                // write first comment at the same time
                $comment->createNew();
            $db->commit();
        }

        /**
         *EDITS A THREAD AND ADDS A COMMENT FOR IT
         *@param $comment
         *@throws ValidationException
         */
        public function setTitle(Comment $comment)
        {
            if (!$this->validate() || !$comment->validate()) {
                throw new ValidationException('invalid thread or comment');
            }
            $db = DB::conn();
            $db->begin();
            $set_params = array('title' => $this->title);
            $where_params = array('id' => $this->id);
            $db->update(self::table, $set_params, $where_params);
            $comment->thread_id = $this->id;
            // write first comment at the same time
            $comment->createNew();
            $db->commit();
        }
        
        /**
         *DELETES A THREAD AND ALL OF IT'S COMMENTS 
         *@param $id
         */
        public function delete(){
            $db = DB::conn();
            $thread_query = 'DELETE FROM thread WHERE id = ?';
            $comment_query = 'DELETE FROM comment WHERE thread_id = ?';
            $where_params = array($this->id);
            $db->query($thread_query, $where_params);    
            $db->query($comment_query, $where_params);    
        }
    }
?>
