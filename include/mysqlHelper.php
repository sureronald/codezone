<?php

/**
*----------------------------------------------------------*
| Author: Osure Ronald Osure                               |
| Author url: {@link http://sureronald.blogspot.com}       |
| License: GNU/GPL                                         |
| Description: A mysql Helper class                        |
|                                                          |
*----------------------------------------------------------*
*/
//Access is within framework?
defined('IN_APP') or die('Restricted Access!');


class mysqlHelper{
  
  /**
  Time in seconds after which a query will be considered erroneous
  @var integer
  */
  var $maxQueryTime=30;
  
  /**
  Email address for sending erroneous queries
  */
  var $notifyAddress='';
  
  /**
  After an INSERT,DELETE,UPDATE affected rows return
  */
  var $affectedRows=0;
  
  /**
  After a SELECT, get number of records that would have been returned
  */
  var $foundRows=0;
  
  /**
  MySQL link idetifier ( access is private )
  */
  var $link=false;
  
  /**
  MySQL resource identifier ( access is private )
  */
  var $resource=false;
  /**
  MySQL select database a success? (access private )
  */
  var $dbselect=false;
  
  /**
   * Constructor of the class
   *
   */

  function __construct()
  {
    global $_dbhost,$_dbname,$_dbuser,$_dbpass,$_mail;
    $this->notifyAddress=$_mail;
    $this->connect($_dbhost,$_dbuser,$_dbpass,$_dbname);
  }
  
  /**
   * Establish a connection; returns true else death
   *
   */

  function connect($dbHost,$dbUser,$dbPass,$dbName)
  {
    $this->link=mysql_connect( $dbHost,$dbUser,$dbPass ) or die( 'Unable to establish a connection' );
   
    $this->dbselect=mysql_select_db( $dbName ) or die( 'Unable to select database' );
    return true;
  }
  
  /**
   * Cleans up a string
   *
   */
  
  function cleaner($string)
  {
    //Get state of magic quotes
    if(get_magic_quotes_gpc())
    {
      $returnValue=stripslashes($string);
    }
    //escape the string
    $returnValue=mysql_real_escape_string($string);
    
    //return escaped string
    return $returnValue;
  }
  
   /**
   * Verify if connection to mysql server has previously been established
   *
   */

  function _connected()
  {
    return ($this->link)?true:false;
  }
  
   /**
   * return an associative array
   *
   */

  function fetch_assoc()
  {
    //Check if given resource is valid
    if(is_resource($this->resource))
    {
	    $data=mysql_fetch_assoc($this->resource);
	    return $data;
    }
    else
      return false;
  }


  /**
   * Perform a query
   *
   */
  
  function setQuery($query)
  {
    //Check to see if there is a connection
    if($this->_connected())
    {
      //If we have a SELECT query and the SQL_CALC_FOUND_ROWS string is not in it
      //Done to find the number of records that would have been returned if there was no LIMIT applied
      if(strtolower(substr(ltrim($query),0,6))=="select" && strpos($query,"SQL_CALC_FOUND_ROWS")===false)
      {
	//add the 'SQL_CALC_FOUND_ROWS' parameter to the query
	$query=preg_replace("/SELECT/i","SELECT SQL_CALC_FOUND_ROWS",$query,1);
      }
      
      //Start a timer
      $startTime=microtime(true);
      //execute the query
      //$query=$this->cleaner($query);
      $result=mysql_query($query) or die(mysql_error());
      //Stop timer
      $endTime=microtime(true);
      
      if($endTime - $startTime > $this->maxQueryTime)
      {
	//then send notification email (Please edit here!!!!!)
	@mail($this->notifyAddress,'ERRONEOUS QUERY','
	Erroneous query on '.date('',time()).' from I.P. '.$_SERVER['REMOTE_ADDR'].' browser'.$_SERVER['HTTP_USER_AGENT'].'','From: CodeZone');
      }
      
      //The query was successful
      if($result)
      {
	//get the number of rows that would have been selected if there was no limit
	$foundRows=mysql_fetch_assoc(mysql_query("SELECT FOUND_ROWS()"));
	
	$this->foundRows=$foundRows["FOUND_ROWS()"];
	
	//get the number of affected rows for DELETE, INSERT, UPDATE queries
	$this->affectedRows=@mysql_affected_rows();
	
	//initialize result resource
	$this->resource=$result;
	
	return true;
	
      }              
         
                
      return false;
    }
    
 }
}
?>