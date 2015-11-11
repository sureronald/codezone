<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Help the online judge function in           |
|              arenaValidatorHelper.php                    |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

class onlineJudgeHelper
{
	/**
	 * Uploaded file new name after zipping. This is a zip archive name
	 */
	var $source_file_name="";
	
	/**
	 * Submission correct?
	 */

	var $correct=0;
	
	/**
	 * Validate submitted files: returns boolean
	 */
	
	/**
	 * Language of submitted file
	 */
	
	var $language='other';
	
	/**
	 * Source file name as it appears in the zipped archive after upload and saving
	 */
	
	var $tmp_source_file="";
	
	function check_files($files)
	{
		$output_file=$files['output_file'];
		$source_file=$files['source_file'];
		
		if($output_file['error']!=0 || $source_file['error']!=0)
			return false;
		//File size too small?
		if($output_file['size']==0 || $source_file['size']==0)
			return false;
		//File size too large? ie more than 100kB
		if($output_file['size']>100000 || $source_file['size']>100000)
			return false;
		//if($output_file['type']!='text/plain')
			//return false;
		return true;
	}
	
	/**
	 * Return source file details e.g extension, language used
	 */
	
	function get_source_file_details($files)
	{
		$src_file=$files['source_file'];
		$file_type=$src_file['type'];
		$lang="other"; //Initialize $lang and $ext to defaults
		$ext='.all';
		if($file_type=='text/x-python')
		{
			$lang='Python';
			$ext='.py';
		}
		if($file_type=='text/x-java')
		{
			$lang='Java';
			$ext='.java';
		}
		if($file_type=='text/x-c++src')
		{
			$lang='C++';
			$ext='.cpp';
		}
		if($file_type=='text/x-csrc')
		{
			$lang='C';
			$ext='.c';
		}
		if($file_type=='application/x-php')
		{
			$lang='PHP';
			$ext='.php';
		}
		
		$this->language=$lang; //Set the language variable to the appropriate one
		return array('type'=>$src_file['type'],
		'size'=>$src_file['size'],
		'language'=>$lang,
		'extension'=>$ext);
	}
	
	/**
	 * Copy submissions to the server and in the appropriate place
	 */
	
	function upload_submissions($files,$match_details)
	{
		require_once('..'.DS.'lib'.DS.'createZipArchive.php');
		//Is there a session running
		if(!isset($_SESSION['user_row_data']))
			return false;
		
		$ud=$_SESSION['user_row_data'];
		
		$source_file_details=$this->get_source_file_details($files);
		
		$user_source_file_name=$ud['nick_name'].$match_details['id'].'.zip';
		$this->source_file_name=$user_source_file_name;
		$this->tmp_source_file=$ud['nick_name'].$match_details['id'].$source_file_details['extension'];
		//Zip and upload the source file, this is important when downloading source files by other users in CodeZone practice mode
		copy($files['source_file']['tmp_name'],'..'.DS.'competition_uploads'.DS.$match_details['match_table_name'].DS.$this->tmp_source_file);
		chdir('..'.DS.'competition_uploads'.DS.$match_details['match_table_name']);
		
		$status=create_zip_archive(array($this->tmp_source_file),$user_source_file_name,true);
		
		chdir('..'.DS.'..'.DS.'ajphp'); #go back to previous directory
		return $status;
		
		
	}
	
	/**
	 * Update user details in ecj_xxxxxxxx table
	 */

