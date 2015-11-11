<?php

/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Processes system requests and initializes   |
|              the appropriate variables. It also checks   |
|              to determine if session is valid...         |
*----------------------------------------------------------*
*/

//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );


//Determines the type of request and initializes the appropriate variables
if(isset($_GET['a'])){
switch($_GET['a']){
	case 'register':
		$action='register';
		break;
	case 'activate':
		$action='activate';
		break;
	case 'login':
		$action='login';
		break;
	case 'loginform':
		$action='loginform';
		break;
	case 'logout':
		$action='logout';
		break;
	case 'arena':
		$action='arena';
		break;
	case 'su':
		$action='su';
		break;
	case 'profile':
		$action='profile';
		break;
	case 'practice':
		$action='practice';
		break;
	case 'faqs':
		$action='faqs';
		break;
	case 'schedule':
		$action='schedule';
		break;
	case 'help':
		$action='help';
		break;
	case 'halloffame':
		$action='halloffame';
		break;
	case 'scoreboard':
		$action='scoreboard';
		break;
	case 'showcoders':
		$action="showcoders";
		break;
	case 'register_match':
		$action="register_match";
		break;
	case 'showlicense':
		$action="showlicense";
		break;
	case 'showstory':
		$action="showstory";
		break;
	default:
		header("HTTP/1.0 404 Not Found");
}
}
else{
//Render Control Panel
$action='frontpage';
}
// array_walk($_GET,'addslashes');
// foreach($_GET as $x)
// 	echo $x;
?>
