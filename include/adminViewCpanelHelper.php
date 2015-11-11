<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Administration panel helper class           |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

class adminViewPanelHelper{
	
	/**
	* Validate global configuration
	*
	*/

	function valGlobalConf($formdata)
	{
		//Restore the values of this global variables since the global configuration has changed
		global $_offline,$_allow_user_reg,$_offline_message,$_mail,$_meta_desc,$_max_submissions,$_submission_timeout,$_session_max,$_dbhost,$_dbname,$_dbuser,$_dbpass,$_pre,$_mail_protocol,$_mail_from,$_smtp_username,$_smtp_pass,$_smtp_host,$_smtp_port;

		list($offline,$allow_user_reg,$offline_message,$mail,$meta_desc,$max_submissions,$submission_timeout,$session_max,$dbhost,$dbuser,$dbname,$dbpass,$pre,$mail_protocol,$mail_from,$smtp_username,$smtp_pass,$smtp_host,$smtp_port,$unused_1,$unused_2)=assoc_to_indexed($formdata);
		
		//Do some basic validation
		$errmsg='';
		$offline=($offline=='offline')?1:0;
		$allow_user_reg=($allow_user_reg=='allow')?1:0;
		$offline_message=htmlspecialchars($offline_message);
		//$offline_message=str_replace("'","",$offline_message);
		$meta_desc=htmlspecialchars($meta_desc);
		//$meta_desc=str_replace("'","",$meta_desc);
		
		//if(!preg_match('[a-z]+@+[a-z]',$mail))
		//	$errmsg='Email address invalid';
		settype($max_submissions,'integer');
		settype($submission_timeout,'integer');
		settype($session_max,'integer');
		settype($smtp_port,'integer');
		$configuration="<?php

	/**
	** This configuration file is automatically modifed by CodeZone. Please follow 
	** EDITING INSTRUCTIONS in the CodeZone manual if you have to do it manually
	** Last modified on : ".time_stamp_to_readable(time())."
	**/

	class ecjConfig{

		//Application settings
		var \$offline=$offline;
		var \$allow_user_reg=$allow_user_reg;
		var \$offline_message='$offline_message';
		var \$notify_mail='$mail';
		var \$meta_desc='$meta_desc';
		var \$max_submissions=$max_submissions;
		var \$submission_timeout=$submission_timeout;
		
		//Session settings
		var \$session_lifetime=$session_max;//seconds
		
		//Database settings
		var \$db_host='$dbhost';
		var \$table_prefix='$pre';
		var \$db_user='$dbuser';
		var \$db_pass='$dbpass';
		var \$db_name='$dbname';
		
		//Mail settings
		var \$mail_protocol='$mail_protocol';
		var \$mail_from='$mail_from';
		var \$smtp_username='$smtp_username';
		var \$smtp_pass='$smtp_pass';
		var \$smtp_host='$smtp_host';
		var \$smtp_port=$smtp_port;
		
		//Author details
		var \$author_url='http://sureronald.blogspot.com';
		

	}
		
?>";
		
		//Save changes to configuration.php
		if(!is_writeable('configuration.php'))
		{
			system_messages(2,'Unable to open configuration.php for update. Please check if it exists and its permissions are set appropriately');
			return;
		}
		else
		{
			file_put_contents('configuration.php',$configuration);
		}
		
		//Echo success message
		system_messages(1,'Global Configuration saved');
		
		//Restore global variables after change
		$_offline=$offline;$_allow_user_reg=$allow_user_reg;$_offline_message=$offline_message;$_mail=$mail;$_meta_desc=$meta_desc;$_max_submissions=$max_submissions;$_submission_timeout=$submission_timeout;$_session_max=$session_max;$_dbhost=$dbhost;$_dbname=$dbname;$_dbuser=$dbuser;$_dbpass=$dbpass;$_pre=$pre;$_mail_protocol=$mail_protocol;$_mail_from=$mail_from;$_smtp_username=$smtp_username;$_smtp_pass=$smtp_pass;$_smtp_host=$smtp_host;$_smtp_port=$smtp_port;
	}
	
	/**
	* Validate create match
	*
	*/

