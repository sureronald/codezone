<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Application constants plus declarations     |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework
defined('IN_APP') or die('Restricted Access!');

$ecjConfig=new ecjConfig;

//Application Constants
$_offline=$ecjConfig->offline;
$_allow_user_reg=$ecjConfig->allow_user_reg;
$_offline_message=$ecjConfig->offline_message;
$_mail=$ecjConfig->notify_mail;
$_meta_desc=$ecjConfig->meta_desc;
$_max_submissions=$ecjConfig->max_submissions;
$_submission_timeout=$ecjConfig->submission_timeout;

//author url
$_author_url=$ecjConfig->author_url;

//Database global variables/constants
$_dbname=$ecjConfig->db_name;
$_dbhost=$ecjConfig->db_host;
$_dbuser=$ecjConfig->db_user;
$_dbpass=$ecjConfig->db_pass;
$_pre=$ecjConfig->table_prefix;

//Mail settings
$_mail_protocol=$ecjConfig->mail_protocol;
$_mail_from=$ecjConfig->mail_from;
$_smtp_username=$ecjConfig->smtp_username;
$_smtp_pass=$ecjConfig->smtp_pass;
$_smtp_host=$ecjConfig->smtp_host;
$_smtp_port=$ecjConfig->smtp_port;

//Initialize database helper class
require_once('mysqlHelper.php');
$db=new mysqlHelper;

//Session specific constants/variables
require_once( 'sessionHelper.php' );
$session=new sessionHelper;
$_session_max=$ecjConfig->session_lifetime;

//Server settings / variables
$_ip=$_SERVER['REMOTE_ADDR'];
$_browser=$_SERVER['HTTP_USER_AGENT'];

//Unset $ecjConfig
unset( $ecjConfig );

//Session register important variables
$_SESSION['pre']=$_pre;
?>
