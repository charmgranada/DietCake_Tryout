<?php
function validate_between($string, $min, $max)
{
    $n = mb_strlen($string);
    return $min <= $n && $n <= $max;
}

function is_valid_username($username)
{
    return !((preg_match('/[^a-zA-Z0-9_]/', $username)) || (preg_match('/_{2}/', $username)) 
        || (preg_match('/(\s)+/', $username)));
}

function is_pass_match($confirm_password)
{
    $password = Param::get('password');
    return $password === $confirm_password;
}

function is_valid_name($string)
{
    return !((preg_match('/[^a-zA-Z \']/', $string)) || (preg_match('/\'{2}/', $string)))
        && !(preg_match('/(\s\s)+/', $string));
}
 
function is_valid_email($email)
{
    return (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email) && !(preg_match('/(\s)+/', $email)));
}
 
function is_valid_num_spaces($text)
{
    if (preg_match('/([^ ]){30}/', $text) || preg_match('/(\s\s)+/', $text)) {
        return false;
    }
    return true;
}

// FOR SESSION VALIDATION //
function check_user_logged_in()
{
    if (isset($_SESSION['user_id'])) {
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
function redirect($controller, $view, array $url_query = null)
{
    $url = "/$controller/$view";
    if (!$view) {
        $url = $controller;
    }
    if ($url_query) {
        foreach ($url_query as $key => $value) {
            $url .= "?$key=$value";
        }
    }
    header("location: {$url}");
}