	<?php
		$colors=array(1=>"primary",2=>"success",3=>"info",4=>"danger",5=>"warning");
		$permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'");
		$data_boleta=$controller->get_data("*","boletas","idAlumno='$idAlumno'");
		$data_alumno=$controller->get_data("*","alumnos","id='$idAlumno'");
		$data_alumno=$data_alumno[0];
		$query="SELECT ppal.*, det.is_part_avr FROM materias AS ppal INNER JOIN bloques AS det ON ppal.idBloque=det.id WHERE ppal.active=1 AND (has_calif=1 OR has_nivel=1) AND ppal.grado='".$data_alumno["grado"]."'";
		if($permisos!==FALSE){
			$ss=TRUE;
			foreach($permisos AS $per=>$permiso){
				if(intval($permiso["idMateria"])>0){
					if($ss){
						$query.=" AND ppal.id IN (SELECT DISTINCT idMateria FROM permisos_boletas WHERE idUsuario = '".$this->session->userdata("id")."') ";
						$ss=!$ss;
					}
				}
			}
			$query.=" ORDER BY ppal.idBloque, ppal.orden ";
		}
		$materias=$controller->get_data_from_query($query);
		if($data_boleta===FALSE){
			$temp=array(
				"idAlumno"		=> $idAlumno,
				"grado"			=> $data_alumno["grado"],
				"idUsuarioAlta"	=> $this->session->userdata('id'),
				"date_start"	=> date('Y-m-d H:i:s')
			);
			$id_temp=$controller->insert_data($temp,"boletas");
			$data_boleta=$controller->get_data("*","boletas","id='$id_temp'");
			$data_boleta=$data_boleta[0];
		}else $data_boleta=$data_boleta[0];

		$query_temp="SELECT * FROM (SELECT * FROM calificaciones ORDER BY id DESC) AS t where idBoleta='".$data_boleta["id"]."' GROUP BY idMateria, bimestre, t.id";
		$data_calif=$controller->get_data_from_query($query_temp);
	?>
	<style>
		.calificacion_continua{color:#000;margin-right:8px;width:60px;}#eval{text-align: center;margin-bottom: 30px;}
		.btn{white-space:unset;}
		.cerrar_logros{color:#fff;float: right;}
	</style>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/default.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/component.css" />
	<script src="<?php echo base_url(); ?>js/modernizr.custom.js"></script>
	<div class="md-modal md-effect-1" id="modal-1">
		<div class="md-content">
			<h3>Editar / ver aspectos<a class='btn cerrar_logros' href='javascript:;'><i class="fa fa-times" aria-hidden="true"></i></a></h3>
			<div style="max-height: 500px;overflow-y: scroll;">
				<ul id="logros_ul" style="list-style: none;"></ul>
				<div id="eval"></div>
				<button class="md-close">Guardar aspectos</button>
			</div>
		</div>
	</div>
	<div class="md-overlay"></div>
	<input type="hidden" id="idBoleta" value="<?php echo $data_boleta['id']; ?>">
	<input type="hidden" id="idAlumno" value="<?php echo $data_boleta['idAlumno']; ?>">
	<input type="hidden" id="grado" value="<?php echo $data_alumno['grado']; ?>">
	<input type="hidden" id="grupo" value="<?php echo $data_alumno['grupo']; ?>">
	<div class="content-wrapper" id="main_content">
		<section class="content-header">
			<h1>Agregar/editar boleta <small><?php echo $data_alumno["nombre"]; ?></small></h1>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-md-5" data-role="content" data-theme="c">
					<div class="form-group">
						<label>Filtrar por grados</label>
						<?php
							$query=$controller->get_query_construct($permisos,0);
							$data=$controller->get_data_from_query($query);
						?>
						<select class="form-control" data-grupoid="grupos" id="grados">
							<option selected value="0">Todos los grados</option>
							<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
							<option value="<?php echo $key['grado']; ?>"><?php echo $key["grado"]; ?></option>
							<?php }} ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5" data-role="content" data-theme="c">
					<div class="form-group">
						<label>Filtrar por grupos</label>
						<?php
							$query=$controller->get_query_construct($permisos,1);
							$data=$controller->get_data_from_query($query);
						?>
						<select class="form-control" id="grupos">
							<option selected data-grado="0" value="0">Todos los grupos</option>
							<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
							<option data-grado="<?php echo $key["grado"]; ?>" value="<?php echo $key['grupo']; ?>"><?php echo $key["grado"]." - ".$key["grupo"]; ?></option>
							<?php }} ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5" data-role="content" data-theme="c">
					<div class="form-group">
						<label>Alumnos</label>
						<?php
							$query=$controller->get_query_construct($permisos,2);
							$data=$controller->get_data_from_query($query);
						?>
						<select class="form-control" id="alumnos">
							<option selected data-grado="0" value="0">Seleccione un alumno</option>
							<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
							<option data-grado="<?php echo $key['grado']; ?>" data-grupo="<?php echo $key['grupo']; ?>" <?php echo intval($key["id"])===intval($idAlumno) ? "selected" : ""; ?> value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]." - ".$key["grado"]." - ".$key["grupo"]; ?></option>
							<?php }} ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div id="wait" style="display:none;" class="box box-warning box-solid">
						<div class="box-header"><h3 class="box-title title">Guardando</h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool close_wait"><i class="fa fa-close"></i></button></div></div>
						<div class="box-body body">Por favor espere</div>
						<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
					</div>
				</div>
			</div>
			<div class="row"><div class="col-md-10"><div class="form-group"><button type="button" id="send_calif" class="btn btn-block btn-primary">Guardar calificaciones</button></div></div></div>
			<div class="row">
				<?php for($i=2; $i<3; $i++){ // ***************************** CHANGE BIMESTRE HERE ?>
				<div class="col-md-2">
					<div class="box box-<?php echo $colors[$i]; ?>">
						<div class="box-header with-border"><h3 class="box-title">Trimestre <?php echo $i; ?></h3></div>
						<form role="form">
							<div class="box-body">
								<?php if($materias!==FALSE) foreach($materias AS $e=>$key){
									$value_temp="";
									$value_nivel="";
									$id_temp=0;
									if($data_calif!==FALSE){
										foreach($data_calif AS $c=>$calif){
											if(intval($calif["bimestre"])===intval($i) && intval($calif["idMateria"])===intval($key["id"])){
												$id_temp=$calif["id"];
												$value_temp=$calif["calificacion"];
												$value_nivel=$calif["nivel"];
												if(strlen($value_temp)===0) $value_temp=$calif["calificacion_letra"];
												else{if(intval($value_temp)<5) $value_temp=5; $value_temp=round($value_temp,1);}
											}
										}
									} ?>
								<div class="form-group calificaciones_container" data-calif="<?php echo $key["has_calif"]; ?>" data-nivel="<?php echo $key["has_nivel"]; ?>" data-id="<?php echo $id_temp; ?>" data-bim="<?php echo $i; ?>" data-materia="<?php echo $key['id']; ?>">
									<label><?php echo $key["nombre"]; ?></label>
									<?php
										$logros_temp=$logros_eval=$logros_temp_sub="";
										$data_logros=$controller->get_data_from_query("SELECT GROUP_CONCAT(idCualidad,'@',porcentaje) AS logros from calificaciones_cualidades WHERE bimestre='$i' AND idAlumno='$idAlumno' AND idMateria='".$key["id"]."' ;");
										if($data_logros!==FALSE) $logros_temp=$data_logros[0]["logros"];
										$data_logros_sub=$controller->get_data_from_query("SELECT GROUP_CONCAT(idCualidadSub,'@',porcentaje) AS logros_sub from calificaciones_cualidades_sub WHERE bimestre='$i' AND idAlumno='$idAlumno' AND idMateria='".$key["id"]."' ;");
										if($data_logros_sub!==FALSE) $logros_temp_sub=$data_logros_sub[0]["logros_sub"];
										//$data_eval=$controller->get_data_from_query("SELECT GROUP_CONCAT(tipo,'@',calificacion) AS eval FROM evaluacion_continua WHERE idAlumno='$idAlumno' AND idMateria='".$key["id"]."' AND bimestre='$i'");
										//if($data_eval!==FALSE) $logros_eval=$data_eval[0]["eval"];

										if(intval($key["has_calif"])>0){
									?>
									<input type="text" class="form-control calificacion <?php echo intval($key['is_complex'])>0 ? 'just_read_or_not' : ''; ?>" data-iscomplex="<?php echo $key['is_complex']; ?>" data-grado="<?php echo $data_alumno['grupo']; ?>" data-id="<?php echo $id_temp; ?>" data-bim="<?php echo $i; ?>" data-bloque="<?php echo $key['idBloque']; ?>" data-materia="<?php echo $key['id']; ?>" id="calificacion-<?php echo $key['id']; ?>-<?php echo $i; ?>" data-logros="<?php echo $logros_temp; ?>" data-subaspectos="<?php echo $logros_temp_sub; ?>" <?php /*data-eval="<?php echo $logros_eval; ?>"*/ ?> placeholder="Calificación" value="<?php echo $value_temp; ?>">
									<?php if(intval($key['is_complex'])>0){ ?>
									<button type="button" class="btn btn-block btn-<?php echo $colors[$i]; ?> btn-sm md-trigger" data-modal="modal-1" data-bim="<?php echo $i; ?>" data-bloque="<?php echo $key['idBloque']; ?>" data-materia="<?php echo $key['id']; ?>">Editar / ver detalles</button>
									<?php }}if(intval($key["has_nivel"])>0){ ?>
									<select class="form-control nivel_aprendizaje" id="nivel-<?php echo $key['id']; ?>-<?php echo $i; ?>">
										<option value="">Seleccionar Nivel de Aprendizaje</option>
										<option <?php echo $value_nivel=='I' ? 'selected' : ''; ?> value="I">Nivel I</option>
										<option <?php echo $value_nivel=='II' ? 'selected' : ''; ?> value="II">Nivel II</option>
										<option <?php echo $value_nivel=='III' ? 'selected' : ''; ?> value="III">Nivel III</option>
										<option <?php echo $value_nivel=='IV' ? 'selected' : ''; ?> value="IV">Nivel IV</option>
									</select>
									<?php } ?>
								</div>
								<?php } ?>
							</div>
						</form>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-5">
					<?php for($i=0;$i<3;$i++){ ?>
					<div class="form-group">
						<label>Comentarios T<?php echo ($i+1); ?></label>
						<?php $data_coment=$controller->get_data("id,nombre","comentarios_cat","idioma='es'","nombre"); ?>
						<select class="form-control" id="comentarios_<?php echo ($i+1); ?>">
							<option value="0">Selecciona un comentario</option>
							<?php if($data_coment!==FALSE){ foreach($data_coment AS $e => $key){ ?>
							<option <?php echo intval($key['id'])===intval($data_boleta['idComentario']) ? 'selected' : ''; ?> value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></option>
							<?php }} ?>
						</select>
					</div>
					<?php } ?>
					<div class="form-group">
						<label>Faltas</label>
						<input type="number" step="1" min="0" max="100" style="width:20%" id="faltas" class="form-control">
					</div>
				</div>
			</div>
		</section>
	</div>
