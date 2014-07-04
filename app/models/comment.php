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
        public function getComments($id, $limit)
            {
            $comments = array();
            $db = DB::conn();
            $query = "SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC {$limit}";
            $rows = $db->rows($query,array($id));
            foreach ($rows as $row) {
                $comments[] = new Comment($row);
            }
            return $comments;
        }
        public static function getTotalCount($id){
            $db = DB::conn();
            $rows = $db->rows(
            'SELECT COUNT(*) FROM comment WHERE thread_id = ?',
            array($id)
            );
            return $rows[0]["COUNT(*)"];            
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