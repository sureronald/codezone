<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Admininistration Control panel ajax calls   |
|              handler helper class                        |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

class adminViewCpanelRemoteHelper
{
	
	/**
	 * Show the user edit form
	 */
	
	function editUserForm()
	{
		global $db,$_pre;
		if(!isset($_GET['reg']))
		{
			system_messages(0,'No registration number supplied');
			return;
		}
		$registration_no=strtoupper(base64_decode($_GET['reg']));
		
		$query="SELECT {$_pre}users.*,{$_pre}profile.ranking_pts FROM {$_pre}users,{$_pre}profile WHERE {$_pre}users.registration_no={$_pre}profile.registration_no AND {$_pre}users.registration_no='$registration_no'";
		$db->setQuery($query);
		if($db->foundRows==0)
		{
			system_messages(0,'No such user!');
			return;
		}
		$row=$db->fetch_assoc();
		?>
		<form id="edit_user_form_details" method="POST" action="index.php?a=su&amp;a1=users&amp;do=edit_user_details">
				<span class="dark10">Editing details for <?php echo $row['registration_no']; ?></span>
				<table border="0" class='admin-panel-forms-table' cellpadding="2" cellspacing="2"><tr><td><span class="form-field-label-text">Full name</span></td><td><input type="text" name="user_full_name" class="admin-panel-text-input" value="<?php echo $row['full_names']; ?>"/></td><td><span class="form-field-label-text">User type</span></td><td><select name="user_type" class="admin-panel-select"><option value="registered" <?php if($row['user_type']=='registered') echo "selected='selected'"; ?>>Registered user</option><option value="su" <?php if($row['user_type']=='su') echo "selected='selected'"; ?>>Super user</option></select></td></tr>
				<tr><td><span class="form-field-label-text">Nickname</span></td><td><input type="text" name="user_nick_name" class="admin-panel-text-input" value="<?php echo $row['nick_name']; ?>"/></td><td><span class="form-field-label-text">Email</span></td><td><input type="text" name="user_email" class="admin-panel-text-input" value="<?php echo $row['email']; ?>"/></td></tr>
				<tr><td><span class="form-field-label-text">New password</span></td><td><input type="password" name="user_pass1" class="admin-panel-text-input" /></td><td><span class="form-field-label-text">Ranking points</span></td><td><input type="text" name="user_rpts" class="admin-panel-text-input" value="<?php echo $row['ranking_pts']; ?>"/></td></tr>
						<tr><td><span class="form-field-label-text">Confirm password</span></td><td><input type="password" name="user_pass2" class="admin-panel-text-input" /></td><td><span class="form-field-label-text">Block User?</span></td><td>Yes&nbsp;<input type="radio" name="user_block" value="-1" <?php if($row['activated']==-1) echo "checked='yes'"; ?>/>&nbsp;No<input type="radio" name="user_block" value="1" <?php if($row['activated']==1) echo "checked='yes'"; ?>/></td></tr>
			
		</table>
		<input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('edit_user_details'); ?>" />
		<input type="hidden" name='reg' value="<?php echo base64_encode($row['registration_no']); ?>" />
		<br /><input type="submit" name="save_changes" class="admin-panel-submit-button" value="save changes"/>&nbsp;<input type="submit" name="delete_user" class="admin-panel-submit-button" value="delete <?php echo $row['registration_no']; ?>?" />
		</form><br />
		<?php
	}
	
	/**
	 * Show the edit match form
	 */
	
