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
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' ); 

/**
 * Class register Helper
 *
 */

class registerHelper
{
  
  /**
   * Function render: renders the registration form
   */

  function render()
  {
	  global $_allow_user_reg;
	  if($_allow_user_reg==0)
	  {
		  system_messages(0,'User registration has been disabled. Please contact the administrator','true');
		  return;
	  }
    ?>
    <link rel="stylesheet" href="theme/jquery.jqtransform/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="js/jquery.jqtransform.js" ></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script language="javascript">
		$(function(){
			$('form').jqTransform({imgPath:'theme/jquery.jqtransform/img/'});
		});
		
		$(document).ready(function()
		{
			$('#modregistration').ajaxForm({
			dataType:'json',
			//beforeSubmit:checkFields,
			success:notifyUser,
			error:alertError,
			});
				
			function notifyUser(data)
			{
				if(data.warning){
				jgrowl_messages(data.warning,'warning',true);
				return;
				}
				if(data.error){
				jgrowl_messages(data.error,'error',true);
				return;
				}
				if(data.success){
				jgrowl_messages(data.success,'success',true);
				}
    				//Now we need to reset the form
				$('#modregistration').find(':input').each(function(){
					switch(this.type){
						case 'text':
						case 'password':
							$(this).val('');
							break;
					}
				
				});
			}
			
			function alertError(e,w,r)
			{
				alert('There was an error communicating to the server');
			}
		});
	</script>
    <form id="modregistration" name="modregistration" class="reg-form" action="ajphp/valRegister.php?a=register&amp;do=save_user_details" method="POST">
   <span class="content-header">CodeZone registration form</span>
  <table border="0" cellpadding="1" cellspacing="5">
  <tr>
  <td><span class="reg-form-field-label-text">Full Names:</span></td>
  <td><div class="rowElem"><input type="text" name="full_names" value="" /></div></td>
  <td><span id="full-name-val">&nbsp;</span></td>
  </tr>
  <tr>
  <td><span class="reg-form-field-label-text">Registration No:</span></td>
  <td><div class="rowElem"><input type="text" name="registration_no" value="" /></div></td>
  <td><span id="registration-no-val">&nbsp;</span></td>
  </tr>
    <tr>
  <td><span class="reg-form-field-label-text">Nick name:</span></td>
  <td><div class="rowElem"><input type="text" name="nick_name" value="" /></div></td>
  <td><span id="nick-name-val">&nbsp;</span></td>
  </tr>
    <tr>
  <td><span class="reg-form-field-label-text">Password:</span></td>
  <td><div class="rowElem"><input type="password" name="password-1" value="" /></div></td>
  <td><span id="pasword-1-val">&nbsp;</span></td>
  </tr>
    <tr>
  <td><span class="reg-form-field-label-text">Confirm Password:</span></td>
  <td><div class="rowElem"><input type="password" name="password-2" value="" /></div></td>
  <td><span id="pasword-2-val">&nbsp;</span></td>
  </tr>
   <tr>
  <td><span class="reg-form-field-label-text">Email:</span></td>
  <td><div class="rowElem"><input type="text" name="email" value="" /></div></td>
  <td><span id="email-val">&nbsp;</span></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>
  <input type="hidden" name="f" value="<?php echo base64_encode('register'); ?>" />
   <input type="hidden" name="p" value="<?php echo base64_encode($_SERVER['REQUEST_URI']); ?>" />
  <div class="rowElem"><input type="submit" value="register" /></div>
  </td>
  <td>&nbsp;</td>
  </tr>
  </table>
  <br />
  </form>
    <?php
  }
  
  /**
   * Show form for requesting lost password
   */
   
   function rpass()
   {
		?>
		<link rel="stylesheet" href="theme/jquery.jqtransform/jqtransform.css" type="text/css" media="all" />
		<script type="text/javascript" src="js/jquery.jqtransform.js" ></script>
		<script type="text/javascript" src="js/jquery.form.js"></script>
		<script language="javascript">
		$(function(){
			$('form').jqTransform({imgPath:'theme/jquery.jqtransform/img/'});
		});
		</script>
		<form name="rpass_form" class="reg-form" action="index.php?a=register&amp;do=rpass_activate" method="POST">
		<p><span class="dark10">Provide your email address below, activation details will be sent to this email address</span></p>
		<div class="rowElem"><input type="text" name="rpass_email" /></div>
		<br />
		<div class="rowElem"><input type="submit" value="submit" /></div>
		</form>
		<?php
   }
   
   /**
    * Send lost password activation email request. If the email address does not exist, do nothing
	*/
	
	function rpass_activate()
	{
		global $db,$_pre,$_mail;
		$email=$_POST['rpass_email'];
		if(!checkEmail($email))
		{
			system_messages(0,'Email address invalid!');
			return;
		}
		
		$query="SELECT * FROM {$_pre}users WHERE email='$email' LIMIT 1";
		$db->setQuery($query);
		
		if($db->foundRows>0)
		{
			$row=$db->fetch_assoc();
			
			//Is the owner of this email address banned...?
			if($row['activated']==-1)
			{
				system_messages(2,'Your account has been blocked by the administrators, you cannot activate it!','true');
				return;
			}
			//Send activation email now first we need to generate another key before sending and another password
			$key=md5(time());
			$pass=random_string();
			$enc_pass=encrypt_password($pass);
			$query="UPDATE {$_pre}users SET activation_key='$key' WHERE email='$email'";
			$db->setQuery($query);
			
			require_once('lib'.DS.'mail'.DS.'mail.php');
		
			$subject='CodeZone account new password request';
			$message="{$row['nick_name']},\nYou or someone claiming to be you has requested a new password for the CodeZone account using this email address ($email). To reset your password, please click on the link below or cut and paste in your browser's location bar.\n Link: http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?a=register&do=rpass_make_active&r=".base64_encode($row['registration_no'])."&k=$key&p=".base64_encode($enc_pass)."\nOnce you click on the link, you will login with the following details:\nLogin Name (Registration No): {$row['registration_no']}\nPassword: $pass\nPlease change your password once you log in for security purposes. If you are having any problems then do not hesitate to contact the admin at $_mail.\n\nWishing you all the best at CodeZone\n\nAdmin";
			
			mailSend(array($email),$subject,$message);
			
			system_messages(1,'An activation link has been sent to your email addresss','true');
		}
		else
		{
			//Even if the email address does not exist, we notify the user that it has been sent. Maybe it's somebody just trying the system
			system_messages(1,'Activation email has been sent');
		}
			
	}
	
	/**
	 * Reset the password of a given account after a request
	 */
	 
	 function rpass_make_active()
	 {
		global $db,$_pre;
		$registration_no=base64_decode($_GET['r']);
		$key=$_GET['k'];
		$password=base64_decode($_GET['p']);
		
		$query="SELECT * FROM {$_pre}users WHERE registration_no='$registration_no' AND activation_key='$key'";
		$db->setQuery($query);
		
		if($db->foundRows<1)
		{
			system_messages(2,'Invalid account activation request!');
			return;
		}
		$key=md5(time()); //Set new key
		$query="UPDATE {$_pre}users SET password='$password',activation_key='$key',activated=1 WHERE registration_no='$registration_no' LIMIT 1";
		$db->setQuery($query);
		
		system_messages(1,'Your password has been reset, You can now proceed to the login page','true');
	 }
  
}

?>
