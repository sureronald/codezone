<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone profile handler; takes care of viewing, |
|              updating user profile                       |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

if($action=='profile'):
	
	if(isset($_GET['do']))
		$do=$_GET['do'];
	else
	{
		system_messages(2,'Invalid arguments in query');
		return;
	}
	$handle='';
	if(isset($_GET['nick_name']))
	{
		$handle=$_GET['nick_name'];
	}
	
	
	//Get the profileHelper class
	require_once('profileHelper.php');
	$profile=new profileHelper($handle);
	
	if($do=='viewProfile')
		$profile->view_profile();
	else if($do=='updateProfile' && $login) //Allow profile update only if user is logged in
		$profile->update_profile_fields();
	else
		system_messages(2,'Invalid arguments in query');
	//print_r($profile);
	
	endif;

?>