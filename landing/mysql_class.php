<?php
//26-09-08 changed MYSQL_ASSOC to MYSQL_BOTH in getData

class MySQL_DB{
	
	
	
	private $DB_Link;
	private $Valid;
	
	function __construct($db_host=null,$db_name=null,$db_user=null,$db_pass=null)
	{
		if(!$db_host){
			$db_host = DB_HOST;
			$db_name = DB_NAME;
			$db_user = DB_USER;
			$db_pass = DB_PASS;
		}
		$this->DB_Link = mysql_connect($db_host, $db_user, $db_pass);
		$this->Valid=mysql_select_db($db_name);
	}
	
	function valid()
	{
		return $this->Valid;
	}
	
	function __destruct ()
	{
		
		//mysql_close($this->DB_Link);
	}
	
	function query($query,$resultType = MYSQL_BOTH,$debug=false){
		$result=mysql_query($query,$this->DB_Link);
		if(!$result){
			if($debug) echo mysql_error();
			return false;
		}
		$data = array();
		if(mysql_num_rows($result)>0){
			while($line= mysql_fetch_array($result,$resultType)){
				$data[]=$line;
			}
		}
		return $data;
	}
    
    function getFirst($query){
        $res = $this->query($query,MYSQL_ASSOC,false);
        if(count($res)){
            return $res[0];
        }
        return array();
    }
	
	function getData($table_name,$fields,$where="",$group_by="",$join="",$order_by="",$debug=false)
	{
		$table_name=mysql_real_escape_string($table_name);
		$join=mysql_real_escape_string($join);
		$group_by=mysql_real_escape_string($group_by);
		
		$query="SELECT ";
		$first=true;
		foreach ($fields as $field)
		{
			$field=mysql_real_escape_string($field);
			if($first)
				$query=$query . $field;
			else
				$query=$query ." , " . $field;
			$first=false;
		}

		$query = $query . " FROM {$table_name}";
		
		if($join!='')
		{
			$query = $query . " " .  $join;
		}
		
		if($where!='')
		{
			$first=true;
			
			foreach ($where as $clause)
			{
				//explode( string $delimiter  , string $string  [, int $limit  ] )
											
				$clause_parts=explode("=",$clause);
				if(sizeof($clause_parts)==2)
				{
					$clause_parts[0]=mysql_real_escape_string($clause_parts[0]);
					$clause_parts[1]=mysql_real_escape_string($clause_parts[1]);
					$full_clause=$clause_parts[0] . "=" . "'" . $clause_parts[1] . "'";
				}
				else
				{
					$clause_parts=explode(">",$clause);
					$clause_parts[0]=mysql_real_escape_string($clause_parts[0]);
					$clause_parts[1]=mysql_real_escape_string($clause_parts[1]);
					$full_clause=$clause_parts[0] . ">" . "'" . $clause_parts[1] . "'";
				}
				
				
				
				//$clause=mysql_real_escape_string($clause);
				
				if($first)
					$query = $query . " WHERE " . $full_clause;
				else
					$query = $query . " AND " . $full_clause;
				$first=false;
			}
		}
		
		if($group_by!='')
		{
			$query = $query . " GROUP BY " . mysql_real_escape_string($group_by);
		}
		
		if($order_by!='')
		{
			$query=$query . " ORDER BY " . mysql_real_escape_string($order_by);
		}
		
		if($debug)
			echo $query . "<br/><br/>";
		$result=mysql_query($query,$this->DB_Link);
		
		if(!$result)
		{
			if($debug)
				echo mysql_error();
			return false;
		}
		else
		{
		
			if(mysql_num_rows($result)>0)
			{
			
				$return_data=array();
				$count=0;
				
				while($line= mysql_fetch_array($result,MYSQL_BOTH))
				{
					$return_data[$count]=$line;
					$count++;
				}
				
				return $return_data;
			}
			else
				return false;
		}
		
		
	}
	
	function storeData($table_name,$fields,$values,$debug=false)
	{
		$table_name=mysql_real_escape_string($table_name);
		
		$query="REPLACE INTO {$table_name} ";
		$first=true;
		foreach ($fields as $field)
		{
			$field=mysql_real_escape_string($field);
			if($first)
				$query=$query . "(" .  $field;
			else
				$query=$query ." , " . $field;
			$first=false;
		}
		
		$query=$query . ") VALUES ";
		
		$first_row=true;
		$first_value=true;
		
		if(is_array($values[0]))
		{
			foreach ($values as $value_row)
			{
				$first_value=true;
				
				if($first_row)
				{
					$query=$query . "(";
					$first_row=false;
				}
				else
				{
					$query=$query . " , (";
				}
					
					$count=0;
					foreach($value_row as $value)
					{
						$value=mysql_real_escape_string($value);
						if($first_value)
							$query=$query . "'" . $value .  "'" ;
						else
							$query=$query . "," . "'" . $value. "'" ;
						
						$first_value=false;
						
						$count++;
						if($count==sizeof($fields))
							break;
					}
					$query=$query . ")";
				
			}
		}
		else
		{
			$query=$query . "(";
			$count=0;
			foreach($values as $value)
			{
				$value=mysql_real_escape_string($value);
				if($first_value)
					$query=$query . "'" . $value .  "'" ;
				else
					$query=$query . "," . "'" . $value. "'" ;
						
				$first_value=false;
						
				$count++;
				if($count==sizeof($fields))
					break;
			}
			$query=$query . ")";
		}
		if($debug)
			echo $query;
		$result=mysql_query($query,$this->DB_Link);
		
		if(!$result)
		{
			if($debug)
				echo mysql_error();
			return false;
		}
		else
			return $result;
	}
	
