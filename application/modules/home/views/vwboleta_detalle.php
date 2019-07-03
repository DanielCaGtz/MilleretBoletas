<?php
	$data_cualidades=$controller->get_data_from_query("SELECT ppal.*, materia.nombre AS nombre_materia, cualidad.nombre AS nombre_cualidad FROM calificaciones_cualidades AS ppal INNER JOIN materias AS materia ON ppal.idMateria=materia.id INNER JOIN cualidades AS cualidad ON ppal.idCualidad=cualidad.id WHERE ppal.idCalificacion='$idCalificacion';");
	function colores($num){
		if($num<=40) return "danger";
		elseif($num>=50 && $num<=60) return "warning";
		elseif($num>=70 && $num<=90) return "info";
		elseif($num>90) return "success";
	}
	function roundDown($decimal, $precision){
		$fraction = substr($decimal - floor($decimal), 2, $precision);
		$newDecimal = floor($decimal). '.' .$fraction;
		//return floatval($newDecimal);
		return number_format((float)$newDecimal, 2, '.', '');
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<title>Boleta <?php echo SCHOOL_NAME; ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/zabuto_calendar.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/gritter/css/jquery.gritter.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lineicons/style.css">   
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/to-do.css"> 
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style-responsive.css">
		<script src="<?php echo base_url(); ?>assets/js/chart-master/Chart.js"></script>
		<?php /*
		<link rel="shortcut icon" href="<?php echo base_url(); ?>ico/favicon.png">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url(); ?>ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url(); ?>ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url(); ?>ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo base_url(); ?>ico/apple-touch-icon-57-precomposed.png">
		*/ ?>
		<style>
			<?php
				for($i=0;$i<=100;$i++){
					echo ".size_".$i."{width:".$i."%;}";
				}
			?>
		</style>
	</head>
	<body>
		<div class="row">
			<div class="col-md-12">
				<?php if($data_cualidades!==FALSE){ ?>
				<div class="showback">
					<h4><i class="fa fa-angle-right"></i> <?php echo $data_cualidades[0]["nombre_materia"]; ?></h4>
					<?php foreach($data_cualidades AS $e => $key){ ?>
						<strong><?php echo $key["nombre_cualidad"]; ?></strong> &nbsp;&nbsp;&nbsp;<?php $num=floatval($key["porcentaje"])*10; echo $num; ?>% Complete
						<div class="progress progress-striped">
							<div class="progress-bar progress-bar-<?php echo colores(intval($num)); ?> size_<?php echo intval($num); ?>" role="progressbar" aria-valuenow="<?php echo $num; ?>" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only"><?php echo $num; ?>% Complete</span>
							</div>
						</div>
						<?php
							$sub_query="SELECT ppal.porcentaje, det.nombre FROM calificaciones_cualidades_sub AS ppal INNER JOIN cualidades_sub AS det ON ppal.idCualidadSub=det.id WHERE ppal.idCalificacion='$idCalificacion' AND det.idCualidad='".$key["idCualidad"]."';";
							//echo $sub_query;
							$data_temp=$controller->get_data_from_query($sub_query);
							if($data_temp!==FALSE){ foreach($data_temp AS $i=>$item){
								$num=floatval($item["porcentaje"])*10;
						?>
						<strong style="margin-left:100px;"><?php echo $item["nombre"]; ?></strong> &nbsp;&nbsp;&nbsp;<?php echo $num; ?>% Complete
						<div class="progress progress-stripped" style="margin-left:100px;">
							<div class="progress-bar progress-bar-<?php echo colores(intval($num)); ?> size_<?php echo intval($num); ?>" role="progressbar" aria-valuenow="<?php echo $num; ?>" aria-valuemin="0" aria-valuemax="100">
								<span class="sr-only"><?php echo $num; ?>% Complete</span>
							</div>
						</div>
					<?php }}} ?>
				</div>
				<?php } ?>
				<button type="button" class="btn btn-primary" onclick="history.go(-1);">Regresar</button>
			</div>
		</div>
		
		<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery-1.8.3.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<script class="include" type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.sparkline.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/common-scripts.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/gritter/js/jquery.gritter.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/gritter-conf.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/sparkline-chart.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/zabuto_calendar.js"></script>
		<script type="application/javascript">
			$(document).ready(function () {
				$("#date-popover").popover({html: true, trigger: "manual"});
				$("#date-popover").hide();
				$("#date-popover").click(function (e) {
					$(this).hide();
				});
			
				$("#my-calendar").zabuto_calendar({
					action: function () {
						return myDateFunction(this.id, false);
					},
					action_nav: function () {
						return myNavFunction(this.id);
					},
					ajax: {
						url: "show_data.php?action=1",
						modal: true
					},
					legend: [
						{type: "text", label: "Special event", badge: "00"},
						{type: "block", label: "Regular event", }
					]
				});
			});
			
			
			function myNavFunction(id) {
				$("#date-popover").hide();
				var nav = $("#" + id).data("navigation");
				var to = $("#" + id).data("to");
				console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
			}
		</script>
	</body>
</html>
