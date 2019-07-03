<?php

class MdlLogin1718 extends CI_Model{
	
	function __construct(){
		parent::__construct();
		//$this->load->library('exceptions');
		$this->ciclo1718 = $this->load->database('ciclo1718', TRUE);
	}

	public function check_login($username,$pwd){
		$pwd=hash('sha512',$pwd);
		$result=$this->getDataFromQuery("SELECT * from usuarios_boletas where username='$username' AND pwd='$pwd' AND date_end IS NULL ;");
		if($result!==FALSE){
			return $result;
		}else return FALSE;
	}

	public function check_login_by_id($id){
		$result=$this->ciclo1718->query("SELECT * from usuarios_boletas where id='$id';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function check_email_existence($dato){
		$result=$this->ciclo1718->query("SELECT id from usuarios_boletas where numero='$dato';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getData($select,$from,$where,$order,$group,$limit){
		$query="SELECT $select FROM $from ";
		if($where!=="") $query.="WHERE $where ";
		if($group!=="") $query.="GROUP BY $group ";
		if($order!=="") $query.="ORDER BY $order ";
		if($limit!=="") $query.="LIMIT $limit ";
		$result=$this->ciclo1718->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getData_School($select,$from,$where,$order,$group,$limit){
		$query="SELECT $select FROM $from ";
		if($where!=="") $query.="WHERE $where ";
		if($group!=="") $query.="GROUP BY $group ";
		if($order!=="") $query.="ORDER BY $order ";
		if($limit!=="") $query.="LIMIT $limit ";
		$result=$this->school->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getDataFromQuery($query){
		$result=$this->ciclo1718->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getDataFromQuery_School($query){
		$result=$this->school->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function insertDataBatch($datos,$tabla){
		$this->ciclo1718->insert_batch($tabla,$datos);
		return $this->ciclo1718->insert_id()>0 ? $this->ciclo1718->insert_id() : FALSE;
	}

	public function insertData($datos,$tabla){
		$this->ciclo1718->insert($tabla,$datos);
		//$this->exceptions->checkForError();
		return $this->ciclo1718->insert_id()>0 ? $this->ciclo1718->insert_id() : FALSE;
	}

	public function editData($data,$table,$id,$idName,$where=";"){
		$consulta="UPDATE $table ";
		$set=FALSE;
		foreach($data AS $e=>$key){
			if($key!==FALSE && $key!=="false"){
				if(!$set){
					$consulta.=" SET ";
					$set=TRUE;
				}else{
					$consulta.=" , ";
				}
				if($key=="NULL") $consulta.="`$e` = NULL ";
				else $consulta.="`$e` = '$key' ";
			}
		}
		if($id!==FALSE) $consulta.=" WHERE $idName = $id ".$where;
		else $consulta.=$where;
		$resultado=$this->ciclo1718->query($consulta);
		if(strpos((string)$this->ciclo1718->conn_id->info,"Rows matched: 0")===FALSE)
			return TRUE;
		else
			return FALSE;
	}

	public function deleteData_multipleWhere($table,$where){
		foreach($where AS $e => $key){
			$this->ciclo1718->where("$e",$key);
		}
		$this->ciclo1718->delete($table);
		return $this->ciclo1718->affected_rows()>0 ? TRUE : FALSE;
	}

	public function deleteData($table,$id,$idName){
		$this->ciclo1718->where("$idName",$id);
		$this->ciclo1718->delete($table);
		return $this->ciclo1718->affected_rows()>0 ? TRUE : FALSE;
	}

	public function deleteDataIfExists($table,$id,$idName){
		$existence=$this->ciclo1718->query("SELECT 1 FROM $table WHERE $idName = '$id' LIMIT 1;");
		if($existence->num_rows()>0){
			$this->ciclo1718->where($idName,$id);
			$this->ciclo1718->delete($table);
			return $this->ciclo1718->affected_rows()>0 ? TRUE : FALSE;
		} else return TRUE;
	}

}

?>