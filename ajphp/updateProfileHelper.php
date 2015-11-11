<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: save user profile helper Class              |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

class updateProfileHelper
{
	/** Store user data */
	var $ud=array();
	
	function __construct()
	{
		global $db,$_pre;
		
		//Let's get user data and pack in our $ud class variable
		$registration_no=$_SESSION['user_row_data']['registration_no'];
		
		$query="SELECT * FROM {$_pre}users LEFT JOIN {$_pre}profile ON {$_pre}users.registration_no={$_pre}profile.registration_no WHERE {$_pre}users.registration_no='$registration_no' LIMIT 1";
		
		$db->setQuery($query);
		$this->ud=$db->fetch_assoc();
	}
	/**
	 * Save personal details
	 */

	function save_personal_details($post)
	{
		global $db,$_pre;
		if(base64_decode($post['f'])!='save personal')
		{
			echo "{'error':'Request source unknown'}";
			return;
		}
		$registration_no=$this->ud['registration_no'];
		
		list($full_name,$nick_name,$cur_passwd,$new_passwd1,$new_passwd2,$email,$unused)=assoc_to_indexed($post);
		
		$full_name=strtolower($full_name);
		if(strlen($full_name)<6)
		{
			echo "{'error':'Full names invalid'}";
			return;
		}
		if(strlen($nick_name)<2)
		{
			echo "{'error':'Nick name too short'}";
			return;
		}
		if(!checkAlphanumPlus($nick_name))
		{
			echo "{'error':'nick name should contain only alphanumeric characters, a full stop or an underscore'}";
			return;
		}

		//Has user shown intention to change password...?
		$p_query='';
		if(strlen($cur_passwd)>0)
		{
			if($new_passwd1!=$new_passwd2)
			{
				echo "{'error':'New password did not match'}";
				return;
			}
			if(strlen($new_passwd1)<5)
			{
				echo "{'error':'New password too short'}";
				return;
			}
			if(encrypt_password($cur_passwd)!=$this->ud['password'])
			{
				echo "{'error':'Current password invalid".encrypt_password($cur_passwd)."--".$this->ud['password']."'}";
				return;
			}
			$new_passwd=encrypt_password($new_passwd1);
			$p_query="password='$new_passwd',";
		}
		
		if(!checkEmail($email))
		{
			echo "{'error':'Email address invalid'}";
			return;
		}
		//Check if the nick name provided is in use with another account
		$query="SELECT * FROM {$_pre}users WHERE nick_name='$nick_name' AND registration_no!='{$registration_no}'";
		$db->setQuery($query);
		if($db->foundRows>0)
		{
			echo "{'error':'This nick name is already in use'}";
			return;
		}
		//Check if the email address provided is in use with another account
		$query="SELECT * FROM ".$_pre."users WHERE email='$email' AND registration_no!='{$registration_no}'";
		$db->setQuery($query);
		if($db->foundRows>0)
		{
			echo "{'error':'This email account is already in use'}";
			return;
		}
		
		$query="UPDATE {$_pre}users SET full_names='$full_name',nick_name='$nick_name',{$p_query}email='$email' WHERE registration_no='{$registration_no}'";
		$db->setQuery($query);
		
		//Update user session data to effect immediate changes
		$_SESSION['user_row_data']['full_names']=$full_name;
		$_SESSION['user_row_data']['nick_name']=$nick_name;
		$_SESSION['user_row_data']['email']=$email;
		$_SESSION['user_row_data']['password']=encrypt_password($new_passwd1);
		
		echo "{'success':'Personal details saved'}";
	}
	
	/**
	 * Save avatar
	 */

