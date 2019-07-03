<?php

class ctrFiles extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		date_default_timezone_set('America/Mexico_City');
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/IOFactory.php";
		require FILE_ROUTE_FULL.'addons/mailer/PHPMailerAutoload.php';
		$this->inicialRow=8;
		$this->materiaRow=4;
		$this->cualidadRow=6;
		$this->subCualidadRow=7;
		$this->maxLen=14;
		$this->idCol="B";
		$this->nombreCol="D";
		$this->alumnosContainer=array();
		$this->alumnosDetails=array();
		$this->alumnosContainerFaltas=array();
		$this->alumnosDetailsFaltas=array();
	}

	public function upload_calificaciones(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$grado=$this->security->xss_clean($this->input->post("grado"));
		$grupo=$this->security->xss_clean($this->input->post("grupo"));
		$bim=$this->security->xss_clean($this->input->post("bim"));
		$write=TRUE;
		foreach($data AS $e => $key){
			$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/".$key);
			$elem=$objPHPExcel->setActiveSheetIndex(1);
			$highestCol=$elem->getHighestColumn();
			$highestColNumber = $this->str_to_num($highestCol);
			$materiaSeleccionada=$cualidadSeleccionada=$subcualidadSeleccionada="";
			$log_id=$this->insert_data(array("name_file"=>$key, "grado"=>$grado, "grupo"=>$grupo, "idUsuarioAlta"=>$this->session->userdata('id'), "date_start"=>date('Y-m-d H:i:s')),"log_files");
			for($i=$this->materiaRow; $i<=$highestColNumber; $i++){
				$val=$elem->getCell($this->num_to_str($i).strval($this->materiaRow))->getCalculatedValue();
				if(strlen($val)>0) $materiaSeleccionada = $val;
				//*
				if(strtolower($materiaSeleccionada)==="faltas"){
					$array_temp=$this->get_all_alumnos_from_col_faltas($elem,$this->num_to_str($i),$grado,$bim);
					if(!empty($array_temp)){
						foreach($array_temp as $j => $item) {
							if(strlen($item["value"])>0){
								if(intval($item["data_faltas_id"])>0){
									if($write){
										$temp=array("faltas"=>strval($item["value"]));
										$this->edit_data($temp,"faltas_boletas",intval($item["data_faltas_id"]),"id");
									}else{
										print_r(array("id"=>$item["data_faltas_id"], "idAlumno"=>$item["idAlumno"], "faltas"=>$item["value"])); echo "=> UPDATE FALTAS ".$item["value"]."<br><br>";
									}
								}else{
									$temp=array("idAlumno"=>$item["idAlumno"], "bim"=>$bim, "faltas"=>$item["value"]);
									if($write){
										$insert_id=$this->insert_data($temp,"faltas_boletas");
									}else{
										print_r($temp); echo "=> NEW FALTAS ".$item["value"]."<br><br>";
									}
								}
							}
						}
					}
				}elseif(strtolower($materiaSeleccionada)==="comentarios"){
					$array_temp=$this->get_all_alumnos_from_col_faltas($elem,$this->num_to_str($i),$grado,$bim);
					if(!empty($array_temp)){
						foreach($array_temp as $j => $item) {
							if(strlen($item["value"])>0){
								if(intval($item["data_faltas_id"])>0){
									if($write){
										$temp=array("idComentario"=>$item["value"]);
										$this->edit_data($temp,"faltas_boletas",intval($item["data_faltas_id"]),"id");
									}else{
										print_r(array("id"=>$item["data_faltas_id"], "idAlumno"=>$item["idAlumno"], "idComentario"=>$item["value"])); echo "=> UPDATE idComent<br><br>";
									}
								}else{
									$temp=array("idAlumno"=>$item["idAlumno"], "bim"=>$bim, "idComentario"=>$item["value"]);
									if($write){
										$insert_id=$this->insert_data($temp,"faltas_boletas");
									}else{
										print_r($temp); echo "=> NEW idComent<br><br>";
									}
								}
							}
						}
					}
				}elseif(strtolower($materiaSeleccionada)==="retardos"){
					$array_temp=$this->get_all_alumnos_from_col_faltas($elem,$this->num_to_str($i),$grado,$bim);
					if(!empty($array_temp)){
						foreach($array_temp as $j => $item) {
							if(strlen($item["value"])>0){
								if(intval($item["data_faltas_id"])>0){
									if($write){
										$this->edit_data(array("retardos"=>strval($item["value"])),"faltas_boletas",intval($item["data_faltas_id"]),"id");
									}else{
										print_r(array("id"=>$item["data_faltas_id"], "idAlumno"=>$item["idAlumno"], "retardos"=>$item["value"])); echo "=> UPDATE RETARDOS ".$item["value"]."<br><br>";
									}
								}else{
									$temp=array("idAlumno"=>$item["idAlumno"], "bim"=>$bim, "retardos"=>$item["value"]);
									if($write){
										$insert_id=$this->insert_data($temp,"faltas_boletas");
									}else{
										print_r($temp); echo "=> NEW RETARDOS ".$item["value"]."<br><br>";
									}
								}
							}
						}
					}
				}else{
					$query="SELECT id FROM materias WHERE grado='$grado' AND nombre = '$materiaSeleccionada' ";
					$data_materia=$this->get_data_from_query($query);
					if($data_materia!==FALSE){
						$data_materia=$data_materia[0];
						$temp=$elem->getCell($this->num_to_str($i).strval($this->cualidadRow));
						if(strlen($temp)>0 && strtolower(substr($temp,0,4))==="prom"){
							$array_temp=$this->get_all_alumnos_from_col($elem,$this->num_to_str($i),$grado,$data_materia["id"],$bim);
							if(!empty($array_temp)){
								foreach($array_temp AS $j => $item){
									if($write){
										$this->edit_data(array("calificacion"=>$item["porcentaje"]),"calificaciones",$item["idCalificacion"],"id");
									}else{
										print_r(array("calificacion"=>$item["porcentaje"]));echo "=> PROMEDIO ".$materiaSeleccionada."<br><br>";
									}
								}
							}
						}elseif(strlen($temp)>0 && strtolower(substr($temp,0,2))==="nd"){
							$array_temp=$this->get_all_alumnos_from_col($elem,$this->num_to_str($i),$grado,$data_materia["id"],$bim);
							if(!empty($array_temp)){
								foreach($array_temp AS $j => $item){
									if($write){
										$this->edit_data(array("nivel"=>$item["porcentaje"]),"calificaciones",$item["idCalificacion"],"id");
									}else{
										print_r(array("calificacion"=>$item["porcentaje"]));echo "=> PROMEDIO ".$materiaSeleccionada."<br><br>";
									}
								}
							}
						}else{
							if(strlen($temp)>0) $cualidadSeleccionada=$temp;
							$query_cual="SELECT id, nombre FROM cualidades WHERE idMateria='".$data_materia["id"]."' AND nombre = '$cualidadSeleccionada' ";
							$data_cual=$this->get_data_from_query($query_cual);
							if($data_cual!==FALSE){
								$data_cual=$data_cual[0];
								$temp=$elem->getCell($this->num_to_str($i).strval($this->subCualidadRow));
								if(strlen($temp)>0 && strtolower(substr($temp,0,4))==="prom"){
									$array_temp=$this->get_all_alumnos_from_col($elem,$this->num_to_str($i),$grado,$data_materia["id"],$bim);
									if(!empty($array_temp)){
										foreach($array_temp AS $j => $item){
											if(intval($item["idCalificacionesCualidades"])>0){
												if($write){
													$this->edit_data(array("porcentaje"=>$item["porcentaje"]),"calificaciones_cualidades",$item["idCalificacionesCualidades"],"id");
												}else{
													print_r(array("porcentaje"=>$item["porcentaje"]));echo "=> PROMEDIO CUALIDAD ".$cualidadSeleccionada." ".$item["idCalificacionesCualidades"]."<br><br>";
												}
											}else{
												$calif_cual=$this->construct_calif_cual($item,$data_materia,$data_cual["id"],$grado,$bim);
												if($write){
													$insert_id=$this->insert_data($calif_cual,"calificaciones_cualidades");
												}else{
													print_r(array("porcentaje"=>$item["porcentaje"]));echo "=> PROMEDIO CUALIDAD ".$cualidadSeleccionada."<br><br>";
												}
											}
										}
									}
								}else{
									if(strlen($temp)>0) $subcualidadSeleccionada=$temp;
									$query_cual_sub="SELECT id, nombre FROM cualidades_sub WHERE idCualidad='".$data_cual["id"]."' AND nombre = '$subcualidadSeleccionada' ";
									$data_cual_sub=$this->get_data_from_query($query_cual_sub);
									if($data_cual_sub!==FALSE){
										$data_cual_sub=$data_cual_sub[0];
										$array_temp=$this->get_all_alumnos_from_col($elem,$this->num_to_str($i),$grado,$data_materia["id"],$bim,$data_cual["id"],$data_cual_sub["id"]);
										if(!empty($array_temp)){
											foreach($array_temp AS $j => $item){
												if(intval($item["idCalificacionesCualidades_sub"])>0){
													if($write){
														$this->edit_data(array("porcentaje"=>$item["porcentaje"]),"calificaciones_cualidades_sub",$item["idCalificacionesCualidades_sub"],"id");
													}else{
														print_r(array("porcentaje"=>$item["porcentaje"]));echo " SUB CUALIDAD => ".$data_cual_sub["id"]." : ".$subcualidadSeleccionada."<br><br>";
													}
												}else{
													$calif_cual=$this->construct_calif_cual_sub($item,$data_materia,$data_cual_sub["id"],$grado,$bim);
													if($write){
														$insert_id=$this->insert_data($calif_cual,"calificaciones_cualidades_sub");
													}else{
														print_r($calif_cual);echo " NEW SUB CUALIDAD => ".$data_cual_sub["id"]." : ".$subcualidadSeleccionada."<br><br>";
													}
												}
											}
										}
									}
								}
							}
						}
					}#END IF data_materia!==FALSE
				}
			}
		}
		print json_encode(array("success"=>TRUE));
	}

	private function get_all_alumnos_from_col($elem,$col,$grado,$idMateria,$bim,$cualidad=0,$cualidad_sub=0){
		$highestRow=$elem->getHighestRow();
		$alumnos=array();
		for($i=$this->inicialRow;$i<=$highestRow;$i++){
			if(strlen($elem->getCell($this->idCol.strval($i)))===0) return $alumnos;
			$nombre=$elem->getCell($this->nombreCol.strval($i))->getValue();
			$find_id=array_search(array("nombre"=>$nombre), $this->alumnosContainer);
			if($find_id===FALSE){
				//$query="SELECT ppal.id, COALESCE((SELECT id FROM boletas WHERE idAlumno=ppal.id LIMIT 1),0) AS idBoleta FROM alumnos AS ppal WHERE ppal.nombre LIKE '%$nombre%' AND ppal.grado='$grado';";
				$query="SELECT ppal.id, bol.id AS idBoleta
						FROM alumnos AS ppal
						LEFT JOIN boletas AS bol ON ppal.id=bol.idAlumno
						WHERE ppal.nombre = '$nombre' AND ppal.grado='$grado';";
				$data_alumno=$this->get_data_from_query($query);
			}else $data_alumno=$this->alumnosDetails[$find_id];

			if($data_alumno!==FALSE){
				if($find_id===FALSE) $data_alumno=$data_alumno[0];

				$data_boleta=intval($data_alumno["idBoleta"]);
				if($data_boleta==0){
					$data_boleta=$this->insert_data(array("idAlumno"=>$data_alumno["id"], "grado"=>$grado, "idUsuarioAlta"=>$this->session->userdata("id"), "date_start"=>date('Y-m-d H:i:s')),"boletas");
					if($data_boleta===FALSE) return $alumnos;
				}

				if($find_id===FALSE){
					array_push($this->alumnosContainer, array("nombre"=>$nombre));
					array_push($this->alumnosDetails, array("id"=>$data_alumno["id"], "idBoleta"=>$data_boleta));
				}

				$data_calif=$this->get_data("id","calificaciones","idBoleta='$data_boleta' AND idMateria='$idMateria' AND idAlumno='".$data_alumno["id"]."' AND bimestre='$bim'");
				if($data_calif!==FALSE){
					$data_calif=$data_calif[0];
					$data_calif=$data_calif["id"];
				}else{
					$data_calif=$this->insert_data(array("idBoleta"=>$data_boleta, "idMateria"=>$idMateria, "idAlumno"=>$data_alumno["id"], "grado"=>$grado, "calificacion"=>0, "bimestre"=>$bim, "idUsuarioAlta"=>$this->session->userdata("id"), "date_start"=>date('Y-m-d H:i:s')),"calificaciones");
					if($data_calif===FALSE) return $alumnos;
				}

				if(intval($cualidad)>0){
					$data_calif_cual=$this->get_data("id","calificaciones_cualidades","idBoleta='$data_boleta' AND idCalificacion='$data_calif' AND idMateria='$idMateria' AND idAlumno='".$data_alumno["id"]."' AND idCualidad='$cualidad' AND bimestre='$bim'");
					if($data_calif_cual!==FALSE){
						$data_calif_cual=$data_calif_cual[0];
						$data_calif_cual=$data_calif_cual["id"];
					}else $data_calif_cual=0;
				}else $data_calif_cual=0;

				if(intval($cualidad_sub)>0){
					$data_calif_cual_sub=$this->get_data("id","calificaciones_cualidades_sub","idBoleta='$data_boleta' AND idCualidadSub='$cualidad_sub' AND bimestre='$bim'");
					if($data_calif_cual_sub!==FALSE){
						$data_calif_cual_sub=$data_calif_cual_sub[0];
						$data_calif_cual_sub=$data_calif_cual_sub["id"];
					}else $data_calif_cual_sub=0;
				}else $data_calif_cual_sub=0;

				$porcentaje=$elem->getCell($col.strval($i))->getCalculatedValue();
				if(strtolower(trim($porcentaje))=="np") $porcentaje=10;
				
				array_push($alumnos, array("idAlumno"=>$data_alumno["id"], "idBoleta"=>$data_boleta, "idCalificacion"=>$data_calif, "porcentaje"=>$porcentaje, "idCalificacionesCualidades"=>$data_calif_cual, "idCalificacionesCualidades_sub"=>$data_calif_cual_sub));
			}
		}
		return $alumnos;
	}

	private function get_all_alumnos_from_col_faltas($elem,$col,$grado,$bim){
		$highestRow=$elem->getHighestRow();
		$alumnos=array();
		for($i=$this->inicialRow;$i<=$highestRow;$i++){
			if(strlen($elem->getCell($this->idCol.strval($i)))===0) return $alumnos;
			$nombre=$elem->getCell($this->nombreCol.strval($i))->getValue();
			$find_id=array_search(array("nombre"=>$nombre), $this->alumnosContainerFaltas);
			if($find_id===FALSE){
				//$query="SELECT ppal.id, COALESCE((SELECT id FROM boletas WHERE idAlumno=ppal.id LIMIT 1),0) AS idBoleta FROM alumnos AS ppal WHERE ppal.nombre LIKE '%$nombre%' AND ppal.grado='$grado';";
				$query="SELECT ppal.id, bol.id AS idBoleta
						FROM alumnos AS ppal
						LEFT JOIN boletas AS bol ON ppal.id=bol.idAlumno
						WHERE ppal.nombre = '$nombre' AND ppal.grado='$grado';";
				$data_alumno=$this->get_data_from_query($query);
			}else $data_alumno=$this->alumnosDetailsFaltas[$find_id];

			if($data_alumno!==FALSE){
				if($find_id===FALSE) $data_alumno=$data_alumno[0];

				$data_faltas=$this->get_data("id", "faltas_boletas", "idAlumno='".$data_alumno["id"]."' AND bim='$bim'");
				if($data_faltas!==FALSE) $data_faltas_id=$data_faltas[0]["id"];
				else $data_faltas_id=0;

				$val=$elem->getCell($col.strval($i))->getCalculatedValue();

				if($find_id===FALSE){
					array_push($this->alumnosContainerFaltas, array("nombre"=>$nombre));
					array_push($this->alumnosDetailsFaltas, array("id"=>$data_alumno["id"]));
				}
				
				array_push($alumnos, array("idAlumno"=>$data_alumno["id"], "bim"=>$bim, "data_faltas_id"=>$data_faltas_id, "value"=>$val));
			}
		}
		return $alumnos;
	}

	private function construct_calif_cual($item,$data_materia,$data_cual,$grado,$bim){
		return array(
			"idBoleta"		=> $item["idBoleta"],
			"idCalificacion"=> $item["idCalificacion"],
			"idMateria"		=> $data_materia["id"],
			"idAlumno"		=> $item["idAlumno"],
			"idCualidad"	=> $data_cual,
			"grado"			=> $grado,
			"porcentaje"	=> strlen($item["porcentaje"])>0 ? $item["porcentaje"] : '0',
			"bimestre"		=> $bim,
			"idUsuarioAlta"	=> $this->session->userdata("id"),
			"date_start"	=> date('Y-m-d H:i:s')
		);
	}

	private function construct_calif_cual_sub($item,$data_materia,$data_cual,$grado,$bim){
		return array(
			"idBoleta"		=> $item["idBoleta"],
			"idCalificacion"=> $item["idCalificacion"],
			"idMateria"		=> $data_materia["id"],
			"idAlumno"		=> $item["idAlumno"],
			"idCualidadSub"	=> $data_cual,
			"grado"			=> $grado,
			"porcentaje"	=> strlen($item["porcentaje"])>0 ? $item["porcentaje"] : '0',
			"bimestre"		=> $bim,
			"idUsuarioAlta"	=> $this->session->userdata("id"),
			"date_start"	=> date('Y-m-d H:i:s')
		);
	}

	private function str_to_num($str){return PHPExcel_Cell::columnIndexFromString($str);}
	private function num_to_str($num){return PHPExcel_Cell::stringFromColumnIndex($num);}

	private function get_self(){return $this;}
	private function insert_data($data,$table){ return $this->mdllogin->insertData($data,$table);}
	private function get_data($select="",$from="",$where="",$order="",$group="",$limit=""){ return $this->mdllogin->getData($select,$from,$where,$order,$group,$limit);}
	private function get_data_from_query($query){return $this->mdllogin->getDataFromQuery($query);}
	private function edit_data($data,$table,$id,$idName,$where=";"){return $this->mdllogin->editData($data,$table,$id,$idName,$where);}

}

?>