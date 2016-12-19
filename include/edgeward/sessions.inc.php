<?php
class SessionManager {

   var $life_time;

   function SessionManager() {

      // Read the maxlifetime setting from PHP
      $this->life_time = get_cfg_var("session.gc_maxlifetime");

      // Register this object as the session handler
      session_set_save_handler( 
        array( &$this, "open" ), 
        array( &$this, "close" ),
        array( &$this, "read" ),
        array( &$this, "write"),
        array( &$this, "destroy"),
        array( &$this, "gc" )
      );

   }

   function open( $save_path, $session_name ) {

      global $sess_save_path;

      $sess_save_path = $save_path;

      // Don't need to do anything. Just return TRUE.

      return true;

   }

   function close() {

      return true;

   }

   function read( $id ) {

      // Set empty result
      $data = '';

      // Fetch session data from the selected database
	  
	  $expires = time() - $this->life_time;
		openConn(); 
      $newid = addslashes($id);
      $sql = "SELECT `session_data` FROM `tbl_sessions` WHERE `idtbl_sessions` = '$newid' AND `access` > $expires AND `deleted` = 0";

      
	  $rs = sqlQuery($sql);
	  mysql_close();
      $a = count($rs);

      if($a > 0) {
		  $data = $rs[0]['session_data'];
        //$row = db_fetch_assoc($rs);
        //$data = $row['session_data'];

      }

      return $data;

   }

   function write( $id, $data ) {

		// Build query                
		$time = time();
		openConn();
		$newid = addslashes($id);
		$newdata = addslashes($data);
		
		$sql = "REPLACE `tbl_sessions` (`idtbl_sessions`,`session_data`,`access`) VALUES('$newid','$newdata', $time)";
		
		mysql_query($sql);
		mysql_close();
		return TRUE;

   }

   function destroy( $id ) {

		// Build query
		openConn();
		$newid = addslashes($id);
		$sql = "UPDATE `tbl_sessions` SET `deleted` = 1 WHERE `idtbl_sessions` = '$newid'";
		
		mysql_query($sql);
		mysql_close();
		return TRUE;

   }

   function gc() {

		// Garbage Collection
		
		$expires = time() - $this->life_time;
				   
		
		// Build DELETE query.  Delete all records who have passed the expiration time
		$sql = "UPDATE `tbl_sessions` SET `deleted` = 1 WHERE `access` < $expires";
		
		mysql_query($sql);
		mysql_close();
		// Always return TRUE
		return true;

   }

}
?>