<?php
    class Registration extends AppModel{
        const PASS_MIN_LENGTH = 8;
        const PASS_MAX_LENGTH = 16;

        public $validation = array(
            'uname' => array(
                'length' => array(
                    'validate_between' , MIN_LENGTH, MAX_LENGTH
                    ),
                ),
            'pword' => array(
                'length' => array(
                    'validate_between' , self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH
                    ),
                ),
            'cpword' => array(
                'length' => array(
                        'validate_between', self::PASS_MIN_LENGTH, self::PASS_MAX_LENGTH
                    ),
                ),
            'fname' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_LENGTH
                    ),
                ),
            'mname' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_LENGTH
                    ),
                ),
            'lname' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_LENGTH
                    ),
                ),
            'cnum' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_LENGTH
                    ),
                ),
            'home_add' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_TEXT_LENGTH
                    ),
                ),
            'email_add' => array(
                'length' => array(
                    'validate_between', MIN_LENGTH, MAX_LENGTH
                    ),
                ),
            );
        /**
         *REGISTERS A NEW USER
         *@throws ValidationException
         */
        public function registerUser()
        {
            $db = DB::conn();
            $where = 'uname = ? OR email_add = ?';
            $where_params = array($this->uname, $this->email_add);
            $row = $db->search(User::table, $where, $where_params);
            if ($row) {
                throw new ExistingUserException(notice("Username/Email Address has already been used","error"));
            } else if(!$this->validate()) {
                throw new ValidationException("");
            } else {
                $where_params = array(
                    'uname' => $this->uname,
                    'pword' => sha1($this->pword),
                    'fname' => $this->fname,
                    'mname' => $this->mname,
                    'lname' => $this->lname,
                    'cnum' => $this->cnum,
                    'home_add' => $this->home_add,
                    'email_add' => $this->email_add
                    );
                $db->insert(User::table, $where_params);
            }
        }
    }