	function loadEditMatchForm()
	{
		global $db,$_pre;
		if(!isset($_GET['m_id']))
		{
			system_messages(0,'No match given');
			return;
		}
		if($_GET['m_id']=='none')
		{
			system_messages(0,'Please select a match to edit');
			return;
		}
		$match_id=$_GET['m_id'];
		settype($match_id,'integer');
		$query="SELECT * FROM {$_pre}matches WHERE id=$match_id ORDER BY start_time DESC";
		$db->setQuery($query);
		if($db->foundRows==0)
		{
			system_messages(0,'No such match!');
			return;
		}
		$row=$db->fetch_assoc();
		?>
		<span class="dark10">Editing CodeZone match <?php echo "$match_id .::. {$row['title']}"; ?></span><br />
		<form class='admin-panel-form' name='global-conf' method='POST' action='index.php?a=su&amp;a1=edit-match-details'>
		<table class='admin-panel-forms-table' cellpadding="2" cellspacing="2" border="0">
				<tr><td class='form-field-label'><span class='form-field-label-text'>Match Name</span></td>
				<td><input type="text" name="edm-title" class="admin-panel-text-input" value="<?php echo $row['title']; ?>"/></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Match duration (sec)</span></td><td><input type="text" name="edm-duration" class="admin-panel-text-input" value="<?php echo $row['duration']; ?>"/></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Start date (yyyy-mm-dd)</span></td><td><input type="text" name="edm-start_date" class="admin-panel-text-input" value="<?php echo get_date($row['start_time']); ?>"/></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Start time (hh:mm:ss)</span></td><td><input type="text" name="edm-start_time" class="admin-panel-text-input" value="<?php echo get_time($row['start_time']); ?>"/></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Difficulty</span></td><td><input type="text" name="edm-difficulty" class="admin-panel-text-input" value="<?php echo $row['difficulty']; ?>"/></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Match points</span></td><td><input type="text" name="edm-match_points" class="admin-panel-text-input" value="<?php echo $row['match_points']; ?>"/></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Ranked match</span></td><td>Yes&nbsp;<input type="radio" name="edm-ranked_match" value="1" <?php if($row['match_ranked']==1) echo "checked='true'"; ?> />&nbsp;&nbsp;No&nbsp;<input type="radio" name="edm-ranked_match" value="0" <?php if($row['match_ranked']==0) echo "checked='true'"; ?> /></td></tr>
				<tr><td class='form-field-label'><span class='form-field-label-text'>Analysis <i>(Allowed tags are: &lt;p&gt;,&lt;a&gt;,&lt;strong&gt;,&lt;i&gt;,&lt;br&gt;. To highlight code blocks enclose the code in a div tag with class code_block e.g &lt;div class=&quot;code_block&quot;&gt;&lt;pre&gt;echo CONSTANT;&lt;/pre&gt;&lt;/div&gt;</i></span></td><td><textarea name="edm-analysis" class="admin-panel-textarea" style="width:400px;" rows="15" cols="45"><?php echo stripslashes($row['analysis']); ?></textarea></td></tr>
				<tr><td><input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" /><input type="hidden" name="f" value="<?php echo base64_encode('edit_match'); ?>" /><input type="hidden" name="m_id" value="<?php echo base64_encode($match_id); ?>" /></td><td><input type="submit" name="save" class="admin-panel-submit-button" value="save" />&nbsp;&nbsp;<input type="submit" name='delete_match' class="admin-panel-submit-button" value='Delete this match?' class="admin-panel-submit-button" onclick="return confirm('WARNING: Are you sure you want to delete this match? this action cannot be reversed!!');" /></td></tr>
		</table>
		</form>
		<br />
		<br />
		<?php
		$query="SELECT COUNT(*) FROM {$_pre}{$row['match_table_name']} WHERE 1";
		$db->setQuery($query);
		$tmp_row=$db->fetch_assoc();
		$st_total_registered=$tmp_row['COUNT(*)']; //Total users registered for the match
		$query="SELECT * FROM {$_pre}user_match_log WHERE match_id={$row['id']} AND participated=1";
		$db->setQuery($query);
		$st_total_participated=$db->foundRows;
		$query="SELECT SUM(downloads_count) FROM {$_pre}{$row['match_table_name']} WHERE 1";
		$db->setQuery($query);
		$tmp_row=$db->fetch_assoc();
		$st_downloads=$tmp_row['SUM(downloads_count)'];
		$query="SELECT SUM(submissions) FROM {$_pre}{$row['match_table_name']} WHERE 1";
		$db->setQuery($query);
		$tmp_row=$db->fetch_assoc();
		$st_submissions=$tmp_row['SUM(submissions)'];
		?>
		<span class="navy10">Match Statistics</span>
		<ul>
		<li><span class="form-field-label-text">Total Registered Users for this match: </span><span class="green10"><?php echo $st_total_registered; ?></span></li>
		<li><span class="form-field-label-text">Total Registered Users who participated: </span><span class="green10"><?php echo $st_total_participated; ?></span></li>
		<li><span class="form-field-label-text">Total Registered Users who did not participate: </span><span class="green10"><?php echo ((($st_total_registered-$st_total_participated)<0)?0:$st_total_registered-$st_total_participated); ?></span></li>
		<li><span class="form-field-label-text">Total Input file downloads: </span><span class="green10"><?php echo $st_downloads; ?></span></li>
		<li><span class="form-field-label-text">Total Submissions: </span><span class="green10"><?php echo $st_submissions; ?></span></li>
		</ul>
		
		<button id="show_problem_st" class="admin-panel-button" value="hidden" onclick="showProblemToggle('<?php echo $row['match_table_name']; ?>','<?php echo $row['problem_sheet']; ?>');">Show/Hide problem sheet</button>&nbsp;&nbsp;
		<button class="admin-panel-button" onclick="downloadSheet('<?php echo $row['match_table_name']; ?>','<?php echo $row['input_sheet']; ?>','input');">Download input sheet</button>&nbsp;&nbsp;
		<button class="admin-panel-button" onclick="downloadSheet('<?php echo $row['match_table_name']; ?>','<?php echo $row['answer_sheet']; ?>','answer');">Download answer sheet</button>
		<div id="problem_statement_view"></div><!--render problem statement here-->
		<iframe id="sheet_download" src="" style="display: none;" /><!--Frame whose src is changed by JS to trigger download-->
		<?php
	}
	
	/**
	 * Show the Create New Story form
	 */
	
	function createStoryForm()
	{
		?>
		<form class='admin-panel-story-form' name='global-conf' method='POST' action='index.php?a=su&amp;a1=stories&amp;do=save_new_story'>
			<div align="right">
				<input type="submit" value="save" class="" />&nbsp;&nbsp;<a href="javascript: void(0);" class="form_hide">hide</a>
			</div>
			<div align="left">
				<span class="dark1">Title: </span>
				<input type="text" name="story_title" size="45" /><br /><br />
				Published&nbsp;<input type="radio" name="story_state" value="1" checked="true"/>&nbsp;Unpublished<input type="radio" name="story_state" value="0" />
			</div>
			<textarea class="admin-panel-textarea-1" name="story_text" ></textarea>
			<input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" />
			<input type="hidden" name="f" value="<?php echo base64_encode('save_new_story'); ?>" />
		<p><i>Allowed tags are &lt;p&gt;,&lt;a&gt;,&lt;strong&gt;,&lt;i&gt;,&lt;br&gt;</i><br />
		To put a read more link insert the tag <i>&lt;rmore&gt;</i></p>
		<hr class="light-hr" />
		</form>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.form_hide').click(function(){
				$('.admin-panel-story-form').fadeTo('slow',0.3).slideUp('slow');
				});
			});
		</script>
		<?php
	}
	
	/**
	 * Show the Story edit form
	 */
	
	function editStoryForm()
	{
		global $db,$_pre;
		$story_id=@$_GET['s_id'];
		
		$story_id=base64_decode($story_id);
		$query="SELECT * FROM {$_pre}stories WHERE id=$story_id";
		$db->setQuery($query);
		$data=$db->fetch_assoc();
		?>
		<form class='admin-panel-story-form' name='global-conf' method='POST' action='index.php?a=su&amp;a1=stories&amp;do=save_update_story'>
			<div align="right">
				<input type="submit" value="save" class="" />&nbsp;&nbsp;<a href="javascript: void(0);" class="form_hide">hide</a>
			</div>
			<div align="left">
				<span class="dark1">Title: </span>
				<input type="text" name="story_title" size="45" value="<?php echo $data['title']; ?>" /><br /><br />
				Published&nbsp;<input type="radio" name="story_state" value="1" <?php if($data['published']) echo 'checked="true"'; ?>/>&nbsp;Unpublished<input type="radio" name="story_state" value="0" <?php if(!$data['published']) echo 'checked="true"'; ?> />
			</div>
			<textarea class="admin-panel-textarea-1" name="story_text" ><?php echo stripslashes($data['content']); ?></textarea>
			<input type="hidden" name="adm" value="<?php echo base64_encode('su'); ?>" />
			<input type="hidden" name="f" value="<?php echo base64_encode('save_update_story'); ?>" />
			<input type="hidden" name="s_id" value="<?php echo $story_id; ?>" />
		<p><i>Allowed tags are &lt;p&gt;,&lt;a&gt;,&lt;strong&gt;,&lt;i&gt;,&lt;br&gt;</i><br />
		To put a read more link insert the tag <i>&lt;rmore&gt;</i></p>
		<hr class="light-hr" />
		</form>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.form_hide').click(function(){
					$('.admin-panel-story-form').fadeTo('slow',0.3).slideUp('slow');
				});
			});
		</script>
		<?php
	}
	
	/*
	 * Load coders for a given match
	 */

	function loadCoders()
	{
		global $db,$_pre;
		$match_id=(int)$_GET['m_id'];
		$query="SELECT * FROM {$_pre}matches WHERE id=$match_id";
		$db->setQuery($query);
		if($db->foundRows<1)
			return;
		
		$match_data=$db->fetch_assoc();
		$match_table_name=$match_data['match_table_name'];
		$query="SELECT * FROM {$_pre}{$match_table_name} ORDER BY nick_name";
		$db->setQuery($query);
		echo "<select name='vs_coders_select' id='vs_coders_select' class='admin-panel-select' onChange=\"loadCodersSource(this.value,'$match_table_name');\">"; //
		echo "<option value='none'>Select coder</option>";
		while($row=$db->fetch_assoc())
			echo "<option value='{$row['registration_no']},{$row['files']}'>{$row['registration_no']} .::. {$row['nick_name']}</option>";
		echo "</select>";
	}
	
	/*
	 * Load coder source code alongside other details such as time taken, submissions made, nick name.
	 * This function returns a JSON object
	 */
	
	function loadCoderSource()
	{
		global $db,$_pre;
		$match_table_name=$_GET['dir'];
		$reg_no=$_GET['reg_no'];
		$files=$_GET['files'];
		
		if($files=='')
		{
			echo "{'state': false,'message':'No source code files submitted for $reg_no'}";
			return;
		}
		
		$query="SELECT * FROM {$_pre}{$match_table_name} WHERE registration_no='$reg_no' LIMIT 1";
		$db->setQuery($query);
		if($db->foundRows<1)
		{
			echo "{'state': false,'message':'Coder registration number invalid'}";
			return;
		}
		$coder_data=$db->fetch_assoc();
		
		$tmp=create_time($coder_data['time_taken']);
		$time_taken=$tmp['hrs'].'hrs, '.$tmp['min'].'min, '.$tmp['sec'].'sec';
		$source_code_path=base64_encode('..'.DS.'competition_uploads'.DS.$match_table_name.DS.$coder_data['actual_file']);
		echo "{'state': true,'message':'Coder source loaded successfully','vs_source':'$source_code_path','vs_coder_name':'{$coder_data['nick_name']}','vs_language':'{$coder_data['language']}','vs_disqualified':{$coder_data['disqualified']},'vs_downloads':'{$coder_data['downloads_count']}','vs_submissions':{$coder_data['submissions']},'vs_correct':{$coder_data['correct']},'vs_lst':{$coder_data['last_submission_time']},'vs_code_tt':'$time_taken','vs_score':{$coder_data['points']},reg_no:'$reg_no'}";
	}
	
	/*
	 * Print source code for a given user to be utilized by ajax
	 */
	
	function loadSourceFromFile()
	{
		$path=base64_decode($_GET['path']);
		
		echo file_get_contents($path);
	}
	
	/*
	 * Load auto complete for email addresses
	 */

	function mailAutoComplete()
	{
		global $db,$_pre;
		$q = strtolower($_GET["q"]);
		if (!$q) return;
		$query="SELECT full_names,registration_no,nick_name,email FROM {$_pre}users ORDER BY full_names";
		$db->setQuery($query);
		$items=array();
		while($row=$db->fetch_assoc())
		{
			$key=$row['full_names'].' ('.$row['nick_name'].') '.$row['registration_no'];
			$items[$key]=$row['email'];
		}
		
		foreach ($items as $key=>$value) {
// 			if (strpos(strtolower($key.$value), $q) != false) {
// 				echo "$key|$value\n";
// 			}
			if (preg_match("/$q/",strtolower($key.$value))) {
				echo "$key|$value\n";
			}
		}
	}
	
	/**
	 * toggle user disqualification status
	 */
	
	function disqualifyUserToggle()
	{
		global $db,$_pre;
		
		$registration_no=$_GET['reg_no'];
		$match_table_name=$_GET['dir_name'];
		$disq_state=(int)$_GET['disq_state'];
		$disq_state=($disq_state==1)?$disq_state:0;
		
		//Toggle disqualification state
		if($disq_state==1)
			$disq_state=0;
		else
			$disq_state=1;
		echo $disq_state;
		$query="UPDATE {$_pre}$match_table_name SET disqualified=$disq_state WHERE registration_no='$registration_no' LIMIT 1";
		$db->setQuery($query);
	}
	
	/**
	 * Using jqPlot, show a user's points scoring history in a graph
	 */
	
	function showCoderHistory()
	{
		global $db,$_pre;
		$registration_no=$_GET['reg_no'];
		
		$query="SELECT * FROM {$_pre}user_match_log WHERE registration_no='$registration_no' AND participated=1 ORDER BY match_date LIMIT 20";
		$db->setQuery($query);
		$p_match_list=array();
		while($row=$db->fetch_assoc())
		{
			$p_match_list[]=array('match_id'=>$row['match_id'],'title'=>$row['title'],'match_date'=>$row['match_date']);
		}
		$p_match_filtered=array();
		$line="[]"; //Default value for graphing data for coder who has never participated
		for($x=0;$x<count($p_match_list);$x++)
		{
			$query="SELECT match_table_name FROM {$_pre}matches WHERE id={$p_match_list[$x]['match_id']} AND match_ranked=1";
			$db->setQuery($query);
			if($db->foundRows<1)
				continue;
			$row=$db->fetch_assoc();
			$match_table_name=$row['match_table_name'];
			
			$query="SELECT points FROM {$_pre}{$match_table_name} WHERE registration_no='$registration_no' AND disqualified=0";
			$db->setQuery($query);
			if($db->foundRows<1)
				continue;
			else
			{
				$row=$db->fetch_assoc();
				if(count($p_match_filtered)==0)
					$cumulative_points=$row['points'];
				else
					$cumulative_points=$row['points']+$p_match_filtered[count($p_match_filtered)-1]['cumulative_points'];
					
					//Pack $p_match_filtered with values
					$p_match_filtered[]=array('title'=>$p_match_list[$x]['title'],'match_date'=>$p_match_list[$x]['match_date'],'cumulative_points'=>$cumulative_points);
			}
			
			//Format array elements into jqplot compatible
			$line="["; //Start bracket
			foreach($p_match_filtered as $val)
			{
				$str="['".date("j-M-y",$val['match_date'])."',{$val['cumulative_points']}],";
				$line.=$str;
			}
			if(count($p_match_filtered)>0)
				$line=substr($line,0,strlen($line)-1); //Remove last comma but we need to take care of when the array is empty meaning there will be no comma at the end
			$line.="]"; //End bracket
		}
		//print_r($p_match_filtered);
		//echo $line;
		?>
		<html>
		<head>
		
		<link rel="stylesheet" type="text/css" href="../theme/jqplot/jquery.jqplot.css" />
		
		<!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
		<script language="javascript" type="text/javascript" src="../js/jquery-1.4.2.min.js"></script><!--important. jqPlot uses jquery 1.4+-->
		<script language="javascript" type="text/javascript" src="../js/jqplot/jquery.jqplot.min.js"></script>
		<script type="text/javascript" src="../js/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
		<script type="text/javascript" src="../js/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
		<script type="text/javascript" src="../js/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
		<script type="text/javascript" src="../js/jqplot/plugins/jqplot.highlighter.min.js"></script>
		<script type="text/javascript" src="../js/jqplot/plugins/jqplot.cursor.min.js"></script>
	
		<script type="text/javascript">
		$(document).ready(function(){
		$.jqplot.config.enablePlugins = true; //Very important
		
		line1=<?php echo $line; ?>; //Assign graphing data to JS var
		plot1 = $.jqplot('chartdiv', [line1], {
		title:'Points score history',
		axes:{
			xaxis:{
			renderer:$.jqplot.DateAxisRenderer,
			rendererOptions:{tickRenderer:$.jqplot.CanvasAxisTickRenderer},
			tickOptions:{
				formatString:'%b %#d, %Y', 
				fontSize:'10pt', 
				fontFamily:'Tahoma', 
				angle:-30
			}
			},
			yaxis:{tickOptions:{formatString:'%.2f'}}
		},
		highlighter: {sizeAdjust: 7.5},
		cursor: {show: false}
		});
		});
		</script>
		</head>
		<body>
		<div id="chartdiv" style="height:300px;width:500px;margin-left: 30px; "></div>
		</body>
		</html>
		<?php
	}
	
	/**
	 * Show problem sheet in admin panel edit match
	 */
	
	function showProblemSheet()
	{
		$dir_name=$_GET['dir_name'];
		$problem_sheet=$_GET['problem_sheet'];
		
		require_once('..'.DS.'competition_uploads'.DS.$dir_name.DS.$problem_sheet);
	}
	
	/**
	 * Start download of answer/problem sheet. This is loaded in a hidden iframe in
	 * the admin panel edit match
	 */
	
	function downloadSheet()
	{
		$dir_name=$_GET['dir_name'];
		$sheet=$_GET['sheet'];
		$sheet_type=$_GET['type'];
		
		header("Content-Type: text/plain");
		
		// It will be called ...
		header('Content-Disposition: attachment; filename="cz-'.$sheet_type.'.txt"');
		
		readfile('..'.DS.'competition_uploads'.DS.$dir_name.DS.$sheet);
	}
	
}

?>
