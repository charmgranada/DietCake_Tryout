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
         *@param $id
         *@param $limit
         */
        public function getAll($id, $limit)
            {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT * FROM comment WHERE thread_id = ? ORDER BY created DESC {$limit}";
            $rows = $db->rows($query,array($id));
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
        public static function getTotalCount($id){
            $db = DB::conn();
            $rows = $db->rows(
            'SELECT COUNT(*) FROM comment WHERE thread_id = ?',
            array($id)
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
                $this->thread_id, 
                $this->username, 
                $this->body
                );
            $db->query($query,$params);
        }
        /**
         *DELETES A COMMENT
         */
        public static function delete($id){
            $db = DB::conn();
            $db->query(
            'DELETE FROM comment WHERE id = ?',
            array($id)
            );    
        }
    }
?>