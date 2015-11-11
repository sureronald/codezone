<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Mail functions (php mail & smtp)            |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework
defined('IN_APP') or die('Restricted Access!');

function mailSend($to,$subject,$body)
{
	//Get the mail specific configuration variables already existing globally
	global $_mail_from,$_mail_protocol,$_mail_protocol,$_mail_from,$_smtp_username,$_smtp_pass,$_smtp_host,$_smtp_port;
	
	$body=wordwrap($body,70);
	if($_mail_protocol=='smtp')
	{
		require_once("smtp.php");
		
		$smtp=new smtp_class;
		
		$smtp->host_name=$_smtp_host;
		$smtp->host_port=$_smtp_port;
		$smtp->user=$_smtp_username;
		$smtp->password=$_smtp_pass;
		$smtp->timeout=10;
		//$smtp->debug=1; Debug: Uncomment this. Note that it will cause errors in cases where this file is contacted by ajax and the requested data format is json e.g in the registration form
		
		$headers="MIME-Version: 1.0\r\n
			From: CodeZone <$_mail_from>\r\n
			Reply-To: $_mail_from\r\n";
		
		foreach($to as $val)
		{
			$smtp->SendMessage(
			$_mail_from,
			array(
			$val
			),
			array(
			"MIME-Version: 1.0",
			"From: CodeZone <$_mail_from>",
			"Reply-To: $_mail_from",
			"To: $val",
			"Subject: $subject",
			"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
		),
		"$body");
		}
		
	}
	else
	{
		foreach($to as $val)
		{
			@mail($val,$subject,$body,"MIME-Version: 1.0\r\nFrom: CodeZone <$_mail_from>\r\nReply-To: $_mail_from\r\nDate: ".strftime("%a, %d %b %Y %H:%M:%S %Z"));
		}
	}
	
	
}

function templateMessages($type)
{
	switch($type)
	{
		case 'register':
			return "lsdjflkdfj";
		default:
			return "";
	}
}

?>
