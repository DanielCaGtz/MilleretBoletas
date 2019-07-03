<?php

	class checkSession{

		function check(){
			$ci =& get_instance();
			$ci->load->model("login/mdllogin");
			if($ci->session->userdata("id")){
				if(intval($ci->session->userdata("idPermisos"))===0){
					$idGrupo = $ci->session->userdata("idGrupo");
					$data_grupo=$ci->mdllogin->getDataFromQuery("SELECT * FROM grupos WHERE id='$idGrupo'");
					if($data_grupo!==FALSE){
						if($data_grupo[0]["date_from"]==date('Y-m-d')){
							$temp_pre=$ci->mdllogin->getDataFromQuery("SELECT * FROM log_historial WHERE idUsuario='".$ci->session->userdata("id")."' AND pre_post=0 AND idCuestionario=(SELECT id FROM cuestionarios WHERE clave='".$ci->session->userdata("clave")."') AND date_end IS NULL AND id IN (SELECT DISTINCT idLog FROM historial_preguntas); ");
							if($temp_pre!==FALSE){
								$date1 = new DateTime($temp_pre[0]["date_start"]);
								$date2 = new DateTime(date('Y-m-d H:i:s'));
								$diff = $date2->diff($date1);
								$hours = $diff->h;
								$hours = $hours + ($diff->days*24);
								if(intval($hours)>2){
									$this->closesession();
								}
							}
						}elseif($data_grupo[0]["date_to"]==date('Y-m-d')){
							if(intval(date('H'))>18){
								$this->closesession();
							}
						}else{
							$this->closesession();
						}
					}
				}
			}
		}

		private function closesession(){
			$ci =& get_instance();
			$ci->session->sess_destroy();
			redirect(base_url());
		}

	}

?>