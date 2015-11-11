<?php
/**
 * Function sendMail: Makes use of the SMTP class in /lib/mail to send email using the SMTP protocol, returns boolean
 */

define('DS',DIRECTORY_SEPARATOR);
require_once('..'.DS.'lib'.DS.'mail'.DS.'smtp.php');

function sendMail($hostname,$hostport,$localhost,$smtp_user,$smtp_pass,$sender,$recipients,$subject,$body)
{
// 	ini_set('smtp',$hostname);
	$smtp=new smtp_class;
	
	$smtp->host_name=$hostname;
	$smtp->host_port=$hostport;
	$smtp->localhost=$localhost;
	$smtp->user=$smtp_user;
	$smtp->password=$smtp_pass;
	//$smtp->debug=1; //DEBUG
	
	$date=strftime("%a, %d %b %Y %H:%M:%S %Z");
	$recipients_in_string=implode(', ',$recipients); //Turn recipients array to string comma separated
	$headers=array("From: $sender","To: $recipients_in_string","Subject: $subject","Date: $date");
	if($smtp->SendMessage($sender,$recipients,$headers,$body))
		return true;
	else
	{
		echo $smtp->error; //DEBUG
		return false;
	}
	
}
 ?>