<?php
    function validate_between($string, $min, $max)
    {
        $n = mb_strlen($string);
        return $min <= $n && $n <= $max;
    }

    function is_valid_username($username)
    {
        return !((preg_match('/[^a-zA-Z0-9_]/', $username)) || (preg_match('/_{2}/', $username)));
    }

    function is_pass_match($confirm_password)
    {
        $password = Param::get('pword');
        return $password === $confirm_password;
    }

    function is_valid_name($string)
    {
        return !((preg_match('/[^a-zA-Z\']/', $string)) || (preg_match('/\'{2}/', $string)));
    }
     
    function is_valid_email($email)
    {
        return (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email));
    }
     
    function is_valid_comment($text)
    {
        if (preg_match('/([^ ]){125}/', $text)) {
            return false;
        }
        return true;
    }

    // FOR SESSION VALIDATION //
    function check_user_logged_in()
    {
        if (isset($_SESSION['user_id'])) {
            $user = User::get($_SESSION['user_id']);
            redirect('thread','index');
        }
    }

    function check_user_logged_out()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('user','index');
        }
    }

    function user_logged_in()
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        return $user = User::get($_SESSION['user_id']);
    }
    
    // FOR REDIRECT //
    function redirect($controller, $view)
    {
        $url = "/$controller/$view";
        header("location: {$url}");
    }