	function update_user_details($match_details)
	{
		global $_pre,$db;
		//Is there a session running
		if(!isset($_SESSION['user_row_data']))
			return false;
		$ud=$_SESSION['user_row_data'];
		
		
		$query="SELECT * FROM ".$_pre.$match_details['match_table_name']." WHERE registration_no='{$ud['registration_no']}'";
		$db->setQuery($query);
		$data=$db->fetch_assoc();
		$language=$this->language;
		$submissions=$data['submissions']+1;
		$source_file=$this->source_file_name;
		$correct=$this->correct;
		$previously_correct=$data['correct']; //Previous submission was correct?
		$previous_points=$data['points']; //Previous submission points
		$submission_time=time()+microtime();
		$time_elapsed=$submission_time-$match_details['start_time'];
		$points=($this->correct)?$this->calc_points($match_details,$ud['registration_no']):0.00;
		
		$query="UPDATE ".$_pre.$match_details['match_table_name']." SET language='$language',files='$source_file',actual_file='{$this->tmp_source_file}',submissions=$submissions,correct=$correct,last_submission_time=$submission_time,time_taken=$time_elapsed,points=$points WHERE registration_no='{$ud['registration_no']}'";
		$db->setQuery($query);
		
		//Update user's profile total ranking points only if MATCH RANKED
		if($match_details['match_ranked'])
		{
			//But we need to know if there was a previos submission in which the user was correct and therefore take care of not just adding points. Details on this is stored in the $previously_correct and $previous_points variables
			$query="SELECT ranking_pts FROM ".$_pre."profile WHERE registration_no='{$ud['registration_no']}'";
			$db->setQuery($query);
			$row=$db->fetch_assoc();
			if($previously_correct)
				$ranking_pts=$row['ranking_pts']-$previous_points+$points;
			else
				$ranking_pts=$row['ranking_pts']+$points;
			$query="UPDATE ".$_pre."profile SET ranking_pts='$ranking_pts' WHERE registration_no='{$ud['registration_no']}'";
			$db->setQuery($query);
		}
	}
	
	/**
	 * Calculate points scored
	 */

	function calc_points($match_details,$registration_no)
	{
		global $_pre,$db;
		
		$submission_time=time()+microtime();
		$time_elapsed=$submission_time-$match_details['start_time'];
		$duration=$match_details['duration'];
		$difficulty=$match_details['difficulty'];
		$match_points=$match_details['match_points'];
		$min_time=($match_details['duration']*$match_details['difficulty'])/100;
		
		//Solved earlier than expected time? give full points
		if($time_elapsed<$min_time)
			return $match_points;
		$extra_time=$time_elapsed-$min_time;
		//Time block on the other side of difficulty
		$difficulty_rem_time=$duration-$min_time;
		//Points penalised because of time elapsed calculated as a fraction of match points where match points is reduced based on the difficulty as below
		$points_time_penalty=($extra_time/$difficulty_rem_time)*(($difficulty_rem_time/$duration)*$match_points);
		
		//Get number of submissions made so far and penalise if greater 0
		$query="SELECT submissions FROM ".$_pre.$match_details['match_table_name']." WHERE registration_no='$registration_no'";
		$db->setQuery($query);
		if($db->foundRows==0)
			return 0;
		$row=$db->fetch_assoc();
		$submissions_so_far=$row['submissions'];
		
		//Submissions penalty is 1% * match_points * submissions_so_far
		if($submissions_so_far>0)
			$submissions_penalty=(1/100)*$match_points*$submissions_so_far;
		else
			$submissions_penalty=0;
		
		$points_scored=$match_points-$points_time_penalty-$submissions_penalty;
		
		if($points_scored>=0)
			return $points_scored;
		else
			return 0;
	}
	
	/**
	 * Parse output file for correctness: returns boolean
	 */

	function parse_output_file($files,$match_details)
	{
		
		$answer_sheet=file_get_contents('..'.DS.'competition_uploads'.DS.$match_details['match_table_name'].DS.$match_details['answer_sheet']);
		$answer_sheet_lines=explode("\n",$answer_sheet);
		
		if(is_readable($files['output_file']['tmp_name']))
		{
			$output_sheet=file_get_contents($files['output_file']['tmp_name']);
			$output_sheet_lines=explode("\n",$output_sheet);
		}
		else
			return false;
		
		/**Compare file line by line to check for correctness*/
		$line_counter=0;
		foreach($answer_sheet_lines as $val)
		{
			if($val=='')
				continue;
			//Submitted file has fewer lines? (takes care of accessing a non-existent index)
			if((count($output_sheet_lines)-1)<$line_counter)
				return false;
			
			if($output_sheet_lines[$line_counter]!=$val)
				return false;
			
			$line_counter++;
		}
		$this->correct=1;
		return true;
	}
	
}
?>
