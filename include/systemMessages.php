<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Helper functions                            |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

?>

<?php
if($expire)
system_messages(0,"Session expired, Please login again!");
if($reg_pass_no)
system_messages(0,"Reg No and password did not match");
if($acc_active)
system_messages(2,"Please activate this account to access CodeZone");
if($acc_disabled)
system_messages(2,"Your account has been disabled by CodeZone. Please contact an administrator");
if($login_notify)
system_messages(1,"Thank you for logging in {$user_row_data['nick_name']}");
if($logout_notify)
system_messages(1,"You are logged out, Bye bye!");
if($request_expired)
system_messages(0,"Request expired, please try again!");
if($request_toofast)
system_messages(0,'Request too fast, Slow down and try again!');
?>

