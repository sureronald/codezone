<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Arena helper class                          |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');


class arenaAppletHelper
{
	/**
	 * Store the current time
	 */
	var $time_now=0;
	
	/**
	 * Submission timeout
	 */
	var $timeout=0;
	/**
	 * Store match details
	 */
	var $match_details=array();
	
	function __construct($time_now)
	{
		global $_submission_timeout;
		$this->time_now=$time_now;
		$this->timeout=$_submission_timeout;
	}
	
	/**
	 * Set participate column to 1 in user_match_log
	 */
	
	function set_participate()
	{
		global $_pre,$db;
		$registration_no=$_SESSION['user_row_data']['registration_no'];
		$md=$this->get_match_details();
		$query="UPDATE {$_pre}user_match_log SET participated=1 WHERE registration_no='$registration_no' AND match_id={$md['id']} LIMIT 1";
		
		$db->setQuery($query);
	}
	
	/**
	 * Check if there's an active match: returns boolean
	 */
	
	function active_match()
	{
		global $db,$_pre;
		$query="SELECT * FROM ".$_pre."matches WHERE start_time<".$this->time_now." ORDER BY start_time DESC LIMIT 1";
		$db->setQuery($query);
		
		if($db->foundRows==0)
		return false;
		
		$match_data=$db->fetch_assoc();
		$match_stop_time=$match_data['duration']+$match_data['start_time'];
		if($match_stop_time>$this->time_now)
		return true;
		else
			return false;
	}
	
	/**
	 * Get match details of a match
	 */
	
	function get_match_details()
	{
		global $db,$_pre;
		$query="SELECT * FROM ".$_pre."matches WHERE start_time<".$this->time_now." ORDER BY start_time DESC LIMIT 1";
		$db->setQuery($query);
		
		if($db->foundRows==0)
		return false;
		
		$match_data=$db->fetch_assoc();
		$match_stop_time=$match_data['duration']+$match_data['start_time'];
		if($match_stop_time>$this->time_now)
		{
			$this->match_details=$match_data;
			return $match_data;
		}
		else
			return false;
	}
	
	/**
	 * Print the problem statement
	 */

	function print_problem()
	{
		global $_mail;
		$md=$this->match_details;
		if(is_readable('competition_uploads'.DS.$md['match_table_name'].DS.$md['problem_sheet']))
		{
			require_once('competition_uploads'.DS.$md['match_table_name'].DS.$md['problem_sheet']);
		}
		else
		{
			system_messages(0,"Unable to render problem");
			return;
		}
	}
	
	/**
	 * Render the arena
	 */
	
	function render_arena()
	{
		global $arnv,$_max_submissions;
		$match_details=$this->get_match_details();
		
		//Verify if there's actually an active match
		if(!$match_details)
		{
			system_messages(0,"There is no active match or match over");
			return;
		}
		//Compute remaining time
		$seconds_rem=($match_details['start_time']+$match_details['duration'])-time();
		?>
		<div id="arena-applet-container">
		<div id="arena-applet-left">
		<?php
		echo "<h3 class='arena-match-title'>Problem Statement ..::.. ".strtoupper($match_details['title'])."</h3>";
		echo "<hr class='h3-bottom-line' />";
		echo "<p class='arena-match-subtitle'>Maximum points: {$match_details['match_points']} .::. Difficulty: {$match_details['difficulty']} .::. Max downloads allowed: $_max_submissions</p>";
		?>
		<div id="match-rem-time" align="right">
		<!--Match remaining time-->
		</div>
		
		<?php
		/**Only show the downloads link if there is remaining downloads*/
		if($arnv->downloads_over()):
		echo "<h4 class='contact-link'><a class='load-iframe' title='CodeZone solution submission' href='ajphp/arenaValidator.php?a=arenaval&amp;v=render_submission_form'>Submit solution</a></h4>";
		endif;
		
		//Print the problem Statement
		$this->print_problem();
		?>
		</div>
		<div id="arena-applet-right">
		<span class='arena-general'>Live Scoreboard</span>
		<!--Live scoreboard goes here-->
		<div id="live-scoreboard"></div>
		
		</div>
		<div id="clear"></div>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$.ajaxSetup({ cache:false });$(".load-iframe").colorbox({width:"60%", height:"45%", iframe:true,overlayClose:false});
			var seconds=<?php echo $seconds_rem; ?>;
			//Show match remaining time
			$(function(){
				$('#match-rem-time').countdown({ layout:"<span class='timer-rem-time'>Remaining time: {hnn} {hl}, {mnn} {ml}, {snn} {sl}</span>",until:+seconds, serverSync:serverTime,onExpiry:function(){alert('Match over!');window.location.href='index.php';}});
			});
			
			
		});
//Code to load scoreboard every 30 seconds
$('#live-scoreboard').load('ajphp/arenaValidator.php?a=arenaval&v=partial_scoreboard'); //Load it for the first time on page load
setInterval(function(){
$('#live-scoreboard').load('ajphp/arenaValidator.php?a=arenaval&v=partial_scoreboard');
}, 30000);
</script>
		<?php
	}
}

?>
