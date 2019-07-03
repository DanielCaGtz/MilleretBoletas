		<?php
			function consulta_uri($txt){
				$url=explode("/",$_SERVER["REQUEST_URI"]); $url=$url[count($url)-1];
				foreach($txt AS $item){
					if($item===$url) return "active";
				}
			}
		?>
		<aside class="main-sidebar">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?php echo base_url(); ?>img/user.jpg" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?php echo $this->session->userdata("nombre"); ?></p>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<?php /*
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Buscar...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</form> */ ?>
				<ul class="sidebar-menu">
					<li class="header">MENÚ PRINCIPAL</li>
					<li class="treeview <?php echo consulta_uri(array('home')); ?>">
						<a href="#">
							<i class="fa fa-dashboard"></i> <span>Home</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('home')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Dashboard</a></li>
						</ul>
					</li>
					<?php if(intval($controller->check_permisos("allow_edit"))>0){ ?>
					<li class="treeview <?php echo consulta_uri(array('edicion','edicion_sub')); ?>">
						<a href="#">
							<i class="fa fa-bar-chart"></i> <span>Edición de aspectos</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('edicion')); ?>"><a href="<?php echo base_url(); ?>edicion"><i class="fa fa-circle-o"></i> Editar/ver aspectos</a></li>
							<li class="<?php echo consulta_uri(array('edicion_sub')); ?>"><a href="<?php echo base_url(); ?>edicion_sub"><i class="fa fa-circle-o"></i> Editar/ver subaspectos</a></li>
						</ul>
					</li>
					<?php } if(intval($controller->check_permisos("allow_materias"))>0){ ?>
					<li class="treeview <?php echo consulta_uri(array('editar_materias')); ?>">
						<a href="#">
							<i class="fa fa-cubes"></i> <span>Editar materias</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if(intval($controller->check_permisos("allow_materias"))>0){ ?>
							<li class="<?php echo consulta_uri(array('editar_materias')); ?>"><a href="<?php echo base_url() ?>editar_materias"><i class="fa fa-circle-o"></i> Editar/ver materias</a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } if(intval($controller->check_permisos("allow_add"))>0 || intval($controller->check_permisos("allow_view"))>0){ ?>
					<li class="treeview <?php echo consulta_uri(array('consultar','registro')); ?>">
						<a href="#">
							<i class="fa fa-edit"></i> <span>Consultas de datos</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if(intval($controller->check_permisos("allow_view"))>0){ ?>
							<li class="<?php echo consulta_uri(array('consultar')); ?>"><a href="<?php echo base_url() ?>consultar"><i class="fa fa-circle-o"></i> Consultar boletas</a></li>
							<?php } if(intval($controller->check_permisos("allow_add"))>0){ ?>
							<li class="<?php echo consulta_uri(array('registro')); ?>"><a href="<?php echo base_url() ?>registro"><i class="fa fa-circle-o"></i> Registrar/ver calificaciones</a></li>
							<?php } ?>
						</ul>
					</li>
					<?php } if(intval(USES_PLANEACION)>0){ if(intval($controller->check_permisos("allow_reporte_diario_add"))>0 || intval($controller->check_permisos("allow_reporte_diario_admin"))>0){ ?>
					<li class="treeview <?php echo consulta_uri(array('agregar_reporte_diario','ver_reporte_diario')); ?>">
						<a href="#">
							<i class="fa fa-edit"></i> <span>Planeación</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php if(intval($controller->check_permisos("allow_reporte_diario_add"))>0){ ?>
							<li class="<?php echo consulta_uri(array('agregar_reporte_diario')); ?>"><a href="<?php echo base_url() ?>agregar_reporte_diario"><i class="fa fa-circle-o"></i> Agregar planeación</a></li>
							<?php }if(intval($controller->check_permisos("allow_reporte_diario_admin"))>0){ ?>
							<li class="<?php echo consulta_uri(array('ver_reporte_diario')); ?>"><a href="<?php echo base_url() ?>ver_reporte_diario"><i class="fa fa-circle-o"></i> Ver planeaciones</a></li>
							<?php } ?>
						</ul>
					</li>
					<?php }} if(intval($controller->check_permisos("allow_admin"))>0){ ?>
					<li class="treeview <?php echo consulta_uri(array('reporte_detalles')); ?>">
						<a href="#">
							<i class="fa fa-bar-chart"></i> <span>Esfuerzo / Detalles reporte</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('reporte_detalles')); ?>"><a href="<?php echo base_url(); ?>reporte_detalles"><i class="fa fa-circle-o"></i> Editar/ver detalles</a></li>
						</ul>
					</li>
					<?php } if(intval($controller->check_permisos("allow_admin_users"))>0){ ?>
					<li class="treeview <?php echo consulta_uri(array('ver_usuarios','agregar_usuario')); ?>">
						<a href="#">
							<i class="fa fa-user"></i> <span>Administración de usuarios</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('ver_usuarios')); ?>"><a href="<?php echo base_url(); ?>ver_usuarios"><i class="fa fa-users"></i> Editar usuarios/permisos</a></li>
							<li class="<?php echo consulta_uri(array('agregar_usuario')); ?>"><a href="<?php echo base_url(); ?>agregar_usuario"><i class="fa fa-user-plus"></i> Agregar usuario</a></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</section>
		</aside>
