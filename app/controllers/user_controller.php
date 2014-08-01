<?php
class UserController extends AppController
{        
    /**
     *USER LOGIN
     */
    public function index()
    {
        check_user_logged_in();
        $user = new User();
        $status = null;
        try {
            $user->username = Param::get('username');
            $user->password = Param::get('password');
            $valid_user = $user->authenticate();     
            $_SESSION['user_id'] = $valid_user->user_id;
            $_SESSION['username'] = $valid_user->username;
            redirect('thread','index');
        } catch (ValidationException $e) {
            $status = notice($e->getMessage(),'error');
        } catch (RecordNotFoundException $e) {
            $status = notice($e->getMessage(),'error');                        
        }
        $this->set(get_defined_vars());
    }

    /**
     *REGISTER A NEW USER
     */
    public function register()
    {
        check_user_logged_in();
        $user = new User();
        $personal_info = new PersonalInfo();
        $errors = array();
        try {
            $user->username = Param::get('username');
            $user->password = Param::get('password');
            $personal_info->confirm_password = Param::get('confirm_password');
            $personal_info->firstname = Param::get('firstname');
            $personal_info->lastname = Param::get('lastname');
            $personal_info->email_add = Param::get('email_add');
            $user->register($personal_info);
            redirect('user','index');
        } catch (ValidationException $e) {
            $registration_form = Param::params();
            foreach ($registration_form as $field => $value) {
                // FOR ERRORS IN THE ACCOUNT INFO //
                if (!empty($user->validation_errors[$field]['length'])) {
                    $errors[$field] = notice('Character length must be ' 
                        .$user->validation[$field]['length'][1]. ' - ' 
                        .$user->validation[$field]['length'][2]. ' long', 'error');
                }
                if (!empty($user->validation_errors[$field]['format'])) {
                    $errors[$field] = notice($user->validation[$field]['format'][1], 'error');
                }
                // FOR ERRORS IN THE PERSONAL INFO //
                if (!empty($personal_info->validation_errors[$field]['length'])) {
                    $errors[$field] = notice('Character length must be ' 
                        .$personal_info->validation[$field]['length'][1]. ' - ' 
                        .$personal_info->validation[$field]['length'][2]. ' long', 'error');
                }
                if (!empty($personal_info->validation_errors[$field]['format'])) {
                    $errors[$field] = notice($personal_info->validation[$field]['format'][1], 'error');
                }
            }
            $status = null;
        } catch (ExistingUserException $e) {
            $status = $e->getMessage();
        }
        $this->set(get_defined_vars());
    }

    /**
     *LOGGED IN USER EDIT HIS/HER INFO
     */
    public function edit_account_info()
    {
        check_user_logged_out();
        $user = User::get($_SESSION['user_id']);
        $personal_info = new PersonalInfo();
        $errors = array();
        try {
            $user->username = Param::get('username');
            $user->password = Param::get('password');
            $personal_info->confirm_password = Param::get('confirm_password');
            $personal_info->firstname = Param::get('firstname');
            $personal_info->lastname = Param::get('lastname');
            $personal_info->email_add = Param::get('email_add');
            $user->update($personal_info);
            $_SESSION['username'] = $user->username;
            redirect('thread','index');
        } catch (ValidationException $e) {
            $registration_form = Param::params();
            foreach ($registration_form as $field => $value) {
                // FOR ERRORS IN THE ACCOUNT INFO //
                if (!empty($user->validation_errors[$field]['length'])) {
                    $errors[$field] = notice('Character length must be ' 
                        .$user->validation[$field]['length'][1]. ' - ' 
                        .$user->validation[$field]['length'][2]. ' long', 'error');
                }
                if (!empty($user->validation_errors[$field]['format'])) {
                    $errors[$field] = notice($user->validation[$field]['format'][1], 'error');
                }
                // FOR ERRORS IN THE PERSONAL INFO //
                if (!empty($personal_info->validation_errors[$field]['length'])) {
                    $errors[$field] = notice('Character length must be ' 
                        .$personal_info->validation[$field]['length'][1]. ' - ' 
                        .$personal_info->validation[$field]['length'][2]. ' long', 'error');
                }
                if (!empty($personal_info->validation_errors[$field]['format'])) {
                    $errors[$field] = notice($personal_info->validation[$field]['format'][1], 'error');
                }
            }
            $status = null;
        } catch (ExistingUserException $e) {
            $status = $e->getMessage();
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