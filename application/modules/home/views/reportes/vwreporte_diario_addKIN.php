	<style>.change_status{cursor:pointer;}.add_alumno,.remove_alumno{font-size: 20px !important;}</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><small>Agregar</small> Planeación</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Planeación</li>
			</ol>
		</section>

		<section class="content" id="main_content">
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
			<div class="row">
				<div class="col-lg-8 col-xs-8">
					<div class="box bg-blue box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">Planeación</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
						</div>
						<form role="form" class="form-contact formulario" id="main_form" style="background: #eaeaea;color: black;">
							<div class="box-body">
								<div class="form-group">
									<?php $d="fecha_inicio"; ?><label for="<?php echo $d; ?>">Fecha</label>
									<input type="date" data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="<?php echo $d; ?>" required>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="grado"; ?><label for="<?php echo $d; ?>">Grado</label>
									<select data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="<?php echo $d; ?>">
										<?php $data=$controller->get_data("grado","permisos_boletas","idUsuario='".$this->session->userdata('id')."'","grado","grado"); if($data!==FALSE){foreach($data AS $e=>$key){ ?>
										<option value="<?php echo $key['grado']; ?>"><?php echo $key['grado']; ?></option>
										<?php }} ?>
									</select>
								</div>
							</div>
							<div class="box-body">
								<label>Campo formativo</label>
								<div class="form-group parent_data_container" data-type="multiple"><?php $x=1; ?>
									<div class="checkbox"><label><input type="checkbox" value="1" class="campo_editable" data-name="campo_formativo_<?php echo $x++; ?>">Lenguaje y comunicación</label></div>
									<div class="checkbox"><label><input type="checkbox" value="1" class="campo_editable" data-name="campo_formativo_<?php echo $x++; ?>">Exploración y conocimiento del mundo</label></div>
									<div class="checkbox"><label><input type="checkbox" value="1" class="campo_editable" data-name="campo_formativo_<?php echo $x++; ?>">Desarrollo social y personal</label></div>
									<div class="checkbox"><label><input type="checkbox" value="1" class="campo_editable" data-name="campo_formativo_<?php echo $x++; ?>">Expresión Artística</label></div>
									<div class="checkbox"><label><input type="checkbox" value="1" class="campo_editable" data-name="campo_formativo_<?php echo $x++; ?>">Pensamiento matemático</label></div>
									<div class="checkbox"><label><input type="checkbox" value="1" class="campo_editable" data-name="campo_formativo_<?php echo $x++; ?>">Desarrollo físico y salud</label></div>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="aprend_esperado"; ?><label for="<?php echo $d; ?>">Aprendizaje esperado</label>
									<textarea data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="<?php echo $d; ?>" required></textarea>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="objetivo"; ?><label for="<?php echo $d; ?>">Objetivo</label>
									<textarea data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="<?php echo $d; ?>" required></textarea>
								</div>
							</div>
							<div class="box-body"><?php $x=1; ?>
								<table class="table table-bordered parent_data_container" data-type="single">
									<tbody>
										<tr>
											<th>Rincones</th>
											<th>Aplicado</th>
											<th>Objetivo logrado</th>
											<th>Objetivo no logrado</th>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de lectura</td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de plástica</td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de juegos lógicos</td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de construcción</td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de juego simbólico</td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de cuentos</td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="rincon_<?php echo $x++; ?>" value="4"></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="box-body"><?php $x=1; ?>
								<label>Aspectos que tomaré en cuenta para la próxima vez o que necesito modificar</label>
								<table class="table table-bordered parent_data_container" data-type="single">
									<tbody>
										<tr>
											<th></th>
											<th>Falta de material</th>
											<th>Cambio de material</th>
											<th>No lo pedí a tiempo</th>
										</tr>
										<tr class="second_container" data-name="aspectos_<?php echo $x; ?>">
											<td>Material</td>
											<td><input type="checkbox" name="aspectos_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="aspectos_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="aspectos_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr>
											<th></th>
											<th>Comunicación entre alumnos</th>
											<th>Vínculo maestra/alumnos</th>
											<th>Uso del lenguaje apropiado para expresar necesidades, emociones e ideas</th>
										</tr>
										<tr class="second_container" data-name="aspectos_<?php echo $x; ?>">
											<td>Interacción e intervención con los niños</td>
											<td><input type="checkbox" name="aspectos_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="aspectos_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="aspectos_<?php echo $x++; ?>" value="4"></td>
										</tr>
										<tr>
											<th></th>
											<th>Explicación adecuada</th>
											<th>Recuperación de aprendizajes previos</th>
											<th>Logro del objetivo</th>
										</tr>
										<tr class="second_container" data-name="aspectos_<?php echo $x; ?>">
											<td>Intervención pedagógica</td>
											<td><input type="checkbox" name="aspectos_<?php echo $x; ?>" value="1"></td>
											<td><input type="checkbox" name="aspectos_<?php echo $x; ?>" value="2"></td>
											<td><input type="checkbox" name="aspectos_<?php echo $x++; ?>" value="4"></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="observaciones"; ?><label for="<?php echo $d; ?>">Observaciones</label>
									<textarea data-field="<?php echo $d; ?>" class="campo_editable_main form-control" id="<?php echo $d; ?>" required></textarea>
								</div>
							</div>
							<div class="box-body">
								<label>Alumnos para dar seguimiento al mes</label><button type="button" class="btn btn-box-tool"><i class="fa fa-plus add_alumno"></i></button>
								<table class="table table-bordered" id="alumnos_seguimiento">
									<thead>
										<tr>
											<th style="width:250px;">Alumno</th>
											<th>Lenguaje oral</th>
											<th>Lenguaje escrito</th>
											<th>Pensamiento matemático</th>
											<th>Autorregulación</th>
											<th>Inasistencia</th>
											<th>Se relaciona</th>
											<th>Enfrenta BAP</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
										<tr class="second_container">
											<td>
												<select class="form-control get_raw_data" data-name="alumnos_id" data-type="select">
													<?php foreach($controller->get_data_school("id,nombre",DATABASE_2.".alumnos","grado IN (SELECT grado FROM ".DATABASE_1.".permisos_boletas WHERE idUsuario='".$this->session->userdata('id')."' GROUP BY grado)","nombre") AS $e=>$key){ ?>
													<option value="<?php echo $key['id']; ?>"><?php echo $key['nombre']; ?></option>
													<?php } ?>
												</select>
											</td><?php $x=1; ?>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td><div class="checkbox"><label><input type="checkbox" class="get_raw_data" data-type="checkbox" data-name="opcion_<?php echo $x++; ?>" value="1"></label></div></td>
											<td>
												<button type="button" class="btn btn-box-tool"><i class="fa fa-trash remove_alumno"></i></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="box-footer"><button type="submit" class="btn bg-blue enviar_formulario">Guardar</button></div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
