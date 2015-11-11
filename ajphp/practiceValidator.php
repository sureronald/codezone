<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Arena Validation controls                   |
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
		
if(isset($_GET['a']) && @$_GET['a']=='arenaval'):
	
	if(isset($_GET['m_id']))
		$match_id=$_GET['m_id'];
	else
	{
		echo "error m_id is not set!";
		return;
	}
	settype($match_id,'integer');
	require_once('practiceValidatorHelper.php');
	/**Instantiate the arenaValidatorHelper class*/
	$pracv=new practiceValidatorHelper($match_id);
	
	if(isset($_GET['v']))
	{
		$v=$_GET['v'];
		if($v=='start_download')
			$pracv->download_link($match_id);
		if($v=='render_submission_form')
			$pracv->render_submission_form($match_id);
		if($v=='solution-submission')
			$pracv->online_judge($_FILES,$_POST);
		if($v=='is_download')
			$pracv->is_download($_GET);
	}
	
endif;

?>