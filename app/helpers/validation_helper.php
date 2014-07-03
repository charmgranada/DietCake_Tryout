<?php
function validate_between($check, $min, $max)
    {
        $n = mb_strlen($check);
        return $min <= $n && $n <= $max;
    }
function valid_username($check){
	return !((preg_match('/[^a-zA-Z0-9_ ]/', $check)) || (preg_match('/_{2}/', $check)));
}
function redirect($controller){
    switch ($controller) {
    	case 'home':
    		header('location: /user/home');
    		break;
    	
    	default:
    		header('location: /');
    		break;
    }
}
function check_if_logged_in(){
    if(isset($_SESSION['uname'])){
        header('location: /user/home');
    }
}