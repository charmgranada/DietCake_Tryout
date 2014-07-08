<?php

	function eh($string)
	{
	    if (!isset($string)) return;
	    echo htmlspecialchars($string, ENT_QUOTES);
	}
	function rh($string)
	{
	    if (!isset($string)) return;
	    return htmlspecialchars($string, ENT_QUOTES);
	}
	function readable_text($s)
	{
	$s = htmlspecialchars($s, ENT_QUOTES);
	$s = nl2br($s);
	return $s;
	}
	function notice($string,$notice_type = NULL){
		$msg = "<center>";
		switch ($notice_type) {
			case 'error':
				$msg .= "<font color=red size=2>";
				break;
			
			default:
				$msg .= "<font color=green size=2>";
				break;
		}
		$msg .=$string. "</font></center>";
		return $msg;
	}
	define("MIN_LENGTH", 1);
	define("MAX_LENGTH", 50);
	define("MAX_TEXT_LENGTH", 200);
	define("PASS_MIN_LENGTH", 8);
	define("PASS_MAX_LENGTH", 16);