<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description:  Show the full scoreboard for a given match |
|               takes into account where the match is      |
|               or not
*----------------------------------------------------------*
*/
//Is in application...?
defined( 'IN_APP' ) or die( 'Restricted access' );

//If match is active, then do not load the full scoreboard features such as options to download source files

if(@$action=='scoreboard'):
$match_table_name=base64_decode(@$_GET['m_tn']);

//We need to see if the match is an active match so that we load the scoreboard in refresh mode while at the same time checking if there's a match by the specified table name
$query="SELECT * FROM ".$_pre."matches WHERE match_table_name='$match_table_name'";
$db->setQuery($query);

if($db->foundRows==0)
{
	system_messages(2,'Unable to load scoreboard for selected match');
	return;
}

//Check if the match is active
$md=$db->fetch_assoc();
$match_active=false;
if($md['start_time']<time() && ($md['start_time']+$md['duration'])>time())
{
	$match_active=true;
}

//Now we can proceed and load the scoreboard by selecting details from both the appropriate match table and the profile table
$query="SELECT ".$_pre.$match_table_name.".*,".$_pre."profile.ranking_pts FROM ".$_pre.$match_table_name.",".$_pre."profile WHERE ".$_pre.$match_table_name.".registration_no=".$_pre."profile.registration_no ORDER BY points DESC";
$db->setQuery($query);

?>
<h3 class='arena-match-title'>Scoreboard .::. <?php echo strtoupper($md['title']); ?></h3>
<hr class='h3-bottom-line' />

<div id="scoreboard">
<p>Match duration <b><?php echo $md['duration']; ?> seconds</b></p>
<p>Match points <b><?php echo $md['match_points']; ?></b></p>
<hr />
<table border="0" cellspacing="0" cellpadding="3">
<tr class='theader'>
<td>Position</td><td>Coder</td><td>Time taken (hh:mm:ss)</td><td>Submissions</td><td>Score / <?php echo $md['match_points']; ?></td><td>Language</td><td>Disqualified?</td><td>Download source file</td>
</tr>
<?php
//Initialize statisitics variables
$submissions_total=0;
$submissions_correct=0;
$coders=array(); //Initialize coders array: holds the each user details in preparation for sorting
$coders_disqualified=array();
while($row=$db->fetch_assoc())
{
	if($row['disqualified']==1) //Take good care of disqualified users so this array will be appended to $coders array at the end of sorting
		array_push($coders_disqualified,$row);
	else
		array_push($coders,$row);
		
	$submissions_total+=$row['submissions'];
	$submissions_correct+=$row['correct'];
}
$coders=sort_coders_array($coders);
foreach($coders_disqualified as $val)
	array_push($coders,$val);
$i=1;

for($x=0;$x<count($coders);$x++)
{
	if(!$match_active)
		$download_js_funct="sourceDownload('".$md['match_table_name']."','".$coders[$x]['files']."')";
	else
		$download_js_funct="";
	
	$time_taken=create_time($coders[$x]['time_taken']);
	$time_taken=$time_taken['hrs'].':'.$time_taken['min'].':'.$time_taken['sec'];
	if($coders[$x]['disqualified']==1)
		$disq_status="<img src=\"theme/images/red-tick.png\" title=\"This user has been disqualified\" />";
	else
		$disq_status="&nbsp;";
	echo "<tr class='tr_data_large'><td>$i</td><td><a class='coder_nickname' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name={$coders[$x]['nick_name']}'><span class='".get_user_class($coders[$x]['ranking_pts'])."'>{$coders[$x]['nick_name']}</span></a></td><td>$time_taken</td><td>{$coders[$x]['submissions']}</td><td>{$coders[$x]['points']}</td><td>{$coders[$x]['language']}</td><td>$disq_status</td><td><a href='javascript:void(0);'  onclick=\"$download_js_funct\">Download source file</a></td></tr>";
	$i++;
}

?>

</table>
<br />
<?php
echo "<span class='dark1'>Statistics</span><br />";
		echo "<span><b>Submissions total:</b> $submissions_total</span><br />";
		echo "<span><b>Correct:</b> $submissions_correct/$submissions_total (".number_format(@(($submissions_correct/$submissions_total)*100),2)."%)</span>";
		if($match_active)
			echo "<p><i>Scoreboard automatically refreshes every 60 seconds</i></p>";
?>
</div>
<script type="text/javascript" src="js/startDownload.js"></script>
<?php if($match_active): ?>
<!--Load code for refreshing scoreboard only if the match is active every 60 seconds-->
<script type="text/javascript">
setInterval(function(){
$('#scoreboard').html("<img src='images/progress.gif' />").load('ajphp/remoteScoreboard.php?a=r_scoreboard&m_tn=<?php echo base64_encode($md['match_table_name']); ?>').fadeIn("slow");
}, 60000);
</script>
<?php endif; ?>
<!--Code to load users profile picture on mouse hover over nickname on scoreboard-->
<script type="text/javascript">
// $(function() {
// $('.coder_nickname').tooltip({
// 	delay: 0, 
// 	showURL: false, 
//         bodyHandler: function() { 
//         return $("<img/>").attr("src", this.src); 
//     } 
// });
// });
</script>
<?php endif; ?>

