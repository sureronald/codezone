<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description:  Loads the scoreboard remotely base on ajax |
|               calls                                      |
*----------------------------------------------------------*
*/
//Is in application...?
//defined( 'IN_APP' ) or die( 'Restricted access' );

session_start();
define('DS',DIRECTORY_SEPARATOR);
define('IN_APP',1);

//Get ecjConfig object
require_once('..'.DS.'configuration.php');
$ecjConfig=new ecjConfig;
$_dbhost=$ecjConfig->db_host;
$_dbname=$ecjConfig->db_name;
$_dbuser=$ecjConfig->db_user;
$_dbpass=$ecjConfig->db_pass;
$_pre=$ecjConfig->table_prefix;
$_submission_timeout=$ecjConfig->submission_timeout;
$_max_submissions=$ecjConfig->max_submissions;
unset($ecjConfig);

//Get the utility functions
require_once('..'.DS.'include'.DS.'utilityFunctions.php');

//Get the mysqlHelper class
require_once('..'.DS.'include'.DS.'mysqlHelper.php');
$db=new mysqlHelper;

require_once('..'.DS.'include'.DS.'cleanPostAndGet.php'); //Clean $_POST and $_GET of malicious

if(@$_GET['a']=='r_scoreboard'):
$match_table_name=base64_decode(@$_GET['m_tn']);

//We need to see if the match is an active match so that we load the scoreboard in refresh mode while at the same time checking if there's a match by the specified table name
$query="SELECT * FROM ".$_pre."matches WHERE match_table_name='$match_table_name'";
$db->setQuery($query);

if($db->foundRows==0)
{
	echo 'Unable to load scoreboard for selected match';
	return;
}

//Check if the match is active
$md=$db->fetch_assoc();
$match_active=false;
if($md['start_time']<time() && ($md['start_time']+$md['duration'])>time())
{}
else
{
	echo "match over!";
	return;
}

//Now we can proceed and load the scoreboard by selecting details from both the appropriate match table and the profile table
$query="SELECT ".$_pre.$match_table_name.".*,".$_pre."profile.ranking_pts FROM ".$_pre.$match_table_name.",".$_pre."profile WHERE ".$_pre.$match_table_name.".registration_no=".$_pre."profile.registration_no ORDER BY points DESC";
$db->setQuery($query);

?>
<p>CodeZone algorithm match <b><?php echo $md['id']; ?></b></p>
<p>Match duration <b><?php echo $md['duration']; ?> seconds</b></p>
<p>Match points <b><?php echo $md['match_points']; ?></b></p>
<hr />
<table border="0" cellspacing="0" cellpadding="3">
<tr class='theader'>
<td>Position</td><td>Coder</td><td>Time taken (sec)</td><td>Submissions</td><td>Score / <?php echo $md['match_points']; ?></td><td>Language</td><td>Download source file</td>
</tr>
<?php
$submissions_total=0;
$submissions_correct=0;
$coders=array(); //Initialize coders array: holds the each user details in preparation for sorting
while($row=$db->fetch_assoc())
{
	array_push($coders,$row);
	$submissions_total+=$row['submissions'];
	$submissions_correct+=$row['correct'];
}
$coders=sort_coders_array($coders);
$i=1;
for($x=0;$x<count($coders);$x++)
{
	$time_taken=create_time($coders[$x]['time_taken']);
	$time_taken=$time_taken['hrs'].':'.$time_taken['min'].':'.$time_taken['sec'];
	
	echo "<tr class='tr_data_large'><td>$i</td><td><a class='coder_nickname' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name={$coders[$x]['nick_name']}'><span class='".get_user_class($coders[$x]['ranking_pts'])."'>{$coders[$x]['nick_name']}</span></a></td><td>$time_taken</td><td>{$coders[$x]['submissions']}</td><td>{$coders[$x]['points']}</td><td>{$coders[$x]['language']}</td><td><a href='javascript:void(0);'  >Download source file</a></td></tr>";
	$i++;
}

endif;
?>
</table>
<?php
echo "<span class='dark1'>Statistics</span><br />";
		echo "<span><b>Submissions total:</b> $submissions_total</span><br />";
		echo "<span><b>Correct:</b> $submissions_correct/$submissions_total (".number_format(@(($submissions_correct/$submissions_total)*100),2)."%)</span>";
?>
<p><i>Scoreboard automatically refreshes every 60 seconds</i></p>