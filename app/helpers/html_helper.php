<?php
function eh($string)
{
    if (!isset($string)) return;
    echo htmlspecialchars($string, ENT_QUOTES);
}

function readable_text($s)
{
    $s = htmlspecialchars($s, ENT_QUOTES);
    $s = nl2br($s);
    return $s;
}

function notice($text, $notice_type = NULL)
{
    $msg = "<center>";
    switch ($notice_type) {
    case 'error':
        $msg .= "<font color=red size=2>";
        break;
    
    default:
        $msg .= "<font color=green size=2>";
        break;
    }
    $msg .= $text. "</font></center>";
    return $msg;
}