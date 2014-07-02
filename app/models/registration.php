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
                        )
                    ),
                'cpword' => array(
                    'length' => array(
                        'validate_between',8,16
                        )
                    ),
                'fname' => array(
                    'length' => array(
                        'validate_between',1,50
                        )
                    ),
                'mname' => array(
                    'length' => array(
                        'validate_between',1,50
                        )
                    ),
                'lname' => array(
                    'length' => array(
                        'validate_between',1,50
                        )
                    ),
                'cnum' => array(
                    'length' => array(
                        'validate_between',1,50
                        )
                    ),
                'home_add' => array(
                    'length' => array(
                        'validate_between',1,100
                        )
                    ),
                'email_add' => array(
                    'length' => array(
                        'validate_between',1,32
                        )
                    )
            );
        public function validateData(){
            $this->validate();
            if($this->hasError() || !is_null(valid_username($this->uname))
                || !is_null(is_pass_match($this->pword, $this->cpword))){
                throw new ValidationException("Data is Invalid");
            }else{
                $db = DB::conn();
                $db->query("INSERT INTO users VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)",
                    array(
                        $this->uname,
                        md5($this->pword),
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