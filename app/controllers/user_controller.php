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

    /* Login a User */
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

    public function logout()
    {
        session_unset();
        session_destroy();
        redirect('index');
    }
}