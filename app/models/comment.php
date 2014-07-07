<?php

    class Comment extends AppModel{
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
        public function getAll($id, $limit)
            {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT * FROM comment WHERE thread_id = ? ORDER BY created DESC {$limit}";
            $rows = $db->rows($query,array($id));
            foreach ($rows as $row) {
                $comments[] = new Comment($row);
            }
            return $comments;
        }
        public function getComment()
            {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT body FROM comment WHERE id = ?";
            $row = $db->rows($query,array($this->id));
            $this->body = $row[0]['body'];
        }
        public static function getTotalCount($id){
            $db = DB::conn();
            $rows = $db->rows(
            'SELECT COUNT(*) FROM comment WHERE thread_id = ?',
            array($id)
            );
            return $rows[0]["COUNT(*)"];            
        }
        public function edit()
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
        public function write()
        {
            $this->validation['body']['format'][] = $this->body;
            if (!$this->validate()) {
                throw new ValidationException('invalid comment');
            }
            $db = DB::conn();
            $db->query(
            'INSERT INTO comment SET thread_id = ?, username = ?, body = ?, created = NOW()',
            array($this->id, $this->username, $this->body)
            );
        }
        public static function delete($id){
            $db = DB::conn();
            $db->query(
            'DELETE FROM comment WHERE id = ?',
            array($id)
            );    
        }
    }
?>