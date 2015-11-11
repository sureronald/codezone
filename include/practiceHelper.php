<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Practice helper class                       |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');


class practiceHelper
{
	/** Database table prefix */
	var $_pre='';
	
	/** Database object */
	var $db='';
	
	/** arenaAppletHelper class object */
	var $arnv='';
	
	function __construct()
	{
		require_once('include'.DS.'arenaAppletHelper.php');
		global $_pre,$db;
		$this->db=$db;
		$this->_pre=$_pre;
		$this->arnv=new arenaAppletHelper(time());
	}
	
	/**
	 * View match analysis
	 */

	function view_match_analysis($m_id)
	{
		$query="SELECT * FROM ".$this->_pre."matches WHERE id=$m_id";
		$this->db->setQuery($query);
		if($this->db->foundRows==0)
		{
			system_messages(2,'Unable to render analysis for the requested match');
			return;
		}
		
		$row=$this->db->fetch_assoc();
		
		echo "<h3 class='arena-match-title'>CodeZone Match {$row['id']} Analysis .::. ".strtoupper($row['title'])."</h3>";
		echo "<hr class='h3-bottom-line' />";
		
		//Print the analysis
		echo "<div id='match_analysis'>";
		if(strlen($row['analysis'])==0)
			echo "Analysis is not yet ready, please check back soon!";
		else
			echo stripslashes($row['analysis']);
		echo "</div>";
		
	}
	
	/**
	 * Show past CodeZone matches for user to select
	 */
	
	function get_past_matches()
	{
		$db=$this->db;
		$_pre=$this->_pre;
		
		//This is a perfect query that gets rid of checking whether there's an active match
		$query="SELECT * FROM ".$_pre."matches WHERE (start_time+duration)<".time()."  ORDER BY start_time DESC";
		$db->setQuery($query);
		?>
		<div id='past_matches_list'>
		<h3 class='arena-match-title'>CodeZone Practice mode .::. MATCH ARCHIVE</h3>
		<hr class='h3-bottom-line' />
		<table border="0" cellspacing="0" cellpadding="3">
		<tr class='theader'>
		<td>M::No</td><td>Match Title</td><td>View Scoreboard</td><td>Analysis</td><td>Date</td><td>Duration (hrs)</td><td>Points</td>
		</tr>
		<?php
		
		while($row=$db->fetch_assoc())
		{
			$duration=create_time($row['duration']);
			$duration=$duration['hrs'].':'.$duration['min'].':'.$duration['sec'];
			echo "<tr class='tr_data_large'><td>{$row['id']}</td><td><a  href='index.php?a=practice&amp;do=load_practice_arena&amp;m_id={$row['id']}'>{$row['title']}</a></td><td><a href='index.php?a=scoreboard&amp;m_tn=".base64_encode($row['match_table_name'])."'>view scoreboard</a></td><td><a href='index.php?a=practice&amp;do=view_analysis&amp;m_id={$row['id']}'>view analysis</a></td><td>".date( "j \of\f F Y, \a\\t g:i:s a",$row['start_time'])."</td><td>$duration</td><td>{$row['match_points']}</td></tr>";
		}
		?>
		</table>
		<p><i>To practice, click on a match title of your choice</i>. In CodeZone practice mode, you are free to download input files and submit output files as much as you like. No time limits!</p>
		</div>
		<?php
	}
	
	/**
	 * Show static scoreboard
	 */

	function static_scoreboard($coders,$md)
	{
		$submissions_total=0;
		$submissions_correct=0;
		
		//No correct participant therefore do nothing
		if(count($coders)==0)
			return;
		
		for($i=0;$i<count($coders);$i++)
		{
			$submissions_total+=$coders[$i]['submissions'];
			$submissions_correct+=$coders[$i]['correct'];
		}
		
		//Sort the coders array by time taken where there is a tie if any
		$coders=sort_coders_array($coders);
		
		$row_class=array('lightblue','shadedwhite');
		echo "<div id='scoreboard'>";
		echo "<table border='0' cellpadding='2' cellspacing='1'>";
		for($i=0;$i<count($coders);$i++)
		{
			echo "<tr class='".$row_class[$i%2]."'>"; //Toggle the class row on each loop
			echo "<td class='position'>".($i+1)."</td>";
			echo "<td class='nickname'><a target='_blank' href='index.php?a=profile&amp;do=viewProfile&amp;nick_name=".$coders[$i]['nick_name']."'>".$coders[$i]['nick_name']."</a></td>";
			echo "<td class='points'>".$coders[$i]['points']."</td>";
			echo "</tr>";
			if($i==12)
				break;
		}
		echo "</table>";
		echo "<a href='index.php?a=scoreboard&amp;m_tn=".base64_encode($md['match_table_name'])."'>full scoreboard</a>";
		echo "<br /><br />";
		echo "<span class='dark1'>Statistics</span><br />";
		echo "<span><b>Submissions total:</b> $submissions_total</span><br />";
		echo "<span><b>Correct:</b> $submissions_correct/$submissions_total (".number_format((($submissions_correct/(($submissions_total==0)?1:$submissions_total))*100),2)."%)</span>";
		echo "</div>";
	}
	
		/**
	 * Print the problem statement
	 */

	function print_problem($md)
	{
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
	 * Render practice arena
	 */

	function render_practice_arena($m_id)
	{
		$arnv=$this->arnv;
		$db=$this->db;
		$_pre=$this->_pre;
		
		$query="SELECT * FROM ".$_pre."matches WHERE id=$m_id AND (start_time+duration)<".time()."";
		$db->setQuery($query);
		if($db->foundRows==0)
		{
			system_messages(2,'Unable to load practice arena for the requested match');
			return;
		}
		$md=$db->fetch_assoc(); //Store match details
		
		//Get data for partial static scoreboard from the match table
		$query="SELECT * FROM ".$_pre.$md['match_table_name']." WHERE disqualified=0 ORDER BY points DESC LIMIT 10";
		$db->setQuery($query);
		$coders=array();
		while($row=$db->fetch_assoc())
		{
			array_push($coders,$row);
		}
		?>
		<div id="arena-applet-container">
		<div id="arena-applet-left">
		<?php
		echo "<h3 class='arena-match-title'>....:Practice mode:....</h3>";
		echo "<h3 class='arena-match-title'>Problem Statement ..::.. ".strtoupper($md['title'])."</h3>";
		echo "<hr class='h3-bottom-line' />";
		
		echo "<p class='arena-match-subtitle'><i>Unlimited submissions, no source code required!</i></p>";
		//Submission button
		echo "<h4 class='contact-link'><a class='load-iframe' title='CodeZone practice mode solution submission' href='ajphp/practiceValidator.php?a=arenaval&amp;v=render_submission_form&amp;m_id={$md['id']}'>Submit solution</a></h4>";
		
		//Print the problem Statement
		$this->print_problem($md);
		?>
		</div>
		<div id="arena-applet-right">
		<span class='arena-general'>Scoreboard</span>
		<!--scoreboard goes here-->
		<div id="static-scoreboard"></div>
		<?php $this->static_scoreboard($coders,$md); ?>
		</div>
		<div id="clear"></div>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$.ajaxSetup({ cache:false });
			$(".load-iframe").colorbox({width:"50%", height:"40%", iframe:true,overlayClose:false});
		});
		</script>
		<?php
		
	}
}

?>
