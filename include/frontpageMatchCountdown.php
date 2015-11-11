	<script type="text/javascript" src="js/jquery.countdown.min.js" ></script>
	<script type="text/javascript">
	$(function() {
	$(".match-timer-info").tooltip({
	track: true, 
	delay: 0, 
	showURL: false, 
	showBody: " - ", 
	extraClass: "pretty", 
	fixPNG: true, 
	opacity: 0.95, 
	left: -120,
	fade: 250
});
});
	</script>
	<?php
	require_once('arenaAppletHelper.php');
	$arn=new arenaAppletHelper(time());
	$query="SELECT * FROM ".$_pre."matches WHERE start_time>".time()." ORDER BY start_time LIMIT 1";
	$db->setQuery($query);
	$first_match=$db->fetch_assoc();
	
	/** Only show the timer if there's no active match else show the link */
if($db->foundRows>0 && !$arn->active_match())
{
	//Show countdown timer
	$time_rem=$first_match['start_time']-time()+1;
	echo "<h3 class='active_match'>ACTIVE MATCH</h3><hr class='h3-bottom-line' />";
	echo "<script type='text/javascript'>";
	echo "\$(function(){\$('#countdown-timer').countdown({ layout:\"<span class='match-timer-info'>{ {$first_match['title']} } <span class='countdown-timer-text'>{d<}{dn} {dl} and {d>}\"+ 
    '{hnn} {hl}, {mnn} {ml}, {snn} {sl}</span></span>',
    until:+$time_rem, serverSync: serverTime,expiryText:\"<span class='match-timer-info' title='CodeZone match {$first_match['id']}: {$first_match['title']} - Start time: ".date( "j \of\f F Y, \a\\t g:i:s a",$first_match['start_time'] ).", Difficulty: {$first_match['difficulty']}, Duration: {$first_match['duration']} seconds and ".(($first_match['match_ranked']==1)?"Affects ranking":"Does not affect ranking")."'><a class='match-timer-a' href='index.php?a=arena&amp;m=".base64_encode($first_match['title'])."'>CodeZone match {$first_match['id']} { {$first_match['title']} }</a></span>\",onExpiry: function(){ $('#match_notes').slideUp('slow');  }}); 
});";
echo "</script>";
echo "<div id='countdown-timer'></div>";
echo "<div id='match_notes' class='font_size_10'><span><a href='index.php?a=register_match&m_id={$first_match['id']}'>Register here to participate</a></span> | <span class='notify'>Registration closes when the match starts!</span></div>";
}

//Show link to arena if there's an active match

if($arn->active_match())
{
	$duration=$first_match['duration'];
	echo "<h3 class='active_match'>ACTIVE MATCH</h3><hr class='h3-bottom-line' />";
	//Load $first_match with match details
	$first_match=$arn->get_match_details();
	echo "<span class='match-timer-info' title='CodeZone match {$first_match['id']}: {$first_match['title']} - Start time: ".date( "j \of\f F Y, \a\\t g:i:s a",$first_match['start_time'] ).", Difficulty: {$first_match['difficulty']}, Duration: {$first_match['duration']} seconds and ".(($first_match['match_ranked']==1)?"Affects ranking":"Does not affect ranking")."'><a class='match-timer-a' href='index.php?a=arena&amp;m=".base64_encode($first_match['title'])."'>CodeZone match {$first_match['id']} { {$first_match['title']} }</a></span>";
	echo "<div class='font_size_10'><span class='notify'>Registration is now closed</span> | <span><a href='index.php?a=scoreboard&m_tn=".base64_encode($first_match['match_table_name'])."'>Watch live scoreboard</a></span></div>";
}
unset($arn);

?>