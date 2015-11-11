<?php

/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Clean $_POST and $_GET from sql injections  |
|                                                          |
*----------------------------------------------------------*
*/

function clean_string(&$item1,$key)
{
	global $db,$_db;
	if(gettype($item1)=='string')
	$item1=mysql_real_escape_string($item1,$db->link);
}

array_walk($_POST,'clean_string');
array_walk($_GET,'clean_string');

?>