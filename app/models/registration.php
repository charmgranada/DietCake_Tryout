<?php
    class Registration extends AppModel{
        public $validation = array(
                'uname' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_LENGTH
                        ),
                    ),
                'pword' => array(
                    'length' => array(
                        'validate_between',PASS_MIN_LENGTH,PASS_MAX_LENGTH
                        ),
                    ),
                'cpword' => array(
                    'length' => array(
                        'validate_between',PASS_MIN_LENGTH,PASS_MAX_LENGTH
                        ),
                    ),
                'fname' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_LENGTH
                        ),
                    ),
                'mname' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_LENGTH
                        ),
                    ),
                'lname' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_LENGTH
                        ),
                    ),
                'cnum' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_LENGTH
                        ),
                    ),
                'home_add' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_TEXT_LENGTH
                        ),
                    ),
                'email_add' => array(
                    'length' => array(
                        'validate_between',MIN_LENGTH,MAX_LENGTH
                        ),
                    ),
            );
        /**
         *REGISTERS A NEW USER
         *@throws ValidationException
         */
        public function NewUser(){
            $this->validate();
            $db = DB::conn();
            $rows = $db->rows('SELECT * FROM users WHERE uname = ? OR email_add = ?', 
            array($this->uname,$this->email_add));
            if($rows){
                throw new ValidationException(notice("Username/Email Address has already been used","error"));
            }    
            else if($this->hasError()){
                throw new ValidationException("");
            }else{
                $params = array(
                    'uname' => $this->uname,
                    'pword' => sha1($this->pword),
                    'fname' => $this->fname,
                    'mname' => $this->mname,
                    'lname' => $this->lname,
                    'cnum' => $this->cnum,
                    'home_add' => $this->home_add,
                    'email_add' => $this->email_add
                    );
                $db->insert('users',$params);
            }
        }
    }