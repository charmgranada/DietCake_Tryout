<?php
function validate_between($check, $min, $max)
    {
        $n = mb_strlen($check);
        return $min <= $n && $n <= $max;
    }
function valid_username($check){

    if ((preg_match('/[^a-zA-Z0-9_]/', $check)) || (preg_match('/_{2}/', $check))){
        return "<center><font size=2 color=red>Username has invalid characters</font>";
    }
    return NULL;
}
function is_pass_match($pword, $cpword){
    if(!($pword === $cpword)){
        return "<center><font size=2 color=red>Passwords do not match</font>";
    }
    return NULL;
}
function is_name($string,$nameType){

    if ((preg_match('/[^a-zA-Z\']/', $string)) || (preg_match('/\'{2}/', $string))){
        return "<center><font size=2 color=red>{$nameType} has invalid characters</font>";
    }
    return NULL;
}
function is_number($string){

    if ((preg_match('/[^0-9]/', $string))){
        return "<center><font size=2 color=red>Contact number must be numbers only</font>";
    }
    return NULL;
}
function is_email_address($email){

    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)){
        return "<center><font size=2 color=red>Email address has an invalid format</font>";
    }
    return NULL;
}