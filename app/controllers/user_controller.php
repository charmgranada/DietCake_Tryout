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
                } catch (ValidationException $e) {
                    $status = notice($e->getMessage(),"error");
                } catch (RecordNotFoundException $e) {
                        $status = notice($e->getMessage(),"error");                        
                }
            }
            $this->set(get_defined_vars());
        }

        /**
         *REGISTER A NEW USER
         *@throws ErrorFoundException
         *@throws ValidationException
         */
        public function register()
        {
            check_user_logged_in();
            $registration = new Registration();
            $empty_field_ctr = 0;
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
                    $empty_field_ctr++;
                } else {
                    $registration->$field = $value;
                }
            }
            if ($empty_field_ctr===0) {
                try {
                    $errors = array();
                    $registration->registerUser();
                    redirect('user','index');
                    $status = "";
                } catch (ValidationException $e) {
                    foreach ($form as $field => $value) {
                        if (!empty($registration->validation_errors[$field]['length'])) {
                            $errors[$field] = notice("Character length must be ". 
                            $registration->validation[$field]['length'][1]. " - " .
                            $registration->validation[$field]['length'][2] . " long", 'error');
                        }
                        if (!empty($registration->validation_errors[$field]['format'])) {
                            if ($field == 'cnum') {
                                $errors[$field] = notice('Contact number must be numbers only', 'error');
                            } else {
                                $errors[$field] = notice($registration->validation[$field]['format'][1], 'error');
                            }
                        }
                    }
                    $status = "";
                } catch (ExistingUserException $e) {
                    $status = $e->getMessage();
                }
            } else {
                $status = notice("Please fill up all fields",'error');
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