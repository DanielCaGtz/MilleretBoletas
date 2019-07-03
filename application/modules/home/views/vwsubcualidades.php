		<?php $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'"); ?>
		<style>
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
			#sortable li { cursor:move; margin: 0 3px 25px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
			#sortable li span { position: absolute; margin-left: -1.3em; }
		</style>
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Sub-Aspectos</h1>
			</section>
			<section class="content">
				<div class="callout callout-info">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4>Aquí puedes editar los subaspectos a evaluar para cada asignatura</h4>
					Selecciona la materia que quieras editar. Puedes arrastrar cada subaspecto en el orden que quieras, así como agregar o eliminar nuevos.
				</div>
				<div class="row">
					<div class="col-md-5" data-role="content" data-theme="c">
						<div class="form-group">
							<label><?php echo ucfirst(BIMESTRE_NAME); ?></label>
							<select class="form-control" id="bimestres">
								<?php for ($i = 1; $i < (intval(BIMESTRE_COUNT) + 1); $i++) { ?>
								<option <?php echo (isset($_GET["bimestre"]) && intval($_GET["bimestre"]) === $i) ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo strtoupper(BIMESTRE_NAME)." ".$i; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Filtrar por grados</label>
							<?php
								$query=$controller->get_query_construct($permisos,0);
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
							<label>Materias</label>
							<?php
								$query=$controller->get_query_construct($permisos,5,"","","",TRUE,FALSE,"idMateria");
								$data=$controller->get_data_from_query($query);
							?>
							<select class="form-control" id="materias">
								<option data-grado="0" selected value="0">Selecciona una materia</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option data-grado="<?php echo $key['grado']; ?>" value="<?php echo $key['id']; ?>"><?php echo $key["grado"]." - ".$key["nombre"]; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Aspectos</label>
							<select class="form-control" id="aspectos">
								<option data-grado="0" selected value="0">Selecciona un aspecto</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" data-role="content" data-theme="c"><ul id="sortable" data-role="listview" data-inset="true" data-theme="d"></ul></div>
					<div class="col-md-2">
						<div class="box-body body">
							<a class="btn btn-app" id="add"><i class="fa fa-plus"></i> Agregar</a>
							<a class="btn btn-app" id="save" data-unsaved="0"><i class="fa fa-save"></i> Guardar</a>
							<a class="btn btn-app" id="repetir"><i class="fa fa-bars"></i> Repetir <?php echo BIMESTRE_NAME; ?> seleccionado</a>
							<select class="form-control" id="bimestre_a_repetir">
								<option value="" selected>Seleccionar bimestre a repetir</option>
								<?php for($i = 1; $i < (intval(BIMESTRE_COUNT) + 1); $i++) { ?>
								<option value="<?php echo $i; ?>"><?php echo strtoupper(BIMESTRE_NAME)." ".$i; ?></option>
								<?php } ?>
							</select>
							<img style="display:none;" id="loader" src="<?php echo base_url(); ?>img/loader.GIF">
						</div>
					</div>
					<div class="col-md-3">
						<div id="wait" style="display:none;" class="box box-warning box-solid">
							<div class="box-header">
								<h3 class="box-title title">Guardando</h3>
								<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool close_wait"><i class="fa fa-close"></i></button></div>
							</div>
							<div class="box-body body">Por favor espere</div>
							<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
						</div>
					</div>
				</div>
			</section>
		</div>
