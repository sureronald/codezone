<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Practice Submission/validator helper Class  |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

require_once('onlineJudgeHelper.php');

class practiceValidatorHelper extends onlineJudgeHelper
{
	/** Store the match id */
	var $match_id=0;
	
	/** Store match details */
	var $md=array();
	
	/** Keep count of downloaded files */
	var $d_count=0;
	
	function __construct($match_id)
	{
		global $_pre,$db;
		$this->match_id=$match_id;
		
		$query="SELECT * FROM ".$_pre."matches WHERE id=$match_id";
		$db->setQuery($query);
		$this->md=$db->fetch_assoc();
		
		//Initialize the session file download count; the cookie lasts for as long as the match duration
		if(!isset($_COOKIE['pd_count']))
			setcookie('pd_count',0,time()+$this->md['duration'],'/');
		//Initialize the session current match id tracker: helps in keeping track of the current match so that if a user chooses another practice match, we reset the download count to 0
		if(!isset($_COOKIE['p_mid']))
			setcookie('p_mid',$match_id,time()+$this->md['duration'],'/');
		
		//Has the user switched to another match? if so we need to reset the download count and the p_mid cookie value itself
		if(isset($_COOKIE['p_mid']))
		{
			if($_COOKIE['p_mid']!=$match_id)
			{
				setcookie('p_mid',$match_id,time()+$this->md['duration'],'/');
				setcookie('pd_count',0,time()+$this->md['duration'],'/');
			}
		}
		
		if(isset($_COOKIE['pd_count']))
			$this->d_count=(int)$_COOKIE['pd_count'];
	}
	
	/**
	 * Function to start automatic download
	 */

	function download_link()
	{	
		$match_folder_name=$this->md['match_table_name'];
		$input_sheet=$this->md['input_sheet'];
		$m_id=$this->md['id'];
		$this->download_plus(); //Increment download count
		echo "To redownload the input file, <a href='javascript:void(0);' onclick=\"startDownload('practiceValidator.php',$m_id,'$match_folder_name','$input_sheet',{$this->d_count});\">click here</a>";
		?>
		<script type="text/javascript">
		startDownload('practiceValidator.php',<?php echo $m_id.",'".$match_folder_name."','".$input_sheet."','".$this->d_count; ?>');
		</script>
		<?php
	}
	
	/**
	 * Function to increment the download count in the cookie[d_count]
	 */
	
	function download_plus()
	{
		$this->d_count=$this->d_count+1;
		setcookie('pd_count',$this->d_count,time()+$this->md['duration'],'/');
	}
	
	/**
	 * Start download of a given file: this is called as part of a url in a hidden iframe
	 */
	
	function is_download($data,$output_type="text/plain")
	{
		//We are outputting
		header("Content-Type: $output_type");
		
		// It will be called ...
		header('Content-Disposition: attachment; filename="cz-practice-attempt-'.$this->d_count.'.txt"');
		
		readfile('..'.DS.'competition_uploads'.DS.$this->md['match_table_name'].DS.$this->md['input_sheet']);
	}
	
	/**
	 * Validate submitted files: returns boolean
	 */
	
	function check_file($files)
	{
		$output_file=$files['output_file'];
		
		
		if($output_file['error']!=0)
			return false;
		//File size too small?
		if($output_file['size']==0)
			return false;
		//File size too large? ie more than 100kB
		if($output_file['size']>100000)
			return false;
		//if($output_file['type']!='text/plain')
			//return false;
		return true;
	}
	
	/**
	 * Online judge: only tell whether the source file is correct
	 */

	function online_judge($files,$post)
	{
		//Hidden form field set?
		if(!isset($post['st']))
		{
			echo "{ 'error':'Submission source invalid' }";
			return;
		}
		
		if(!$this->check_file($files))
		{
			echo "{ 'error':'File not allowed' }";
			return;
		}
		
		if(parent::parse_output_file($files,$this->md))
		{
			echo "{ 'correct':'yes','message':'Submission Correct!' }";
		}
		else
			echo "{ 'correct':'no','message':'Submission incorrect!' }";
		
		//Delete the uploads
		unlink($files['output_file']['tmp_name']);
	}
	
	/**
	 * Render submission form 
	 */
	
	function render_submission_form()
	{
		?>
		<html>
		<head>
		<link type="text/css" media="screen" rel="stylesheet" href="../theme/jquery.jgrowl/jquery.jgrowl.css" />
		<link type="text/css" media="screen" rel="stylesheet" href="../theme/jquery.jgrowl/jgrowl.custom.css" />
		<link type="text/css" media="screen" rel="stylesheet" href="../theme/submission.form.css" />
		<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="../js/jquery.form.js"></script>
		<script type="text/javascript" src="../js/jquery.jgrowl.min.js"></script>
		<script type="text/javascript" src="../js/startDownload.js" ></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$.ajaxSetup({ cache:false });
			var loadUrl='practiceValidator.php';
			var ajax_load = "<img src='../images/loader.gif' alt='loading...' />";
			$('#loading-bar').css({'display':'none'});
			
			//Start the download and show the download link
			$("#download-link").html(ajax_load).load(loadUrl,'a=arenaval&v=start_download&m_id=<?php echo $this->match_id; ?>',function(responseText){});
			
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
			$('#loading-bar').css({'display':'none'});
			if(data.error){
				$.jGrowl(data.error,{header:'CodeZone online judge says...',theme:'error',life:3000});
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
			
			if(output_file==''){
				$.jGrowl('This field is required!',{header:'CodeZone online judge says...',theme:'error'});
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
		<form id="submission-form" name="submissionForm" action="practiceValidator.php?a=arenaval&amp;v=solution-submission&amp;m_id=<?php echo $this->match_id; ?>" method="POST" id="submission_form" enctype="multipart/form-data">
		<div id="download-link">&nbsp;</div><!--Echo download link here-->
		<table cellpadding="3" cellspacing="3">
		<tr><td class='form-field-label'><span class='form-field-label-text'>Your output file</span></td><td><input type="file" name="output_file" class="admin-panel-text-input" id="output-file" /></td></tr>
		<tr><td><input type="hidden" name="st" value="<?php echo base64_encode(time()); ?>" /></td><td><input type="submit" class="admin-panel-submit-button" /></td></tr>
		</table>
		</form>
		<img id="loading-bar" alt="loading..." src="../images/loading.gif" />
		<iframe id="if_download" src="" style="display: none;" /><!--Frame whose src is changed by JS to trigger download-->
		</div>
		</body>
		</html>
		<?php
		
	}

}
?>
