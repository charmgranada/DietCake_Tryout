<?php    
    class User extends AppModel{
        const USERS_TABLE = "users";
        const PASS_MIN_LENGTH = 8;
        const PASS_MAX_LENGTH = 16;

        public $validation = array(
            "username" => array(
                "length" => array(
                    "validate_between" , MIN_LENGTH, MAX_LENGTH
                    ),
                "format" => array(
                    "is_valid_username"
                    ),
                ),
            "password" => array(
                "length" => array(
                    "validate_between" , self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH
                    ),
                ),
            );
        /**
         *LOGIN A REGISTERED USER
         *@param $username
         *@param $password
         *@throws ValidationException
         */
        public function authenticate($username,$password)
        {
            $this->username = $username;
            $this->password = $password;
            if (!$this->validate()) {
                throw new ValidationException("Invalid Username/Password");
            }
            $db = DB::conn();
            $query = "SELECT * FROM " . self::USERS_TABLE . " WHERE uname = ? AND pword = ?";
            $where_params = array($username, sha1($password));
            $row = $db->row($query, $where_params);
            if(!$row) {
                throw new RecordNotFoundException("Username/Password is incorrect");
            }        
            return new self ($row);
        }
        /**
         *RETURNS ALL INFO OF A USER
         */
        public static function get($id)
        {
            $db = DB::conn();
            $query = "SELECT * FROM " . self::USERS_TABLE . " WHERE id = ?";
            $where_params = array($id);
            $row = $db->row($query, $where_params); 
            return new self ($row);
        }
    }
?>