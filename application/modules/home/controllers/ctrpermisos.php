<?php

class ctrPermisos extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		date_default_timezone_set('America/Mexico_City');
	}

	public function view_all_users(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_admin_users"))){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("permisos/vwusers",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("permisos/vwsidebar",$data,TRUE);
		}else redirect(base_url());
	}

	public function eliminar_user(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->edit_data(array("date_end"=>date('Y-m-d H:i:s')),"usuarios_boletas",$id,"id");
		print json_encode(array("success"=>TRUE));
	}

	public function consultar_materias(){
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$materia=$this->security->xss_clean($this->input->post("materia"));
		$query="SELECT ppal.id, ppal.grado, ppal.nombre FROM materias AS ppal WHERE ppal.active=1";
		if(strlen($grado)>0) $query.=" AND ppal.grado LIKE '%$grado%'";
		if(strlen($materia)>0) $query.=" AND ppal.nombre LIKE '%$materia%'";
		$query.=" ORDER BY ppal.nombre, ppal.grado";
		$data=$this->get_data_from_query($query);
		if($data!==FALSE) print json_encode(array("success"=>TRUE,"result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function view_edit_user($id){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_admin_users"))){
			$data["controller"]=$this;
			$data["id"]=$id;
			$data["home_controller"]=Modules::run("home/ctrhome/get_self");
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("permisos/vwedit_user",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("permisos/vwsidebar_add",$data,TRUE);
		}else redirect(base_url());
	}

	public function view_add_user(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_admin_users"))){
			$data["controller"]=$this;
			$data["home_controller"]=Modules::run("home/ctrhome/get_self");
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("permisos/vwadd_user",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("permisos/vwsidebar_add",$data,TRUE);
		}else redirect(base_url());
	}

	public function save_permisos_per_user(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$materias=$this->security->xss_clean($this->input->post("materias"));
		$main_id=$this->security->xss_clean($this->input->post("main_id"));
		$main_data=array();
		foreach ($data as $e=>$key){
			if($key[1]=="pwd"){
				if(strlen($key[0])>0) $main_data[$key[1]]=hash('sha512',$key[0]);
			}else $main_data[$key[1]]=$key[0];
		}
		$main_data["date_start"]=date('Y-m-d H:i:s');
		if(intval($main_id)>0){
			$id=$main_id;
			$this->edit_data($main_data,"usuarios_boletas",$main_id,"id");
			$this->delete_data("permisos_boletas",$id,"idUsuario");
		} else $id=$this->insert_data($main_data,"usuarios_boletas");
		if(intval($id)>0){
			foreach ($materias as $e=>$key){
				$this->insert_data(array("idUsuario"=>$id,"grado"=>$key[1],"idMateria"=>$key[0]),"permisos_boletas");
			}
			print json_encode(array("success"=>TRUE));
		}else print json_encode(array("success"=>FALSE));
	}

	public function get_self(){return $this;}
	public function insert_data($data,$table){
		return $this->mdllogin->insertData($data,$table);
	}
	public function get_data($select="",$from="",$where="",$order="",$group="",$limit=""){
		return $this->mdllogin->getData($select,$from,$where,$order,$group,$limit);
	}
	public function get_data_from_query($query){
		return $this->mdllogin->getDataFromQuery($query);
	}
	public function edit_data($data,$table,$id,$idName,$where=";"){
		return $this->mdllogin->editData($data,$table,$id,$idName,$where);
	}
	public function delete_data($table,$id,$idName){
		return $this->mdllogin->deleteData($table,$id,$idName);
	}
	public function delete_data_multiple_where($table,$where){
		return $this->mdllogin->deleteData_multipleWhere($table,$where);
	}
	public function check_permisos($permiso){
		$temp=$this->get_data($permiso,"usuarios_boletas","id='".$this->session->userdata("id")."'");
		return $temp[0][$permiso];
	}
	private function roundDown($decimal, $precision){
        $fraction = substr($decimal - floor($decimal), 2, $precision);
        $newDecimal = floor($decimal). '.' .$fraction;
        return floatval($newDecimal);
    }

}

?>