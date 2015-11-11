<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Handle offline login requests               |
|                                                          |
*----------------------------------------------------------*
*/

define('DS',DIRECTORY_SEPARATOR);
define('IN_APP',1);

//Get ecjConfig object
require_once('..'.DS.'configuration.php');
$ecjConfig=new ecjConfig;
$_dbhost=$ecjConfig->db_host;
$_dbname=$ecjConfig->db_name;
$_dbuser=$ecjConfig->db_user;
$_dbpass=$ecjConfig->db_pass;
$_pre=$ecjConfig->table_prefix;
$_submission_timeout=$ecjConfig->submission_timeout;
$_max_submissions=$ecjConfig->max_submissions;
$_session_max=$ecjConfig->session_lifetime;
unset($ecjConfig);

//Get the mysqlHelper class
require_once('..'.DS.'include'.DS.'mysqlHelper.php');
$db=new mysqlHelper;

require_once('..'.DS.'include'.DS.'cleanPostAndGet.php'); //Clean $_POST and $_GET of malicious
require_once('..'.DS.'include'.DS.'utilityFunctions.php');

$handle=strtoupper($_POST['handle']);
$password=encrypt_password($_POST['password']);

//check for existence of user account
$query="SELECT * FROM ".$_pre."users WHERE registration_no='$handle' AND password='$password' AND activated=1";
$db->setQuery($query);
if($db->foundRows==0)
{
	echo "{'error':'Username and password did not match'}";
	return;
}

//Is this a superuser account?
$user_data=$db->fetch_assoc();
if($user_data['user_type']!='su')
{
	echo "{'error':'You are not allowed to login via this screen'}";
	return;
}

//Authentication successful and we now need to start a session before sending a response for redirection to CodeZone home page

$query="SELECT avatar_path,quote,about_me,language,match_count,ranking_pts FROM ".$_pre."profile WHERE registration_no='$handle'";
$db->setQuery($query);
$row=$db->fetch_assoc();

//Add user profile info to user_row_data array
$user_data=array_merge($user_data,$row);

//Start a session and session register this user's row data
session_start();
$_SESSION['user_row_data']=$user_data; 

//Update user details in sessions & users table
$query="UPDATE ".$_pre."users SET last_visit_date=NOW() WHERE registration_no='$handle'";
$db->setQuery($query);

$user_type=$user_data['user_type'];
$new_session_id=md5(session_id()); //Encrypt the session id; similar to the function regenerate_id in the sessionHelper.php class
$new_session_expire=time()+$_session_max;
$ip=mysql_real_escape_string($_SERVER['REMOTE_ADDR'],$db->link);
$browser=mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'],$db->link);

$query="UPDATE ".$_pre."sessions SET session_id='$new_session_id',registration_no='$handle',user_type='$user_type',nick_name='{$user_data['nick_name']}',session_expire='$new_session_expire' WHERE session_ip='$ip' AND session_browser='$browser'";
$db->setQuery($query);

if($db->affectedRows==0)
{
	//Perform an insert because either the guest details have been wiped away by session garbage cleaner
	$query="INSERT INTO ".$_pre."sessions (session_id,registration_no,user_type,nick_name,session_expire,session_ip,session_browser) VALUES ('$new_session_id','$handle','$user_type','{$user_data['nick_name']}','$new_session_expire','$ip','$browser')";
	$db->setQuery($query);
}

//Set the session cookie [l]
setcookie('l',$new_session_id,$new_session_expire+$_session_max,'/');

//Everything is now set up we send a redirect response (fun .. ha?)
echo "{'redirect_url':'../index.php'}";

?>