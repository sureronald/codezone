<?php

if(@$action=='frontpage'):
/** Get the countdown module */
require_once('frontpageMatchCountdown.php');

echo "<div id='frontpage-text'>";
//Get news stories from stories table. Only the top five are shown on the frontpage
$query="SELECT {$_pre}stories.*,{$_pre}users.nick_name FROM {$_pre}stories LEFT JOIN {$_pre}users ON {$_pre}stories.registration_no = {$_pre}users.registration_no WHERE {$_pre}stories.published=1 ORDER BY {$_pre}stories.create_time DESC";
$db->setQuery($query);
$num_articles=$db->foundRows;
$more_stories=array();
$i=0;
while($row=$db->fetch_assoc())
{
	if($i>=5)
	{
		$more_stories[]=array($row['id'],$row['title']);
		$i++;
		continue;
	}
	echo "<h3 class='news_article_title'><span>&nbsp;</span> ".ucfirst($row['title'])."</h3>";
	echo "<hr />";
	if(preg_match("/<rmore>/",$row['content']))
	{
		$tmp=preg_split("/<rmore>/",$row['content']);
		echo $tmp[0];
		//Append the read more link
		echo "<span>... <a href=\"index.php?a=showstory&s_id={$row['id']}\">read more</a></span>";
	}
	else
		echo stripslashes($row['content']);
	
	echo "<p class='article_details'><span class='light1'>".time_stamp_to_readable($row['create_time'])."</span> .::. Author: <span class='admin_orange'>{$row['nick_name']}</span></p>";
	$i++;
}
if($num_articles>5)
{
	echo "<span class=\"dark1\">More stories</span>";
	echo "<ul>";
	foreach($more_stories as $tmp):
		echo "<li><a href=\"index.php?a=showstory&s_id=$tmp[0]\">$tmp[1]</a></li>";
		endforeach;
	echo "</ul>";
}
?>
</div>
<?php
endif;
?>