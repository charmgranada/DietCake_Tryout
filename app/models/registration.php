<?php
    class Registration extends AppModel{
        public $validation = array(
                'uname' => array(
                    'length' => array(
                        'validate_between',8,16
                        ),
                    ),
                'pword' => array(
                    'length' => array(
                        'validate_between',8,16
                        ),
                    ),
                'cpword' => array(
                    'length' => array(
                        'validate_between',8,16
                        ),
                    ),
                'fname' => array(
                    'length' => array(
                        'validate_between',1,16
                        ),
                    ),
                'mname' => array(
                    'length' => array(
                        'validate_between',1,16
                        ),
                    ),
                'lname' => array(
                    'length' => array(
                        'validate_between',1,16
                        ),
                    ),
                'cnum' => array(
                    'length' => array(
                        'validate_between',1,16
                        ),
                    ),
                'home_add' => array(
                    'length' => array(
                        'validate_between',1,16
                        ),
                    ),
                'email_add' => array(
                    'length' => array(
                        'validate_between',1,16
                        ),
                    ),
            );
        public function validateData(){
            $this->validate();
            if($this->hasError()){
                throw new ValidationException("Data is Invalid");
            }else{
                $db = DB::conn();
                $db->query("INSERT INTO users VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)",
                    array(
                        $this->uname,
                        sha1($this->pword),
                        $this->fname,
                        $this->mname,
                        $this->lname,
                        $this->cnum,
                        $this->home_add,
                        $this->email_add));
                return "Success";
            }
        } 	
    }