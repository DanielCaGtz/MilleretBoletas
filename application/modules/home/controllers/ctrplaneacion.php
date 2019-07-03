<?php

class ctrPlaneacion extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		$this->load->model('login/mdllogin1718');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		date_default_timezone_set('America/Mexico_City');
	}

	public function get_new_alumno_row(){
		$x=0;
		$return='	<tr class="second_container">
						<td>
							<select class="form-control get_raw_data" data-name="alumnos_id" data-type="select">';
								foreach($this->get_data_school("id,nombre",DATABASE_2.".alumnos","grado IN (SELECT grado FROM ".DATABASE_1.".permisos_boletas WHERE idUsuario='".$this->session->userdata('id')."' GROUP BY grado)","nombre") AS $e=>$key){
								$return.='<option value="'.$key['id'].'">'.$key['nombre'].'</option>';
								}
		$return.='			</select>
						</td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_'.$x++.'" value="1"></label></div></td>
						<td>
							<button type="button" class="btn btn-box-tool"><i class="fa fa-trash remove_alumno"></i></button>
						</td>
					</tr>';
		print $return;
	}

	public function get_new_secuencia_row(){
		$elem=array("Inicio","Desarrollo","Cierre");
		$return='	<tr class="second_container">
						<td>';
							for($j=0;$j<3;$j++){
		$return.='			<div><label>'.$elem[$j].'</label> <textarea class="form-control get_raw_data" data-id="'.$j.'" data-type="text" data-name="secuencia"></textarea></div><br>';
							}
		$return.='		</td>
						<td>';
							for($j=0;$j<3;$j++){
		$return.='			<div><label>'.$elem[$j].'</label> <input type="text" class="form-control get_raw_data" data-id="'.$j.'" data-type="text" data-name="tiempo"></div><br>';
							}
		$return.='		</td>
						<td>
							<table class="table table-bordered" style="background-color:transparent !important;">
								<tr>
									<th></th>
									<th>Libro SEP</th>
									<th>Libro de ASC</th>
									<th>Cuaderno</th>
									<th>Apoyos visuales</th>
									<th>TICs</th>
									<th>Otro</th>
								</tr>';
								for($j=0;$j<3;$j++){
		$return.='				<tr>
									<td><b>'.$elem[$j].'</b></td>';
									for($i=0;$i<6;$i++){
		$return.='					<td><div class="checkbox"><label><input type="checkbox" value="1" class="get_raw_data" data-id="'.$j.'" data-type="checkbox" data-name="recursos_'.($i+1).'"></label></div></td>';
									}
		$return.='				</tr>';
								}
		$return.='			</table>
						</td>
						<td><button type="button" class="btn btn-box-tool"><i class="fa fa-trash remove_secuencia"></i></button></td>
					</tr>';
		print $return;
	}

	public function save_planeacion_KIN(){
		$main_data=$this->security->xss_clean($this->input->post());
		$alumnos=json_decode($main_data["alumnos"]);
		unset($main_data["alumnos"]);
		$main_data["usuarios_boletas_id"]=$this->session->userdata("id");
		$main_data["date_start"]=date('Y-m-d H:i:s');
		//try{
			$planeaciones_id=$this->insert_data($main_data,"planeaciones");
		//}catch(UserException $e){
			//print_r($e);
		//}
		//print_r($alumnos);
		foreach($alumnos AS $e=>$key){
			$data_temp=array();
			$x=0;
			$data_temp["alumnos_id"]=$key[$x++];
			for($i=0;$i<7;$i++)
				$data_temp["opcion_".$x]=$key[$x++];
			$data_temp["usuarios_boletas_id"]=$this->session->userdata("id");
			$data_temp["planeaciones_id"]=$planeaciones_id;
			//print_r($data_temp);
			$this->insert_data($data_temp,"alumnos_seguimiento");
		}

		print json_encode(array("success"=>TRUE));
	}

	public function save_planeacion_PRI(){
		$main_data=$this->security->xss_clean($this->input->post());
		$alumnos=json_decode($main_data["alumnos"]);
		$secuencias=json_decode($main_data["secuencias"]);
		unset($main_data["alumnos"]);
		unset($main_data["secuencias"]);

		$sec_array=array("secuencia","tiempo","recursos_1","recursos_2","recursos_3","recursos_4","recursos_5","recursos_6");
		$secuencias_totales=array();
		foreach($secuencias AS $e=>$key){
			foreach($key AS $c=>$child){
				$secuencias_totales[$child[3]][$child[0]][$child[2]]=$child[1];
			}
		}

		$main_data["usuarios_boletas_id"]=$this->session->userdata("id");
		$main_data["date_start"]=date('Y-m-d H:i:s');
		$planeaciones_id=$this->insert_data($main_data,"planeaciones");
		if(!empty($secuencias_totales)){
			foreach($secuencias_totales AS $e => $key){
				foreach($key AS $r=>$rows){
					$secuencias_totales[$e][$r]["tipo"]=$r;
					$secuencias_totales[$e][$r]["row"]=$e;
					$secuencias_totales[$e][$r]["planeaciones_id"]=$planeaciones_id;
					$this->insert_data($secuencias_totales[$e][$r],"secuencias_didacticas");
				}
			}
		}
		if(!empty($alumnos)){
			foreach($alumnos AS $e=>$key){
				$data_temp=array();
				$x=0;
				$data_temp["alumnos_id"]=$key[$x++];
				for($i=0;$i<7;$i++)
					$data_temp["opcion_".$x]=$key[$x++];
				$data_temp["usuarios_boletas_id"]=$this->session->userdata("id");
				$data_temp["planeaciones_id"]=$planeaciones_id;
				$this->insert_data($data_temp,"alumnos_seguimiento");
			}
		}

		print json_encode(array("success"=>TRUE));
	}

	public function save_planeacion_SEC(){
		$main_data=$this->security->xss_clean($this->input->post());
		$alumnos=json_decode($main_data["alumnos"]);
		$secuencias=json_decode($main_data["secuencias"]);
		unset($main_data["alumnos"]);
		unset($main_data["secuencias"]);

		$sec_array=array("secuencia","tiempo","recursos_1","recursos_2","recursos_3","recursos_4","recursos_5","recursos_6");
		$secuencias_totales=array();
		foreach($secuencias AS $e=>$key){
			foreach($key AS $c=>$child){
				$secuencias_totales[$child[3]][$child[0]][$child[2]]=$child[1];
			}
		}
		//*
		$main_data["usuarios_boletas_id"]=$this->session->userdata("id");
		$main_data["date_start"]=date('Y-m-d H:i:s');
		$planeaciones_id=$this->insert_data($main_data,"planeaciones");
		if(!empty($secuencias_totales)){
			foreach($secuencias_totales AS $e => $key){
				#print_r($key);
				foreach($key AS $r=>$rows){
					$secuencias_totales[$e][$r]["tipo"]=$r;
					$secuencias_totales[$e][$r]["row"]=$e;
					$secuencias_totales[$e][$r]["planeaciones_id"]=$planeaciones_id;
					$this->insert_data($secuencias_totales[$e][$r],"secuencias_didacticas");
					#print_r($secuencias_totales[$e][$r]);
					#echo "<br><br>";
				}
				#echo "<br><br>";
			}
		}
		if(!empty($alumnos)){
			foreach($alumnos AS $e=>$key){
				$data_temp=array();
				$x=0;
				$data_temp["alumnos_id"]=$key[$x++];
				for($i=0;$i<7;$i++)
					$data_temp["opcion_".$x]=$key[$x++];
				$data_temp["usuarios_boletas_id"]=$this->session->userdata("id");
				$data_temp["planeaciones_id"]=$planeaciones_id;
				$this->insert_data($data_temp,"alumnos_seguimiento");
			}
		}
		/* */

		print json_encode(array("success"=>TRUE));
	}

	public function save_comentarios_direccion(){
		$com=$this->security->xss_clean($this->input->post("com"));
		$id=$this->security->xss_clean($this->input->post("id"));

		$this->edit_data(array("comentarios"=>$com),"planeaciones",$id,"id");
		print json_encode(array("success"=>TRUE));
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
	public function get_data_school($select="",$from="",$where="",$order="",$group="",$limit=""){return $this->mdllogin->getData_School($select,$from,$where,$order,$group,$limit);}

}

?>