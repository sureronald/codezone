<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Administration panel controller             |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

if(@$action=='su' && $login && $su):
	?>
	<!--Admin panel javascript events-->
	<script src="js/jquery.tabs.min.js" type="text/javascript"></script>
	<script src="js/jquery.form.js" type="text/javascript"></script>
	<script src="js/jquery.autocomplete.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="theme/jquery.tabs/jquery.tabs.css" type="text/css" media="print, projection, screen">
	<link rel="stylesheet" type="text/css" href="theme/jquery.autocomplete/jquery.autocomplete.css" />
        <!-- Additional IE/Win specific style sheet (Conditional Comments) -->
        <!--[if lte IE 7]>
        <link rel="stylesheet" href="theme/jquery.tabs/jquery.tabs-ie.css" type="text/css" media="projection, screen">
        <![endif]-->

	<?php
  require_once('adminViewCpanelHelper.php');
  $admin=new adminViewPanelHelper;
  
  //Form has been sent?... do something then
  if(isset($_POST['adm']))
  {
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='global_conf')
		  $admin->valGlobalConf($_POST);
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='create_match')
		  $admin->valCreateMatch($_POST,$_FILES);
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='edit_user_details')
		  $admin->editUserDetails();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='add_bulk_users')
		  $admin->addBulkUsers();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='edit_match')
		  $admin->valEditMatch();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='delete_bulk_users')
		  $admin->deleteBulkUsers();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='modify_news_story')
		  $admin->modifyNewsStories();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='save_new_story')
		  $admin->saveNewStory();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='save_update_story')
		  $admin->saveUpdateStory();
	  if(isset($_POST['f']) && base64_decode(@$_POST['f'])=='send_mail')
		  $admin->sendMail();
  }
	  //Open the appropriate tab
  if(isset($_GET['a1']))
  {
	if($_GET['a1']=='global-conf')
		  $active_tab=1;
	if($_GET['a1']=='new-match')
		  $active_tab=2;
	if($_GET['a1']=='users')
		  $active_tab=4;
	if($_GET['a1']=='edit-match-details')
		  $active_tab=3;
	if($_GET['a1']=='stories')
		$active_tab=7;
	if($_GET['a1']=='mail')
		$active_tab=5;
  }
	
  
  ?>
 <span class='content-header'>CodeZone Administration Control panel</span>
  <div id="admin-panel-container" class="admin-panel">
   <ul>
  <li><a href="#global-conf-1"><span>System</span></a></li>
  <li><a href="#create-match-2"><span>New Match</span></a></li>
  <li><a href="#edit-match-3"><span>Edit Match</span></a></li>
  <li><a href="#users-4"><span>Users</span></a></li>
  <li><a href="#mail-5"><span>Mail</span></a></li>
  <li><a href="#submissions-6"><span>Submissions</span></a></li>
  <li><a href="#stories-7"><span>Stories</span></a></li>
  </ul>
  
  <!--Begin global configuration td-->
  <div class="admin-panel-tabs" id="global-conf-1">
  <fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>Global Configuration</legend>
  <?php $admin->renderGlobalConf(); ?>
  </fieldset>
  </div>
  
  <div class="admin-panel-tabs" id="create-match-2">
    <fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>CodeZone create new match</legend>
  <?php $admin->renderCreateMatch(); ?>
  </fieldset>
  </div>
  <div class="admin-panel-tabs" id="edit-match-3">
   <fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>CodeZone edit match</legend>
  <?php $admin->showMatches(); ?>
  </fieldset>
  </div>
  <div class="admin-panel-tabs" id="users-4">
   <fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>CodeZone user management</legend>
  <?php $admin->renderUserAdministration(); ?>
  </fieldset>
  </div>
  <div class="admin-panel-tabs" id="mail-5">
  <fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>CodeZone Mail</legend>
  <?php $admin->renderMail(); ?>
  </fieldset>
  </div>
  <div class="admin-panel-tabs" id="submissions-6">
   <fieldset class='admin-panel-fieldset'><legend class='admin-panel-legend'>CodeZone view submissions</legend>
  <?php $admin->renderViewSubmissions(); ?>
  </fieldset>
  </div>
  <div class="admin-panel-tabs" id="stories-7">
  <?php $admin->renderNewsStories(); ?>
  </div>
  </div><!--End div admin panel-->
  <script type="text/javascript">
  <?php
  if(isset($active_tab))
  echo "$(function() {
	  $('#admin-panel-container').tabs($active_tab,{ fxFade:true, fxSpeed:'fast'});
  });";
  else
	  echo "$(function() {
	  $('#admin-panel-container').tabs({ fxFade: true, fxSpeed: 'fast' });
  });";
  ?>
  </script>

  <?php  
  endif;
?>
