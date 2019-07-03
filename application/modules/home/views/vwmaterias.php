		<?php $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'"); ?>
		<style>
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
			#sortable li { cursor:move; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
			#sortable li span { position: absolute; margin-left: -1.3em; }
		</style>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Menú</h1>
			</section>
			<section class="content">
				<div class="callout callout-info">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4>Aquí puedes editar las materias de cada grado</h4>
					Puedes arrastrar cada materia en el orden que quieras, así como agregar o eliminar nuevas.
				</div>
				<div class="row">
					<div class="col-md-5" data-role="content" data-theme="c">
						<div class="form-group">
							<label>Materias por grados</label>
							<?php
								if(intval($controller->check_permisos("allow_admin"))>0) $query="SELECT grado FROM alumnos GROUP BY grado ORDER BY grado";
								else{
									if($permisos!==FALSE){
										$query="SELECT grado FROM alumnos WHERE id>0 ";
										$s=TRUE;
										$ss=FALSE;
										foreach($permisos AS $e=>$key){
											if($s){
												$s=!$s;
												$query.=" AND (";
											}
											if(strlen($key["grado"])>0){
												if($ss){
													$ss=!$ss;
													$query.=" OR ";
												}
												$query.=" grado LIKE '%".$key["grado"]."%' ";
												$ss=!$ss;
											}
										}
										if(!$s) $query.=") GROUP BY grado ORDER BY grado";
									}else $query="SELECT grado FROM alumnos GROUP BY grado ORDER BY grado";
								}
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="grados">
								<option selected value="0">Todos los grados</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['grado']; ?>"><?php echo $key["grado"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Bloques de materias</label>
							<?php $data=$controller->get_data("*","bloques","active=1","nombre"); ?>
							<select class="form-control" id="bloques">
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option data-complex="<?php echo $key['is_complex']; ?>" value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></option>
								<?php }} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5" data-role="content" data-theme="c"><ul id="sortable" data-role="listview" data-inset="true" data-theme="d"></ul></div>
					<div class="col-md-2">
						<div class="box-body">
							<a class="btn btn-app" id="add"><i class="fa fa-plus"></i> Agregar</a>
							<a class="btn btn-app" id="save" data-unsaved="0"><i class="fa fa-save"></i> Guardar</a>
							<img style="display:none;" id="loader" src="<?php echo base_url(); ?>img/loader.GIF">
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
			</section>
		</div>
