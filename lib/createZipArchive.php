<?php
/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: Zip creation library                        |
|                                                          |
*----------------------------------------------------------*
*/

//Access is within framework?
defined('IN_APP') or die('Restricted Access!');

function create_zip_archive($files=array(),$destination='',$overwrite=false)
{
	//If the zip file already exists and overwrite is false return false
	if(file_exists($destination) && !$destination)
		return false;
	
	$valid_files=array();
	
	//If files were passed in...
	
	if(is_array($files))
	{
		//cycle through each file
		foreach($files as $file)
		{
			//Make sure the file exists
			if(file_exists($file))
			{
				$valid_files[]=$file;
			}
		}
	}
	
	//If we have good files...
	if(count($valid_files))
	{
		//create the archive
		$zip=new zipArchive();
		
		if($zip->open($destination,$overwrite?ZIPARCHIVE::OVERWRITE:ZIPARCHIVE::CREATE)!=true)
		{
			return false;
		}
		
		//Add the files
		foreach($valid_files as $file)
		{
			$zip->addFile($file,$file);
		}
		
		//debug
		//echo 'The zip archive contains',$zip->numFiles,' files with a status of ',$zip->status;
		
		//Close the zip -- done!
		$zip->close();
		
		//Check to make sure the file exists
		return file_exists($destination);
	}
	else
		return false;
}

function extract_zip_archive($filename,$extract_to)
{
	$zip=new zipArchive;
	$res=$zip->open($filename);

	if($res==TRUE){
		$zip->extractTo($extract_to);

		$zip->close();
	}
	else
		return false;
}


?>
