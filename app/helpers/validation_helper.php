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