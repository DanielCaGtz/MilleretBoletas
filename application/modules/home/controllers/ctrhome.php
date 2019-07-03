<?php

class ctrHome extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		//$this->load->model('login/mdllogin1718');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		date_default_timezone_set('America/Mexico_City');
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/IOFactory.php";
		require FILE_ROUTE_FULL.'addons/mailer/PHPMailerAutoload.php';
	}

	public function get_module($module){
		if(constant($module)){
			return Modules::run("home/ctrmodules/"."get_module_".$module);
		}
	}

	public function publish_boleta($name="Publicar boletas"){
		$mail = new PHPMailer;
		$body="<h3>EL USUARIO <b>".$this->session->userdata("nombre")."</b> HA SOLICITADO PUBLICAR BOLETAS</h3>";
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = "mx1.hostinger.mx";
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = "noreply@softandgo.com";
		$mail->Password = "6FysjDIS8LxvuU";
		$mail->setFrom('noreply@softandgo.com', 'Administrador de Milleret Boletas');
		#$mail->addReplyTo('daniel@softandgo.com', 'Administrador de Sitio');
		$mail->AddAddress("lflores@integralle.com.mx", "Luis Flores");
		$mail->AddCC("dani.daniel_@hotmail.com", "Daniel C");
		$mail->Subject = 'Solicitud para publicar boletas';
		$mail->Body=$body;
		$mail->AltBody = $body;
		$mail->IsHTML(true);
		#$mail->addAttachment($attach1);
		//send the message, check for errors
		#if (!$mail->send()) echo "Mailer Error: " . $mail->ErrorInfo; else echo "Message sent!";
		$mail->send();
		print json_encode(array("success"=>TRUE));
	}

	public function index(){
		if($this->session->userdata("id")){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwhome",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar",$data,TRUE);
		}else redirect(base_url());
	}

	public function closesession(){
		$this->session->sess_destroy();
		redirect(base_url());
	}

	private function insert_calificaciones_cualidades_eval($idCalificacion,$data,$idBoleta,$idMateria,$idAlumno,$grado,$bim,$idUsuarioAlta){
		$this->delete_data_multiple_where("evaluacion_continua",array("idAlumno"=>$idAlumno,"bimestre"=>$bim,"idMateria"=>$idMateria));
		$cualidades=explode(",",$data);
		foreach($cualidades AS $c => $cualidad){
			$temp=explode("@", $cualidad);
			if(floatval($temp[1])>0){
				$temp_insert=array(
					"idMateria"			=> $idMateria,
					"idAlumno"			=> $idAlumno,
					"bimestre"			=> $bim,
					"tipo"				=> $temp[0],
					"calificacion"		=> floatval($temp[1])
				);
				$id_temp=$this->insert_data($temp_insert,"evaluacion_continua");
			}
		}
	}

	private function insert_calificaciones_cualidades($idCalificacion,$data,$idBoleta,$idMateria,$idAlumno,$grado,$bim,$idUsuarioAlta){
		$this->delete_data("calificaciones_cualidades",$idCalificacion,"idCalificacion");
		$cualidades=explode(",",$data);
		foreach($cualidades AS $c => $cualidad){
			$temp=explode("@", $cualidad);
			if(intval($temp[1])>0){
				$temp_insert=array(
					"idBoleta"			=> $idBoleta,
					"idCalificacion"	=> $idCalificacion,
					"idMateria"			=> $idMateria,
					"idAlumno"			=> $idAlumno,
					"idCualidad"		=> $temp[0],
					"grado"				=> $grado,
					"porcentaje"		=> $temp[1],
					"bimestre"			=> $bim,
					"idUsuarioAlta"		=> $idUsuarioAlta,
					"date_start"		=> date('Y-m-d H:i:s')
				);
				$id_temp=$this->insert_data($temp_insert,"calificaciones_cualidades");
			}
		}
	}

	private function insert_calificaciones_subcualidades($idCalificacion,$data,$idBoleta,$idMateria,$idAlumno,$grado,$bim,$idUsuarioAlta){
		$this->delete_data("calificaciones_cualidades_sub",$idCalificacion,"idCalificacion");
		$cualidades=explode(",",$data);
		foreach($cualidades AS $c => $cualidad){
			$temp=explode("@", $cualidad);
			if(intval($temp[1])>0){
				$temp_insert=array(
					"idBoleta"			=> $idBoleta,
					"idCalificacion"	=> $idCalificacion,
					"idMateria"			=> $idMateria,
					"idAlumno"			=> $idAlumno,
					"idCualidadSub"		=> $temp[0],
					"grado"				=> $grado,
					"porcentaje"		=> $temp[1],
					"bimestre"			=> $bim,
					"idUsuarioAlta"		=> $idUsuarioAlta,
					"date_start"		=> date('Y-m-d H:i:s')
				);
				$query=$this->get_data_from_query("SELECT id FROM calificaciones_cualidades_sub WHERE idBoleta='$idBoleta' AND idCalificacion='$idCalificacion' AND idMateria='$idMateria' AND bimestre='$bim' AND idCualidadSub='".$temp[0]."'");
				if($query!==FALSE) $this->edit_data($temp_insert,"calificaciones_cualidades_sub",$query[0]["id"],"id");
				else $id_temp=$this->insert_data($temp_insert,"calificaciones_cualidades_sub");
			}
		}
	}

	public function save_calificaciones(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$idAlumno=$this->security->xss_clean($this->input->post("idAlumno"));
		$idBoleta=$this->security->xss_clean($this->input->post("idBoleta"));
		$grado=$this->security->xss_clean($this->input->post("grado"));
		//*
		$s=TRUE;
		$ids=array();
		foreach($data AS $e => $key){

			$has_calif=intval($key[0])>0;
			$has_nivel=intval($key[1])>0;
			$id_calif=intval($key[2]);
			$id_mat=intval($key[3]);
			$id_bim=intval($key[4]);

			if($id_calif>0){
				if($has_calif){
					$val_temp=5;
					$aspectos_temp=6;
					$subaspec_temp=7;
					$temp=array(
						"calificacion"		=> is_numeric($key[$val_temp]) ? (strlen($key[$val_temp])>0 ? $key[$val_temp] : "NULL") : "NULL",
						"calificacion_letra"=> is_numeric($key[$val_temp]) ? "NULL" : $key[$val_temp],
						"idUsuarioAlta"		=> $this->session->userdata("id"),
						"date_start"		=> date('Y-m-d H:i:s')
					);
					$this->edit_data($temp,"calificaciones",$id_calif,"id");
					$ids[$e]=$id_calif;
					if(isset($key[$aspectos_temp]) && $key[$aspectos_temp]!="") $this->insert_calificaciones_cualidades($id_calif, $key[$aspectos_temp], $idBoleta, $id_mat, $idAlumno, $grado, $id_bim, $this->session->userdata("id"));
					if(isset($key[$subaspec_temp]) && $key[$subaspec_temp]!="") $this->insert_calificaciones_subcualidades($id_calif, $key[$subaspec_temp], $idBoleta, $id_mat, $idAlumno, $grado, $id_bim, $this->session->userdata("id"));
				}

				if($has_nivel){
					$val_temp=5;
					if($has_calif) $val_temp+=3;
					$temp=array(
						"nivel" 			=> $key[$val_temp],
						"idUsuarioAlta"		=> $this->session->userdata("id"),
						"date_start"		=> date('Y-m-d H:i:s')
					);
					$this->edit_data($temp,"calificaciones",$id_calif,"id");
					$ids[$e]=$id_calif;
				}
			}else{
				$id_temp=0;
				if($has_calif){
					$val_temp=5;
					$aspectos_temp=6;
					$subaspec_temp=7;
					$temp=array(
						"idBoleta"			=> $idBoleta,
						"idMateria"			=> $id_mat,
						"idAlumno"			=> $idAlumno,
						"grado"				=> $grado,
						"calificacion"		=> is_numeric($key[$val_temp]) ? (strlen($key[$val_temp])>0 ? $key[$val_temp] : NULL) : NULL,
						"calificacion_letra"=> is_numeric($key[$val_temp]) ? NULL : $key[$val_temp],
						"bimestre"			=> $id_bim,
						"idUsuarioAlta"		=> $this->session->userdata("id"),
						"date_start"		=> date('Y-m-d H:i:s')
					);
					$id_temp=$this->insert_data($temp,"calificaciones");
					if($id_temp===FALSE) $s=FALSE;
					$ids[$e]=intval($id_temp);
					if(isset($key[$aspectos_temp]) && $key[$aspectos_temp]!="") $this->insert_calificaciones_cualidades(intval($id_temp), $key[$aspectos_temp], $idBoleta, $id_mat, $idAlumno, $grado, $id_bim, $this->session->userdata("id"));
					if(isset($key[$subaspec_temp]) && $key[$subaspec_temp]!="") $this->insert_calificaciones_subcualidades(intval($id_temp), $key[$subaspec_temp], $idBoleta, $id_mat, $idAlumno, $grado, $id_bim, $this->session->userdata("id"));
				}

				if($has_nivel){
					$val_temp=5;
					$temp=array(
						"nivel" 			=> $key[$val_temp],
						"idUsuarioAlta"		=> $this->session->userdata("id"),
						"date_start"		=> date('Y-m-d H:i:s')
					);
					if($has_calif && intval($id_temp)>0){
						$val_temp+=3;
						$this->edit_data($temp,"calificaciones",intval($id_temp),"id");
						$ids[$e]=$id_calif;
					}else{
						array_merge($temp, array(
							"idBoleta"			=> $idBoleta,
							"idMateria"			=> $id_mat,
							"idAlumno"			=> $idAlumno,
							"grado"				=> $grado,
							"bimestre"			=> $id_bim,
						));
						$id_temp=$this->insert_data($temp,"calificaciones");
						if($id_temp===FALSE) $s=FALSE;
						$ids[$e]=intval($id_temp);
					}
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
		/* */
	}

	public function consulta_cualidades_por_materia(){
		$id=$this->security->xss_clean($this->input->post("idMateria"));
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$data=$this->get_data("*","cualidades","idMateria='$id' AND bimestre='$bim'","orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consulta_cualidades_por_materia_con_subcualidades(){
		$id=$this->security->xss_clean($this->input->post("idMateria"));
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$data=$this->get_data("*","cualidades","idMateria='$id' AND bimestre='$bim'","orden");
		if($data!==FALSE){
			foreach ($data as $e=>$key){
				$data_sub=$this->get_data("*","cualidades_sub","idCualidad='".$key["id"]."'","orden");
				if($data_sub!==FALSE) $data[$e]["childs"]= $data_sub;
				else $data[$e]["childs"]=array();
			}
			print json_encode(array("success"=>TRUE, "result"=>$data));
		}else print json_encode(array("success"=>FALSE));
	}

	public function consulta_subcualidades_por_nombre_cualidad() {
		$id=$this->security->xss_clean($this->input->post("idCualidad"));
		$idMat=$this->security->xss_clean($this->input->post("idMateria"));
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$cualidadNombre = $this->get_data("nombre", "cualidades", "id='$id'");
		$cualidadNombre = $cualidadNombre !== FALSE ? $cualidadNombre[0]["nombre"] : "";
		$data=$this->get_data("*","cualidades_sub","idCualidad=(SELECT id FROM cualidades WHERE nombre='$cualidadNombre' AND bimestre='$bim' AND idMateria='$idMat' LIMIT 1) AND idMateria='$idMat' AND bimestre='$bim'","orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consulta_subcualidades_por_cualidad(){
		$id=$this->security->xss_clean($this->input->post("idCualidad"));
		$idMat=$this->security->xss_clean($this->input->post("idMateria"));
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$data=$this->get_data("*","cualidades_sub","idCualidad='$id' AND idMateria='$idMat' AND bimestre='$bim'","orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consulta_materias_por_grado(){
		$id=$this->security->xss_clean($this->input->post("idBloque"));
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$data=$this->get_data("*","materias","idBloque='$id' AND grado='$grado' AND active='1'","idBloque, orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consult_materias_to_delete(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("1","calificaciones","idMateria='$id'");
		if($data!==FALSE) print json_encode(array("result"=>FALSE));
		else print json_encode(array("result"=>TRUE));
	}

	public function delete_materia(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->delete_data("calificaciones_cualidades",$id,"idMateria");
		$this->delete_data("calificaciones",$id,"idMateria");
		$this->delete_data("cualidades",$id,"idMateria");
		$this->delete_data("materias",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function consult_cualidad_to_delete(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("1","calificaciones_cualidades","idCualidad='$id'");
		if($data!==FALSE) print json_encode(array("result"=>FALSE));
		else print json_encode(array("result"=>TRUE));
	}

	public function delete_cualidad(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->delete_data("calificaciones_cualidades",$id,"idCualidad");
		$this->delete_data("cualidades",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function save_materias(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$id=$this->security->xss_clean($this->input->post("idBloque"));
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$is_complex=$this->security->xss_clean($this->input->post("is_complex"));
		$s=TRUE;
		$ids=array();
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$temp=array(
						"nombre"		=> $key[0],
						"is_complex"	=> $is_complex,
						"orden"			=> ($e+1),
						"has_calif" 	=> $key[2],
						"has_nivel" 	=> $key[3]
					);
					$this->edit_data($temp,"materias",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$temp=array(
						"idBloque"		=> $id,
						"grado"			=> $grado,
						"nombre"		=> $key[0],
						"active"		=> 1,
						"is_complex"	=> $is_complex,
						"orden"			=> ($e+1),
						"has_calif" 	=> $key[2],
						"has_nivel" 	=> $key[3]
					);
					$id_temp=$this->insert_data($temp,"materias");
					if($id_temp === FALSE) $id_temp=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function save_subcualidades(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$s=TRUE;
		$ids=array();
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$temp=array(
						"nombre"		=> $key[0],
						"orden"			=> ($e+1),
						"idUsuarioAlta"	=> $this->session->userdata("id"),
						"date_start"	=> date('Y-m-d H:i:s')
					);
					$temp["valor"] = $key[2];
					$this->edit_data($temp,"cualidades_sub",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$id=$this->security->xss_clean($this->input->post("idMateria"));
					$idCualidad=$this->security->xss_clean($this->input->post("idCualidad"));
					$grado=$this->security->xss_clean($this->input->post("grado"));
					$bim=$this->security->xss_clean($this->input->post("bim"));
					$temp=array(
						"idMateria"		=> $id,
						"idCualidad"	=> $idCualidad,
						"grado"			=> $grado,
						"bimestre"		=> $bim,
						"nombre"		=> $key[0],
						"orden"			=> ($e+1),
						"idUsuarioAlta"	=> $this->session->userdata("id"),
						"date_start"	=> date('Y-m-d H:i:s')
					);
					$temp["valor"] = $key[2];
					$id_temp=$this->insert_data($temp,"cualidades_sub");
					if($id_temp === FALSE) $id_temp=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function save_cualidades(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$s=TRUE;
		$ids=array();
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$temp=array(
						"nombre"		=> $key[0],
						"orden"			=> ($e+1),
						"idUsuarioAlta"	=> $this->session->userdata("id"),
						"date_start"	=> date('Y-m-d H:i:s')
					);
					$temp["valor"] = $key[2];
					$this->edit_data($temp,"cualidades",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$id=$this->security->xss_clean($this->input->post("idMateria"));
					$grado=$this->security->xss_clean($this->input->post("grado"));
					$bim=$this->security->xss_clean($this->input->post("bim"));
					$temp=array(
						"idMateria"		=> $id,
						"grado"			=> $grado,
						"bimestre"		=> $bim,
						"nombre"		=> $key[0],
						"orden"			=> ($e+1),
						"idUsuarioAlta"	=> $this->session->userdata("id"),
						"date_start"	=> date('Y-m-d H:i:s')
					);
					$temp["valor"] = $key[2];
					$id_temp=$this->insert_data($temp,"cualidades");
					if($id_temp === FALSE) $id_temp=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function consulta_boleta($id){
		//if($this->session->userdata("id") && intval($this->check_permisos("allow_view"))){
			$data["controller"]=$this;
			$data["idAlumno"]=$id;
			$data_alumno=$this->get_data("grado,grupo","alumnos","SHA2(id,256)='$id'");
			if($data_alumno!==FALSE){
				$data_alumno=$data_alumno[0];
				$grado=substr($data_alumno["grado"],0,3);
				// if($grado=="SEC") print $this->load->view("vwboletaSEC",$data,TRUE);
				print $this->load->view("vwboleta",$data,TRUE);
			}else redirect(base_url());
		//}else redirect(base_url());
	}

	public function consulta_logros($id){
		#if($this->session->userdata("id") && intval($this->check_permisos("allow_view"))){
			$data["controller"]=$this;
			$data["idCalificacion"]=$id;
			print $this->load->view("vwboleta_detalle",$data,TRUE);
		#}else redirect(base_url());
	}

	public function editar_boleta($id){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_add"))){
			$data["controller"]=$this;
			$data["idAlumno"]=$id;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwagregar_detalle",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_agregar",$data,TRUE);
		}else redirect(base_url());
	}

	public function consulta_de_boletas(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_view"))){
			$data["controller"]=$this;
			$data["filesJS"]=array(
				#1=>"js/bootstrap.js",
				12=>"plugins/slimScroll/jquery.slimscroll.min.js",
				13=>"plugins/fastclick/fastclick.js",
				14=>"js/app.js",
				16=>"js/demo.js"
			);
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwconsulta",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_consulta",$data,TRUE);
		}else redirect(base_url());
	}

	public function registro_de_boletas(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_add"))){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwagregar",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_agregar",$data,TRUE);
		}else redirect(base_url());
	}

	public function edicion_de_boletas(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_edit"))){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwcualidades",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_cualidades",$data,TRUE);
		}else redirect(base_url());
	}

	public function edicion_de_sub_aspectos(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_edit"))){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwsubcualidades",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_subcualidades",$data,TRUE);
		}else redirect(base_url());
	}

	public function edicion_de_materias(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_materias"))){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwmaterias",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_materias",$data,TRUE);
		}else redirect(base_url());
	}

	public function view_agregar_esfuerzo(){
		if($this->session->userdata("id") && intval($this->check_permisos("allow_admin"))){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("reportes/vwdetalles",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("reportes/vwsidebar_detalles",$data,TRUE);
		}else redirect(base_url());
	}

	public function consulta_detalles_reporte(){
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$grupo=$this->security->xss_clean($this->input->post("grupo"));
		$query="SELECT * FROM reportes_detalles WHERE bimestre='$bim' AND grado='$grado' AND grupo='$grupo'; ";
		$data_main=$this->get_data_from_query($query);
		if($data_main!==FALSE){
			$data_main=$data_main[0];
			print json_encode(array("success"=>TRUE, "result"=>$data_main));
		}else print json_encode(array("success"=>FALSE));
	}

	public function save_detalles_reporte(){
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$grupo=$this->security->xss_clean($this->input->post("grupo"));
		$query="SELECT * FROM reportes_detalles WHERE bimestre='$bim' AND grado='$grado' AND grupo='$grupo'; ";
		$data_main=$this->get_data_from_query($query);
		if($data_main!==FALSE){
			$data_main=$data_main[0];
			$this->edit_data(array(
				"idAlumno" 	=> $this->security->xss_clean($this->input->post("idAlumno")),
				"dirTec" 	=> $this->security->xss_clean($this->input->post("dirTec")),
				"prof" 		=> $this->security->xss_clean($this->input->post("prof")),
				"teacher" 	=> $this->security->xss_clean($this->input->post("teacher"))
			),"reportes_detalles",$data_main["id"],"id");
			print json_encode(array("success"=>TRUE));
		}else{
			$insert_id=$this->insert_data(array(
				"bimestre" 	=> $bim,
				"grado" 	=> $grado,
				"grupo" 	=> $grupo,
				"idAlumno" 	=> $this->security->xss_clean($this->input->post("idAlumno")),
				"dirTec" 	=> $this->security->xss_clean($this->input->post("dirTec")),
				"prof" 		=> $this->security->xss_clean($this->input->post("prof")),
				"teacher" 	=> $this->security->xss_clean($this->input->post("teacher"))
			),"reportes_detalles");
			if(intval($insert_id)>0) print json_encode(array("success"=>TRUE));
			else print json_encode(array("success"=>FALSE));
		}
	}

	public function get_reporte_full(){
		$permisos=$this->get_data_from_query("SELECT DISTINCT grado FROM permisos_boletas WHERE idUsuario='".$this->session->userdata("id")."' AND idMateria>0 GROUP BY grado");
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$grupo=$this->security->xss_clean($this->input->post("grupo"));
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/reporte_full.xlsx");
		$name="Reporte_Completo_".$grado."-".date("Ymd_His").".xlsx";
		$objPHPExcel->setActiveSheetIndex(0);
		
		$if_no_kinder=substr($grado,0,4)!="KIND" || $grado=="KINDER-PRE";
		$if_is_sec=substr($grado,0,4)=="SECU";
		$start_alumnos=5;
		
		$limit="";
		$whats_to_select="id, idBloque, nombre, is_complex, has_calif, has_nivel";
		if($permisos!==FALSE || intval($this->check_permisos("allow_admin"))===0) $query="SELECT $whats_to_select FROM materias WHERE active=1 AND idBloque IN (SELECT id FROM bloques WHERE is_part_avr=1) AND grado='$grado' AND (has_calif=1 OR has_nivel=1) AND id IN (SELECT idMateria FROM permisos_boletas WHERE idUsuario='".$this->session->userdata("id")."') ORDER BY idBloque, orden ";
		else $query="SELECT $whats_to_select FROM materias WHERE active=1 AND idBloque IN (SELECT id FROM bloques WHERE is_part_avr=1) AND grado='$grado' AND (has_calif=1 OR has_nivel=1) ORDER BY idBloque, orden ";

		$data_materias=$this->get_data_from_query($query);
		
		$query=$this->get_query_construct($permisos,3,$grado,$grupo,$limit);
		$data_alumnos=$this->get_data_from_query($query);
		$s='D';
		$ss='D';
		$style_center = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
		$num_promedio_total=0;
		$promedios_columns=array();
		$promedios_spanish=array();
		$promedios_english=array();
		$s_a=0;
		if($data_materias!==FALSE){
			$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(-1);
			$alumnos_promedio_total=array();
			foreach($data_materias AS $m=>$materia){
				$num_promedio_total=0;
				$alumnos_promedio=array();

				if(intval($materia["has_nivel"])>0){
					$objPHPExcel->getActiveSheet()->mergeCells(strval($s).'2:'.strval($s).'3');
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", "NIVEL DE DESEMPEÑO");
					$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
					$temp=$s;
					if($data_alumnos!==FALSE){
						$s_a=$start_alumnos;
						foreach($data_alumnos AS $al=>$alumno){
							$temp_calif=$this->get_data_from_query("SELECT nivel FROM calificaciones WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$materia["id"]."' AND bimestre='$bim' ;");
							if($temp_calif!==FALSE) $temp_calif="NIVEL ".$temp_calif[0]["nivel"];
							else $temp_calif="";
							$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a), $temp_calif);
							$s_a++;
						}
					}
					$s++;
				}

				if(intval($materia["has_calif"])>0){
					$data_cual=$this->get_data_from_query("SELECT id, nombre FROM cualidades WHERE idMateria='".$materia["id"]."' AND bimestre='$bim' ORDER BY idMateria, orden");
					$num_promedio=0;
					if($data_cual!==FALSE){
						foreach($data_cual AS $c=>$cual){
							$num_promedio++;

							$data_sub=$this->get_data_from_query("SELECT id, nombre FROM cualidades_sub WHERE idCualidad='".$cual["id"]."' AND bimestre='$bim' ORDER BY orden ");
							$temp_sub=$s;

							if($data_sub!==FALSE){
								$lastElement=end($data_sub);
								foreach($data_sub AS $i=>$item){
									$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(6);
									$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."3", $item["nombre"]);
									$objPHPExcel->getActiveSheet()->getStyle(strval($s)."3")->getAlignment()->setTextRotation(90);
									if($data_alumnos!==FALSE){
										$s_a=$start_alumnos;
										foreach($data_alumnos AS $al=>$alumno){
											$temp_calif=$this->get_data_from_query("SELECT porcentaje FROM calificaciones_cualidades_sub WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$materia["id"]."' AND idCualidadSub='".$item["id"]."' AND bimestre='$bim' ;");
											if($temp_calif!==FALSE) $temp_calif=$temp_calif[0]["porcentaje"];
											else $temp_calif="";
											$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a), $temp_calif);
											$objPHPExcel->getActiveSheet()->getStyle(strval($s).strval($s_a))->getNumberFormat()->setFormatCode('#,##0.00');
											$s_a++;
										}
									}
									if($item["id"]!==$lastElement["id"]) $s++;
								}
								$s++;
							}
							$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."3", "PROMEDIO");
							$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(6);
							$objPHPExcel->getActiveSheet()->getStyle(strval($s)."3")->getAlignment()->setTextRotation(90);
							if($s_a>0) $objPHPExcel->getActiveSheet()->getStyle($s.'3:'.$s.($s_a-1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('f59b4d');

							$objPHPExcel->getActiveSheet()->mergeCells(strval($temp_sub).'2:'.strval($s).'2');
							$objPHPExcel->getActiveSheet()->getStyle(strval($temp_sub).'2:'.strval($s).'2')->applyFromArray($style_center);
							$objPHPExcel->getActiveSheet()->getStyle(strval($temp_sub)."2")->getAlignment()->setTextRotation(0);
							$objPHPExcel->getActiveSheet()->SetCellValue(strval($temp_sub)."2", $cual["nombre"]);
							
							$temp=$s;
							if($data_alumnos!==FALSE){
								$s_a=$start_alumnos;
								foreach($data_alumnos AS $al=>$alumno){
									$temp_calif=$this->get_data_from_query("SELECT porcentaje FROM calificaciones_cualidades WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$materia["id"]."' AND idCualidad='".$cual["id"]."' AND bimestre='$bim' ;");
									if($temp_calif!==FALSE) $temp_calif=$temp_calif[0]["porcentaje"];
									else $temp_calif="";
									$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a), $temp_calif);
									$objPHPExcel->getActiveSheet()->getStyle(strval($s).strval($s_a))->getNumberFormat()->setFormatCode('#,##0.00');
									$s_a++;
								}
							}
							$s++;
						}
					}
					if($if_no_kinder) $eval_continua=array("promedio"=>"PROMEDIO");
					else $eval_continua=array("promedio"=>"CALIFICACIÓN");

					foreach($eval_continua AS $e=>$eval){
						array_push($promedios_columns, $s);
						$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(6);
						$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", $eval);
						$temp=$s;
						$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
						if($data_alumnos!==FALSE){
							$s_a=$start_alumnos;
							foreach($data_alumnos AS $al=>$alumno){
								if($if_no_kinder){
									$tot_temp=$this->get_data_from_query("SELECT COALESCE(calificacion,0) AS calif FROM (SELECT * FROM calificaciones WHERE idAlumno='".$alumno["id"]."' ORDER BY id DESC) AS t WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$materia["id"]."' AND bimestre='$bim' GROUP BY idMateria ;");
									if($tot_temp!==FALSE) $tot_temp=$tot_temp[0]["calif"];
									else $tot_temp=0;
									if(!array_key_exists($alumno["id"],$alumnos_promedio_total))$alumnos_promedio_total[$alumno["id"]]=array();
									array_push($alumnos_promedio_total[$alumno["id"]],array("idBloque"=>$materia["idBloque"],"tot"=>$tot_temp));
									$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a),$tot_temp);
									$objPHPExcel->getActiveSheet()->getStyle(strval($s).strval($s_a))->getNumberFormat()->setFormatCode('#,##0.00');
								}else{
									$tot_temp=$this->get_data_from_query("SELECT COALESCE(calificacion_letra,'') AS calif FROM (SELECT * FROM calificaciones WHERE idAlumno='".$alumno["id"]."' ORDER BY id DESC) AS t WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$materia["id"]."' AND bimestre='$bim' GROUP BY idMateria ;");
									if($tot_temp!==FALSE){
										$tot_temp=$tot_temp[0]["calif"];
										$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a),$tot_temp);
									}
								}
								$s_a++;
							}
						}
						$s++;
					}
				}

				$objPHPExcel->getActiveSheet()->mergeCells(strval($ss).'1:'.strval($temp).'1');
				$objPHPExcel->getActiveSheet()->getStyle(strval($ss).'1:'.strval($temp).'1')->applyFromArray($style_center);
				$objPHPExcel->getActiveSheet()->SetCellValue(strval($ss)."1", $materia["nombre"]);
				$ss=$temp;$ss++;
				$num_promedio_total=$m+1;
			}

			/* MATERIAS ADICIONALES */
			if($if_is_sec){
				if($permisos!==FALSE || intval($this->check_permisos("allow_admin"))===0) $query_temp="SELECT $whats_to_select FROM materias WHERE active=1 AND idBloque IN (SELECT id FROM bloques WHERE is_part_avr=0) AND grado='$grado' AND id IN (SELECT idMateria FROM permisos_boletas WHERE idUsuario='".$this->session->userdata("id")."') ORDER BY idBloque, orden ";
				else $query_temp="SELECT $whats_to_select FROM materias WHERE active=1 AND idBloque IN (SELECT id FROM bloques WHERE is_part_avr=0) AND grado='$grado' ORDER BY idBloque, orden ";
				$data_materias_adicionales=$this->get_data_from_query($query_temp);
				$s_inicio=$s; $temp=0;
				if($data_materias_adicionales!==FALSE){
					foreach($data_materias_adicionales as $m => $mat_adic){
						
						$data_cual_temp=$this->get_data_from_query("SELECT * FROM cualidades WHERE idMateria='".$mat_adic["id"]."' AND bimestre='$bim' ORDER BY idMateria, orden");
						if($data_cual_temp!==FALSE){
							foreach($data_cual_temp AS $c=>$cualidad_adic){
								$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(6);
								$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", $cualidad_adic["nombre"]);
								$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
								if($data_alumnos!==FALSE){
									$s_a=$start_alumnos;
									foreach($data_alumnos AS $al=>$alumno){
										$temp_calif=$this->get_data_from_query("SELECT porcentaje FROM calificaciones_cualidades WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$mat_adic["id"]."' AND idCualidad='".$cualidad_adic["id"]."' AND bimestre='$bim' ;");
										if($temp_calif!==FALSE) $temp_calif=$temp_calif[0]["porcentaje"];
										else $temp_calif="";
										$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a), $temp_calif);
										$objPHPExcel->getActiveSheet()->getStyle(strval($s).strval($s_a))->getNumberFormat()->setFormatCode('#,##0.00');
										$s_a++;
									}
								}
								$temp=$s;
								$s++;
							}
						}

						$objPHPExcel->getActiveSheet()->mergeCells(strval($s_inicio).'1:'.strval($s).'1');
						$objPHPExcel->getActiveSheet()->getStyle(strval($s_inicio).'1:'.strval($s).'1')->applyFromArray($style_center);
						$objPHPExcel->getActiveSheet()->SetCellValue(strval($s_inicio)."1", $mat_adic["nombre"]);
						$objPHPExcel->getActiveSheet()->getColumnDimension($s_inicio)->setWidth(6);

						$eval_continua=array("promedio"=>"PROMEDIO");

						foreach($eval_continua AS $e=>$eval){
							array_push($promedios_columns, $s);
							$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(6);
							$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", $eval);
							$temp=$s;
							$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
							if($data_alumnos!==FALSE){
								$s_a=$start_alumnos;
								foreach($data_alumnos AS $al=>$alumno){
									$tot_temp=$this->get_data_from_query("SELECT COALESCE(calificacion,0) AS calif, COALESCE(calificacion_letra,'') AS calif_letra FROM (SELECT * FROM calificaciones WHERE idAlumno='".$alumno["id"]."' ORDER BY id DESC) AS t WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$mat_adic["id"]."' AND bimestre='$bim' GROUP BY idMateria ;");
									if($tot_temp!==FALSE) $tot_temp=$tot_temp[0]["calif"];
									else $tot_temp=0;
									array_push($alumnos_promedio_total[$alumno["id"]],array("idBloque"=>$mat_adic["idBloque"],"tot"=>$tot_temp));
									$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a),$tot_temp);
									$objPHPExcel->getActiveSheet()->getStyle(strval($s).strval($s_a))->getNumberFormat()->setFormatCode('#,##0.00');
									$s_a++;
								}
								$s++;
							}
						}

					}
				}
			}

			if($if_no_kinder){
				$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(7);
				$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", "PROMEDIO GENERAL");
				$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
				$prom=$s;$s++;
			}

			/* MATERIAS ADICIONALES */
			if(!$if_is_sec){
				if($permisos!==FALSE || intval($this->check_permisos("allow_admin"))===0) $query_temp="SELECT $whats_to_select FROM materias WHERE active=1 AND idBloque IN (SELECT id FROM bloques WHERE is_part_avr=0) AND grado='$grado' AND id IN (SELECT idMateria FROM permisos_boletas WHERE idUsuario='".$this->session->userdata("id")."') ORDER BY idBloque, orden ";
				else $query_temp="SELECT $whats_to_select FROM materias WHERE active=1 AND idBloque IN (SELECT id FROM bloques WHERE is_part_avr=0) AND grado='$grado' ORDER BY idBloque, orden ";
				$data_materias_adicionales=$this->get_data_from_query($query_temp);
				if($data_materias_adicionales!==FALSE){
					foreach ($data_materias_adicionales as $m => $mat_adic){
						$temp=$s;

						if(intval($mat_adic["has_calif"])>0){
							$objPHPExcel->getActiveSheet()->mergeCells(strval($s).'2:'.strval($s).'3');
							$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", "CALIFICACIÓN");
							$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
							if($data_alumnos!==FALSE){
								$s_a=$start_alumnos;
								foreach($data_alumnos AS $al=>$alumno){
									$tot_temp=$this->get_data_from_query("SELECT COALESCE(calificacion,0) AS calif, COALESCE(calificacion_letra,'') AS calif_letra FROM (SELECT * FROM calificaciones WHERE idAlumno='".$alumno["id"]."' ORDER BY id DESC) AS t WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$mat_adic["id"]."' AND bimestre='$bim' GROUP BY idMateria ;");
									if($tot_temp!==FALSE){
										$tot_temp=$tot_temp[0]["calif"];
									}else{
										if($if_no_kinder) $tot_temp=0;
										else $tot_temp="";
									}
									$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a),$tot_temp);
									if($if_no_kinder) $objPHPExcel->getActiveSheet()->getStyle(strval($s).strval($s_a))->getNumberFormat()->setFormatCode('#,##0.00');
									$s_a++;
								}
							}
						}

						if(intval($mat_adic["has_nivel"])>0){
							if(intval($mat_adic["has_calif"])>0) $s++;
							$objPHPExcel->getActiveSheet()->mergeCells(strval($s).'2:'.strval($s).'3');
							$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", "NIVEL DE DESEMPEÑO");
							$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
							if($data_alumnos!==FALSE){
								$s_a=$start_alumnos;
								foreach($data_alumnos AS $al=>$alumno){
									$tot_temp=$this->get_data_from_query("SELECT nivel FROM calificaciones WHERE idAlumno='".$alumno["id"]."' AND idMateria='".$mat_adic["id"]."' AND bimestre='$bim' ;");
									if($tot_temp!==FALSE) $tot_temp="NIVEL ".$tot_temp[0]["nivel"];
									else $tot_temp="";
									$objPHPExcel->getActiveSheet()->SetCellValue(strval($s).strval($s_a),$tot_temp);
									$s_a++;
								}
							}
						}

						//$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(6);
						if($temp!=$s) $objPHPExcel->getActiveSheet()->mergeCells(strval($temp).'1:'.strval($s).'1');
						$objPHPExcel->getActiveSheet()->SetCellValue(strval($temp)."1", $mat_adic["nombre"]);
						$s++;
					}
				}
			}

			$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(30);
			$objPHPExcel->getActiveSheet()->mergeCells(strval($s).'2:'.strval($s).'3');
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", "COMENTARIOS");
			$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
			$coment1=$s;$s++;$coment2=$s;
			$objPHPExcel->getActiveSheet()->getColumnDimension($s)->setWidth(30);
			$objPHPExcel->getActiveSheet()->mergeCells(strval($s).'2:'.strval($s).'3');
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($s)."2", "COMMENTS");
			$objPHPExcel->getActiveSheet()->getStyle(strval($s)."2")->getAlignment()->setTextRotation(90);
			$s=$start_alumnos;
			if($data_alumnos!==FALSE){
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(40);
				foreach($data_alumnos AS $al=>$alumno){
					$a='A';
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).$s, $alumno["id"]);
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).$s, ($al+1));
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).$s, $alumno["nombre"]);
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($coment1).$s, $alumno["idComentario"]);
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($coment2).$s, $alumno["idComentario_en"]);
					$bloques=array("1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0);
					$bloques_tot=array("1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0);
					$tot=0;
					if($if_no_kinder){

						if(array_key_exists($alumno["id"],$alumnos_promedio_total)){
							foreach($alumnos_promedio_total[$alumno["id"]] AS $e => $key){
								$bloques[$key["idBloque"]]+=floatval($key["tot"]);
								$bloques_tot[$key["idBloque"]]++;
							}
							$tot=0;
							if(!$if_is_sec){
								foreach($bloques AS $ee => $keey){
									if(intval($bloques_tot[$ee])>0) $tot += floatval($this->roundDown((floatval($bloques[$ee])/intval($bloques_tot[$ee])),2));
								}
								if(intval($bloques_tot["1"])>0) array_push($promedios_spanish,array("id"=>$s, "prom"=>floatval((floatval($bloques["1"])/intval($bloques_tot["1"])))));
								if(intval($bloques_tot["2"])>0) array_push($promedios_english,array("id"=>$s, "prom"=>floatval((floatval($bloques["2"])/intval($bloques_tot["2"])))));
							}else{
								$tot += floatval(floatval($bloques["1"])/intval($bloques_tot["1"]));
								$lang = floatval(floatval($bloques["2"])/intval($bloques_tot["2"]));
								$lang += floatval($bloques["5"]);
								$lang /= 2;
								$tot += $lang;
							}
						}
					
						$objPHPExcel->getActiveSheet()->SetCellValue(strval($prom).strval($s), $this->roundDown($tot/2,2));
						$objPHPExcel->getActiveSheet()->getStyle(strval($prom).strval($s))->getNumberFormat()->setFormatCode('#,##0.00');
					}
					$s++;
				}
			}
		}

		foreach($promedios_columns AS $col){
			$objPHPExcel->getActiveSheet()->getStyle($col.'2:'.$col.($s-1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('e3f54d');
		}
		if($if_no_kinder) $objPHPExcel->getActiveSheet()->getStyle($prom.'2:'.$prom.($s-1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('5ff54d');

		//CUADRO DE HONOR
		if(!$if_is_sec && $if_no_kinder && 0){
			$s++;
			
			$arr_spanish = $this->array_msort($promedios_spanish, array('prom'=>SORT_DESC));
			$arr_english = $this->array_msort($promedios_english, array('prom'=>SORT_DESC));
			//$first = array_shift($arr_english);
			$query="SELECT al.nombre AS nombre_alumno, dt.nombre AS nombre_dt, prof.nombre AS nombre_prof, teach.nombre AS nombre_teacher 
					FROM reportes_detalles AS ppal
					INNER JOIN alumnos AS al ON ppal.idAlumno=al.id
					INNER JOIN usuarios_boletas AS dt ON ppal.dirTec=dt.id
					INNER JOIN usuarios_boletas AS prof ON ppal.prof=prof.id
					INNER JOIN usuarios_boletas AS teach ON ppal.teacher=teach.id WHERE ppal.bimestre='$bim' AND ppal.grado='$grado' AND ppal.grupo='$grupo'; ";

			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s++, "CUADRO HONOR");
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "LUGAR");
			$objPHPExcel->getActiveSheet()->SetCellValue("F".$s, "ESFUERZO:");
			$esf=$s;
			$objPHPExcel->getActiveSheet()->SetCellValue("S".$s++, "DIRECTOR TÉCNICO:");

			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "1º");
			$objPHPExcel->getActiveSheet()->SetCellValue("C".$s++, $objPHPExcel->getActiveSheet()->getCell("C".array_shift($arr_spanish)["id"])->getValue());
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "2º");
			$objPHPExcel->getActiveSheet()->SetCellValue("C".$s, $objPHPExcel->getActiveSheet()->getCell("C".array_shift($arr_spanish)["id"])->getValue());
			$maestro=$s;
			$objPHPExcel->getActiveSheet()->SetCellValue("S".$s++, "MAESTRO GRUPO:");
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "3º");
			$objPHPExcel->getActiveSheet()->SetCellValue("C".$s++, $objPHPExcel->getActiveSheet()->getCell("C".array_shift($arr_spanish)["id"])->getValue());
			$teach=$s;
			$objPHPExcel->getActiveSheet()->SetCellValue("S".$s++, "TEACHER'S NAME:");
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s++, "HONOR ROLL");
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "1º");
			$objPHPExcel->getActiveSheet()->SetCellValue("C".$s++, $objPHPExcel->getActiveSheet()->getCell("C".array_shift($arr_english)["id"])->getValue());
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "2º");
			$objPHPExcel->getActiveSheet()->SetCellValue("C".$s++, $objPHPExcel->getActiveSheet()->getCell("C".array_shift($arr_english)["id"])->getValue());
			$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, "3º");
			$objPHPExcel->getActiveSheet()->SetCellValue("C".$s++, $objPHPExcel->getActiveSheet()->getCell("C".array_shift($arr_english)["id"])->getValue());

			$data_temp=$this->get_data_from_query($query);
			if($data_temp!==FALSE){
				$data_temp=$data_temp[0];
				$objPHPExcel->getActiveSheet()->SetCellValue("G".$esf, $data_temp["nombre_alumno"]);
				$objPHPExcel->getActiveSheet()->SetCellValue("T".$esf, $data_temp["nombre_dt"]);
				$objPHPExcel->getActiveSheet()->SetCellValue("T".$maestro, $data_temp["nombre_prof"]);
				$objPHPExcel->getActiveSheet()->SetCellValue("T".$teach, $data_temp["nombre_teacher"]);
			}
		}

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(str_replace(__FILE__,FILE_ROUTE_FULL.'files/'.$name,__FILE__));
		print json_encode(array("success"=>TRUE,"ruta"=>$name));
		/* */
	}

	/* ************************* HELPERS *********************** */

	public function get_query_construct($permisos,$tipo,$grado="",$grupo="",$limit="",$has_permisos=TRUE,$use_like=TRUE,$main_key="grado"){ //0=grado, 1=grupo, 2=alumno, 3=data_alumnos, 4=profesores, 5=materias
		$grupo_w="";
		if($grupo!=="" && $grupo!="0") $grupo_w=" AND grupo='$grupo'";
		$s=TRUE;
		if($has_permisos) $s=intval($this->check_permisos("allow_admin"))>0;
		$wheres=array(
			array(
				"SELECT grado FROM alumnos GROUP BY grado ORDER BY grado",
				"SELECT grado FROM alumnos WHERE id>0 ",
				"grado",
				") GROUP BY grado ORDER BY grado",
				"SELECT grado FROM alumnos GROUP BY grado ORDER BY grado"
			),
			array(
				"SELECT grupo, grado FROM grados_boletas GROUP BY grado, grupo ORDER BY grado",
				"SELECT grupo, grado FROM grados_boletas WHERE id>0 ",
				"grado",
				") GROUP BY grado, grupo ORDER BY grado",
				"SELECT grupo, grado FROM grados_boletas GROUP BY grado, grupo ORDER BY grado"
			),
			array(
				"SELECT ppal.*, (SELECT grupo FROM grados_boletas WHERE idAlumno=ppal.id LIMIT 1) AS grupo FROM alumnos AS ppal ORDER BY ppal.grado, grupo, ppal.nombre",
				"SELECT ppal.*, (SELECT grupo FROM grados_boletas WHERE idAlumno=ppal.id LIMIT 1) AS grupo FROM alumnos AS ppal WHERE id>0 ",
				"grado",
				") ORDER BY ppal.grado, grupo, ppal.nombre",
				"SELECT ppal.*, (SELECT grupo FROM grados_boletas WHERE idAlumno=ppal.id LIMIT 1) AS grupo FROM alumnos AS ppal ORDER BY ppal.grado, grupo, ppal.nombre"
			),
			array(
				"SELECT ppal.*, COALESCE((SELECT nombre FROM comentarios_cat WHERE id = (SELECT idComentario FROM boletas WHERE idAlumno=ppal.id)),'') AS idComentario, COALESCE((SELECT nombre FROM comentarios_cat WHERE id = (SELECT idComentario_en FROM boletas WHERE idAlumno=ppal.id)),'') AS idComentario_en, (SELECT grupo FROM grados_boletas WHERE idAlumno=ppal.id LIMIT 1) AS grupo FROM alumnos AS ppal WHERE ppal.grado='$grado' $grupo_w GROUP BY ppal.id ORDER BY ppal.grado, grupo, ppal.nombre $limit",
				"SELECT ppal.*, COALESCE((SELECT nombre FROM comentarios_cat WHERE id = (SELECT idComentario FROM boletas WHERE idAlumno=ppal.id)),'') AS idComentario, COALESCE((SELECT nombre FROM comentarios_cat WHERE id = (SELECT idComentario_en FROM boletas WHERE idAlumno=ppal.id)),'') AS idComentario_en, (SELECT grupo FROM grados_boletas WHERE idAlumno=ppal.id LIMIT 1) AS grupo FROM alumnos AS ppal WHERE ppal.grado='$grado' $grupo_w $limit",
				"ppal.grado",
				") GROUP BY ppal.id ORDER BY ppal.grado, grupo, ppal.nombre",
				"SELECT ppal.*, COALESCE((SELECT nombre FROM comentarios_cat WHERE id = (SELECT idComentario FROM boletas WHERE idAlumno=ppal.id)),'') AS idComentario, COALESCE((SELECT nombre FROM comentarios_cat WHERE id = (SELECT idComentario_en FROM boletas WHERE idAlumno=ppal.id)),'') AS idComentario_en, (SELECT grupo FROM grados_boletas WHERE idAlumno=ppal.id LIMIT 1) AS grupo FROM alumnos AS ppal WHERE ppal.grado='$grado' $grupo_w GROUP BY ppal.id ORDER BY ppal.grado, grupo, ppal.nombre $limit"
			),
			array(
				"SELECT id, nombre, allow_admin FROM usuarios_boletas WHERE date_end IS NULL"
			),
			array(
				"SELECT ppal.* FROM materias AS ppal WHERE ppal.active=1 ORDER BY ppal.grado, ppal.idBloque, ppal.orden, ppal.nombre",
				"SELECT ppal.* FROM materias AS ppal WHERE ppal.active=1",
				"ppal.id",
				") ORDER BY ppal.grado, ppal.idBloque, ppal.orden, ppal.nombre",
				"SELECT ppal.* FROM materias AS ppal WHERE ppal.active=1 ORDER BY ppal.grado, ppal.idBloque, ppal.orden, ppal.nombre",
			)
		);
		$query="";
		$x=0;
		if($s) $query=$wheres[$tipo][0];
		else{
			if($permisos!==FALSE){
				$query=$wheres[$tipo][1];
				$s=TRUE;
				$ss=FALSE;
				foreach($permisos AS $e=>$key){
					if($s){
						$s=!$s;
						$query.=" AND (";
					}
					if(strlen($key[$main_key])>0){
						if($ss){
							$ss=!$ss;
							$query.=" OR ";
						}
						if($use_like) $query.=" ".$wheres[$tipo][2]." LIKE '%".$key[$main_key]."%' ";
						else $query.=" ".$wheres[$tipo][2]." = '".$key[$main_key]."' ";
						$ss=!$ss;
					}
				}
				if(!$s) $query.=$wheres[$tipo][3];
			}else $query=$wheres[$tipo][4];
		}
		return $query;
	}

	public function check_permisos($permiso){
		$temp=$this->get_data($permiso,"usuarios_boletas","id='".$this->session->userdata("id")."'");
		return $temp[0][$permiso];
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
	public function edit_data($data,$table,$id,$idName,$where=";"){
		if(intval($this->session->userdata('is_ciclo1718')))
			return $this->mdllogin1718->editData($data,$table,$id,$idName,$where);
		return $this->mdllogin->editData($data,$table,$id,$idName,$where);
	}
	public function delete_data($table,$id,$idName){
		if(intval($this->session->userdata('is_ciclo1718')))
			return $this->mdllogin1718->deleteData($table,$id,$idName);
		return $this->mdllogin->deleteData($table,$id,$idName);
	}
	public function delete_data_multiple_where($table,$where){
		if(intval($this->session->userdata('is_ciclo1718')))
			return $this->mdllogin1718->deleteData_multipleWhere($table,$where);
		return $this->mdllogin->deleteData_multipleWhere($table,$where);
	}

	private function roundDown($decimal, $precision){
		$fraction = substr($decimal - floor($decimal), 2, $precision);
		$newDecimal = floor($decimal). '.' .$fraction;
		//return floatval($newDecimal);
		return number_format((float)$newDecimal, 2, '.', '');
	}

	private function array_msort($array, $cols){
		$colarr = array();
		foreach ($cols as $col => $order) {
			$colarr[$col] = array();
			foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
		}
		$eval = 'array_multisort(';
		foreach ($cols as $col => $order) {
			$eval .= '$colarr[\''.$col.'\'],'.$order.',';
		}
		$eval = substr($eval,0,-1).');';
		eval($eval);
		$ret = array();
		foreach ($colarr as $col => $arr) {
			foreach ($arr as $k => $v) {
				$k = substr($k,1);
				if (!isset($ret[$k])) $ret[$k] = $array[$k];
				$ret[$k][$col] = $array[$k][$col];
			}
		}
		return $ret;
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

}

?>