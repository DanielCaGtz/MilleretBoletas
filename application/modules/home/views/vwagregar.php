		<?php $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'"); ?>
		<style>
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
			#sortable li { cursor:move; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
			#sortable li span { position: absolute; margin-left: -1.3em; }
		</style>
		<div class="content-wrapper">
			<section class="content-header"><h1>Agregar boletas</h1></section>
			<form action="" method="post" id="form_container" name="form_container" role="form" enctype="multipart/form-data">
				<section class="content">
					<div class="callout callout-info">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						<h4>Aquí puedes editar y agregar boletas de los alumnos</h4>
						Selecciona el alumno del cual quieras ver su boleta y luego da click en el botón Editar.
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
					<div class="row toggle_this">
						<div class="col-md-5" data-role="content" data-theme="c">
							<div class="form-group">
	      						<label>Alumnos</label>
								<?php
									$query=$controller->get_query_construct($permisos,2);
									$data=$controller->get_data_from_query($query);
								?>
								<select class="form-control" id="alumnos">
									<option data-grado="0" selected value="0">Selecciona un alumno</option>
									<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
									<option data-grado="<?php echo $key['grado']; ?>" data-grupo="<?php echo $key['grupo']; ?>" value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]." - ".$key["grado"]." - ".$key["grupo"]; ?></option>
									<?php }} ?>
								</select>
							</div>
						</div>
					</div>
					<?php if(HAS_FILE_UPLOAD){ ?>
					<div class="row toggle_this" style="display: none;">
						<div class="col-md-5" data-role="content" data-theme="c">
							<div class="form-group">
								<label><?php echo ucfirst(BIMESTRE_NAME); ?></label>
								<select class="form-control" id="bimestre">
									<?php for($i=1;$i<4;$i++){ ?>
									<option value="<?php echo $i; ?>"><?php echo ucfirst(BIMESTRE_NAME)." - ".$i; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row toggle_this" style="display: none;">
						<div class="col-md-5" data-role="content" data-theme="c">
							<div class="form-group">
								<label>Elegir archivo de calificaciones</label>
								<input type="file" name="file-5[]" id="file-5" class="form-control inputfile inputfile-4" data-multiple-caption="{count} archivos seleccionados" />
								<p class="help-block" style="display:none;"><img src="<?php echo base_url(); ?>img/loader.GIF"></p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<br><button type="button" id="consulta_alumno" class="btn btn-block btn-success btn-lg toggle_this">Editar</button>
							<button type="submit" id="cargar_archivo" class="btn btn-block btn-warning btn-lg toggle_this" style="display: none;">Cargar</button>
						</div>
						<div class="box-body">
							<div class="form-group">
								<a href="javascript:;" id="swap_method" style="font-size: 22px;"><i class="fa fa-exchange"></i> <span>Cambiar a archivo</span><span style="display: none;">Editar manualmente</span></a>
							</div>
						</div>
					</div>
					<?php }else{ ?>
					<div class="row">
						<div class="col-md-2">
							<br><button type="button" id="consulta_alumno" class="btn btn-block btn-success btn-lg">Editar</button>
						</div>
					</div>
					<?php } ?>
				</section>
			</form>
		</div>
