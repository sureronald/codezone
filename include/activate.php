<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Activate user accounts                      |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

if(@$action=='activate'):
  $registration_no=base64_decode(@$_GET['r']);
  $key=@$_GET['k'];
  
  //Check if account has been previosly activated
  $query="SELECT * FROM ".$_pre."users WHERE registration_no='$registration_no' AND activated=1";
  $db->setQuery($query);
  if($db->foundRows>0)
  {
    system_messages(2,'Your account is already active, please use it constructively');
    return;
  }
  
  //Check if the account is waiting activation ie activate==2
  $query="SELECT * FROM ".$_pre."users WHERE registration_no='$registration_no' AND activated=2";
  $db->setQuery($query);
  if($db->foundRows==0)
  {
    system_messages(0,"This account is non-existent or is already being used by somebody else, Please contact the admin if you continue experiencing this problem");
    return;
  }
  //Activate account
  $query="UPDATE ".$_pre."users SET activated=1 WHERE registration_no='$registration_no' AND activation_key='$key'";
  $db->setQuery($query);
  if($db->affectedRows==0)
  system_messages(2,'There is no such an account; Contact admin');
  else
  {
    system_messages(1,'Success! Your CodeZone account is now active...');
  }
  endif;

?>