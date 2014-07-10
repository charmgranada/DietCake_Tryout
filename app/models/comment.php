<?php
    class Comment extends AppModel
    {
        const TABLE = 'comment';
        public $validation = array(
            'body' => array(
                'length' => array(
                    'validate_between' , MIN_LENGTH, MAX_TEXT_LENGTH
                ),
                'format' => array(
                    'is_valid_comment'
                )
            )
        );

        /**
         *RETURNS ALL COMMENTS OF A THREAD
         *@param $thread_id
         *@param $limit
         */
        public function getAll($limit)
        {
            $comments = array();
            $db = DB::conn();
            $where = 'thread_id = ?';
            $where_params = array($this->thread_id);
            $order = 'created DESC';
            $rows = $db->search(self::TABLE, $where, $where_params, $order, $limit);
            foreach ($rows as $row) 
            {
                $comments[] = new self($row);
            }
            return $comments;
        }

        /**
         *RETURNS A SPECIFIC COMMENT
         */
        public static function get($comment_id)
        {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT * FROM comment WHERE id = ?";
            $where_params = array($comment_id);
            $row = $db->row($query, $where_params);
            return new self ($row);
        }

        /**
         *RETURNS TOTAL NUMBER OF COMMENTS
         *@param $id
         */
        public function getNumRows()
        {
            $db = DB::conn();
            $query = 'SELECT COUNT(*) FROM comment WHERE thread_id = ?';
            $where_params = array($this->thread_id);
            $row = $db->row($query, $where_params);
            return $row["COUNT(*)"];            
        }

        /**
         *SAVE CHANGES MADE TO A COMMENT
         *@throws ValidationException
         */
        public function setBody()
        {
            $this->validation['body']['format'][] = $this->body;
            if (!$this->validate()) {
                throw new ValidationException('invalid comment');
            }
            $db = DB::conn();
            $set_params = array(
                'body' => $this->body
                );
            $where_params = array('id' => $this->id);
            $db->update(self::TABLE, $set_params, $where_params);
        }

        /**
         *CREATES A NEW COMMENT
         *@throws ValidationException
         */
        public function createNew()
        {
            $this->validation['body']['format'][] = $this->body;
            if (!$this->validate()) {
                throw new ValidationException('invalid comment');
            }
            $db = DB::conn();
            $set_params = array(
                'thread_id' => $this->thread_id, 
                'username' => $this->username, 
                'body' => $this->body
                );
            $db->insert(self::TABLE, $set_params);
        }
        
        /**
         *DELETES A COMMENT
         */
        public function delete()
        {
            $db = DB::conn();
            $query = 'DELETE FROM comment WHERE id = ?';
            $where_params = array($this->id);
            $db->query($query, $where_params);    
        }
    }
?>