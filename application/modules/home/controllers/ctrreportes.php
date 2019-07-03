<?php

class ctrReportes extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		//$this->load->model('login/mdllogin1718');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		date_default_timezone_set('America/Mexico_City');
	}

	public function create_pdf_from_boleta(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$curl = curl_init();

		$id_temp=$id;
		if(USE_HASH) $id_temp=hash('sha256',$id);

		curl_setopt_array($curl, array(
		    CURLOPT_URL => "https://api.pdfshift.io/v2/convert/",
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POST => false,
		    CURLOPT_POSTFIELDS => json_encode(array("source" => URL_PDF_SOURCE.$id_temp."?hide=1", "landscape" => false, "use_print" => false, "timeout"=>15)),
		    CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
		    CURLOPT_USERPWD => CURLOPT_USERPWD_
		));

		$response = curl_exec($curl);
		file_put_contents(FILE_ROUTE_FULL.'files/Boleta_'.$id.'.pdf', $response);
		print json_encode(array("success"=>TRUE,"ruta"=>'Boleta_'.$id.'.pdf'));
	}

	public function view_agregar_reporte_diario(){
		if($this->session->userdata("id")){
			$data["controller"]=$this;
			$this->check_idioma();
			$sufix=$this->get_data("UPPER(LEFT(grado,3)) AS sufix","permisos_boletas","idUsuario='".$this->session->userdata('id')."'","","","1");
			if($sufix!==FALSE){$sufix=$sufix[0];$sufix=$sufix["sufix"];}else $sufix="PRI";
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("reportes/vwreporte_diario_add".$sufix,$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("reportes/vwsidebar".$sufix,$data,TRUE);
		}else redirect(base_url());
	}

	public function view_ver_reporte_diario(){
		if($this->session->userdata("id")){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("reportes/vwver_reportes_diarios",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("reportes/vwsidebar",$data,TRUE);
		}else redirect(base_url());
	}

	public function view_consultar_planeacion($id){
		if($this->session->userdata("id")){
			$data["controller"]=$this;
			$data["id"]=$id;
			$data_plan=$this->get_data("UPPER(LEFT(grado,3)) AS grado","planeaciones","id='$id'");
			if($data_plan!==FALSE){
				$data_plan=$data_plan[0];
				$data_plan=$data_plan["grado"];
				print $this->load->view("vwheader",$data,TRUE);
				print $this->load->view("vwaside",$data,TRUE);
				print $this->load->view("reportes/vwconsultar_planeacion".$data_plan,$data,TRUE);
				print $this->load->view("vwfooter",$data,TRUE);
				print $this->load->view("reportes/vwsidebar",$data,TRUE);
			}else redirect(base_url());
		}else redirect(base_url());
	}

	private function getInicials($string){
		$special_chars_table = array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'R'=>'R', 'r'=>'r', "'"=>'', " "=>""
		);
		$string=strtr($string, $special_chars_table);
		$words=explode(" ", $string);
		$acronym="";
		foreach($words AS $w){
			if(strlen($w)>0) $acronym.= $w[0];
		}
		if($acronym==="") $acronym=$string;
		return $string;
	}

	public function get_class_from_checkboxes($val,$type){
		switch($type){
			case 1:
				if($val===1 || $val===3 || $val===5 || $val===7) return "check";
				else return "close";
			break;
			case 2:
				if($val===2 || $val===3 || $val===6 || $val===7) return "check";
				else return "close";
			break;
			case 4:
				if($val===4 || $val===5 || $val===6 || $val===7) return "check";
				else return "close";
			break;
		}
	}

	public function check_permisos($permiso){
		$temp=$this->get_data($permiso,"usuarios_boletas","id='".$this->session->userdata("id")."'");
		return $temp[0][$permiso];
	}

	public function check_idioma(){
		$temp=$this->get_data("id","permisos_boletas","idUsuario='".$this->session->userdata('id')."' AND idMateria IN (SELECT id FROM materias WHERE idBloque=2)");
		if($temp!==FALSE) $this->lang->load('EN','EN');
		else $this->lang->load('ES','ES');
	}

	public function get_self(){return $this;}
	public function insert_data($data,$table){
		if(intval($this->session->userdata('is_ciclo1718')))
			return $this->mdllogin1718->insertData($data,$table);
		return $this->mdllogin->insertData($data,$table);
	}
	public function get_data($select="",$from="",$where="",$order="",$group="",$limit=""){
		if(intval($this->session->userdata('is_ciclo1718')))
			return $this->mdllogin1718->getData($select,$from,$where,$order,$group,$limit);
		return $this->mdllogin->getData($select,$from,$where,$order,$group,$limit);
	}
	public function get_data_from_query($query){
		if(intval($this->session->userdata('is_ciclo1718')))
			return $this->mdllogin1718->getDataFromQuery($query);
		return $this->mdllogin->getDataFromQuery($query);
	}
	public function get_data_school($select="",$from="",$where="",$order="",$group="",$limit=""){return $this->mdllogin->getData_School($select,$from,$where,$order,$group,$limit);}

	public function closesession(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

}

?>