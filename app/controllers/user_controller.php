<?php
class UserController extends AppController
{
    public function home(){
        if(user_logged_in()===false){
            redirect('index');
        }
        $var ="This is the home page";
        $this->set(get_defined_vars());
    }

/***********************LOGIN A USER************************/
    public function index()
    {
        if(user_logged_in()===true){
            redirect('home');
        }
        $status = NULL;
        $username = Param::get('username');
        $password = Param::get('password');
        $user = new User();
        $page = Param::get('page_next','index');
        if(!isset($username) || !isset($password)){
            $status = "";
        }else if(empty($username) || empty($password)){
            $status = notice("Please fill up all fields","error");
        }else{
            try {
                $result = $user->authenticate($username, $password);
                $_SESSION['id'] = $result[0]['id'];
                $_SESSION['uname'] = $result[0]['uname'];
                redirect('home');
            } catch (Exception $e) {
                $status = notice($e->getMessage(),"error");
            }
        }
        $this->set(get_defined_vars());
    }
    
/***********************REGISTER A USER************************/
    public function registration(){
        $registration = new Registration();
        $empty = 0;
        $dataPassed = array(
            'uname', 'pword', 'cpword',
            'fname', 'mname', 'lname',
            'cnum', 'home_add', 'email_add');
        foreach($dataPassed as $field){
            $dataPassed[$field] = Param::get($field);
        }
        $errors = array();
            foreach ($dataPassed as $key => $value) {
                $errors[$key] = "";
            }
        foreach ($dataPassed as $key => $value) {
            if(empty($value)){
                $empty++;
            }else{
                $registration->$key = $value;
            }
        }
        if($empty===0){
            try {
                $status = $registration->validateData();
                $errors['uname'] = !valid_username($dataPassed['uname'])
                ? notice("Username is invalid","error") : "";
                $errors['pass'] = is_pass_match($dataPassed['pword'], $dataPassed['cpword']);
                $errors['fname'] = is_name($dataPassed['fname'],"First Name");
                $errors['mname'] = is_name($dataPassed['mname'],"Middle Name");
                $errors['lname'] = is_name($dataPassed['lname'],"Last Name");
                $errors['cnum'] = is_number($dataPassed['cnum']);
                $errors['email_add'] = is_email_address($dataPassed['email_add']);
                redirect('index');
            } catch (Exception $e) {
                foreach ($dataPassed as $key => $value) {
                    if(!empty($registration->validation_errors[$key]['length'])){
                        $errors[$key] = "<center><font size=2 color=red>Character length must be ". 
                        $registration->validation[$key]['length'][1]. " - " .
                        $registration->validation[$key]['length'][2] . " long</font>";
                    }
                }
                $status = "";
            }
        }else{
            $status = "<font color=red>Please fill up all fields</font>";
        }
        $this->set(get_defined_vars());
    }

/***********************LOGOUT ACCOUNT************************/
    public function logout()
    {
        session_unset();
        session_destroy();
        redirect('index');
    }
}