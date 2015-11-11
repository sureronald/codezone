<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone story rendering. Handles even read more |
|              links clicked on the frontpage              |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

if($action=='showstory'):
	if(!isset($_GET['s_id']))
	{
		system_messages(0,'Requested story unknown');
		return;
	}
	$story_id=(int)$_GET['s_id'];
	
	
	$query="SELECT {$_pre}stories.*,{$_pre}users.nick_name FROM {$_pre}stories LEFT JOIN {$_pre}users ON {$_pre}stories.registration_no = {$_pre}users.registration_no WHERE {$_pre}stories.published=1 AND {$_pre}stories.id=$story_id";
	$db->setQuery($query);
	if($db->foundRows<1)
	{
		system_messages(0,'Invalid story requested');
		return;
	}
	$data=$db->fetch_assoc();
	?>
	<h3 class='arena-match-title'>Title:: <?php echo ucfirst($data['title']); ?></h3>
	<hr class='h3-bottom-line' />
	<div id="showstories">
	<?php
	
	echo stripslashes(preg_replace("/<rmore>/","",$data['content'])); //Remove the read more tag and strip any splashes
	
	echo "<p class='article_details'><span class='light1'>".time_stamp_to_readable($data['create_time'])."</span> .::. Author: <span class='admin_orange'>{$data['nick_name']}</span></p>";
	
	//Get next and previous stories if any
	$query="SELECT * FROM {$_pre}stories WHERE id<$story_id AND published=1";
	$db->setQuery($query);
	$prev_stories="<span class=\"dark1\">&lt;&lt; prev</span>";
	if($db->foundRows>0)
	{
		$tmp=$db->fetch_assoc();
		$prev_stories="<a class=\"bold\" href=\"index.php?a=showstory&s_id={$tmp['id']}\">&lt;&lt; prev</a>";
	}
	
	$query="SELECT * FROM {$_pre}stories WHERE id>$story_id AND published=1";
	$db->setQuery($query);
	$next_stories="<span class=\"dark1\">next &gt;&gt;</span>";
	if($db->foundRows>0)
	{
		$tmp=$db->fetch_assoc();
		$next_stories="<a class=\"bold\" href=\"index.php?a=showstory&s_id={$tmp['id']}\">next &gt;&gt;</a>";
	}
	echo "<div align=\"center\">";
	echo "<p>".$prev_stories."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$next_stories."</p>";
	echo "</div>";
	echo "</div>";
	endif;
?>
