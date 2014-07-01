<?php
class Login extends AppModel{
    public $validation = array(
        'Username' => array(
            'length' => array(
                'validate_between' , 8, 16
                ),
            ),
        'Password' => array(
            'length' => array(
                'validate_between' , 8, 16
                ),
            ),
        );
    public function verifyLogin(){
        $result = array();
        $this->validate();
        if($this->hasError()){
            throw new ValidationException('Invalid Username/Password');
        }else{
            $uname = $this->Username;
            $pword = md5($this->Password);
            $db = DB::conn();
            $rows = $db->rows('Select * FROM users WHERE uname = ? AND pword = ?', 
            array($uname,$pword));
            if(count($rows) > 0){
                foreach ($rows as $key => $value) {
                    $result[$key] = $value;
                }
                $result['status'] = "Success";
            }else{
                $result['status'] = "Failed";
            }        
        }
        return $result;
    }
}