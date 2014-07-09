<?php
    function validate_between($string, $min, $max)
    {
        $n = mb_strlen($string);
        return $min <= $n && $n <= $max;
    }
    /*---------------------------------------------------------------------------------------*/
    function validate_format($username)
    {
        return !((preg_match('/[^a-zA-Z0-9_]/', $username)) || (preg_match('/_{2}/', $username)));
    }
    /*---------------------------------------------------------------------------------------*/
    function is_pass_match($password, $confirm_password)
    {
        if ($password !== $confirm_password) {
            return notice("Passwords do not match","error");
        }
        return NULL;
    }
    /*---------------------------------------------------------------------------------------*/
    function is_name($string,$nameType)
    {
        if ((preg_match('/[^a-zA-Z\']/', $string)) || (preg_match('/\'{2}/', $string))) {
            return notice("{$nameType} has invalid characters","error");
        }
        return NULL;
    }
    /*---------------------------------------------------------------------------------------*/
    function is_email_address($email)
    {
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
            return notice("Email address has an invalid format","error");
        }
        return NULL;
    }
    /*---------------------------------------------------------------------------------------*/
    function check_for_spaces($text)
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
        return true;
    }
    // FOR REDIRECT //
    function redirect($controller, $view)
    {
        $url = "/$controller/$view";
        header("location: {$url}");
    }