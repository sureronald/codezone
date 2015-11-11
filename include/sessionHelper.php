<?php

/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: A custom session helper class               |
|                                                          |
*----------------------------------------------------------*
*/
//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

class sessionHelper
{
  /**
  Session ip
  */
  var $ip='';
  
  /**
  Client Browser
  */
  var $browser='';
  
  /**
  Session id
  */
  var $sessionid='';
  
  /**
  Time now
  */
  var $time_now=0;
  
  /**
  Session lifetime
  */
  var $session_lifetime=0;
  /**
   * constructor for the sesionHelper class
   */

  function __construct()
  {
    $ecjConfig=new ecjConfig;
    
    $this->session_lifetime=$ecjConfig->session_lifetime;
    $this->ip=htmlspecialchars($_SERVER['REMOTE_ADDR']);
    $this->browser=htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
    $this->sessionid=session_id();
    
  }
  
  /**
   * Start session management
   *This is where all session activity begins. We gather various pieces of
   * information from the client and server. We test to see if a session already
   * exists. If it does, fine and dandy. If it doesn't we'll go on to create a
   * new one ... pretty logical heh?
   */
  
  function begin()
  {
    global $db,$_pre,$expire,$login,$su;
    $sessionid=md5(session_id());
    $registration_no='undefined';
    $session_expire=time()+$this->session_lifetime;
    $ip=$this->ip;
    $browser=$this->browser;
    
    //Get user details: NOT EXPIRED PLEASE!!
    $query="SELECT * FROM ".$_pre."sessions WHERE session_ip='$ip' AND session_browser='$browser' AND session_expire>".time()."";
    $db->setQuery($query);
    $ud=$db->fetch_assoc();
    
//     //Check for browser closure here since it destroys the $_SESSION['user_row_data'] 
//     if(isset($_COOKIE['l']) && !isset($_SESSION['user_row_data']))
//     {
// 	    
//     }
    //Check if user was previously authenticated i.e. logged in
    if(isset($_COOKIE['l']))
    {
      if($db->foundRows>0) //Session running therefore perform an update
      {
		$query="UPDATE ".$_pre."sessions SET session_expire='$session_expire' WHERE session_ip='$ip' AND session_browser='$browser'";
		$db->setQuery($query);
	
		//Set cookie i.e increase expiry of cookie l
		setcookie('l',md5(session_id()),$session_expire+$this->session_lifetime,'/');
	
		//Set $login variable to true: this is the variable that allows a user access to member privilleges
		$login=true;
	
		//Set superuser privilleges ie set $su to true if user is superuser
		if($ud['user_type']=='su')
	$su=true;
      }
      else //Session expired therefore delete the cookie and start a guest session and return from this method begin()
      {
	setcookie('l',md5(session_id()),time()-$this->session_lifetime*3,'/');
	
	$this->regenerate_id();
	
	$sessionid=md5(session_id());
	//Insert guest details to database
	$query="INSERT INTO ".$_pre."sessions (session_id,registration_no,user_type,session_expire,session_ip,session_browser) VALUES ('$sessionid','$registration_no','guest',$session_expire,'$ip','$browser' )";
	$db->setQuery($query);
	
	//Notify user that session has expired by setting expire to true & unset session user_row_data
	$expire=true;
	//unset($_SESSION['user_row_data']);
      }
    }
    else //Cookie [l] not SET
    {
      if($db->foundRows>0) //If user details in sessions table, then perform update
      {
	//This is a guest session so just increment the session expire ie update
	$query="UPDATE ".$_pre."sessions SET session_expire='$session_expire' WHERE session_ip='$ip' AND session_browser='$browser'";
	$db->setQuery($query);
      }
      else //Insert guest details to database
      {
	$query="INSERT INTO ".$_pre."sessions (session_id,registration_no,user_type,session_expire,session_ip,session_browser) VALUES ('$sessionid','$registration_no','guest',$session_expire,'$ip','$browser' )";
	$db->setQuery($query);
      }
    }
    
    //session start
    session_start();
    
  }
  
  /**
   * Regenerate session_id: Call it on privellege change
   */

  function regenerate_id()
  {
    session_regenerate_id();
    return md5(session_id());
  }
  
  /**
   * Get the number of online users i.e only those whose times have not expired
   */

  function get_users_online()
  {
    global $db,$_pre;
    $registered=array();
    $guests=array();
    $admins=array();
    $query="SELECT nick_name,user_type FROM ".$_pre."sessions WHERE session_expire > ".time()."";
    $db->setQuery($query);
    
    while($row=$db->fetch_assoc())
    {
	    if($row['user_type']=="registered")
	    array_push($registered,$row['nick_name']);
	    if($row['user_type']=="guest")
	    array_push($guests,$row['nick_name']);
	    if($row['user_type']=='su')
	    array_push($admins,$row['nick_name']);
    }    
    
    return array("registered"=>$registered,
    "guests"=>$guests,
    "admins"=>$admins);
    
  }
  
  /**
   * Kill an existing session: Called at logout
   */

  function session_kill()
  {
    global $db,$_pre;
    $query='DELETE FROM '.$_pre.'sessions WHERE registration_no='.$_SESSION['user_row_data']['registration_no'].' AND session_ip='.$this->ip.' AND session_browser='.$this->browser.' ';
    $db->setQuery($query);
    $_SESSION['logged_in']=false; //reset to false
    
  }
  
  /**
   * Session garbage cleaner: removes users whose time has expired
   */

  function session_gc()
  {
    global $_pre,$db;
    $query='DELETE FROM '.$_pre.'sessions WHERE session_expire < '.time().'';
    $db->setQuery($query);
  }
  
  /**
   * Destructor for the session class
   */

  function __destruct()
  {
    $this->session_gc();
    unset($ecjConfig);
    register_shutdown_function('session_write_close');
  }
  
  
}

?>