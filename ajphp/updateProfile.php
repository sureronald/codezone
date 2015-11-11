<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Save user profile details                   |
|                                                          |
*----------------------------------------------------------*
*/

session_start();
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
unset($ecjConfig);

//Get the mysqlHelper class
require_once('..'.DS.'include'.DS.'mysqlHelper.php');
$db=new mysqlHelper;

require_once('..'.DS.'include'.DS.'cleanPostAndGet.php'); //Clean $_POST and $_GET of malicious

if(isset($_GET['a']) && @$_GET['a']=='profile'):
	require_once('..'.DS.'include'.DS.'utilityFunctions.php');
	
	//Get the profile helper class
	require_once('updateProfileHelper.php');
	$updf=new updateProfileHelper;
	
	if(isset($_GET['do']))
	{
		$do=$_GET['do'];
		if($do=='save_personal_details')
			$updf->save_personal_details($_POST);
		else if($do=='save_avatar')
			$updf->save_avatar($_POST,$_FILES);
		else if($do=='save_extras')
			$updf->save_extras($_POST);
		else if($do=='delete_avatar')
			$updf->delete_avatar();
		else
			echo "{'error':'Error in query string'}";
	}
	else
		echo "{'error':'Error in query string'}";
	
	endif;

?>