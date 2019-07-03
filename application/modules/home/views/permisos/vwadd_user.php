	<?php $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'"); ?>
	<style>
		.change_status{cursor:pointer;}
		fieldset{border-radius: 5px;padding: 15px;margin: 10px;background: #cfecfd;border: 1px solid #d4d0d0;}
	</style>
	<link href="<?php echo base_url();?>plugins/select2/dist/css/select2.css" rel="stylesheet" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Agregar <small>usuario</small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Agregar usuario</li>
			</ol>
		</section>

		<section class="content" id="main_content" data-id="0">
			<div class="row">
				<div class="col-md-2">
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
			<div class="row"><div class="col-md-4"><div class="form-group"><button type="button" id="send_calif" class="btn btn-block bg-blue">Guardar</button></div></div></div>
			<div class="row">
				<div class="col-lg-4 col-xs-4">
					<div class="box bg-blue box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">Datos principales</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
						</div>
						<form role="form" class="form-contact" id="main_form" style="background: #eaeaea;color: black;">
							<div class="box-body">
								<div class="form-group">
									<?php $d="nombre"; ?><label for="field_<?php echo $d; ?>">Nombre completo</label>
									<input type="text" data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="field_<?php echo $d; ?>" required placeholder="<?php echo ucfirst($d); ?>">
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="username"; ?><label for="field_<?php echo $d; ?>">Nombre de usuario</label>
									<input type="text" data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="field_<?php echo $d; ?>" required placeholder="<?php echo ucfirst($d); ?>">
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="pwd"; ?><label for="field_<?php echo $d; ?>">Contraseña</label>
									<input type="text" data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="field_<?php echo $d; ?>" required placeholder="Contraseña">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-xs-4">
					<div class="box bg-blue box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">Permisos generales</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
						</div>
						<form role="form" class="form-contact" id="main_form2" style="background: #eaeaea;color: black;">
							<div class="box-body">
								<div class="form-group">
									<label>Seleccionar permisos</label>
									<?php $d="allow_edit"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" checked class="campo_editable_main" id="field_<?php echo $d; ?>"> Editar habilidades</label></div>
									<?php $d="allow_materias"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" checked class="campo_editable_main" id="field_<?php echo $d; ?>"> Editar materias</label></div>
									<?php $d="allow_add"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" checked class="campo_editable_main" id="field_<?php echo $d; ?>"> Registrar/ver calificaciones</label></div>
									<?php $d="allow_view"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" checked class="campo_editable_main" id="field_<?php echo $d; ?>"> Consultar boletas</label></div>
									<?php $d="allow_publish"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" class="campo_editable_main" id="field_<?php echo $d; ?>"> Publicar calificaciones</label></div>
									<?php $d="allow_admin_users"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" class="campo_editable_main" id="field_<?php echo $d; ?>"> Editar usuarios</label></div>
									<?php $d="allow_reporte_diario_add"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" class="campo_editable_main" id="field_<?php echo $d; ?>"> Agregar reporte diario</label></div>
									<?php $d="allow_reporte_diario_admin"; ?><div class="checkbox"><label><input type="checkbox" data-field="<?php echo $d; ?>" class="campo_editable_main" id="field_<?php echo $d; ?>"> Consultar reportes diarios</label></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-xs-4">
					<div class="box bg-blue box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">Permisos por materia</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
						</div>
						<form role="form" class="form-contact" id="main_form2" style="background: #eaeaea;color: black;">
							<div class="box-body">
								<div class="form-group">
									<label>Seleccionar materias</label>
									<?php
										$query=$home_controller->get_query_construct($permisos,5);
										$data=$controller->get_data_from_query($query);
									?>
									<select class="form-control" id="materias">
										<option selected data-grado="0" value="0">Seleccione una materia</option>
										<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
										<option data-grado="<?php echo $key['grado']; ?>" value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]." - ".$key["grado"]; ?></option>
										<?php }} ?>
									</select>
									<a href="javascript:;" id="advanced_search">Búsqueda avanzada</a>
								</div>
								<div class="form-group" id="advanced_options" style="display: none;">
									<div class="input-group margin">
										<div class="input-group-btn"><button type="button" class="btn bg-blue">Grado</button></div>
										<input type="text" class="form-control" id="grado_filter">
									</div>
									<div class="input-group margin">
										<div class="input-group-btn"><button type="button" class="btn bg-blue">Materia</button></div>
										<input type="text" class="form-control" id="materia_filter">
									</div>
								</div>
								<div class="form-group">
									<div class="col-lg-4 col-xs-4">
										<div>
											<a class="btn btn-app" id="add_materia">
												<i class="fa fa-plus"></i>Agregar
											</a>
										</div>
										<div>
											<a class="btn btn-app" id="remove_all">
												<i class="fa fa-trash"></i>Eliminar todas
											</a>
										</div>
									</div>
									<div class="col-lg-8 col-xs-8">
										<div id="materias_container"></div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>