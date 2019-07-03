<div class="content-wrapper">
	<section class="content-header">
		<h1>Usuarios<small>listado de usuarios</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Usuarios</a></li>
		</ol>
	</section>
	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header"><h3 class="box-title">Administración de usuarios</h3></div>
					<div class="box-body table-responsive no-padding">
			  			<table id="example1" class="table table-bordered table-striped">
			  				<thead>
								<tr>
				  					<th>ID</th>
									<th>Nombre</th>
									<th>Username</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$data=$controller->get_data_from_query("SELECT ppal.id, ppal.nombre, ppal.username FROM usuarios_boletas AS ppal WHERE ppal.date_end IS NULL AND ppal.is_ciclo1718=0; ");
									if($data!==FALSE){ foreach($data AS $e => $key){
								?>
								<tr>
				  					<td><?php echo $key["id"]; ?></td>
				  					<td><?php echo $key["nombre"]; ?></td>
				  					<td><?php echo $key["username"]; ?></td>
				  					<td>
				  						<button type="button" class="btn btn-box-tool editar_registro" data-id="<?php echo $key['id']; ?>"><i class="fa fa-pencil"></i></button>
				  						<button type="button" class="btn btn-box-tool eliminar_registro" data-id="<?php echo $key['id']; ?>"><i class="fa fa-trash"></i></button>
				  					</td>
								</tr>
								<?php }} ?>
							</tbody>
						</table>
					</div>
		  		</div>
			</div>
			<div class="box-footer"><button type="button" class="btn bg-blue" id="agregar_nuevo">Agregar usuario</button></div>
	  	</div>
	</section>
  </div>