<?php

//set the probability that session data will be cleaned
ini_set("session.gc_probability",1);

//define DB stuff
/*if  ( $_SERVER['HTTP_HOST']=="10.0.0.10"  || $_SERVER['HTTP_HOST']=="213.2.72.242") 
	define ('DB_HOST',"10.0.0.3");
else
	define ('DB_HOST',"localhost");
	
define ('DB_USER',"webuser2");
define('DB_PASS',"v7UtHOes");
define ('DB_NAME',"ancestry");*/

require_once ("./mysql_class.php");

function _open()
{
    global $_sess_db;
    
    $_sess_db = new MySQL_DB(DB_HOST,DB_NAME,DB_USER,DB_PASS);
 	
    return $_sess_db->valid();
}
 
function _close()
{
    global $_sess_db;
 
    unset ($_sess_db); 
}

function _read($id)
{
	global $_sess_db;
 
	$result=$_sess_db->getData("tbl_sessions",array("session_data"),array("idtbl_sessions={$id}","deleted=0"));
   
	if($result)
		return $result[0]['session_data'];
	else
		return '';
}

function _write($id, $data)
{
	global $_sess_db;

	//fix as global variables seem to be lost
	//when calling the write function
	if(!is_object($_sess_db))
	{
		if(!_open())
			return false;
	}
	
    $access = time();
 	
    //Check to see if the seession data has allready been created in DB
   	$result=$_sess_db->getData("tbl_sessions",array("session_data"),array("idtbl_sessions={$id}","deleted=0"));
   	//If allready exists, then update, else insert the data
   	//REPLACE done in storeData causes an error due to the session id being used as a key in other DB
   	if($result)
   		$result=$_sess_db->updateData("tbl_sessions",array("session_data","access"),array($data,$access),array("idtbl_sessions={$id}"));
  	else
  		$result=$_sess_db->storeData("tbl_sessions",array("idtbl_sessions","access","session_data"),array($id,$access,$data));
  		
    return $result;
}

function _destroy($id)
{
   global $_sess_db;
 
   $result=$_sess_db->updateData("tbl_sessions",array("deleted"),"1",array("idtbl_sessions={$id}"));	
   
   return $result;
}

function _clean($max)
{
   global $_sess_db;
 	
   $old = time() - $max;
    
   $result=$_sess_db->updateData("tbl_sessions",array("deleted"),"1",array("access<={$old}"),false);	 
   
   return $result;
}


session_set_save_handler('_open',
                         '_close',
                         '_read',
                         '_write',
                         '_destroy',
                         '_clean');
                         
                      
session_start();

//$_SESSION['degrg']='ertert';
  
                   
?>