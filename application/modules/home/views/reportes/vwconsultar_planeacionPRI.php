	<?php $data_plan=$controller->get_data("*","planeaciones","id='$id'"); $data_plan=$data_plan[0]; ?>
	<style>.change_status{cursor:pointer;}.add_alumno,.add_secuencia,.remove_secuencia,.remove_alumno{font-size: 20px !important;}</style>
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
									<?php $d="fecha_inicio"; ?><label for="<?php echo $d; ?>">Fecha de inicio</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="fecha_fin"; ?><label for="<?php echo $d; ?>">Fecha de fin</label>
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
								<div class="form-group">
									<?php $d="materias_id"; ?><label for="<?php echo $d; ?>">Planeación de la materia</label>
									<p><?php $temp=$controller->get_data("nombre","materias","id='".$data_plan[$d]."'"); $temp=$temp[0]; $temp=$temp["nombre"]; echo $temp; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $xx=1; $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Competencia</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Ámbito / Eje</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Aprendizaje esperado</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<label>Nivel taxonómico</label>
								<div class="form-group parent_data_container" data-type="multiple"><?php $x=1; ?>
									<div class="checkbox"><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Conocimiento/Recuperación</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Comprensión</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Aplicación/Uso</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Análisis</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Síntesis/Metacognición</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo intval($data_plan['campo_formativo_'.$x++])>0 ? 'check' : 'close'; ?>" ></i> Evaluación/Creación</label></div>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">No. Sesión</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Producto final</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Autoevaluación</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Tema</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-bordered" id="secuencias_didacticas">
									<thead>
										<tr>
											<th>Secuencia didáctica</th>
											<th width="150">Tiempo estimado</th>
											<th width="400">Recursos Didácticos</th>
										</tr>
									</thead>
									<tbody id="table_body">
										<?php
											$elem=array("Inicio","Desarrollo","Cierre");
											$tot_rows=$controller->get_data("DISTINCT(row) AS row","secuencias_didacticas","planeaciones_id='$id'","row","row");
											if($tot_rows!==FALSE){
												foreach($tot_rows AS $r=>$row){
													$data_sec_temp=$controller->get_data("secuencia,tiempo,recursos_1,recursos_2,recursos_3,recursos_4,recursos_5,recursos_6,tipo","secuencias_didacticas","planeaciones_id='$id' AND row='".$row["row"]."'");
													$data_sec=array();
													if($data_sec_temp!==FALSE){
														foreach($data_sec_temp AS $e=>$key){
															$temp=$key["tipo"];
															foreach($key AS $c=>$child){
																if($c!=="tipo"){
																	$data_sec[strval($temp)][$c]=$child;
																}
															}
														}
													}
													#print_r($data_sec);
										?>
										<tr class="second_container">
											<td>
												<?php for($j=0;$j<3;$j++){ ?>
												<div><label><?php echo $elem[$j]; ?></label> <?php echo $data_sec[$j]["secuencia"]; ?></div><br>
												<?php } ?>
											</td>
											<td>
												<?php for($j=0;$j<3;$j++){ ?>
												<div><label><?php echo $elem[$j]; ?></label> <?php echo $data_sec[$j]["tiempo"]; ?></div><br>
												<?php } ?>
											</td>
											<td>
												<table class="table table-bordered" style="background-color:transparent !important;">
													<tr>
														<th></th>
														<th>Libro SEP</th>
														<th>Libro</th>
														<th>Cuaderno</th>
														<th>Apoyos visuales</th>
														<th>TICs</th>
														<th>Otro</th>
													</tr>
													<?php for($j=0;$j<3;$j++){ ?>
													<tr>
														<td><b><?php echo $elem[$j]; ?></b></td><?php for($i=0;$i<6;$i++){ ?>
														<td><div class="checkbox"><label>
															<i class="fa fa-<?php echo intval($data_sec[$j]['recursos_'.($i+1)])>0 ? 'check' : 'close'; ?>" ></i>
														</label></div></td>
														<?php } ?>
													</tr>
													<?php } ?>
												</table>
											</td>
										</tr>
										<?php }} ?>
									</tbody>
								</table>
							</div>
							<div class="box-body">
								<label>Rincones y Clubes</label>
								<div class="form-group parent_data_container" data-type="multiple"><?php $x=1; ?>
									<div class="checkbox"><label><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i> Lectura</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i> Ortografía</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i> Caligrafía</label></div>
									<div class="checkbox"><label><i class="fa fa-<?php echo $controller->get_class_from_checkboxes(intval($data_plan['rincon_'.$x]),1); ?>" ></i> Razonamiento lógico</label></div>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="text_field_".$xx++; ?><label for="<?php echo $d; ?>">Descripción de actividades</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
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
											$data_alumnos=$controller->get_data_school("al.*, det.nombre",DATABASE_1.".alumnos_seguimiento AS al LEFT JOIN ".DATABASE_2.".alumnos AS det ON al.alumnos_id=det.id","al.planeaciones_id='$id'");
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
							<div class="box-body">
								<div class="form-group">
									<?php $d="observaciones"; ?><label for="<?php echo $d; ?>">Observaciones</label>
									<p><?php echo $data_plan[$d]; ?></p>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<?php $d="comentarios"; ?><label for="<?php echo $d; ?>">Comentarios de Dirección</label>
									<textarea data-field="<?php echo $d; ?>" class="form-control" data-id="<?php echo $id; ?>" id="<?php echo $d; ?>" required><?php echo $data_plan[$d]; ?></textarea>
								</div>
							</div>
							<div class="box-footer"><button type="submit" class="btn bg-blue enviar_formulario">Guardar</button></div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
