<?php
function validate_between($check, $min, $max)
    {
        $n = mb_strlen($check);
        return $min <= $n && $n <= $max;
    }
function valid_username($check){
    return !((preg_match('/[^a-zA-Z0-9_]/', $check)) || (preg_match('/_{2}/', $check)));
}
function user_logged_in(){
    if(!isset($_SESSION['id'])){
        return false;
    }
    return true;
}
function redirect($controller){
    header("location: /user/" . $controller);
}
function is_pass_match($pword, $cpword){
    if(!($pword === $cpword)){
        return notice("Passwords do not match","error");
    }
    return NULL;
}
function is_name($string,$nameType){

    if ((preg_match('/[^a-zA-Z\']/', $string)) || (preg_match('/\'{2}/', $string))){
        return notice("{$nameType} has invalid characters","error");
    }
    return NULL;
}
function is_number($string){

    if ((preg_match('/[^0-9]/', $string))){
        return notice("Contact number must be numbers only","error");
    }
    return NULL;
}
function is_email_address($email){

    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)){
        return notice("Email address has an invalid format","error");
    }
    return NULL;
}