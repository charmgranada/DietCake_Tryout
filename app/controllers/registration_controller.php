<?php
    class RegistrationController extends AppController{
        public function index(){
            $registration = new Registration();
            $empty = 0;
            $dataPassed = array();
            $dataPassed['uname'] = Param::get('uname');
            $dataPassed['pword'] = Param::get('pword');
            $dataPassed['cpword'] = Param::get('cpword');
            $dataPassed['fname'] = Param::get('fname');
            $dataPassed['mname'] = Param::get('mname');
            $dataPassed['lname'] = Param::get('lname');
            $dataPassed['cnum'] = Param::get('cnum');
            $dataPassed['home_add'] = Param::get('home_add');
            $dataPassed['email_add'] = Param::get('email_add');
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

            $errors['uname'] = valid_username($dataPassed['uname']);
            $errors['pass'] = is_pass_match($dataPassed['pword'], $dataPassed['cpword']);
            }else{
                $status = "<font color=red>Please fill up all fields</font>";
            }
            $this->set(get_defined_vars());
        }
    }