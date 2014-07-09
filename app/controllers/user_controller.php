<?php
    class UserController extends AppController
    {        
        /**
         *USER LOGIN
         */
        public function index()
        {
            check_user_logged_in();
            $status = NULL;
            $username = Param::get('username');
            $password = Param::get('password');
            $user = new User();
            $page = Param::get('page_next','index');
            if (!isset($username) || !isset($password)) {
                $status = "";
            } else if (!($username) || !($password)) {
                $status = notice("Please fill up all fields","error");
            } else {
                try {
                    $existing_user = $user->authenticate($username, $password);
                    $_SESSION['user_id'] = $existing_user->id;
                    $_SESSION['uname'] = $existing_user->uname;
                    redirect('thread','index');
                } catch (Exception $e) {
                    $status = notice($e->getMessage(),"error");
                }
            }
            $this->set(get_defined_vars());
        }

        /**
         *REGISTER A NEW USER
         *@throws Exception
         */
        public function register()
        {
            check_user_logged_in();
            $registration = new Registration();
            $empty = 0;
            $form = array(
                'uname', 'pword', 'cpword',
                'fname', 'mname', 'lname',
                'cnum', 'home_add', 'email_add');
            foreach($form as $field) {
                $form[$field] = Param::get($field);
            }
            $errors = array();
            foreach ($form as $field => $value) {
                if (!$value) {
                    $empty++;
                } else {
                    $registration->$field = $value;
                }
            }
            if ($empty===0) {
                try {
                    $errors = array();
                    $errors['uname'] = !validate_format($form['uname'])
                        ? notice("Username is invalid","error") : "";
                    $errors['pass'] = is_pass_match($form['pword'], $form['cpword']);
                    $errors['fname'] = is_name($form['fname'],"First Name");
                    $errors['mname'] = is_name($form['mname'],"Middle Name");
                    $errors['lname'] = is_name($form['lname'],"Last Name");
                    $errors['cnum'] = !is_numeric($form['cnum']) 
                        ? notice("Contact number must be numbers only","error") : "";
                    $errors['email_add'] = is_email_address($form['email_add']);
                    foreach ($errors as $field => $value) {
                        if ($value) {
                            throw new Exception("");                                                                        
                        }
                    }
                    $registration->newUser();
                    redirect('user','index');
                    $status = "";
                } catch (Exception $e) {
                    foreach ($form as $field => $value) {
                        if (!empty($registration->validation_errors[$field]['length'])) {
                            $errors[$field] = "<center><font size=2 color=red>Character length must be ". 
                            $registration->validation[$field]['length'][1]. " - " .
                            $registration->validation[$field]['length'][2] . " long</font>";
                        }
                    }
                    $status = $e->getMessage();
                }
            } else {
                $status = "<font color=red>Please fill up all fields</font>";
            }
            $this->set(get_defined_vars());
        }
        
        /**
         *LOGOUT ACCOUNT
         */
        public function logout()
        {
            session_unset();
            session_destroy();
            redirect('user','index');
        }
    }