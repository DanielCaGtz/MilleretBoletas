	<?php $data_plan=$controller->get_data("*","planeaciones","id='$id'"); $data_plan=$data_plan[0]; ?>
	<style>.change_status{cursor:pointer;}.add_alumno{font-size: 20px !important;}</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><small>Consultar</small> Planeación</h1>
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
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="grado"; ?><label for="<?php echo $d; ?>">Grado</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<label>Campo formativo</label>
								<div class="form-group parent_data_container" data-type="multiple"><?php $x=1; ?>
									<p><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Lenguaje y comunicación</label></p>
									<p><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Exploración y conocimiento del mundo</label></p>
									<p><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Desarrollo social y personal</label></p>
									<p><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Expresión Artística</label></p>
									<p><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Pensamiento matemático</label></p>
									<p><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Desarrollo físico y salud</label></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="aprend_esperado"; ?><label for="<?php echo $d; ?>">Aprendizaje esperado</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="objetivo"; ?><label for="<?php echo $d; ?>">Objetivo</label>
									<p><?php echo $data_plan[$d]; ?></p>
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
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de plástica</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de juegos lógicos</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de construcción</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de juego simbólico</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr class="second_container" data-name="rincon_<?php echo $x; ?>">
											<td>Rincón de cuentos</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x++]),4); ?>" ></i></td>
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
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr>
											<th></th>
											<th>Comunicación entre alumnos</th>
											<th>Vínculo maestra/alumnos</th>
											<th>Uso del lenguaje apropiado para expresar necesidades, emociones e ideas</th>
										</tr>
										<tr class="second_container" data-name="aspectos_<?php echo $x; ?>">
											<td>Interacción e intervención con los niños</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x++]),4); ?>" ></i></td>
										</tr>
										<tr>
											<th></th>
											<th>Explicación adecuada</th>
											<th>Recuperación de aprendizajes previos</th>
											<th>Logro del objetivo</th>
										</tr>
										<tr class="second_container" data-name="aspectos_<?php echo $x; ?>">
											<td>Intervención pedagógica</td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x]),1); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x]),2); ?>" ></i></td>
											<td><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['aspectos_'.$x++]),4); ?>" ></i></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="observaciones"; ?><label for="<?php echo $d; ?>">Observaciones</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<label>Alumnos para dar seguimiento al mes</label>
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
										</tr>
									</thead>
									<tbody>
										<?php
											$data_alumnos=$controller->get_data_school("al.*, det.nombre",DATABASE_1.".alumnos_seguimiento AS al INNER JOIN ".DATABASE_2.".alumnos AS det ON al.alumnos_id=det.id","al.planeaciones_id='$id'");
											if($data_alumnos!==FALSE){
												foreach($data_alumnos AS $e=>$key){ ?>
										<tr>
											<td><?php echo $key["nombre"]; ?></td><?php $x=1; ?>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
											<td><label><i class="fa fa-<?php echo intval($key['opcion_'.$x++])>0 ? 'check' : 'close'; ?>" ></i></label></td>
										</tr>
										<?php }} ?>
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
