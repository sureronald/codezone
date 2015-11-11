<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Helper functions                            |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

/**
 * Function to convert associative array to indexed
 */

function assoc_to_indexed( $assoc )
{
  $array=array();
  $i=0;
  foreach($assoc as $val)
  {
    $array[$i]=$val;
    $i++;
  }
  return $array;
}

/**
 * Print custom system messages
 */

function system_messages($state,$mesg,$sticky='false')
{
  if(isset($state))
  switch($state)
  {
    case 0:
//       echo "<div id=\"system-error\">";
//       echo "<p class=\"system-mesages\"><span>&nbsp;</span>$mesg</p>";
//       echo "</div>";
       echo "<script type='text/javascript'>jgrowl_messages('$mesg','error',$sticky)</script>";
      break;
    case 1:
//       echo "<div id=\"system-success\">";
//       echo "<p class=\"system-mesages\"><span>&nbsp;</span>$mesg</p>";
//       echo "</div>";
      echo "<script type='text/javascript'>jgrowl_messages('$mesg','success',$sticky)</script>";
      break;
    case 2:
//       echo "<div id=\"system-warning\">";
//       echo "<p class=\"system-mesages\"><span>&nbsp;</span>$mesg</p>";
//       echo "</div>";
       echo "<script type='text/javascript'>jgrowl_messages('$mesg','warning',$sticky)</script>";
      break;
  }

}

/**
 * Encrypt a password
 */

function encrypt_password( $pass,$salt=false,$saltLength=4 )
{
//   if ( $salt === false )
//   {
//     $res = '';
//     for( $i=0;$i<$saltLength;$i++ )
//     {
//       $res .= pack( 's',mt_rand() );
//     }
//     $salt = substr( base64_encode( $res ),0,$saltLength );
//   }
//   return $salt . sha1( $salt . $pass );
return md5($pass.$pass);
}

/**
 * Generate Random string
 */
 
 function random_string($length=5)
 {
	$s_value='';
	 for ($i=0; $i<=$length; $i++) {
		$d=rand(1,30)%2;
		$s_value.= $d ? chr(rand(65,90)) : chr(rand(48,57));
	}
	
	return $s_value;
 }
/**
 * Check Date
 */

function check_date($date)
{
	if(preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/",$date,$parts))
	{
		if(checkdate($parts[2],$parts[3],$parts[1]))
			return true;
		else
			return false;
	}
	else
		return false;
}

/**
 * Check time
 */

function check_time($time)
{
	if(preg_match("/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/",$time,$parts))
	{
		settype($parts[1],'integer');settype($parts[2],'integer');settype($parts[3],'integer');
		if($parts[1]>=0 && $parts[1]<24 && $parts[2]>=0 && $parts[2]<60 && $parts[3]>=0 && $parts[1]<60)
			return true;
		else
			return false;
	}
	else
		return false;
}

/**
 * Make time
 */

function make_time($strtime)
{
	list($date,$time)=explode(" ",$strtime);
	$date=explode("-",$date);
	$time=explode(":",$time);
	return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
}

/**
 * Build time from some seconds to format: (hh:mm:ss)
 */

function create_time($timestamp)
{
	$hrs=(int)($timestamp/3600);
	$tmp_min=$timestamp%3600;
	$min=(int)($tmp_min/60);
	$sec=$tmp_min%60;
	$hrs=($hrs<10)?("0".$hrs):$hrs;
	$min=($min<10)?("0".$min):$min;
	$sec=($sec<10)?("0".$sec):$sec;
	return array("hrs"=>$hrs,"min"=>$min,"sec"=>$sec);
}

/**
 * Build date in the format yyyy-mm-dd from timestamp
 */
 
function get_date($timestamp)
{
	return date('Y-m-d',$timestamp);
}

/**
 * Build time in the format hh:mm:ss from timestamp
 */

function get_time($timestamp)
{
	return date('H:i:s',$timestamp);
}

function time_stamp_to_readable($timestamp)
{
	return date("j \of F Y, \a\\t g:i:s a",$timestamp);
}

function time_stamp_to_date($timestamp)
{
	return date("j \of F Y",$timestamp);
}

/**
 * Validate an email address
 */
 
function checkEmail($email)
{
	$pattern='/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/';
	if(preg_match($pattern,$email))
		return true;
	else
		return false;
}

/**
 * Validate string that has to be one word with only [a-zA-z0-9._]
 */

function checkAlphanumPlus($string)
{
	$pattern='/^[a-zA-Z0-9._]+$/';
	if(preg_match($pattern,$string))
		return true;
	else
		return false;
}

/**
 * Return user class color
 */

function get_user_class($points,$is_admin=false)
{
	if($is_admin)
		return "admin_orange";
	if($points>=2501)
		return "coder_red";
	else if($points>=2001)
		return "coder_yellow";
	else if($points>=1501)
		return "coder_blue";
	else if($points>=1001)
		return "coder_green";
	else if($points>=501)
		return "coder_gray";
	else
		return "coder_black";
}

/**
 * Return the rank of a coder in a double susbscripted array [0]=position,[1]=total members
 */

function get_coders_rank($registration_no)
{
	global $db,$_pre;
	$query="SELECT {$_pre}users.registration_no,{$_pre}users.user_type,{$_pre}profile.ranking_pts FROM {$_pre}users,{$_pre}profile WHERE {$_pre}users.registration_no={$_pre}profile.registration_no ORDER by ranking_pts DESC";
	$db->setQuery($query);
	
	$total_coders=$db->foundRows;
	$coders=array();
	while($row=$db->fetch_assoc())
	{
		if($row['user_type']=='registered') //Filter out users
			$coders[]=array($row['registration_no'],$row['ranking_pts']);
	}
	//print_r($coders);
	//Get the users index and return
	$index=0;
	while($index<=count($coders))
	{
		if(@$coders[$index][0]==$registration_no)
			break;
		$index++;
	}
	return array($index+1,count($coders));
	
}

/**
 * Sort the coders array for loading to the scoreboard where there is a tie
 */

function sort_coders_array($coders)
{
	for($y=0;$y<count($coders);$y++)
	for($x=0;$x<count($coders)-1;$x++)
		{
			if($coders[$y]['points']==$coders[$x+1]['points'] && $coders[$y]['time_taken']>$coders[$x+1]['time_taken'])
			{
				//Perform a swap
				$temp=$coders[$x+1];
				$coders[$x+1]=$coders[$y];
				$coders[$y]=$temp;
			}
		}
	return $coders;
}

?>
