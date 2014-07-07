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
        public function validateData(){
            $this->validate();
            $db = DB::conn();
            $rows = $db->rows('SELECT * FROM users WHERE uname = ? OR email_add = ?', 
            array($this->uname,$this->email_add));
            if($rows){
                throw new Exception(notice("Username/Email Address has already been used","error"));
            }    
            if($this->hasError()){
                throw new ValidationException("");
            }else{
                $query = "INSERT INTO users set uname = ?, pword = ?, 
                fname = ?, mname = ?, lname = ?, cnum = ?, home_add = ?, email_add = ?";
                $db->query($query ,
                    array(
                        $this->uname,
                        sha1($this->pword),
                        $this->fname,
                        $this->mname,
                        $this->lname,
                        $this->cnum,
                        $this->home_add,
                        $this->email_add
                        )
                    );
            }
        }     
    }