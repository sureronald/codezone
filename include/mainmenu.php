<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: CodeZone accordion menu                          |
|                                                          |
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );
$ud=@$_SESSION['user_row_data'];

?>

<div id="content-pane" class="pane-sliders">
<?php if($login): ?>
	
		<h3 class="jpane-toggler" id="cpanel-panel-logged"><span>My CodeZone</span></h3>
		<div class="jpane-slider">
		<table class="mainmenu-user-info" border="0" cellpadding="2" cellspacing="2">
		<tr>
		<td valign="top"><img class="user_avatar" src='images/avatars/<?php echo ((strlen($_SESSION['user_row_data']['avatar_path'])==0)?"default.gif":$_SESSION['user_row_data']['avatar_path']); ?>' width="100" title="<?php echo "{$ud['nick_name']}'s avatar"; ?>" alt="<?php echo "{$ud['nick_name']}'s avatar"; ?>" /><br />
		<?php echo "<a class='logout-a' href='index.php?a=logout&amp;hash=".base64_encode(time())."'>logout  [ {$_SESSION['user_row_data']['nick_name']} ]</a><br />";
		if($ud['user_type']=='su')
		{
		 echo "<a class='mainmenu-admin-link' href='index.php?a=su&hash=".base64_encode(time())."'>control panel</a>";
		}?>
		</td>
		<td valign="top">
		<?php
		echo "<a class='user-view-profile' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name={$ud['nick_name']}'><span class='".(($su)?get_user_class(0,true):get_user_class($ud['ranking_pts']))."'>{$ud['nick_name']} ({$ud['registration_no']})</span></a><br />";
		echo "<span class='users'>Last visited on: </span><span class='user-last-visit'>".(($ud['last_visit_date']=='0000-00-00 00:00:00')?'never':date( "j \of\f F Y, \a\\t g:i:s a",make_time($ud['last_visit_date'])))."</span><br />";
		if(!$su)
		{
			$rank=get_coders_rank($ud['registration_no']);
			echo "<span class='users'>Rank: </span><span class='".get_user_class($ud['ranking_pts'])."'>{$rank[0]} / {$rank[1]}</span><br />";
			echo "<span class='users'>Points: </span><span class='".get_user_class($ud['ranking_pts'])."'>{$ud['ranking_pts']}</span><br />";
		}
		echo "<a class='user-view-profile' href='index.php?a=profile&amp;do=updateProfile'>Edit profile</a>";
		?>
		</td>
		</tr>
		</table>
		
		</div>
	
	<?php endif; ?>
	
		<h3 class="jpane-toggler" id="cpanel-panel-popular"><span>Algorithm Stats</span></h3>
		<div class="jpane-slider" id="algorithm_stats">
		<table border='0' cellpadding='2' cellspacing='2'>
		<tr>
		<td valign="top">
		<?php
		//Load top 10 algorithmists
		$query="SELECT ".$_pre."users.*,".$_pre."profile.* FROM ".$_pre."users,".$_pre."profile WHERE ".$_pre."users.registration_no=".$_pre."profile.registration_no AND user_type='registered' AND activated=1 ORDER BY ranking_pts DESC LIMIT 10";
		echo "<span class='dark10'>Top 10 algorithmists</span>";
		$db->setQuery($query);
		echo "<table border='0' cellpadding='2' cellspacing='0'>";
		$i=1;
		while($row=$db->fetch_assoc())
		{
			echo "<tr class='tr_data_small'>";
			echo "<td>$i. </td><td><a class='coder_nickname' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name={$row['nick_name']}'><span class='".get_user_class($row['ranking_pts'])."'>{$row['nick_name']}</span></a></td><td>{$row['ranking_pts']}</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		?>
		<a href='index.php?a=showcoders'>view all coders</a>
		</td>
		<td valign="top">
		<?php
		//Load most recent winner
		$query="SELECT * FROM ".$_pre."matches WHERE (start_time+duration)<".time()." ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		if($db->foundRows>0):
		$match_won_details=$db->fetch_assoc();
		
		$query="SELECT ".$_pre.$match_won_details['match_table_name'].".*,".$_pre."profile.* FROM ".$_pre.$match_won_details['match_table_name'].",".$_pre."profile WHERE ".$_pre.$match_won_details['match_table_name'].".registration_no=".$_pre."profile.registration_no ORDER BY points DESC";
		//$query="SELECT * FROM ".$_pre.$match_won_details['match_table_name']." ORDER BY points DESC";
		$db->setQuery($query);
		
		$coders=array(); //Initialize coders array: holds the each user details in preparation for sorting to get winner
		if($db->foundRows>0) //Have we found something?...
		{
			while($row=$db->fetch_assoc())
			{
				array_push($coders,$row);
			}
			if($coders[0]['points']!=0): //Has the first coder scored something?
				$coders=sort_coders_array($coders);
				echo "<span class='light10'>Most recent winner</span>";
				echo "<table border='0' cellpadding='2' cellspacing='0'>";
				echo "<tr><td><img id='user_avatar' width='70' src='images/avatars/".((strlen($coders[0]['avatar_path'])==0)?"default.gif":$coders[0]['avatar_path'])."' title='{$match_won_details['title']} - Match winner: {$coders[0]['nick_name']} ' />
				<p><strong>{$match_won_details['title']}</strong>.<br />Winner: <a class='coder_nickname' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name={$coders[0]['nick_name']}'><span class='".get_user_class($coders[0]['ranking_pts'])."'>{$coders[0]['nick_name']}</span></a></p>
				</td>
				</tr>";
				echo "</table>";
			endif;
		}
		endif;
		?>
		</td>
		</tr>
		</table>
		
		</div>
	
	
		<h3 class="jpane-toggler" id="cpanel-panel-stats"><span>Who's online</span></h3>
		<div class="jpane-slider" align="center">
		<?php 
		//Get the users online module
		require_once('modUsersOnline.php');
		?>
		</div>
	
	
	
		<h3 class="jpane-toggler" id="cpanel-panel-stats"><span>Credits</span></h3>
		<div class="jpane-slider">
		<p><!--<img src="images/sureronald.png" title="powered by sureronald - R labs" />--></p>
		</div>
	
</div> 
