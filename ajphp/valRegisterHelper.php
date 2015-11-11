<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Registration helper class                   |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

class valRegisterHelper
{
	/**
	 * Save user details
	 */
	 
	function save_user_details()
	{
		global $db,$_mail,$_pre,$valreg,$_allow_user_reg;
		
		//Is user registration allowed...?
		if($_allow_user_reg==0)
		{
			echo "{'warning':'User registration has been disabled. Please contact the administrator'}";
			return;
		}
		//Do validation and add user
		list($full_names,$registration_no,$nick_name,$pass1,$pass2,$email,$unused1,$unused2)=assoc_to_indexed($_POST);
		$error='';
		if(strlen($full_names)<6)
		$error=$error.'Full name invalid, ';
		if(strlen($registration_no)>20 || strlen($registration_no)<3) //Use regex!
		$error.='Registration Number invalid, ';
		if(!checkAlphanumPlus($nick_name) || strlen($nick_name)<2)
		$error=$error.'Nick name invalid or is too short, nick name needs to be at least 5 characters in length and should contain only alphanumeric characters, a full stop or an underscore, ';
		if($pass1!=$pass2)
		$error.='Passwords do not match, ';
		if(strlen($pass1)<5)
		$error.='Password too short, password must be at least 5 characters in length, ';
		if(!checkEmail($email))
		$error=$error.'Email address invalid, ';
		if(strlen($error)>0)
		{
			$error=substr($error,0,strlen($error)-2);
			echo "{'error': '$error'}";
			return;
		}
		else
		{
		  //Check if the registration no provided exists in users table
		  $query="SELECT * FROM ".$_pre."users WHERE registration_no='$registration_no'";
		  $db->setQuery($query);
				
		  if($db->foundRows>0)
		  {
			echo "{'error':'The registration number you provided is already in use'}";
			return;
		  }
		  //Check if the nick name provided exists
		  $query="SELECT * FROM {$_pre}users WHERE nick_name='$nick_name' AND registration_no!='$registration_no'";
		  $db->setQuery($query);
		  if($db->foundRows>0)
		  {
			echo "{'error':'The nick name you provided is already in use'}";
			return;
		  }
		  //Check if the email address provided exists
		  $query="SELECT * FROM ".$_pre."users WHERE email='$email'";
		  $db->setQuery($query);
		  
		  if($db->foundRows>0)
		  {
			echo "{'error':'The email account you provided is already in use'}";
			return;
		  }
		  //Check if the given account has been updated ie activated == 2
		  $query="SELECT * FROM ".$_pre."users WHERE registration_no='$registration_no' AND activated=2";
		  $db->setQuery($query);
		  if($db->foundRows>0)
		  {
			echo "{'warning':'Your account has been created but not yet activated, please activate it'}";
			return;
		  }
		  //Check if the given accout has been activated
		  $query="SELECT * FROM ".$_pre."users WHERE registration_no='$registration_no' AND activated=1";
		  $db->setQuery($query);
		  if($db->foundRows>0)
		  {
			echo "{'error':'What the heck...? Your account is active, please login or if you are not the owner of the registration number you just provided, provide yours!'}";
			return;
		  }     
		  
		$password=encrypt_password($pass1);
		$full_names=strtolower($full_names);
		$registration_no=strtoupper($registration_no);
		$user_type='registered';
		
		$key=md5(time());
		$query="INSERT INTO {$_pre}users (full_names,registration_no,user_type,nick_name,password,email,register_date,last_visit_date,activated,activation_key) VALUES ('$full_names','$registration_no','$user_type','$nick_name','$password','$email',NOW(),NOW(),2,'$key')";
		$db->setQuery($query);
		
		//Create a row in profiles table for this user
		$query="INSERT INTO ".$_pre."profile (registration_no) VALUE ('$registration_no')";
		$db->setQuery($query);
		
		//Send mail to provided account number
		require_once('..'.DS.'lib'.DS.'mail'.DS.'mail.php');
		
		$subject='Your CodeZone account has been created';
		$message="$nick_name,\nYour CodeZone account has been created. To complete the registration, please click on the link below or cut and paste in your browser's location bar to activate your account.\n Link: http://{$_SERVER['HTTP_HOST']}/index.php?a=activate&r=".base64_encode($registration_no)."&k=$key\nYour details are as follows:\nLogin Name (Registration No): $registration_no\nPassword: $pass1\nPlease change your password once you log in for security purposes. If you are having any problems then do not hesitate to contact the admin at $_mail.\n\nWishing you all the best at CodeZone";
		
		mailSend(array($email),$subject,$message);
		
		echo "{'success':'Your account has been created. An activation link has been sent to the email address you provided'}";
		  
		}
		
	}
	
	/**
	 * Do unit validations on single text fields
	 */
	 
	function unitValidate()
	{
		
	}
	
}

?>