	function save_avatar($post,$files)
	{
		global $db,$_pre;
		$ud=$this->ud;
		if(base64_decode($post['f'])!='save avatar')
		{
			echo "{'error':'Request source unknown'}";
			return;
		}
		
		$tmp_name=$files['avatar']['tmp_name'];
		$size=$files['avatar']['size'];
		$error=$files['avatar']['error'];
		$type=$files['avatar']['type'];
		
		if($error!=0)
		{
			echo "{'error':'Error in submitted file'}";
			return;
		}
		if($type=='image/jpeg' || $type=='image/png' || $type=='image/gif')
		{}
		else
		{
			echo "{'error':'Invalid file type'}";
			return;
		}
		if($size>1000000)
		{
			echo "{'error':'File size too large'}";
			return;
		}
		//Build the file name to be saved with
		if($type=='image/jpeg')
			$ext='.jpg';
		else if($type=='image/png')
			$ext='.png';
		else
			$ext='.gif';
		$user_no=$ud['id'];
		$users_avatar_name='ecm_member_'.$user_no.'_avatar_'.rand(10,100).''.$ext;
		
		
		//Copy the image to server as it is resized by width but hey! we need to delete the the old avatar if it was previously uploaded
		if($ud['avatar_path']!='')
		@ unlink('..'.DS.'images'.DS.'avatars'.DS.$ud['avatar_path']);
		
		list($width,$height,$type_int,$attr)=getimagesize($tmp_name);
		if($width>250)
		{
			require_once('..'.DS.'lib'.DS.'rvjImageResize.php'); #get the image resizing library
			$objResize = new RVJ_ImageResize($tmp_name, '..'.DS.'images'.DS.'avatars'.DS.$users_avatar_name, 'W', '250');
		}
		else
		{
			//Image dimensions okey, Just copy to server
			copy($tmp_name,'..'.DS.'images'.DS.'avatars'.DS.$users_avatar_name);
		}
		
		//Update user details in profiles table
		$query="UPDATE {$_pre}profile SET avatar_path='$users_avatar_name' WHERE registration_no='{$ud['registration_no']}'";
		$db->setQuery($query);
		
		//Update user session data to effect immediate changes
		$_SESSION['user_row_data']['avatar_path']=$users_avatar_name;
		
		//Delete the upload from tmp
		unlink($tmp_name);
		echo "{'success':'Successfully uploaded avatar',
			'path':'images/avatars/$users_avatar_name'}";
	}
	
	/**
	 * Save extras
	 */

	function save_extras($post)
	{
		global $db,$_pre;
		$ud=@$_SESSION['user_row_data'];
		if(base64_decode($_POST['f'])!='save extras')
		{
			echo "{'error':'Request source unknown'}";
			return;
		}
		list($language,$quote,$about_me,$unused)=assoc_to_indexed($post);
		$language=htmlspecialchars($language);
		$quote=htmlspecialchars($quote);
		$quote=str_replace("\"","",$quote);
		$about_me=htmlspecialchars($about_me);
		
		$query="UPDATE {$_pre}profile SET quote='$quote',about_me='$about_me',language='$language' WHERE registration_no='{$ud['registration_no']}'";
		$db->setQuery($query);
		
		//Update user session data to effect immediate changes
		$_SESSION['user_row_data']['language']=$language;
		$_SESSION['user_row_data']['quote']=$quote;
		$_SESSION['user_row_data']['about_me']=$about_me;
		
		echo "{'success':'Extras saved'}";
	}
	
	/**
	 ** Delete avatar
	 */

	function delete_avatar()
	{
		sleep(1);
		global $db,$_pre;
		$ud=@$_SESSION['user_row_data'];
		
		//Does the user really have an avatar?
		if($ud['avatar_path']=='')
		{
			echo "You have no avatar!";
			return;
		}
		
		//Proceed with delete
		@ unlink('..'.DS.'images'.DS.'avatars'.DS.$ud['avatar_path']);
		
		#Update session variable
		$_SESSION['user_row_data']['avatar_path']='';
		
		#Update profile table and set avatar path to ''
		$query="UPDATE {$_pre}profile SET avatar_path='' WHERE registration_no='{$ud['registration_no']}'";
		$db->setQuery($query);
		echo "done";
	}
	
}

?>