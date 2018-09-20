<?php
set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path() );
require_once 'htmlpurifier/library/HTMLPurifier.auto.php';

class dbForm extends db {
	var $fields;
	var $table;
	var $purifier;
	var $safeSql;
	
	function dbForm($table = null){
		$this->db();
		
		if(!empty($table))
			$this->setFields($table);
			
		//$purifierconfig = HTMLPurifier_Config::createDefault();
		//$this->purifier = new HTMLPurifier($purifierconfig);
		//$this->safeSql = new SafeSQL_MySQL();
	}
	
	function setFields($table){
		$this->table = $table;
		$this->fields = array();
		
		$query = "show fields from {$this->table}";	
		$_fields = $this->get_results($query, ARRAY_A);
		
		foreach($_fields as $_field){
			$this->fields[$_field["Field"]] = $_field["Type"];
		}
	}

	function add($fields){
		$keys = ""; 
		$values = "";

		while(list($key, $value) = each($fields)){
			$keys .= $key.",";
			$value = str_ireplace("'","&#039;",$value);
			//$value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $value);
			$value = $this->purifier->purify($value);
			$values .=  str_replace("'","",$this->formatField($key, $value)).",,,";
		}		
		
		$keys = substr($keys, 0, strlen($keys) - 1);
		$values = substr($values, 0, strlen($values) - 3);
		$sql = "insert into $this->table($keys) values($values)";
		
		$arr2 = explode(",,,",$values);
		//print_r($arr2);

		$values = "";
		reset($fields);
		
		while(list($key, $value) = each($fields)){
			$values .= "'%s'".",";
		}
		$values = substr($values, 0, strlen($values) - 1);
		
		$sql2 = $this->safeSql->query("insert into $this->table($keys) values($values)", $arr2);
		//echo $sql2;
		if(strlen($values) > 0)
			$this->query($sql2);
	}
	
	function update($id, $fields, $id_field = null){
		if(!is_numeric($id))
			return false;
		
		$table = $this->table;
		$values = "";
		$values2 = "";
		
		if(empty($id_field))
			$id_field = "id_{$table}";
		
		while(list($key, $value) = each($fields)){
			$value = str_ireplace("'","&#039;",$value);
			//$value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $value);
			$value = $this->purifier->purify($value);
			$tmpValue = $this->formatField($key, $value, true);
			if(!is_numeric($tmpValue) && empty($tmpValue))
				continue;
			
			if($value == " ")
				$value = "";
			
			$values .= "$key = '%s', ";
			$values2 .=  str_replace("'","",$this->formatField($key, $value, true)).",,,";
		}
		
		$values = substr($values, 0, strlen($values) - 2);
		$values2 = substr($values2, 0, strlen($values2) - 3);
		$sql = "update $table set $values where $id_field = $id";
		
		$arr2 = explode(",,,",$values2);
		$sql2 = $this->safeSql->query($sql, $arr2);
		
		if(strlen($values)>0)
			$this->query($sql2);
	}			
	
	function postArray($ignoreFields = array(), $i = -1){
		$result = array();					
		reset($this->fields);

		if($i == -1){
			while(list($key, $value) = each($this->fields)){
				if(isset($_POST[$key]))
					$result[$key] = $_POST[$key];
			}			
		} else {
			while(list($key, $value) = each($this->fields)){
				if(isset($_POST[$key]))
					$result[$key] = $_POST[$key][$i];  
			}			
		}
		
		return $result;
	}
	
	function formatField($key, $value, $update = false) {
		$result = null;

		if(!(strpos($this->fields[$key],"int") === false)){
			if(is_numeric($value))
				$result = $value;
			else  {
				$result = "0";
				if($update)
					$result = null;
			}
		} else {
			if(empty($value) && !($value === "0")){
				$result = "''";
				if($update)
					$result = null;
			}
			else 
				$result = "'$value'";
		}

		return $result;
	}	
	
	function getddl($table, $where = ""){		
		return $this->get_results("select id_{$table}, {$table} from {$table} $where order by {$table} asc");
	}
}