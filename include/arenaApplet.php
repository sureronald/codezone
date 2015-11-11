<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Arena applet control center                 |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

if(@$action=='arena'):

	//Get the arena helper class
	require_once('arenaAppletHelper.php');
	$arn=new arenaAppletHelper(time());
	?>
	<!--Arena Events-->
	<link type="text/css" media="screen" rel="stylesheet" href="theme/jquery.colorbox/colorbox.css" />
	<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
	<script src="js/jquery.countdown.min.js" type="text/javascript"></script>
	<script src="js/countdown.custom.js" type="text/javascript"></script>
	<script src="js/server.time.js" type="text/javascript"></script>
	<?php
	
	//Check if there's an active match
	if(!$arn->active_match())
	{
		system_messages(0,"There is no active match");
		return;
	}
	
	//Check if user logged in i.e to entice him/her to login
	if(!$login)
	{
		system_messages(0,"To participate in the arena, you must login");
		return;
	}
	
	
	//Initialize arena validation
	require_once('ajphp'.DS.'arenaValidatorHelper.php');
	$arnv=new arenaValidatorHelper(time(),$_pre,$db,$arn,$_max_submissions,$_submission_timeout);
	
	//Is this user registered...?
	if(!$arnv->isUserRegistered() && !$su){
		system_messages(0,'You are not registered to participate in this match');
		return;
	}
	
	//Set participated column to 1 in user_match_log table
	$arn->set_participate();
	
	/**
	There's an active match... Now we need to render the match arena ie. the problem statement plus the relevant ajax scripts to handle data transfer, countdown timing, scoreboard refreshes ...
	*/
	
	$arn->render_arena();
	endif;
?>