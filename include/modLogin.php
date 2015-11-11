<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Show the login/logout/register links on the |
|              top right corner                            |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

if($login)
echo "<a class='logout-a' href='index.php?a=logout&hash=".base64_encode(time())."'>logout  [ {$_SESSION['user_row_data']['nick_name']} ]</a>";
else
{
	echo "<span class='modlogin-span'><a class='modlogin-a' href='index.php?a=loginform'>login</a> | <a class='modlogin-a' href='index.php?a=register'>register</a></span><br />";
	echo "<span class='modlogin-span'><a class='modlogin-a' href='index.php?a=register&amp;do=rpass'>forgot your password?</a></span>";
}
?>