	function valCreateMatch($formdata,$files)
	{
		global $db,$_pre,$_max_submissions;
		list($match_name,$duration,$start_date,$start_time,$difficulty,$match_points,$match_ranked)=assoc_to_indexed($formdata);
		
		$errmsg="";
		//Validate match name
		if(strlen($match_name)<2)
		$errmsg.="Match name invalid";
		//Validate duration
		settype($duration,'integer');
		if($duration<600)
		$errmsg.=", Duration invalid";
		//Validate start date
		if(!check_date($start_date))
		$errmsg.=", Invalid date";
		//Validate start time
		if(!check_time($start_time))
		$errmsg.=", Invalid time";
		//Join start date and start time
		$full_date=$start_date." ".$start_time;
		//Validate match difficulty : scale of 0-100, but min is 10
		settype($difficulty,'integer');
		if($difficulty<10 || $difficulty>100)
		$errmsg.=", Difficulty invalid";
		//Validate match points
		settype($match_points,'integer');
		if($match_points<100 || $match_points>999)
		$errmsg.=", Match points invalid";
		//Validate match ranked
		$match_ranked=($match_ranked!='0' && $match_ranked!='1')?'0':$match_ranked;
		
		/**Validate submitted files*/
		$problem_sheet=$files['problem_sheet'];
		$answer_sheet=$files['answer_sheet'];
		$input_sheet=$files['input_sheet'];
		
		//Check for errors
		if($problem_sheet['error']!=0 || $answer_sheet['error']!=0 || $input_sheet['error']!=0)
		$errmsg.=", Error uploading file or no file supplied";
		//verify that size is not zero
		if($problem_sheet['size']==0 || $answer_sheet['size']==0 || $input_sheet['size']==0)
		$errmsg.=", Invalid file size (=0kB)";
		//Verify that size is not exceeding 100Kilobytes(100000bytes)
		if($problem_sheet['size']>100000 || $answer_sheet['size']>100000 || $input_sheet['size']>100000)
		$errmsg.=", Invalid file size (>100kB)";
		//Verify file types
		if($problem_sheet['type']!="text/html" || $answer_sheet['type']!="text/plain" || $input_sheet['type']!="text/plain")
		$errmsg.=", File type invalid";
		//Check that the file was uploaded via HTTP POST... i.e. we are not being attacked... funny?
		if(!is_uploaded_file($problem_sheet['tmp_name']) && !is_uploaded_file($answer_sheet['tmp_name']) && !is_uploaded_file($input_sheet['tmp_name']))
		$errmsg.=", Invalid upload! NOT ALLOWED";
		//Initialize upload path
		$upload_path='competition_uploads'.DS;
		
		//Check if server is unwriteable
		if(!is_writeable($upload_path))
		$errmsg.=", Server unwriteable";
		
		if(strlen($errmsg)>0)
		{
			system_messages(0,$errmsg,'true');
			return;
		}
		
		/**Now we can create the match... fun? */
		//Upload files and insert details to matches table... but first we need to check  if there's a match with similar title
		$query="SELECT * FROM ".$_pre."matches WHERE title='$match_name'";
		$db->setQuery($query);
		if($db->foundRows>0)
		{
			system_messages(2,"\"There is a match with this title\"");
			return;
		}
		$problem_sheet_tmp_path=$problem_sheet['tmp_name'];
		$answer_sheet_tmp_path=$answer_sheet['tmp_name'];
		$input_sheet_tmp_path=$input_sheet['tmp_name'];
		
		$problem_sheet_new_name=sha1($problem_sheet_tmp_path).'.php';
		$answer_sheet_new_name=sha1($answer_sheet_tmp_path).'.txt';
		$input_sheet_new_name=sha1($input_sheet_tmp_path).'.txt';
		
		/**Perform uploads */
		//Get the zipping library
		require_once('lib'.DS.'createZipArchive.php');
		$new_dir_name=substr(md5($match_name).time(),15);
		$uploads_dir=$upload_path.$new_dir_name.DS;
		
		if(!mkdir($upload_path.$new_dir_name, 0700))
		{
			system_messages(0,"Unable to create match folder");
			return;
		}
		
		
		if(!copy($problem_sheet_tmp_path,$uploads_dir.$problem_sheet_new_name) || !copy($answer_sheet_tmp_path,$uploads_dir.$answer_sheet_new_name) || !copy($input_sheet_tmp_path,$uploads_dir.$input_sheet_new_name))
		{
			system_messages(0,"Unable to copy/upload file");
			return;
		}
		
		//Copy index.html to the match directory for security purposes
		copy($upload_path.'index.html',$upload_path.$new_dir_name.DS.'index.html');
		//Create the files to be downloaded i.e. equivalent to no of submissions in global settings
		
// 		for($i=1;$i<=$_max_submissions;$i++)
// 		{
// 			copy($input_sheet_tmp_path,$uploads_dir."ecm-attempt-$i-".substr($input_sheet_new_name,15));
// 		}
		
		//Zip the files just uploaded
// 		chdir($uploads_dir); #IMPORTANT!
// 		$zip_archive_name=str_replace('.txt','.zip',$input_sheet_new_name);
// 		
// 		create_zip_archive(array($input_sheet_new_name),$zip_archive_name,true); #zip the original input file
// 		for($i=1;$i<=$_max_submissions;$i++)
// 		{
// 			create_zip_archive(array("ecm-attempt-$i-".substr($input_sheet_new_name,15)),"ecm-attempt-$i-".substr($zip_archive_name,15),true);
// 			@ unlink("ecm-attempt-$i-".substr($input_sheet_new_name,15));
// 		}
// 		unlink($input_sheet_new_name);
		
		//Continue with insert
		$match_table_name=$new_dir_name;
		$new_start_time=make_time($full_date);
		$query="INSERT INTO ".$_pre."matches (title,duration,start_time,difficulty,match_points,match_ranked,problem_sheet,answer_sheet,input_sheet,match_table_name,max_submissions) VALUES ('$match_name','$duration','$new_start_time','$difficulty','$match_points','$match_ranked','$problem_sheet_new_name','$answer_sheet_new_name','$input_sheet_new_name','$match_table_name',".($_max_submissions+1).")"; //Note the +1 on max submissions
		$db->setQuery($query);
		
		//Create the match table
		$query="CREATE TABLE `".$_pre.$match_table_name."` (
		`id` int(8) NOT NULL auto_increment,
		`registration_no` varchar(20) NOT NULL,
		`nick_name` varchar(100) NOT NULL,
		`language` varchar(255) NOT NULL,
		`disqualified` tinyint default 0,
		`downloads_count` tinyint default 0,
		`files` varchar(256) NOT NULL,
  		`actual_file` varchar(256) NOT NULL,
		`submissions` tinyint(4) default 0,
		`correct` tinyint default 0,
		`last_submission_time` int(11) default 0,
		`time_taken` float(8,2) default 0,
		`points` float(8,2) default 0,
		PRIMARY KEY  (`id`)
		)";
		$db->setQuery($query);
		
