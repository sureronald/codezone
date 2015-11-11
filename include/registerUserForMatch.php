<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Add user to an active match table           |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

if(@$action=='register_match'):
	if(!$login){
	echo "<p><span class='notify_error'>You must login to register for this match!</span></p>";
	echo "<a href='index.php'>Go to home page</a>";
	return;
	}
	//Is the user a superuser?... get him out of here (funny ha..?)
	if($su){
		system_messages(0,'Super user cannot register for a match');
		return;
	}
	$match_id=(int) @$_GET['m_id'];
	$registration_no=$_SESSION['user_row_data']['registration_no'];
	$nick_name=$_SESSION['user_row_data']['nick_name'];
	//Query database to get the match table name
	$query="SELECT * FROM {$_pre}matches WHERE id=$match_id";
	$db->setQuery($query);
	//Is the requested match valid?
	if($db->foundRows<1){
		system_messages(2,'Invalid match requested');
		return;
	}
	$match_data=$db->fetch_assoc();
	//Is the requested match still active?
	if($match_data['start_time']<time()){
		system_messages(0,'Match is inactive');
		return;
	}
	
	$query="SELECT * FROM {$_pre}{$match_data['match_table_name']} WHERE registration_no='$registration_no'";
	$db->setQuery($query);
	//Is this user already registered?
	if($db->foundRows>0){
		system_messages(0,'You are already registered for this match!');
		return;
	}
	//Now we can safely register this user (fun..)
	$query="INSERT INTO {$_pre}{$match_data['match_table_name']} (registration_no,nick_name) VALUES ('$registration_no','$nick_name')";
	$db->setQuery($query);
	
	//Update profile table that is, match_count column and add the user to user_match_log table
	$query="SELECT match_count FROM ".$_pre."profile WHERE registration_no='$registration_no'";
	$db->setQuery($query);
	$data=$db->fetch_assoc();
	$new_match_count=$data['match_count']+1;
	//$matches_participated=(strlen($data['matches_participated'])==0)?$match_data['id'].'---'.$match_data['title']:$data['matches_participated'].'*****'.$match_data['id'].'---'.$match_data['title'];
	
	$query="UPDATE ".$_pre."profile SET match_count=$new_match_count WHERE registration_no='$registration_no'";
	$db->setQuery($query);
	$query="INSERT INTO {$_pre}user_match_log (registration_no,match_id,title,match_date,register_date) VALUES ('$registration_no',{$match_data['id']},'{$match_data['title']}',{$match_data['start_time']},".time().")";
	$db->setQuery($query);
	
	//Mail confirmation to user
	require_once('lib'.DS.'mail'.DS.'mail.php');
	
	$subject='Match Registration Confirmation';
	$message="{$_SESSION['user_row_data']['nick_name']},\nYou have been successfuly registered to participate in the CodeZone match {$match_data['title']} scheduled to take place on the ".time_stamp_to_readable($match_data['start_time']).".\nMore details on this match can be found here http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}?a=schedule .\nWishing you all the best!\n\nCodeZone Admin";
	
	mailSend(array($_SESSION['user_row_data']['email']),$subject,$message);
	
	//Echo success message
	echo "<p><span class='notify_success'>You have been successfully registered. A confirmation email will be sent to your account shortly</span></p>";
	echo "<a href='index.php'>Go to home page</a>";
endif;

?>