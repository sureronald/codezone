<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description:  Show the full list of coders on CodeZone        |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

//If match is active, then do not load the full scoreboard features such as options to download source files

if(@$action=='showcoders'):
	echo "<h3 class='arena-match-title'>CodeZone algorithmists .::. All coders list</h3>
	<hr class='h3-bottom-line' />";
	echo "<div id='coders_list' align='center'>";
	?>
	<table cellpadding='2' cellspacing='3' border='0'>
	<tr><th>&nbsp;#</th><th>Nick name</th><th>Match Count</th><th>Cumulative Points</th><th>Favourite language</th><th>Join Date</th></tr>
	<?php
	$page_limit=((isset($_GET['page_limit']))?(int)$_GET['page_limit']:10);
	$page_num=((isset($_GET['page_num']))?(int)$_GET['page_num']:1); //Default page is 1
	if($page_limit<0 || $page_limit>50)
		$page_limit=0;
	
	$query="SELECT ".$_pre."users.*,".$_pre."profile.* FROM ".$_pre."users,".$_pre."profile WHERE ".$_pre."users.registration_no=".$_pre."profile.registration_no AND user_type='registered' AND activated=1 ORDER BY ranking_pts DESC";
	$db->setQuery($query);
	$num_rows=$db->foundRows;
	$total_ecm_users=$num_rows;
	if($num_rows==0)
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
	//Get coders from ecj_profiles table
	$query="SELECT ".$_pre."users.*,".$_pre."profile.* FROM ".$_pre."users,".$_pre."profile WHERE ".$_pre."users.registration_no=".$_pre."profile.registration_no AND user_type='registered' AND activated=1 ORDER BY ranking_pts DESC $maximum";
	$db->setQuery($query);
	$rank_position=($page_num-1)*$page_limit+1;
	while($row=$db->fetch_assoc())
	{
		$join_date=explode(' ',$row['register_date']);
		$language=((strlen($row['language'])>0)?$row['language']:'- - - - -');
		echo "<tr>";
		echo "<td>$rank_position</td><td><a class='".get_user_class($row['ranking_pts'])."' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name={$row['nick_name']}'>{$row['nick_name']}</a></td>
		<td>{$row['match_count']}</td>
		<td>{$row['ranking_pts']}</td>
		<td><span class='dark1'>$language</span></td>
		<td><span class='light_blue'>$join_date[0]</span></td>";
		echo "</tr>";
		$rank_position++;
	}
	
	echo "</table>";
	
	?>
	<br />
	<div class="limit">
	<p class="pagination_links">
	<?php
	if($page_num==1)
		echo "<span class='dead'>back  </span>";
	else
		echo "<a href='index.php?a=showcoders&page_limit=$page_limit&page_num=".($page_num-1)."' class='active'>back  </a>";
	for($i=1;$i<=$last_page;$i++)
	{
		if($page_num==$i)
			echo "<span class='dead'>$i  </span>";
		else
			echo "<a href='index.php?a=showcoders&page_limit=$page_limit&page_num=$i' class='active'>$i  </a>";
	}
	if($page_num==$last_page)
		echo "<span class='dead'>next  </span>";
	else
		echo "<a href='index.php?a=showcoders&page_limit=$page_limit&page_num=".($page_num+1)."' class='active'>next  </a>";
	echo "</p></div>";
	?>
	Display #<select name="limit" class="" id="render_users" onchange="submitForm('index.php?a=showcoders',this.value);"><option value="5" <?php echo (($page_limit==5)?"selected='selected'":'') ?> >5</option><option value="10" <?php echo (($page_limit==10)?"selected='selected'":'') ?> >10</option><option value="15" <?php echo (($page_limit==15)?"selected='selected'":'') ?> >15</option><option value="20" <?php echo (($page_limit==20)?"selected='selected'":'') ?> >20</option><option value="25" <?php echo (($page_limit==25)?"selected='selected'":'') ?> >25</option><option value="30" <?php echo (($page_limit==30)?"selected='selected'":'') ?> >30</option><option value="50" <?php echo (($page_limit==50)?"selected='selected'":'') ?> >50</option><option value="0" <?php echo (($page_limit==0)?"selected='selected'":'') ?> >all</option></select>
	<p>Displaying coder No <?php
			$end=$page_limit;
			if($end==0)
			$end=$total_ecm_users;
			if($end>$total_ecm_users) #Display no is greater available users?
			$end=$total_ecm_users;
			if($page_num==$last_page)
			$end=$total_ecm_users;
			echo (($page_num-1)*$page_limit+1).' to '.$end; ?></p>
	<p><span class="dark1">CodeZone has <?php echo $total_ecm_users; ?> coders</span></p>	
	<?php
	echo "</div>";
	endif;
?>

