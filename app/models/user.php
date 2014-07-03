<?php
class User extends AppModel{
    public $validation = array(
        'Username' => array(
            'length' => array(
                'validate_between' , 1, 16
                ),
            'format' => array(
                'valid_username'
                ),
            ),
        'Password' => array(
            'length' => array(
                'validate_between' , 1, 16
                ),
            ),
        );
    public function authenticate(){
        $this->validation['Username']['format'][] = $this->Username;
        $this->validate();
        if($this->hasError()){
            throw new Exception('Invalid Username/Password');
        }else{
            $db = DB::conn();
            $rec = $db->rows('Select * FROM users WHERE uname = ? AND pword = ?', 
            array($this->Username,sha1($this->Password)));
            if(!$rec){
                throw new Exception('Username/Password is incorrect');
            }
                return $rec;
        }
    }
}