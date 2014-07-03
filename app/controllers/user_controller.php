<?php
class UserController extends AppController
{
    public function login(){ 
    check_if_logged_in();
    $status = NULL;
    $username = Param::get('username');
    $password = Param::get('password');
    $user = new User;
    $user->Username = $username;
    $user->Password = $password;
    $page = Param::get('page_next','login');
    switch ($page) {
        case 'login':
            break;
        case 'home':
                if(empty($username) || empty($password)){
                    $status = notice("Please fill up all fields","error");
                    $page = "login";
                }else if(!isset($username) || !isset($username)){
                    $status = "";
                }else{
                    try {
                        $result = $user->authenticate();
                        $_SESSION['id'] = $result[0]['id'];
                        $_SESSION['uname'] = $result[0]['uname'];

                    } catch (Exception $e) {
                        $page = "login";
                        $status = notice($e->getMessage(),"error");
                    }
                }
        break;                    
    default:
        throw new Exception("{$page} not found");                        
        break;
    } 
    $this->set(get_defined_vars());
    $this->render($page);
    }
    public function logout(){
        session_unset();
        session_destroy();
        redirect();
    }
}