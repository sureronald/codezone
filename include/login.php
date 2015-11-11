<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Login/logout users and set user             |
|                     specific variables                   |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );
// print_r($_COOKIE);
if(@$action=='login'):

//Check the request time for forms
if(isset($_GET['hash']))
{
	$page_load_time=(int)base64_decode($_GET['hash']);
	if((time()-$page_load_time)>120)
	{
		$request_expired=true;
		$action='loginform';
		return;
	}
// 	if((time()-$page_load_time)<6)
// 	{
// 		$request_toofast=true;
// 		$action='loginform';
// 		return;
// 	}
}
  if(isset($_POST['lkey']) && isset($_POST['lurl']))
  {
    $registration_no=strtoupper($_POST['handle']);
    $password=encrypt_password($_POST['password']);
    
    //check for existence of user account
    $query="SELECT * FROM ".$_pre."users WHERE registration_no='$registration_no' AND password='$password'";
    $db->setQuery($query);
    if($db->foundRows==0)
    {
      //Trigger reg no & pass did not match error message
      $reg_pass_no=true;
      $action='loginform'; //load login form again
      return;
    }

    //Get data from user row
    $user_row_data=$db->fetch_assoc();
    //verify if user account disabled
		    
	if($user_row_data['activated']==-1)
	{
      	//Trigger account not activated error
	$acc_disabled=true;
        $action='loginform'; //load login form again
	return;
	}
    //Verify if user account is activated
    if($user_row_data['activated']!=1)
    {
      //Trigger account not activated error
      $acc_active=true;
      $action='loginform'; //load login form again
      return;
    }
    
    //If user is super user, set the $su variable to true
    if($user_row_data['user_type']=='su')
	$su=true;

    $query="SELECT avatar_path,quote,about_me,language,match_count,ranking_pts FROM ".$_pre."profile WHERE registration_no='$registration_no'";
    $db->setQuery($query);
    $row=$db->fetch_assoc();
    
    //Add user profile info to user_row_data array
    $user_row_data=array_merge($user_row_data,$row);
    
    //Session register user row data
    $_SESSION['user_row_data']=$user_row_data; 
    
    //Update user details in sessions & users table
    $query="UPDATE ".$_pre."users SET last_visit_date=NOW() WHERE registration_no='$registration_no'";
    $db->setQuery($query);
    
    $user_type=$user_row_data['user_type'];
    $new_session_id=$session->regenerate_id();
    $new_session_expire=time()+$_session_max;
    $ip=$_ip;
    $browser=$_browser;
    
    $query="UPDATE ".$_pre."sessions SET session_id='$new_session_id',registration_no='$registration_no',user_type='$user_type',nick_name='{$user_row_data['nick_name']}',session_expire='$new_session_expire' WHERE session_ip='$ip' AND session_browser='$browser'";
    $db->setQuery($query);
    
    if($db->affectedRows==0)
    {
      //Perform an insert because either the guest details have been wiped away by session garbage cleaner
      $query="INSERT INTO ".$_pre."sessions (session_id,registration_no,user_type,nick_name,session_expire,session_ip,session_browser) VALUES ('$new_session_id','$registration_no','$user_type','{$user_row_data['nick_name']}','$new_session_expire','$ip','$browser')";
      $db->setQuery($query);
    }
    
    //Set the session cookie [l]
    setcookie('l',$new_session_id,$new_session_expire+$_session_max,'/');
    
    
    //Set the login variable to true
    $login=true;
    
    //Notify user of successful login
    $login_notify=true;
  }
  
  $action='frontpage'; //Restore action varialbe
  endif;

  if(@$action=='logout'):
  
  $session_expire=time()+$_session_max;
  $ip=mysql_real_escape_string($_SERVER['REMOTE_ADDR'],$db->link);
  $browser=mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'],$db->link);
  $query="DELETE FROM ".$_pre."sessions WHERE session_ip='$ip' AND session_browser='$browser'";
  $db->setQuery($query);
  
  //Unset cookie [l]
  setcookie('l',md5(session_id()),time()-$_session_max*3,'/');
  
  //Regenerate session id
  $session->regenerate_id();
  $sessionid=md5(session_id());
  
  //Start a new guest session
  $query="INSERT INTO ".$_pre."sessions (session_id,registration_no,user_type,session_expire,session_ip,session_browser) VALUES ('$sessionid','undefined','guest',$session_expire,'$ip','$browser' )";
  $db->setQuery($query);
  
  //Unset $_SESSION['user_row_data']
  unset($_SESSION['user_row_data']);
  
  //Is the application offline..? If true the redirect to offline page. This makes sure that a when a super user is logged into CodeZone and it is offline, on logout he is redirected to offline/
  if($_offline==1)
  	header('Location: offline');
  //Set the login variable to false
  $login=false;
  
  //Set logout notification to true
  $logout_notify=true;
  
  //Load frontpage
  $action='frontpage';
  endif;
?>