		system_messages(1,"Match successfully created");
	}
	
		
	
	/**
	* Render Global Configuration form
	*
	*/
	
	function renderGlobalConf(){
       global $_offline,$_allow_user_reg,$_offline_message,$_mail,$_meta_desc,$_max_submissions,$_submission_timeout,$_session_max,$_dbhost,$_dbname,$_dbuser,$_dbpass,$_pre,$_mail_protocol,$_mail_from,$_smtp_username,$_smtp_pass,$_smtp_host,$_smtp_port;
		?>
		<form class='admin-panel-form' name='global-conf' method='POST' action='index.php?a=su&amp;a1=global-conf'>
  <table class='admin-panel-forms-table' cellpadding="2" cellspacing="2" border="0">
  <tr><td class='form-field-label'><span class='form-field-label-text'>CodeZone offline</span></td><td><span class="form-inline-text">Yes&nbsp;</span><input type="radio" name="global-offline" class="admin-panel-radio" value="offline" <?php if($_offline==1) echo 'checked=\'true\''; ?> />&nbsp;&nbsp;&nbsp;<span class="form-inline-text">No&nbsp;</span><input type="radio" name="global-offline" class="admin-panel-radio" value="online"  <?php if($_offline==0) echo 'checked=\'true\''; ?> /></td></tr>
<tr><td class='form-field-label'><span class='form-field-label-text'>Allow user registration</span></td><td><span class="form-inline-text">Yes&nbsp;</span><input type="radio" name="global-allow-user-reg" class="admin-panel-radio" value="allow" <?php if($_allow_user_reg==1) echo 'checked=\'true\''; ?> />&nbsp;&nbsp;&nbsp;<span class="form-inline-text">No&nbsp;</span><input type="radio" name="global-allow-user-reg" class="admin-panel-radio" value="disallow"  <?php if($_allow_user_reg==0) echo 'checked=\'true\''; ?> /></td></tr>
  <tr>
  <td class='form-field-label'><span class='form-field-label-text'>Offline Message</span></td><td><textarea name="global-offline-message" class="admin-panel-textarea" ><?php echo $_offline_message; ?></textarea></td>
  </tr>
  <tr>
  <td class="form-field-label"><span class="form-field-label-text">Notify mail</span></td><td><input type="text" name="global-notify-mail" class="admin-panel-text-input" value="<?php echo $_mail; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Meta Description</span></td><td><input type="text" name="global-meta-desc" class="admin-panel-text-input" value="<?php echo $_meta_desc; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Max  submissions</span></td><td><input type="text" name="global-max-submissions" class="admin-panel-text-input" value="<?php echo $_max_submissions; ?>"/></td>
  </tr>
  <tr>
  <td class="form-field-label"><span class="form-field-label-text">Submission timeout (sec)</span></td><td><input type="text" name="global-submissions-timeout" class="admin-panel-text-input" value="<?php echo $_submission_timeout; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Session Lifetime (sec)</span></td><td><input type="text" name="global-session-lifetime" class="admin-panel-text-input" value="<?php echo $_session_max; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Database Host Name</span></td><td><input type="text" name="global-db-hostname" class="admin-panel-text-input" value="<?php echo $_dbhost; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Database username</span></td><td><input type="text" name="global-db-username" class="admin-panel-text-input" value="<?php echo $_dbuser; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Database Name</span></td><td><input type="text" name="global-db-name" class="admin-panel-text-input" value="<?php echo $_dbname; ?>"/></td>
  </tr>
   <tr>
  <td class="form-field-label"><span class="form-field-label-text">Database password</span></td><td><input type="password" name="global-db-pass" class="admin-panel-text-input" value="<?php echo $_dbpass; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Database table prefix</span></td><td><input type="text" name="global-db-table-prefix" class="admin-panel-text-input" value="<?php echo $_pre; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">Mail protocol</span></td><td><select name="global-mail-protocol" class="admin-panel-select"><option value="php_mail" <?php if($_mail_protocol=='php_mail') echo "selected=\"selected\""; ?>>PHP MAIL</option><option value="smtp" <?php if($_mail_protocol=='smtp') echo "selected=\"selected\""; ?>>SMTP</option></select></td>
  </tr>
  <tr>
  <td class="form-field-label"><span class="form-field-label-text">Mail from</span></td><td><input type="text" name="global-mail-from" class="admin-panel-text-input" value="<?php echo $_mail_from; ?>"/></td>
</tr>
  <tr>
  <td class="form-field-label"><span class="form-field-label-text">SMTP username</span></td><td><input type="text" name="global-smtp-username" class="admin-panel-text-input" value="<?php echo $_smtp_username; ?>"/></td>
  </tr>
  <tr>
  <td class="form-field-label"><span class="form-field-label-text">SMTP password</span></td><td><input type="password" name="global-smtp-password" class="admin-panel-text-input" value="<?php echo $_smtp_pass; ?>"/></td>
  </tr>
    <tr>
  <td class="form-field-label"><span class="form-field-label-text">SMTP host</span></td><td><input type="text" name="global-smtp-host" class="admin-panel-text-input" value="<?php echo $_smtp_host; ?>"/></td>
  </tr>
<tr>
  <td class="form-field-label"><span class="form-field-label-text">SMTP port</span></td><td><input type="text" name="global-smtp-port" class="admin-panel-text-input" value="<?php echo $_smtp_port; ?>" /></td>
</tr>
        <tr>
      <td><input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('global_conf'); ?>" /></td>
      <td><input type="submit" class="admin-panel-submit-button" value="save" />
      </td>
      </tr>
  </table>
  </form>
		<?php
	}
	
	/**
	* Update user details
	*
	*/
	
	function editUserDetails()
	{
		global $db,$_pre;
		$registration_no=base64_decode($_POST['reg']);
		@list($full_names,$user_type,$nick_name,$email,$pass1,$ranking_pts,$pass2,$blocked,$unused1,$unused2,$unused3,$unused4)=assoc_to_indexed($_POST);
		//If request is to DELETE a user account...
		if(isset($_POST['delete_user']))
		{
			$query="DELETE FROM {$_pre}users WHERE registration_no='$registration_no'";
			$query2="DELETE FROM {$_pre}profile WHERE registration_no='$registration_no'";
			$db->setQuery($query);
			$db->setQuery($query2);
			system_messages(1,"$full_names -> $registration_no -> $nick_name successfully deleted",'true');
			return;
		}
		if(strlen($full_names)<6)
		{
			system_messages(0,'Full name too short');
			return;
		}
		if(strlen($nick_name)<3)
		{
			system_messages(0,'Nick name too short');
			return;
		}
		$change_password=false;
		if(strlen($pass1)>0)
		{
			if($pass1!=$pass2)
			{
				system_messages(0,'Passwords do not match');
				return;
			}
			if(strlen($pass1)<6)
			{
				system_messages(0,'Password too short');
				return;
			}
			//Password should therefore be changed
			$change_password=true;
		}
		$user_type=(($user_type=='registered' || $user_type=='su')?$user_type:'registered');
		
		if(strlen($email)>0)
		{
			if(!checkEmail($email))
			{
				system_messages(0,'Email address invalid');
				return;
			}
			//Check if the email address provided is in use with another account
			$query="SELECT * FROM {$_pre}users WHERE email='$email' AND registration_no!='$registration_no'";
			$db->setQuery($query);
			if($db->foundRows>0)
			{
				system_messages(0,"This email account ($email) is already in use");
				return;
			}
		}
		settype($ranking_pts,'integer');
		if($ranking_pts<0)
		{
			system_messages(0,'Ranking points invalid');
			return;
		}
		$change_block=false;
		if(isset($_POST['user_block']))
		{
			settype($blocked,'integer');
			$blocked=(($blocked==-1 || $blocked==1)?$blocked:1);
			$change_block=true;
		}
		
		//Update user details in database
		if($change_block)
			$change_block_query=",activated=$blocked";
		else
			$change_block_query='';
		if($change_password)
			$change_password_query=",password='".encrypt_password($pass1)."'";
		else
			$change_password_query='';
		//Update users table
		$query="UPDATE {$_pre}users SET full_names='$full_names',user_type='$user_type',nick_name='$nick_name',email='$email'$change_block_query$change_password_query WHERE registration_no='$registration_no'";
		$db->setQuery($query);
		//update profile table
		$query="UPDATE {$_pre}profile SET ranking_pts=$ranking_pts WHERE registration_no='$registration_no'";
		$db->setQuery($query);
		//Echo success message
		system_messages(1,"Details for $full_names -> $registration_no -> $nick_name successfully updated",'true');
	}
	
	/**
	* Add bulk users to CodeZone
	*/
	
	function addBulkUsers()
	{
		global $db,$_pre;
		if(!isset($_POST['bulk']))
		{
			system_messages(0,'Nothing supplied!');
			return;
		}
		$bulk=trim($_POST['bulk']);
		if($bulk=='')
		{
			system_messages(0,'No registration number supplied');
			return;
		}
		$activated=0;
		if(isset($_POST['activated']))
			$activated=(($_POST['activated']=='1')?1:0);
		$parts=explode(',',$bulk);
		//Remove empty registration numbers
		$valid_regs=array();
		foreach($parts as $val)
		{
			if(trim($val)!='')
				array_push($valid_regs,strtoupper($val));
		}
		if(count($valid_regs)==0)
		{
			system_messages(0,'No valid registration number supplied');
			return;
		}
		//Get current ecm users and check to make sure we are not adding an existing registration number
		$query="SELECT registration_no FROM {$_pre}users";
		$db->setQuery($query);
		$ecm_users=array();
		while($row=$db->fetch_assoc())
			array_push($ecm_users,$row['registration_no']);
		$existing_regs='';
		for($i=0;$i<count($valid_regs);$i++)
		{
			if(in_array($valid_regs[$i],$ecm_users))
				$existing_regs.=$valid_regs[$i].' ';
		}
		if(strlen($existing_regs)>0)
		{
			system_messages(0,"The following registration number(s) exist: $existing_regs",'true');
			return;
		}
		
		foreach($valid_regs as $val)
		{
			$registration_no=$val;
			$full_names='';
			$nick_name='cz'.rand(1,1000).'_'.rand(100000,10000000);
			$user_type='registered'; //Default. Any changes to this should be made through the control panel
			$activation_key=md5($nick_name);
			$password=encrypt_password(strtolower($registration_no));
			
			$query="INSERT INTO {$_pre}users (full_names,registration_no,user_type,nick_name,password,register_date,last_visit_date,activated,activation_key) VALUES ('$full_names','$registration_no','$user_type','$nick_name','$password',NOW(),NOW(),$activated,'$activation_key')";
			$query2="INSERT INTO {$_pre}profile (registration_no) VALUES ('$registration_no')";
			$db->setQuery($query);
			$db->setQuery($query2);
		}
		
		system_messages(1,'Successfully added '.count($valid_regs).' user(s)','true');
	}
	
	/**
	* Delete bulk users
	*
	*/
	
	function deleteBulkUsers()
	{
		global $db,$_pre;
			
		//Has a selection been made?
		if(!isset($_POST['ubox']))
		{
			system_messages(0,'Please select user(s) to delete');
			return;
		}
		
		$users_to_del=$_POST['ubox'];
		
		//Proceed and delete the users
		foreach($users_to_del as $val)
		{
			$query[0]="DELETE FROM {$_pre}users WHERE registration_no='".mysql_real_escape_string(base64_decode($val),$db->link)."'";
			$query[1]="DELETE FROM {$_pre}profile WHERE registration_no='".mysql_real_escape_string(base64_decode($val),$db->link)."'";
			$db->setQuery($query[0]);
			$db->setQuery($query[1]);
		}
		system_messages(1,'Successfully deleted '.count($users_to_del).' user(s)');
	}
	
	/**
	* Render Create new match form
	*
	*/

	function renderCreateMatch(){
		?>
		<form class='admin-panel-form' name='new-match' method='POST' action="index.php?a=su&amp;a1=new-match" enctype="multipart/form-data">
  <table class='admin-panel-forms-table' cellpadding="2" cellspacing="2" border="0">
  <tr><td class='form-field-label'><span class='form-field-label-text'>Match name</span></td><td><input type="text" name="match-name" class="admin-panel-text-input" /></td></tr>
  <tr><td class='form-field-label'><span class='form-field-label-text'>Match duration (sec)</span></td><td><input type="text" name="match-duration" class="admin-panel-text-input" /></td></tr>
  <tr><td class='form-field-label'><span class='form-field-label-text'>Start date (yyyy-mm-dd) </span></td><td><input type="text" name="match-start-date" class="admin-panel-text-input" /></td></tr>
    <tr><td class='form-field-label'><span class='form-field-label-text'>Start time (hh:mm:ss) </span></td><td><input type="text" name="match-start-time" class="admin-panel-text-input" /></td></tr>
    <tr><td class='form-field-label'><span class='form-field-label-text'>Difficulty(10-100)</span></td><td><input type="text" name="match-difficulty" class="admin-panel-text-input" /></td></tr>
    <tr><td class='form-field-label'><span class='form-field-label-text'>Match Points(3 digit)</span></td><td><input type="text" name="match-points" class="admin-panel-text-input" /></td></tr>
    <tr><td class='form-field-label'><span class='form-field-label-text'>Ranked match?</span></td><td><span class="form-inline-text">Yes&nbsp;</span><input type="radio" name="match-ranked" class="admin-panel-radio" value="1" checked="true" />&nbsp;&nbsp;&nbsp;<span class="form-inline-text">No&nbsp;</span><input type="radio" name="match-ranked" class="admin-panel-radio" value="0" /></td></tr>
     <tr><td class='form-field-label'><span class='form-field-label-text'>Problem sheet (HTML file)</span></td><td><input type="file" name="problem_sheet" class="admin-panel-text-input" /></td></tr>
      <tr><td class='form-field-label'><span class='form-field-label-text'>Answer sheet (text file)</span></td><td><input type="file" name="answer_sheet" class="admin-panel-text-input" /></td></tr>
      <tr><td class='form-field-label'><span class='form-field-label-text'>Input sheet (text file)</span></td><td><input type="file" name="input_sheet" class="admin-panel-text-input" /></td></tr>
      <tr>
      <td><input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('create_match'); ?>" /></td><td><input type="submit" class="admin-panel-submit-button" value="create" /></td><!--End create match td-->
      </tr>
  </table>
  </form>
		<?php
	}

	
	/**
	 * Render the user administration form 
	 */
	
	function renderUserAdministration()
	{
		global $db,$_pre;
		$page_limit=((isset($_GET['page_limit']))?(int)$_GET['page_limit']:10); //Default is 10
		$page_num=((isset($_GET['page_num']))?(int)$_GET['page_num']:1); //Default page is 1
		if($page_limit<0 || $page_limit>50)
			$page_limit=0;
		$filter_string=((isset($_GET['filter']) && $_GET['a1']=='users')?$_GET['filter']:"");
		
		$filter_query=((strlen($filter_string)>0)?"WHERE nick_name LIKE '%$filter_string%' or registration_no LIKE '%$filter_string%' or full_names LIKE '%$filter_string%'":"");
		$query="SELECT * FROM {$_pre}users $filter_query ORDER BY nick_name";
		$db->setQuery($query);
		$num_rows=$db->foundRows;
		if($num_rows==0) //Avoid division by zero here below
			$num_rows=1;
		$last_page=($page_limit!=0)?ceil($num_rows/$page_limit):1;
		if($page_num<1)
			$page_num=1;
		if($page_num>$last_page)
			$page_num=$last_page;
		if($page_limit!=0)
			$maximum='LIMIT '.($page_num-1)*$page_limit.','.$page_limit;
		else
			$maximum='';
		$query="SELECT * FROM {$_pre}users $filter_query ORDER BY nick_name $maximum";
		$db->setQuery($query);
		$found_rows=$db->foundRows;
		?>
		<div id='user_admin_panel'>
		<div id="loading-bar"><img src="images/progress.gif" /></div><!--Progress bar -->
		<!--User details are loaded here for editing-->
		<div id='edit_user_form'></div>
				Filter: <input id="user_filter_input" onchange="submitFilter('user_filter_input','index.php?a=su&amp;a1=users&amp;page_limit=<?php echo $page_limit; ?>&amp;page_num=<?php echo $page_num; ?>');" type="text" class="admin-panel-text-input" style="width:70px;" value="<?php echo $filter_string; ?>" />&nbsp;<button class="admin-panel-button" onclick="submitFilter('user_filter_input','index.php?a=su&amp;a1=users&amp;page_limit=<?php echo $page_limit; ?>&amp;page_num=<?php echo $page_num; ?>');">go</button>&nbsp;<button class="admin-panel-button" onclick="submitFilterReset('index.php?a=su&amp;a1=users&amp;page_limit=<?php echo $page_limit; ?>&amp;page_num=<?php echo $page_num; ?>');">reset</button>
		<form id="delete_bulk_users" name="delete_bulk_users" method="POST" action="index.php?a=su&amp;a1=users&amp;do=delete_bulk_users" >
		<table class='user_row_details' border="0" cellpadding="3" cellspacing="0">
		<tr class="header">
				<td>#</td><td><input type="checkbox" name="check_all" onclick="checkAll('ubox',<?php echo $found_rows; ?>,this.checked)" /></td><td>Nick name</td><td>Registration No</td><td>User type</td><td>Status</td><td>Email</td><td>Last visit date</td><td>ID</td>
		</tr>
		<?php
		$i=($page_num-1)*$page_limit+1;
		$u=1;
		while($row=$db->fetch_assoc())
		{
			$acc_state=$row['activated'];
			if($acc_state==1)
				$activated="<span class='acc_active'>Active</span>";
			else if($acc_state==0)
				$activated="<span class='acc_unvisited'>Unvisited</span>";
			else if($acc_state==1)
				$activated="<span class='acc_active'>Active</span>";
			else if($acc_state==2)
				$activated="<span class='acc_inactive'>Inactive</span>";
			else
				$activated="<span class='acc_disabled'>Disabled</span>";
			
			echo "<tr class='user_row_data'>";
			echo "<td>$i</td><td><input type='checkbox' name='ubox[]' id='ubox$u' value='".base64_encode($row['registration_no'])."' /></td><td>{$row['nick_name']}</td><td><a href='javascript:void(0);' class='edit_user' onclick=\"editUser('".base64_encode($row['registration_no'])."')\" title='click to edit {$row['nick_name']} details'>{$row['registration_no']}</a></td><td>{$row['user_type']}</td><td>$activated</td><td><a href='mailto:{$row['email']}'>{$row['email']}</a></td><td class='greyish'>".(($row['last_visit_date']=='0000-00-00 00:00:00')?'never':$row['last_visit_date'])."</td><td class='greyish'>{$row['id']}</td>";
			echo "</tr>";
			$i++;$u++;
		}
		echo "</table>";
		?>
		<input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('delete_bulk_users'); ?>" />
		<div align="right"><input type="submit" class="admin-panel-submit-button" value="delete selected user(s)" /></div>
		</form>
		<div align="center" class="limit">
		<p class="pagination_links">
		<?php
		if($page_num==1)
			echo "<span class='dead'>back  </span>";
		else
			echo "<a href='index.php?a=su&a1=users&page_limit=$page_limit&page_num=".($page_num-1)."&filter=$filter_string' class='active'>back  </a>";
		for($i=1;$i<=$last_page;$i++)
		{
			if($page_num==$i)
				echo "<span class='dead'>$i  </span>";
			else
				echo "<a href='index.php?a=su&a1=users&page_limit=$page_limit&page_num=$i&filter=$filter_string' class='active'>$i  </a>";
		}
		if($page_num==$last_page)
			echo "<span class='dead'>next  </span>";
		else
			echo "<a href='index.php?a=su&a1=users&page_limit=$page_limit&page_num=".($page_num+1)."&filter=$filter_string' class='active'>next  </a>";
		?></p>
		Display #<select name="limit" class="admin-panel-select" id="render_users" onchange="submitForm('index.php?a=su&amp;a1=users',this.value);"><option value="5" <?php echo (($page_limit==5)?"selected='selected'":'') ?> >5</option><option value="10" <?php echo (($page_limit==10)?"selected='selected'":'') ?> >10</option><option value="15" <?php echo (($page_limit==15)?"selected='selected'":'') ?> >15</option><option value="20" <?php echo (($page_limit==20)?"selected='selected'":'') ?> >20</option><option value="25" <?php echo (($page_limit==25)?"selected='selected'":'') ?> >25</option><option value="30" <?php echo (($page_limit==30)?"selected='selected'":'') ?> >30</option><option value="50" <?php echo (($page_limit==50)?"selected='selected'":'') ?> >50</option><option value="0" <?php echo (($page_limit==0)?"selected='selected'":'') ?> >all</option></select></div>
		<br />
		<form id="add_bulk_users" name="add_bulk_users" method="POST" action="index.php?a=su&amp;a1=users&amp;do=add_bulk_users" >
		<span class="dark10">Paste or edit registration number(s) here comma separated. User account will be created with default values. Password==Registration number. Each registration number should be less or equal 20 characters</span><br />
		<textarea id="bulk_users" name="bulk" class="admin-panel-textarea" style="width:400px; height: 100px;"><?php if(isset($_POST['bulk'])) echo $_POST['bulk']; ?></textarea><br />
		Activated? <input type="checkbox" value="1" name="activated" /><br />
		<input type="submit" class="admin-panel-submit-button" value="add user(s)" />
		<input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('add_bulk_users'); ?>" />
		</form>
		<script type="text/javascript">
		//Load user edit form
		function editUser(base64_reg_no)
		{
			$('#edit_user_form').html("<img src='images/progress.gif' />").load('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=edit_user_form&reg='+base64_reg_no);
		}
		</script>
		
		<?php
		echo "</div>";
	}
	
	/**
	 * Show all the matches in a select box
	 */
	
	function showMatches()
	{
		global $db,$_pre;
		$query="SELECT * FROM {$_pre}matches ORDER by start_time DESC";
		$db->setQuery($query);
		echo "<div id='all_matches_list'>";
		echo "<select name='all_matches' id='matches_list' class='admin-panel-select' onChange='loadMatchForm(this.value);'>";
		echo "<option value='none'>Please select a match to edit</option>";
		while($row=$db->fetch_assoc())
			echo "<option value='{$row['id']}'>CodeZone match {$row['id']} .::. {$row['title']}</option>";
		echo "</select>";
		echo "<p></p>";
		echo "</div>";
		
		echo "<div id='edit_match_form'></div>";
	}
	
	/**
	 * Save edited match details
	 */
	
	function valEditMatch()
	{
		global $db,$_pre;
		list($title,$duration,$start_date,$start_time,$difficulty,$match_points,$match_ranked,$analysis,$unused_1,$unused_2,$match_id,$action)=assoc_to_indexed($_POST);
		
		$match_id=base64_decode($match_id);
		settype($match_id,'integer');
		
		//If action is delete, do and return
		if($action=='Delete this match?')
		{
			//Get match table name first
			$query="SELECT match_table_name FROM {$_pre}matches WHERE id=$match_id";
			$db->setQuery($query);
			$row=$db->fetch_assoc();
			$match_table_name=$row['match_table_name'];
			//Delete records from matches table
			$query="DELETE FROM {$_pre}matches WHERE id=$match_id";
			$db->setQuery($query);
			//Drop the match table
			$query="DROP TABLE {$_pre}$match_table_name";
			$db->setQuery($query);
			//Remove logs with this match ID
			$query="DELETE FROM {$_pre}user_match_log WHERE match_id=$match_id";
			$db->setQuery($query);
			
			//Rename this match's table to have suffix ".old" so it can be deleted later with a script or manually
			rename("competition_uploads".DS.$match_table_name,"competition_uploads".DS.$match_table_name.".old");
			
			system_messages(1,"Match number $match_id successfully deleted");
			return;
		}
		$errmsg="";
		//Validate match name
		if(strlen($title)<2)
			$errmsg.="Match name too short";
		//Validate duration
			settype($duration,'integer');
		if($duration<600)
			$errmsg.=", Duration invalid";
		//Validate start date
		if(!check_date($start_date))
			$errmsg.=", Invalid date";
		//Validate start time
		if(!check_time($start_time))
			$errmsg.=", Invalid time";
		//Join start date and start time
		$full_date=$start_date." ".$start_time;
		//Validate match difficulty : scale of 0-100, but min is 10
		settype($difficulty,'integer');
		if($difficulty<10 || $difficulty>100)
			$errmsg.=", Difficulty invalid";
		//Validate match points
		settype($match_points,'integer');
		if($match_points<100 || $match_points>999)
			$errmsg.=", Match points invalid";
		//Validate match ranked
		$match_ranked=($match_ranked!='0' && $match_ranked!='1')?'0':$match_ranked;
		
		//Validate match analysis
		$analysis_text=strip_tags($analysis,"<p><a><strong><i><br><div><pre>"); //Strip HTML tags
		if(strlen($errmsg)>0)
		{
			system_messages(0,$errmsg,'true');
			return;
		}
		//Update match details
		$query="UPDATE {$_pre}matches SET title='$title',duration=$duration,start_time=".make_time($full_date).",difficulty=$difficulty,match_points=$match_points,match_ranked=$match_ranked,analysis='$analysis' WHERE id=$match_id";
		$db->setQuery($query);
		
		//We also need to update user_match_log table match_date column to the new changes
		$query="UPDATE {$_pre}user_match_log SET match_date=".make_time($full_date)." WHERE match_id=$match_id";
		$db->setQuery($query);
		//Echo success message
		system_messages(1,'Match details saved');
	}
	
	/**
	 * Save news story
	 */
	
	function saveNewStory()
	{
		global $db,$_pre;
		$story_title=strip_tags($_POST['story_title']);
		$story_state=(int)$_POST['story_state'];
		$story_state=(($story_state==1)?1:0);
		$story_text=strip_tags($_POST['story_text'],"<p><a><strong><i><br><rmore>"); //Strip HTML tags
		$author_reg_no=$_SESSION['user_row_data']['registration_no'];
		//some validation
		if(strlen($story_title)==0 || strlen($story_text)==0)
		{
			system_messages(0,'Title or Content missing');
			return;
		}
		
		$query="INSERT INTO {$_pre}stories (title,content,published,create_time,registration_no) VALUES ('$story_title','$story_text',$story_state,".time().",'$author_reg_no')";
		$db->setQuery($query);
		system_messages(1,'Story saved');
		//print_r($_POST);
		
	}
	
	/**
	 * Update news story
	 */
	
	function saveUpdateStory()
	{
		global $db,$_pre;
		$story_id=(int)$_POST['s_id'];
		$story_title=strip_tags($_POST['story_title']);
		$story_state=(int)$_POST['story_state'];
		$story_state=(($story_state==1)?1:0);
		$story_text=strip_tags($_POST['story_text'],"<p><a><strong><i><br><rmore>"); //Remove unwanted HTML tags
		//$story_text=stripslashes($story_text);
		//$story_text=addcslashes($story_text,"'");
		//some validation
		if(strlen($story_title)==0 || strlen($story_text)==0)
		{
			system_messages(0,'Title or Content missing');
			return;
		}
		
		$query="UPDATE {$_pre}stories SET title='$story_title',content='$story_text',published=$story_state WHERE id=$story_id";
		$db->setQuery($query);
		system_messages(1,'Changes to story saved');
	}
	
	/**
	 * Modify news story: can delete news story(s), publish news story(s) and also unpublish news story(s)
	 */
	
	function modifyNewsStories()
	{
		global $db,$_pre;
		
		if(!isset($_POST['sbox']))
		{
			system_messages(0,'Please select a story');
			return;
		}
		$stories=$_POST['sbox'];
		$story_action=@$_POST['story_modify_action'];
		
		//Is the action known...?
		if($story_action=='delete')
		{
			$query="DELETE FROM {$_pre}stories WHERE id=";
			$notify_message='deleted';
		}
		else if($story_action=='publish')
		{
			$query="UPDATE {$_pre}stories SET published=1 WHERE id=";
			$notify_message='published';
		}
		else if($story_action=='unpublish')
		{
			$query="UPDATE {$_pre}stories SET published=0 WHERE id=";
			$notify_message='unpublished';
		}
		else
		{
			system_messages(2,'Unknown action requested');
			return;
		}
		
		//Now we can proceed
		foreach($stories as $val)
		{
			$id=(int) base64_decode($val);
			$tmp_query=$query;
			$tmp_query.=$id; //Now we can complete our query
			$db->setQuery($tmp_query);
		}
		
		system_messages(1,"Successfully $notify_message ".count($stories)." story(s)");
		
	}
	
	/**
	 * Render news stories in the administration panel
	 */
	
	function renderNewsStories()
	{
		global $db,$_pre;
		$page_limit=((isset($_GET['page_limit']))?(int)$_GET['page_limit']:10); //Default is 10
		$page_num=((isset($_GET['page_num']))?(int)$_GET['page_num']:1); //Default page is 1
		if($page_limit<0 || $page_limit>50)
			$page_limit=0;
		$filter_string=((isset($_GET['filter']) && $_GET['a1']=='stories')?$_GET['filter']:"");
		
		$filter_query=((strlen($filter_string)>0)?"WHERE {$_pre}stories.title LIKE '%$filter_string%'":"");
		$query="SELECT * FROM {$_pre}stories $filter_query ORDER BY create_time";
		$db->setQuery($query);
		$num_rows=$db->foundRows;
		if($num_rows==0) //Avoid division by zero here below
			$num_rows=1;
		$last_page=($page_limit!=0)?ceil($num_rows/$page_limit):1;
		if($page_num<1)
			$page_num=1;
		if($page_num>$last_page)
			$page_num=$last_page;
		if($page_limit!=0)
			$maximum='LIMIT '.($page_num-1)*$page_limit.','.$page_limit;
		else
			$maximum='';
		$query="SELECT {$_pre}stories.*,{$_pre}users.nick_name FROM {$_pre}stories LEFT JOIN {$_pre}users ON {$_pre}stories.registration_no = {$_pre}users.registration_no $filter_query ORDER BY create_time DESC $maximum";
		$db->setQuery($query);
		$found_rows=$db->foundRows;
		?>
		<div id="user_admin_panel">
		<div id="loading-bar"><img src="images/progress.gif" /></div><!--Progress bar -->
		
		<!--User details are loaded here for editing-->
		<div id='edit_story_form'></div>
			
		<div><!--Story toolbar -->
		<p>
		<button class="admin-panel-button" onclick="newStory()">New story</button>&nbsp;&nbsp;
		<button class="admin-panel-button" onclick="editStoryCb('sbox',<?php echo $found_rows; ?>)">Edit story</button>&nbsp;&nbsp;
		<button class="admin-panel-button" onclick="storyModify('delete')">Delete story</button>&nbsp;&nbsp;
		<button class="admin-panel-button" onclick="storyModify('publish')">Publish</button>&nbsp;&nbsp;
		<button class="admin-panel-button" onclick="storyModify('unpublish')">Unpublish</button>
		</p>
		</div>
		Filter: <input id="story_filter_input" onchange="submitFilter('story_filter_input','index.php?a=su&amp;a1=stories&amp;page_limit=<?php echo $page_limit; ?>&amp;page_num=<?php echo $page_num; ?>');" type="text" class="admin-panel-text-input" style="width:70px;" value="<?php echo $filter_string; ?>" />&nbsp;<button class="admin-panel-button" onclick="submitFilter('story_filter_input','index.php?a=su&amp;a1=stories&amp;page_limit=<?php echo $page_limit; ?>&amp;page_num=<?php echo $page_num; ?>');">go</button>&nbsp;<button class="admin-panel-button" onclick="submitFilterReset('index.php?a=su&amp;a1=stories&amp;page_limit=<?php echo $page_limit; ?>&amp;page_num=<?php echo $page_num; ?>');">reset</button>
		
		<form id="modify_news_story" name="modify_news_story" method="POST" action="index.php?a=su&amp;a1=stories&amp;do=modify_news_story" >
		<table class='user_row_details' border="0" cellpadding="3" cellspacing="0">
		<tr class="header">
		<td>#</td><td><input type="checkbox" name="check_all" onclick="checkAll('sbox',<?php echo $found_rows; ?>,this.checked)" /></td><td>Title</td><td>Published</td><td>Author</td><td>Created on</td><td>ID</td>
		</tr>
		<?php
		$i=($page_num-1)*$page_limit+1;
		$u=1;
		while($row=$db->fetch_assoc())
		{
			$article_state=$row['published'];
			if($article_state==1)
				$article_state_icon="<img src='theme/images/story_active.png' alt='published' title='published' />";
			else
				$article_state_icon="<img src='theme/images/story_inactive.png' alt='unpublished' title='unpublished' />";
			
			echo "<tr class='story_row_data'>";
			echo "<td>$i</td><td><input type='checkbox' name='sbox[]' id='sbox$u' value='".base64_encode($row['id'])."' /></td><td><a href='javascript:void(0);' class='edit_story' onclick=\"editStory('".base64_encode($row['id'])."')\" title='click to edit'>{$row['title']}</a></td><td>$article_state_icon</td><td><span class='admin_orange'>{$row['nick_name']}</span></td><td class='greyish'>".time_stamp_to_readable($row['create_time'])."</td><td class='greyish'>{$row['id']}</td>";
			echo "</tr>";
			$i++;$u++;
		}
		echo "</table>";
		?>
		<input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('modify_news_story'); ?>" />
		<input type="hidden" name="story_modify_action" id="story_modify_action" value="" />
		</form>
		<div align="center" class="limit">
		<p class="pagination_links">
		<?php
		if($page_num==1)
			echo "<span class='dead'>back  </span>";
		else
			echo "<a href='index.php?a=su&a1=stories&page_limit=$page_limit&page_num=".($page_num-1)."&filter_story=$filter_string' class='active'>back  </a>";
		for($i=1;$i<=$last_page;$i++)
		{
			if($page_num==$i)
				echo "<span class='dead'>$i  </span>";
			else
				echo "<a href='index.php?a=su&a1=stories&page_limit=$page_limit&page_num=$i&filter_story=$filter_string' class='active'>$i  </a>";
		}
		if($page_num==$last_page)
			echo "<span class='dead'>next  </span>";
		else
			echo "<a href='index.php?a=su&a1=stories&page_limit=$page_limit&page_num=".($page_num+1)."&filter_story=$filter_string' class='active'>next  </a>";
		?></p>
		Display #<select name="limit" class="admin-panel-select" id="" onchange="submitForm('index.php?a=su&amp;a1=stories',this.value);"><option value="5" <?php echo (($page_limit==5)?"selected='selected'":'') ?> >5</option><option value="10" <?php echo (($page_limit==10)?"selected='selected'":'') ?> >10</option><option value="15" <?php echo (($page_limit==15)?"selected='selected'":'') ?> >15</option><option value="20" <?php echo (($page_limit==20)?"selected='selected'":'') ?> >20</option><option value="25" <?php echo (($page_limit==25)?"selected='selected'":'') ?> >25</option><option value="30" <?php echo (($page_limit==30)?"selected='selected'":'') ?> >30</option><option value="50" <?php echo (($page_limit==50)?"selected='selected'":'') ?> >50</option><option value="0" <?php echo (($page_limit==0)?"selected='selected'":'') ?> >all</option></select>
		</div>
		
		</div>
		<?php
	}
	
	/**
	 * Send Mail
	 */
	
	function sendMail()
	{
		global $db,$_pre;
		$mail_subject=$_POST['mail_subject'];
		
		$emails = array();
		if($_POST['toggle_send_to']=='to_group')
		{
			$group=$_POST['mail_to_g'];
			if($group!='all')
				$g_type="user_type='$group'";
			else
				$g_type="user_type='registered' OR user_type='su'";
			
			$query="SELECT email FROM {$_pre}users WHERE $g_type";
			$db->setQuery($query);
			while($row=$db->fetch_assoc())
			{
				if($row['email']!='')
					$emails[]=$row['email'];
			}
		}
		else
		{
			$recepients=$_POST['mail_to_i'];
			preg_match_all("/\b\w+\@\w+[\.\w+]+\b/", $recepients, $output);
			foreach($output[0] as $email)
				$emails[]=strtolower($email);
		}
		
		if(count($emails)<1)
		{
			system_messages(0,'Group has no members or no email address supplied');
			system_messages(2,'Only valid email addresses are accepted!');
			return;
		}
		$body=$_POST['mail_body'];
		
		require_once('lib'.DS.'mail'.DS.'mail.php'); //Mail function
		
		mailSend($emails,$mail_subject,$body);
		
		system_messages(1,'Mail sent to '.count($emails).' recepient(s)');
		
			
	}
	/**
	 * Render Mail
	 */
	
	function renderMail()
	{
		global $db,$_pre;
		echo "<div id='mail'>";
		echo '<form id="mail_form" name="mail_form" method="POST" action="index.php?a=su&amp;a1=mail&amp;do=send_mail">';
		echo '<span class="form-field-label-text">Subject: </span><br />';
		echo '<input id="mail_subject" type="text" name="mail_subject" class="admin-panel-text-input" style="width: 80%;" />';
		echo "<br /><br />";
		echo '<span class="form-field-label-text">Send to: </span><br />';
		echo '<span class="size10">Group </span><input type="radio" name="toggle_send_to" id="to_group" value="to_group" /> <span class="size10">Individuals </span><input type="radio" name="toggle_send_to" id="to_individual" value="to_individual" checked="yes" />';
		echo "<br /><br />";
		echo '<div id="mail_group">';
		echo '<select id="mail_to" name="mail_to_g" class="admin-panel-select">
		<option value="registered">REGISTERED MEMBERS GROUP</option>
		<option value="su">ADMINISTRATORS GROUP</option>
		<option value="all">ALL GROUPS</option>
		</select>';
		echo '</div>';
		echo '<div id="mail_individual">';
		echo '<textarea id="mail_to_addresses" name="mail_to_i" class="admin-panel-textarea" style="width: 80%;" rows="3"></textarea>';
		echo '<br />';
		echo '<span class="size10"><i>Add email addresses here comma separated</i></span>';
		echo '</div>';
		echo '<br />';
		echo '<textarea id="mail_body" name="mail_body" class="admin-panel-textarea" rows="18" style="width: 80%;"></textarea>';
		echo "<br />";
		echo '<br />';
		echo '<input id="mail_form_submit" type="submit" class="admin-panel-submit-button" value="Send" />';
		echo '<input type="hidden" name="adm" value="'.base64_encode('su').'" /><input type="hidden" name="f" value="'.base64_encode('send_mail').'" />';
		echo "</form>";
		echo "</div>";
		?>
		<script type="text/javascript">
		$().ready(function() {
			
		function formatItem(row) {
			return row[0] + " &lt;<strong>" + row[1] + "</strong>&gt;";
		}
		function formatResult(row) {
			var tmp=row[0].split('(');
			var tmp1=tmp[1].split(')');
			return tmp1[0]+' <'+row[1]+'>';
		}
		$("#mail_to_addresses").autocomplete('ajphp/adminViewCpanelRemote.php?a=adminviewcpanelremote&do=load_mail_auto_complete', {
		minChars: 0,
		width: 450,
		matchContains: "word",
		multiple: true,
		autoFill: false,
		formatItem: formatItem,
		formatResult:formatResult
		});
		
		$('#mail_form_submit').click(checkMailForm);
		
		function checkMailForm()
		{
			if($('#mail_subject').val()=='')
			{
				jgrowl_messages('Subject cannot be blank','error',false);
				return false;
			}
			
		if($('#mail_to_addresses').val()=='' && $("input[name='toggle_send_to']").val=='to_individual')
		{
			jgrowl_messages('No recepient specified','error',false);
			return false;
		}
		if($('#mail_body').val()=='')
		{
			jgrowl_messages('Mail body cannot be blank!','error',false);
			return false;
		}
		
			return true;
		
		}
		});
		</script>
		<?php
	}
	
	/*
	 * Show coder submissions
	 */
	
	function renderViewSubmissions()
	{
		global $db,$_pre;
		echo '<div id="view_submissions">';
		echo '<span class="form-field-label-text">Select a match to view submissions made</span><br />';
		$query="SELECT * FROM {$_pre}matches ORDER by start_time DESC";
		$db->setQuery($query);
		echo "<select name='vs_matchname' id='vs_match_list' class='admin-panel-select' onChange='loadCoderSelect(this.value);'>"; //
		echo "<option value='none'>Please select a match</option>";
		while($row=$db->fetch_assoc())
			echo "<option value='{$row['id']}'>CodeZone match {$row['id']} .::. {$row['title']}</option>";
		echo "</select>";
		echo "&nbsp;&nbsp;";
		echo '<span id="vs_coder_select"></span>'; //Load user select box here
		echo '<br /><br />';
		echo '<span class="form-field-label-text">Showing code for: </span><span id="vs_coder_name" class="navy10"></span>&nbsp;&nbsp;';
		echo '<span class="form-field-label-text">Language: </span><span class="green10" id="vs_language">&nbsp;</span>';
		echo '<br /><br />';
		 //Code is loaded here
		echo '<textarea id="vs_code_view" class="source_code_view" readonly="true"></textarea>';
		echo '<br />';
		echo '<span class="navy10">Coder Stats</span>';
		echo '<br />';
		echo '<span class="form-field-label-text">Time taken: </span><span class="green10" id="vs_code_tt">&nbsp;</span>';
		echo '<br />';
		echo '<span class="form-field-label-text">Submissions made: </span><span class="green10" id="vs_submissions">&nbsp;</span>';
		echo '<br />';
		echo '<span class="form-field-label-text">Disqualified? </span><span class="green10" id="vs_disqualified">&nbsp;</span><span id="vs_disq_user"></span>';
		echo '<br />';
		echo '<span class="form-field-label-text">Downloads Count </span><span class="green10" id="vs_downloads">&nbsp;</span>';
		echo '<br />';
		echo '<span class="form-field-label-text">Correct? </span><span class="green10" id="vs_correct">&nbsp;</span>';
		echo '<br />';
		echo '<span class="form-field-label-text">Score: </span><span class="green10" id="vs_score">&nbsp;</span>';
		
		echo '</div>';
	}
	
}

?>
