<?php
	$permisos_where="";
	if(intval($controller->check_permisos("allow_reporte_diario_add"))>0 && intval($controller->check_permisos("allow_reporte_diario_admin"))>0) $permisos_where="WHERE usuarios_boletas_id='".$this->session->userdata("id")."'";
?>
<style>.hide{display:none;}</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1><small>Listado</small> Planeación</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Planeación</a></li>
		</ol>
	</section>
	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header"><h3 class="box-title">Planeaciones registradas</h3></div>
					<div class="box-body table-responsive no-padding">
						<table id="example1" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Profesor</th>
									<th>Grado</th>
									<th>Materia</th>
									<th>Fecha</th>
									<th>Observaciones</th>
									<th>Estatus</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$data=$controller->get_data_from_query("SELECT ppal.id, u.nombre, ppal.grado, COALESCE(m.nombre,'N/A') AS materia, ppal.fecha_inicio, ppal.observaciones FROM planeaciones AS ppal INNER JOIN usuarios_boletas AS u ON ppal.usuarios_boletas_id=u.id LEFT JOIN materias AS m ON ppal.materias_id=m.id $permisos_where ");
									if($data!==FALSE){
										foreach($data AS $e=>$key){
											$dStart = new DateTime($key["fecha_inicio"]);
											$dEnd = new DateTime(date('Y-m-d'));
											$dDiff = $dStart->diff($dEnd);
											if(intval($dDiff->days)>15) $ruta="img/red_circle.png";
											else $ruta="img/green_circle.png";
								?>
								<tr>
									<td><?php echo $key['nombre']; ?></td>
									<td><?php echo $key['grado']; ?></td>
									<td><?php echo $key['materia']; ?></td>
									<td><?php echo $key['fecha_inicio']; ?></td>
									<td><?php echo $key['observaciones']; ?></td>
									<td><div class="hide">1</div><img src="<?php echo base_url($ruta); ?>" style="width:30px"></td>
									<td><button type="button" class="btn btn-box-tool ver_planeacion" data-id="<?php echo $key['id']; ?>"><i class="fa fa-eye"></i></button></td>
								</tr>
								<?php }} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
  </div>
