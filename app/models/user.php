<?php
    class User extends AppModel{
        public $validation = array(
            'Username' => array(
                'length' => array(
                    'validate_between' , MIN_LENGTH, MAX_LENGTH
                    ),
                'format' => array(
                    'valid_username' 
                    ),
                ),
            'Password' => array(
                'length' => array(
                    'validate_between' , PASS_MIN_LENGTH, PASS_MAX_LENGTH
                    ),
                ),
            );
        /**
         *LOGIN A REGISTERED USER
         *@param $username
         *@param $password
         *@throws ValidationException
         */
        public function authenticate($username,$password){
            $this->Username = $username;
            $this->Password = $password;
            $this->validate();
            if($this->hasError()){
                throw new ValidationException('Invalid Username/Password');
            }else{
                $db = DB::conn();
                $rows = $db->rows('SELECT * FROM users WHERE uname = ? AND pword = ?', 
                array($username,sha1($password)));
                if(!$rows){
                    throw new Exception("Username/Password is incorrect");
                }        
                return $rows;
            }
        }
        /**
         *RETURNS ALL INFO OF A USER
         */
        public function getInfo(){
            $db = DB::conn();
            $rows = $db->rows('SELECT * FROM users WHERE id = ?', 
            array($this->id)); 
            return $rows;
        }
    }
?>