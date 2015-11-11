<?php 
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Finds and displays the users online         |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

echo "<div id='mod_users_online'>";
$users=$session->get_users_online();

echo "<p><span class='users-online-info'>Guests online: ".count($users['guests'])."</span></p>";


//Display members online
echo "<p>";
if(count($users['registered'])==0)
{
	echo "<span class='users-online-info'>Members online: 0</span>";
}
else
{
	echo "<span class='users-online-info'>Members online: ".count($users['registered'])." .::.</span>&nbsp;&nbsp;";
	$users_registered=$users['registered'];
	shuffle($users_registered);
	$i=0;
	foreach($users['registered'] as $name)
	{
		echo "<a class='users-online-alink' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name=$name'>$name</a>,&nbsp;";
		//Show only 30 randomly selected users
		$i++;
		if($i==30)
			break;
	}
}
echo "</p>";
//Check for admin privilleges so as to display logged in administrators
echo "<p><span class='users-online-info'>Administrators online:</span>&nbsp;";
if(count($users['admins'])>0)
	foreach($users['admins'] as $name)
	{
		echo "<a class='users-online-alink' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name=$name'>$name</a>,&nbsp;";
	}
echo "</p>";

echo "</div>";
?>