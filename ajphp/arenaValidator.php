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

	//Get the arena helper class
	require_once('..'.DS.'include'.DS.'arenaAppletHelper.php');
	$arn=new arenaAppletHelper(time());

	require_once('arenaValidatorHelper.php');
	/**Instantiate the arenaValidatorHelper class*/
	$arnv=new arenaValidatorHelper(time(),$_pre,$db,$arn,$_max_submissions,$_submission_timeout);
	
	if(isset($_GET['v']))
	{
		$v=$_GET['v'];
		
		if($v=='start_download')
			echo $arnv->download_link();
		else if($v=='get_timeout')
			echo $arnv->get_timeout();
		else if($v=='render_submission_form')
			$arnv->render_submission_form();
		else if($v=='is_download')
			$arnv->is_download($_GET);
		else if($v=='solution-submission')
			$arnv->online_judge($_FILES,$_POST);
		else if($v='partial_scoreboard')
			$arnv->partial_scoreboard();
		else
			die('Action unavailable!!');
	}
	
endif;

?>