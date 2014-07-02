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