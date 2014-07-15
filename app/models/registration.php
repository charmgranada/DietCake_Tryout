<?php
class Registration extends AppModel
{
    public $validation = array(
        "uname" => array(
            "length" => array(
                "validate_between" , MIN_LENGTH, MAX_LENGTH
                ),
            "format" => array(
                "is_valid_username", "Invalid format for Username" 
                ),
            ),
        "pword" => array(
            "length" => array(
                "validate_between" , User::PASS_MIN_LENGTH, User::PASS_MAX_LENGTH
                ),
            ),
        "cpword" => array(
            "length" => array(
                    "validate_between", User::PASS_MIN_LENGTH, User::PASS_MAX_LENGTH
                ),
            "format" => array(
                "is_pass_match", "Passwords do not match" 
                ),
            ),
        "fname" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
                ),
            "format" => array(
                    "is_valid_name", "First Name has invalid characters"
                )
            ),
        "mname" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
                ),
            "format" => array(
                    "is_valid_name", "Middle Name has invalid characters"
                )
            ),
        "lname" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
                ),
            "format" => array(
                    "is_valid_name", "Last Name has invalid characters"
                )
            ),
        "cnum" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
                ),
            "format" => array(
                    "is_numeric"
                )
            ),
        "home_add" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_TEXT_LENGTH
                ),
            ),
        "email_add" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
                ),
            "format" => array(
                    "is_valid_email", "Invalid format for Email Address"
                )
            ),
        );

    /**
     *REGISTERS A NEW USER
     *@throws ExistingUserException
     *@throws ValidationException
     */
    public function registerUser()
    {
        $db = DB::conn();
        $where = "uname = ? OR email_add = ?";
        $where_params = array($this->uname, $this->email_add);
        $row = $db->search(User::USERS_TABLE, $where, $where_params);
        if ($row) {
            throw new ExistingUserException(notice("Username/Email Address has already been used","error"));
        }
        if(!$this->validate()) {
            throw new ValidationException(notice("Validation Error", "error"));
        }
        $where_params = array(
            "uname" => $this->uname,
            "pword" => sha1($this->pword),
            "fname" => $this->fname,
            "mname" => $this->mname,
            "lname" => $this->lname,
            "cnum" => $this->cnum,
            "home_add" => $this->home_add,
            "email_add" => $this->email_add
            );
        $db->insert(User::USERS_TABLE, $where_params);
    }
}