<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone arena practice mode                     |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

if(@$action=='practice'):

	//Get the practice helper class
	require_once('practiceHelper.php');
	$practice=new practiceHelper;
	?>
	<!--Arena Events-->
	<link type="text/css" media="screen" rel="stylesheet" href="theme/jquery.colorbox/colorbox.css" />
	<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
	<?php
	
	//Check if user logged in i.e to entice him/her to login or register
// 	if(!$login)
// 	{
// 		system_messages(0,"To practice, you must login");
// 		return;
// 	}
	if(!isset($_GET['do']))
	{
		//Load past matches
		$practice->get_past_matches();
	}
	else
	{
		$do=$_GET['do'];
		$m_id=@$_GET['m_id'];
		settype($m_id,'integer');
		//Load practice arena
		if($do=='load_practice_arena')
			$practice->render_practice_arena($m_id);
		else if($do=='view_analysis')
			$practice->view_match_analysis($m_id);
		else
		{}
	}
	endif;
?>