		<?php $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'"); ?>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Detalles de reporte</h1>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-5" data-role="content" data-theme="c">
						<div class="form-group">
							<label>Trimestre</label>
							<select class="form-control" id="bimestres">
								<?php for($i=1;$i<intval(BIMESTRE_COUNT)+1;$i++){ ?>
								<option value="<?php echo $i; ?>"><?php echo strtoupper(BIMESTRE_NAME)." ".$i; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Grado</label>
							<?php
								$query=$controller->get_query_construct($permisos,0);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" data-grupoid="grupos" id="grados">
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['grado']; ?>"><?php echo $key["grado"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Grupo</label>
							<?php
								$query=$controller->get_query_construct($permisos,1);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="grupos">
								<option selected data-grado="0" value="0">Seleccionar un grupo</option>
								<?php $xc=TRUE; if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option data-grado="<?php echo $key["grado"]; ?>" value="<?php echo $key['grupo']; ?>"><?php echo $key["grado"]." - ".$key["grupo"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Alumno para esfuerzo</label>
							<?php
								$query=$controller->get_query_construct($permisos,2);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="alumnos">
								<option data-grado="0" data-grupo="0" selected value="0">Selecciona un alumno</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option data-grado="<?php echo $key['grado']; ?>" data-grupo="<?php echo $key['grupo']; ?>" value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]." - ".$key["grado"]." - ".$key["grupo"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Director técnico</label>
							<?php
								$query=$controller->get_query_construct($permisos,4,"","","",FALSE);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="director">
								<option selected value="0">Selecciona un profesor</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Maestro de grupo</label>
							<?php
								$query=$controller->get_query_construct($permisos,4,"","","",FALSE);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="maestro">
								<option selected value="0">Selecciona un profesor</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Teacher's name</label>
							<?php
								$query=$controller->get_query_construct($permisos,4,"","","",FALSE);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="teacher">
								<option selected value="0">Selecciona un profesor</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></option>
								<?php }} ?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div id="wait" style="display:none;" class="box box-warning box-solid">
							<div class="box-header">
								<h3 class="box-title title">Guardando</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool close_wait"><i class="fa fa-close"></i></button>
								</div>
							</div>
							<div class="box-body body">
								Por favor espere
							</div>
							<div class="overlay">
								<i class="fa fa-refresh fa-spin"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2"><br><button type="button" id="save_esfuerzo" class="btn btn-block btn-success btn-lg">Guardar</button></div>
				</div>
			</section>
		</div>
