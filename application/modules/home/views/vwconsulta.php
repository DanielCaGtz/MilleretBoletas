		<?php $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'"); ?>
		<style>
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
			#sortable li { cursor:move; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
			#sortable li span { position: absolute; margin-left: -1.3em; }
			.btn-block{white-space:unset;}
		</style>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Consulta de boletas</h1>
			</section>
			<section class="content">
				<div class="callout callout-info">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4>Aquí puedes seleccionar las boletas de los alumnos</h4>
					Selecciona el alumno del cual quieras ver su boleta y luego da click en el botón Consultar.
				</div>
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
								<option data-grado="0" data-grupo="0" selected value="0">Selecciona un alumno</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option data-grado="<?php echo $key['grado']; ?>" data-grupo="<?php echo $key['grupo']; ?>" value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]." - ".$key["grado"]." - ".$key["grupo"]; ?></option>
								<?php }} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2"><br><button type="button" id="consulta_alumno" class="btn btn-block btn-success btn-lg">Consultar</button></div>
					<?php echo $controller->get_module('PDF_DOWNLOAD'); ?>
					<?php /*
					<div class="col-md-2"><br><button type="button" id="reporte_completo" class="btn btn-block btn-primary btn-lg">Reporte</button></div>
					<div class="col-md-2"><br><button type="button" id="reporte_individual" class="btn btn-block btn-warning btn-lg">Reporte individual</button></div>
					*/ ?>
				</div>
				<div class="row"><div class="col-md-2"><br><img id="loader_img" style="display:none;" src="<?php echo base_url(); ?>img/loader.GIF"></div></div>
				<div class="row" style="margin-top:50px;">
					<div class="col-md-5" data-role="content" data-theme="c">
						<div class="form-group">
							<label>Grados</label>
							<?php
								$query=$controller->get_query_construct($permisos,0);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" data-grupoid="grupos2" id="grados2">
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['grado']; ?>"><?php echo $key["grado"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Grupos</label>
							<?php
								$query=$controller->get_query_construct($permisos,1);
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="grupos2">
								<?php $xc=TRUE; if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option selected data-grado="<?php echo $key["grado"]; ?>" value="<?php echo $key['grupo']; ?>"><?php echo $key["grado"]." - ".$key["grupo"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Trimestres</label>
							<select class="form-control" id="bimestres">
								<?php for($i=1;$i<intval(BIMESTRE_COUNT)+1;$i++){ ?>
								<option value="<?php echo $i; ?>"><?php echo strtoupper(BIMESTRE_NAME)." ".$i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2"><br><button type="button" id="reporte_full" class="btn btn-block btn-success btn-lg">Reporte completo</button></div>
					<?php /*
					<div class="col-md-2"><br><button type="button" id="reporte_sencillo" class="btn btn-block btn-primary btn-lg">Reporte sencillo</button></div>
					*/ ?>
				</div>
				<div class="row"><div class="col-md-2"><br><img id="loader_img_2" style="display:none;" src="<?php echo base_url(); ?>img/loader.GIF"></div></div>
				<div id="create_here"></div>
			</section>
		</div>
