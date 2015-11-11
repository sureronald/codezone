<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Arena Submission/validator helper Class     |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

require_once('onlineJudgeHelper.php');

class arenaValidatorHelper extends onlineJudgeHelper
{
	/**
	 * Hold the current time to accuracy of microseconds
	 */
	var $time_now=0;
	
	/**
	 * Maximum submissions
	 */
	var $_max_submissions=0;
	
	/**
	 * Submission timeout
	 */
	var $submission_timeout;
	/**
	 * Hold the mysql database class object
	 */
	var $db='';
	
	/**
	 * Store the match details
	 */
	var $md=array();
	
	/**
	 * Store user data
	 */
	var $user_data=array();
	
	/**
	 * Tables prefix
	 */
	var $_pre='';
	
	/**
	 * arenaAppletHelper object
	 */
	var $arn=0;
	
	
	function __construct($time_now,$table_prefix,$db,$arn,$_max_submissions,$submission_timeout)
	{
		$this->time_now=$time_now;
		$this->db=$db;
		$this->user_data=$_SESSION['user_row_data'];
		$this->_pre=$table_prefix;
		$this->arn=$arn;
		$this->_max_submissions=$_max_submissions;
		$this->submission_timeout=$submission_timeout;
		$this->md=$arn->get_match_details();
	}
	
	/**
	 * Checks to see if a user with a given registration number is registered 
	 */

	function isUserRegistered()
	{
		$db=$this->db;
		$user_data=$this->user_data;
		$time_now=$this->time_now;
		$md=$this->md;
		$_pre=$this->_pre;
		
		$query="SELECT * FROM ".$_pre.$md['match_table_name']." WHERE registration_no='".$user_data['registration_no']."'";
		$db->setQuery($query);
		
		if($db->foundRows<1)
			return false;
		else
			return true;
	}
	
	/**
	 * Return the download link
	 */

