<?php
    class Comment extends AppModel
    {
        public $validation = array(
            'body' => array(
                'length' => array(
                    'validate_between' , MIN_LENGTH, MAX_TEXT_LENGTH
                ),
                'format' => array(
                    'must_fit_screen'
                )
            )
        );

        /**
         *RETURNS ALL COMMENTS OF A THREAD
         *@param $thread_id
         *@param $limit
         */
        public function getAll($thread_id, $limit)
            {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT * FROM comment WHERE thread_id = ? ORDER BY created DESC {$limit}";
            $rows = $db->rows($query,array($thread_id));
            foreach ($rows as $row) {
                $comments[] = new self($row);
            }
            return $comments;
        }

        /**
         *RETURNS A SPECIFIC COMMENT
         */
        public function getComment()
            {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT body FROM comment WHERE id = ?";
            $row = $db->rows($query,array($this->id));
            $this->body = $row[0]['body'];
        }

        /**
         *RETURNS TOTAL NUMBER OF COMMENTS
         *@param $id
         */
        public static function getTotalCount($thread_id){
            $db = DB::conn();
            $rows = $db->rows(
            'SELECT COUNT(*) FROM comment WHERE thread_id = ?',
            array($thread_id)
            );
            return $rows[0]["COUNT(*)"];            
        }

        /**
         *SAVE CHANGES MADE TO A COMMENT
         *@throws ValidationException
         */
        public function save()
        {
            $this->validation['body']['format'][] = $this->body;
            if (!$this->validate()) {
                throw new ValidationException('invalid comment');
            }
            $db = DB::conn();
            $db->query(
            'UPDATE comment SET body = ?, created = NOW() WHERE id = ?',
            array($this->body, $this->id)
            );
        }

        /**
         *CREATES A NEW COMMENT
         *@throws ValidationException
         */
        public function write()
        {
            $this->validation['body']['format'][] = $this->body;
            if (!$this->validate()) {
                throw new ValidationException('invalid comment');
            }
            $db = DB::conn();
            $query = 'INSERT INTO comment SET thread_id = ?, username = ?, body = ?, created = NOW()';
            $params = array(
                'thread_id' => $this->thread_id, 
                'username' => $this->username, 
                'body' => $this->body,
                'created' => today()
                );
            $db->insert('comment',$params);
        }
        
        /**
         *DELETES A COMMENT
         */
        public function delete(){
            $db = DB::conn();
            $db->query(
            'DELETE FROM comment WHERE id = ?',
            array($this->id)
            );    
        }
    }
?>