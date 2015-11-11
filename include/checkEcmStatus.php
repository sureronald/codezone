<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Checks the status of CodeZone i.e offline or     |
|               not offline                                           |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

if($_offline==1 && !$su)
{
	session_destroy();
	header( 'Location: offline' );
}

?>