	function removeData($table_name,$fields,$data,$debug=false)
	{
		$query="DELETE ";
		$first=true;
		
		$query = $query . " FROM {$table_name}";
		
		$count=0;
		foreach ($fields as $field)
		{
			if(isset($data[$count]))
				$value=$data[$count];
			else
				$value=$data[$field];
				
			if($first)
				$query=$query . " WHERE " .  $field ."='" . $value ."'";
			else
				$query=$query ." AND  " . $field . "='" . $value ."'";
			$first=false;
			$count++;
		}
		
 		if($debug)
 			echo $query;
 			
		$result=mysql_query($query,$this->DB_Link);
		
		if(!$result)
		{
			if($debug)
				echo mysql_error();
			return false;
		}
		
		return $result;
	}
	
	function removeDataWhere($table_name,$where,$debug=false)
	{
		$query="DELETE ";
		$first=true;
		
		$query = $query . " FROM {$table_name}";
		
		$count=0;
		
		$first=true;
			
			foreach ($where as $clause)
			{
				//explode( string $delimiter  , string $string  [, int $limit  ] )
											
				$clause_parts=explode("=",$clause);
				if(sizeof($clause_parts)==2)
				{
					$clause_parts[0]=mysql_real_escape_string($clause_parts[0]);
					$clause_parts[1]=mysql_real_escape_string($clause_parts[1]);
					$full_clause=$clause_parts[0] . "=" . "'" . $clause_parts[1] . "'";
				}
				else
				{
					$clause_parts=explode(">",$clause);
					$clause_parts[0]=mysql_real_escape_string($clause_parts[0]);
					$clause_parts[1]=mysql_real_escape_string($clause_parts[1]);
					$full_clause=$clause_parts[0] . ">" . "'" . $clause_parts[1] . "'";
				}
				
				
				
				//$clause=mysql_real_escape_string($clause);
				
				if($first)
					$query = $query . " WHERE " . $full_clause;
				else
					$query = $query . " AND " . $full_clause;
				$first=false;
			}
		
		
		
		if($debug)
 			echo $query;
 			
		$result=mysql_query($query,$this->DB_Link);
		
		return $result;
	}
	
	function updateData($table_name,$fields,$values,$where,$debug=false)
	{
		$table_name=mysql_real_escape_string($table_name);
		$query ="UPDATE {$table_name} SET  ";
		$count=0;
		$first=true;
		
		foreach ($fields as $field)
		{
			
			if($first)
				$query = $query . $field . "=" . "'{$values[$count]}' ";
			else
				$query = $query . "," .  $field . "=" . "'{$values[$count]}' ";
			
			$first=false;
			$count++;
		}
		
		$first=true;
			
		foreach ($where as $clause)
		{
			//explode( string $delimiter  , string $string  [, int $limit  ] )
											
			$clause_parts=explode("=",$clause);
			if(sizeof($clause_parts)==2)
			{
				$clause_parts[0]=mysql_real_escape_string($clause_parts[0]);
				$clause_parts[1]=mysql_real_escape_string($clause_parts[1]);
				$full_clause=$clause_parts[0] . "=" . "'" . $clause_parts[1] . "'";
			}
			else
			{
				$clause_parts=explode(">",$clause);
				$clause_parts[0]=mysql_real_escape_string($clause_parts[0]);
				$clause_parts[1]=mysql_real_escape_string($clause_parts[1]);
				$full_clause=$clause_parts[0] . ">" . "'" . $clause_parts[1] . "'";
			}
				
			//$clause=mysql_real_escape_string($clause);
			
			if($first)
				$query = $query . " WHERE " . $full_clause;
			else
				$query = $query . " AND " . $full_clause;
			$first=false;
		}
			
		if($debug)
 			echo $query;
 			
		$result=mysql_query($query,$this->DB_Link);
		
		return $result;
		
	}
	
	function format_date($date_string,$format='')
	{
		$values=explode("-",$date_string);
		
		$year=$values[0];
		$month=$values[1];
		$day=$values[2];
		
		$formatted_date=$day . "/" . $month . "/" . $year;
		
		return $formatted_date;
	}
	
}


?>