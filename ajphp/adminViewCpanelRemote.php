<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Admininistration Control panel ajax calls   |
|              handler                                     |
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
		
if(isset($_GET['a']) && @$_GET['a']=='adminviewcpanelremote'):
	
	//Get the utility functions
	require_once('..'.DS.'include'.DS.'utilityFunctions.php');
	
	require_once('adminViewCpanelRemoteHelper.php');
	$avcrh=new adminViewCpanelRemoteHelper;
	
	if(isset($_GET['do']))
	{
		$do=$_GET['do'];
		
		if($do=='edit_user_form')
			$avcrh->editUserForm();
		else if($do=='add_bulk_users')
			$avcrh->addUsersBulk();
		else if($do=='update_user_details')
			$avcrh->updateUserDetails();
		else if($do=='load_edit_match_form')
			$avcrh->loadEditMatchForm();
		else if($do=='load_new_story_form')
			$avcrh->createStoryForm();
		else if($do=='load_edit_story_form')
			$avcrh->editStoryForm();
		else if($do=='load_coder_select')
			$avcrh->loadCoders();
		else if($do=='load_coder_source')
			$avcrh->loadCoderSource();
		else if($do=='load_mail_auto_complete')
			$avcrh->mailAutoComplete();
		else if($do=='load_source_from_file')
			$avcrh->loadSourceFromFile();
		else if($do=='disq_user')
			$avcrh->disqualifyUserToggle();
		else if($do=='show_coder_history')
			$avcrh->showCoderHistory();
		else if($do=='show_problem_sheet')
			$avcrh->showProblemSheet();
		else if($do=='download_sheet')
			$avcrh->downloadSheet();
		else
			die('Action unavailable!!');
	}
	
endif;

?>
