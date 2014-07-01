<?php
class LoginController extends AppController
{
    public function index(){
    $login = new Login;
    $username = Param::get('username');
    $password = Param::get('password');
    $login->Username = $username;
    $login->Password = $password;
    $filter_uname = valid_username($username);
    try {
        if(!$filter_uname){
            $status = "Username is Invalid";
        }else{
            $result = $login->verifyLogin();
            $status = $result['status'];
        }
    } catch (ValidationException $e) {
        $status = "Username and Password Length should be between " .
        $login->validation['Username']['length'][1]
        . " and " .
        $login->validation['Password']['length'][2];
    }
    $this->set(get_defined_vars());
    }
}