	function download_link()
	{
		$match_folder_name=$this->md['match_table_name'];
		$download=$this->next_download();
		if($download==-1)
		{
			echo "<script type='text/javascript'>
			alert('Your downloads are over!');
			
			</script>";
			return;
		}
		echo "To redownload the input file, <a href='javascript:void(0);' onclick=\"startDownload('arenaValidator.php',{$this->md['id']},'$match_folder_name','{$this->md['input_sheet']}',$download);\">click here</a>";
		?>
		<script type="text/javascript">
		var submission_timeout=<?php echo $this->submission_timeout; ?>;
		startDownload('arenaValidator.php',<?php echo $this->md['id'].",'".$match_folder_name."','".$this->md['input_sheet']."','".$download; ?>');
		</script>
		<?php
	}
	
	
	
	/**
	 * Return the next file count to be downloaded or else -1 (false) if downloads are over. It also increments download count value in db
	 */
	
	function next_download()
	{
		$_pre=$this->_pre;
		$md=$this->md;
		$user_data=$this->user_data;
		$max_submit=$this->_max_submissions;
		$query="SELECT downloads_count FROM ".$_pre.$md['match_table_name']." WHERE registration_no='{$user_data['registration_no']}'";
		$this->db->setQuery($query);
		$data=$this->db->fetch_assoc();
		$downloads_count=$data['downloads_count'];
		
		//Has the user exhausted his/her downloads?
		if(!$this->timed_out() && ($downloads_count)<=$max_submit)
		{
			$query="UPDATE ".$_pre.$md['match_table_name']." SET downloads_count=$downloads_count+1 WHERE registration_no='{$user_data['registration_no']}'";
			$this->db->setQuery($query);
			
			
			return $downloads_count+1;
		}
		else
			return -1;
	}
	
	/**
	* Start download of a given file: this is called as part of a url in a hidden iframe
	*/
	
	function is_download($data,$output_type="text/plain")
	{
		//We are outputting
		header("Content-Type: $output_type");
		
		// It will be called ...
		header('Content-Disposition: attachment; filename="cz-attempt-'.$this->db_download_count().'.txt"');
		
		readfile('..'.DS.'competition_uploads'.DS.$this->md['match_table_name'].DS.$this->md['input_sheet']);
	}
	
	/**
	 * Check if downloads are over, returns boolean. Useful when arena is being loaded first time and any other subsequent downloads
	 */

	function downloads_over()
	{
		
		$query="SELECT downloads_count FROM ".$this->_pre.$this->md['match_table_name']." WHERE registration_no='{$this->user_data['registration_no']}'";
		$this->db->setQuery($query);
		$data=$this->db->fetch_assoc();
		$downloads_count=$data['downloads_count'];
		
		if($downloads_count==$this->_max_submissions)
			return false;
		else
			return true;
		
	}
	
	/**
	 * Get and return the download count in the database
	 */
	
	function db_download_count()
	{
		$query="SELECT downloads_count FROM ".$this->_pre.$this->md['match_table_name']." WHERE registration_no='{$this->user_data['registration_no']}'";
		$this->db->setQuery($query);
		
		$data=$this->db->fetch_assoc();
		
		return $data['downloads_count'];
	}
	
	/**
	 * Return global submission timeout on ajax calls
	 */
	
	function get_timeout()
	{
		return $this->submission_timeout;
	}
	
	/**
	 * Return the number of seconds remaining for match to come to a close
	 */
	
	function match_rem_time()
	{
		return ($this->md['start_time']+$this->md['duration'])-$this->time_now;
	}
	
	/**
	 * Show partial scoreboard for the arena live scoreboard
	 */

	function partial_scoreboard()
	{
		//sleep(1);
		$db=$this->db;
		$_pre=$this->_pre;
		$md=$this->md;
		require_once('..'.DS.'include'.DS.'utilityFunctions.php');
		$query="SELECT * FROM ".$_pre.$md['match_table_name']." ORDER BY points DESC LIMIT 10";
		$db->setQuery($query);
		
		//No participant so far therefore do nothing
		if($db->foundRows==0)
			return;
		
		$submissions_total=0;
		$submissions_correct=0;
		
		$coders=array();
		while($row=$db->fetch_assoc())
		{
			if($row['correct']==1)
				array_push($coders,$row);//Add correct users to the coders array
			
			$submissions_total+=$row['submissions'];
			$submissions_correct+=$row['correct'];
		}
		
		//No correct participant therefore do nothing
		if(count($coders)==0)
			return;
		
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
		echo "<span><b>Correct:</b> $submissions_correct/$submissions_total (".number_format(@(($submissions_correct/$submissions_total)*100),2)."%)</span>";
		echo "</div>";
	}
	
	/**
	 * Checks for the expiry of submission time and returns boolean
	 */
	
	function timed_out()
	{
		$form_submitted_time=$this->time_now;
		$form_load_time=$this->user_data['form_load_time'];
		
		return ($form_submitted_time-$form_load_time)>$this->submission_timeout;
	}

	/**
	 * Intelligent function to validate files
	 */

	function online_judge($files,$post)
	{
		//Check for expiry of submission time

		if($this->timed_out())
		{
			echo "{ 'error':'Submission time expired!' }";
			return;
		}
		
		//Is submission from superuser?
		$su=false;
		
		if($this->user_data['user_type']=='su')
			$su=true;
		//Hidden form field set?
		if(!isset($post['st']))
		{
			echo "{ 'error':'Submission source invalid' }";
			return;
		}
		
		//Submissions over?
// 		if(!$this->downloads_over())
// 		{
// 			echo "{ 'submission_over':'Your chances are over, Lets meet next time!' }";
// 			return;
// 		}
		
		if(!parent::check_files($files))
		{
			echo "{ 'error':'Files not allowed' }";
			return;
		}
		
		//Only upload for non ~su~ users
		if(!$su)
		{
			if(!parent::upload_submissions($files,$this->md))
			{
				echo "{ 'error':'Unable to upload files' }";
				return;
			}
		}
		
		if(parent::parse_output_file($files,$this->md))
		{
			echo "{ 'correct':'yes','message':'Submission Correct!' }";
		}
		else
			echo "{ 'correct':'no','message':'Submission incorrect!' }";
		
		//Update user details in match table but first check the user type so that we only update for a registered user
		if(!$su)
			parent::update_user_details($this->md);
		
		//Delete the uploads
		unlink($files['output_file']['tmp_name']);
		unlink($files['source_file']['tmp_name']);
	}
	
	/**
	 * Render submission form
	 */
	
	function render_submission_form()
	{
		//Set the form load time in sessio variable
		$_SESSION['user_row_data']['form_load_time']=$this->time_now;
		?>
		<html>
		<head>
		<link type="text/css" media="screen" rel="stylesheet" href="../theme/jquery.jgrowl/jquery.jgrowl.css" />
		<link type="text/css" media="screen" rel="stylesheet" href="../theme/jquery.jgrowl/jgrowl.custom.css" />
		<link type="text/css" media="screen" rel="stylesheet" href="../theme/submission.form.css" />
		<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="../js/jquery.form.js"></script>
		<script type="text/javascript" src="../js/jquery.jgrowl.min.js"></script>
		<script type="text/javascript" src="../js/jquery.countdown.min.js" ></script>
		<script type="text/javascript" src="../js/startDownload.js" ></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$.ajaxSetup({ cache:false });
			var loadUrl='arenaValidator.php';
			var ajax_load = "<img src='../images/loader.gif' alt='loading...' />";
			$('#loading-bar').css({'display':'none'});
			
			//Start the download and show the download link
			$("#download-link").html(ajax_load).load(loadUrl,'a=arenaval&v=start_download',function(responseText){});
			
			//Start the countdown timer
			$('#timeout').countdown({ layout:'<span>{mnn}:{snn} </span>',until:+<?php echo $this->submission_timeout; ?>,onExpiry:function(){
				//Send timeout notification to server
				$.ajax({
					type: "GET",
				       url: "ajphp/arenaValidator.php",
				       data: "a=arenaval&v=timedout"
				});
				$('#arena-applet-submission-form').fadeOut('slow');
			}
			});
			
			//Initialize submission Form
			$('#submission-form').ajaxForm({
				dataType:'json',
				beforeSubmit:checkFields,
				success:notifyUser,
				error:alertError
			});
		});
		
