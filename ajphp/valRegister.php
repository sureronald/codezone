<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Validate user registration                  |
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
$_mail=$ecjConfig->mail_from;
$_allow_user_reg=$ecjConfig->allow_user_reg;
unset($ecjConfig);

//Get the mysqlHelper class
require_once('..'.DS.'include'.DS.'mysqlHelper.php');
$db=new mysqlHelper;

require_once('..'.DS.'include'.DS.'cleanPostAndGet.php'); //Clean $_POST and $_GET of malicious

if(isset($_GET['a']) && @$_GET['a']=='register'):
	require_once('..'.DS.'include'.DS.'utilityFunctions.php');
	
	//Get the registration helper class
	require_once('valRegisterHelper.php');
	$valreg=new valRegisterHelper;
	
	if(isset($_GET['do']))
	{
		$do=$_GET['do'];
		if($do=='save_user_details')
			$valreg->save_user_details();
		else
			echo "{'error':'Error in query string'}";
	}
	else
		echo "{'error':'Error in query string'}";
	
	endif;

?>