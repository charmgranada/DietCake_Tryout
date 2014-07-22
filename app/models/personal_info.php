<?php
class PersonalInfo extends AppModel
{
    public $validation = array(
        "confirm_password" => array(
            "length" => array(
                "validate_between", User::PASS_MIN_LENGTH, User::PASS_MAX_LENGTH
            ),
            "format" => array(
                "is_pass_match", "Passwords do not match" 
            ),
        ),
        "firstname" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
            ),
            "format" => array(
                "is_valid_name", "First Name has invalid characters"
            )
        ),
        "lastname" => array(
            "length" => array(
                "validate_between", MIN_LENGTH, MAX_LENGTH
            ),
            "format" => array(
                "is_valid_name", "Last Name has invalid characters"
            )
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
}