		//User notification
		function notifyUser(data){
			$('#loading-bar').css({'display':'none'}); //hide loading bar
			if(data.error){
				$.jGrowl(data.error,{header:'CodeZone online judge says...',theme:'error',life:3000});
				return;
			}
			if(data.submission_over){
				$.jGrowl(data.submission_over,{header:'CodeZone online judge says...',theme:'warning',sticky:true});
				return;
			}
			if(data.correct=='yes')
			$.jGrowl(data.message,{header:'CodeZone online judge says...',theme:'correct',sticky:true});
			else
				$.jGrowl(data.message,{header:'CodeZone online judge says...',theme:'failed',sticky:true});
// 			alert(data);
			//$('#submission-form').clearForm();
		}
		
		//Alert of ajax error
		function alertError(e,w,f)
		{
			alert('There was an error communicating to the server');
			//alert(w)
		}
		//Validate form submission fields and show loading bar if everything okey
		function checkFields(){
			var output_file=$('#output-file').val();
			var source_file=$('#source-file').val();
			
			if(output_file=='' || source_file==''){
				$.jGrowl('All fields are required!',{header:'CodeZone online judge says...',theme:'error'});
				return false;
			}
			
			$('#loading-bar').css({'display':'inline'});
			return true;
		}
		</script>
		</head>
		<body>
		<div id="arena-applet-submission-form">
		<h3 class="content-header">Submit your solution</h3>
		<span class="submission-timer" align="right" id="timeout"></span>
		<form id="submission-form" name="submissionForm" action="arenaValidator.php?a=arenaval&amp;v=solution-submission" method="POST" id="submission_form" enctype="multipart/form-data">
		<div id="download-link">&nbsp;</div><!--Echo download link here-->
		<table cellpadding="3" cellspacing="3">
		<tr><td class='form-field-label'><span class='form-field-label-text'>Your output file</span></td><td><input type="file" name="output_file" class="admin-panel-text-input" id="output-file" /></td></tr>
		<tr><td class='form-field-label'><span class='form-field-label-text'>Your source file</span></td><td><input type="file" name="source_file" class="admin-panel-text-input" id="source-file" /></td></tr>
		<td><input type="hidden" name="st" value="<?php echo base64_encode($this->time_now); ?>" /></td><td><input type="submit" class="admin-panel-submit-button" /></td></tr>
		</table>
		</form>
		<img id="loading-bar" alt="loading..." src="../images/loading.gif" />
		</div>
		<iframe id="if_download" src="" style="display: none;" /><!--Frame whose src is changed by JS to trigger download-->
		</body>
		</html>
		<?php
		
	}
}
