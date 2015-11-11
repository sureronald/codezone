<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Registration processing                     |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

require_once('registerHelper.php');
$reg=new registerHelper;

if(@$action=='register'):
	$do=isset($_GET['do'])?$_GET['do']:'';
	if($do=='')
		$reg->render();
	else if($do=='rpass') //Request lost password
		$reg->rpass();
	else if($do=='rpass_activate')
		$reg->rpass_activate();
	else if($do=='rpass_make_active')
		$reg->rpass_make_active();
	else
		echo "";
  endif;
?>
