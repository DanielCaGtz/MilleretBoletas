<?php
	date_default_timezone_set('America/Mexico_City');
	$data_boleta=$controller->get_data("*","boletas","SHA2(idAlumno,256)='$idAlumno'");
	$data_alumno=$controller->get_data("*","alumnos","SHA2(id,256)='$idAlumno'");
	$data_grupo=$controller->get_data("*","grados_boletas","SHA2(idAlumno,256)='$idAlumno'");
	$data_alumno=$data_alumno[0];

	if($this->session->userdata("id")) $permisos=$controller->get_data("*","permisos_boletas","idUsuario='".$this->session->userdata("id")."'");
	else $permisos=FALSE;

	if($data_boleta!==FALSE) $data_calif=$controller->get_data_from_query("SELECT * FROM (SELECT * FROM calificaciones GROUP BY idMateria, idAlumno, bimestre, id ORDER BY id DESC) AS t where idBoleta='".$data_boleta[0]["id"]."' GROUP BY idMateria, bimestre, t.id");
	else $data_calif=FALSE;

	$data_boleta[0]["grupo"]=$data_alumno["grupo"];

	$promedios_gral=array(1=>0,2=>0,3=>0,4=>0,5=>0);
	$tot_bloques=3;
	if(substr($data_boleta[0]["grupo"],0,4)==="KIND") $tot_bloques=3;

	function roundDown($decimal, $precision){
		$fraction = substr($decimal - floor($decimal), 2, $precision);
		$newDecimal = floor($decimal). '.' .$fraction;
		//return floatval($newDecimal);
		return number_format((float)$newDecimal, 2, '.', '');
	}

	$main_grado=substr($data_alumno["grupo"],0,3);

	$meses=array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");

	$hide=FALSE;
	if(isset($_GET["hide"]) && intval($_GET["hide"])>0) $hide=TRUE;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/component_tables.css" />
		<title>Boleta <?php echo SCHOOL_NAME; ?></title>
		<script type="text/javascript">window.url = {base_url:"<?php echo nombre_ruta_host(); ?>"};</script>
		<style type="text/css">
			body,td,th {color: #333;}a:link {color: #333;text-decoration: none;}a:visited {text-decoration: none;color: #333;}a:hover {text-decoration: underline;color: #1c2c79;}a:active {text-decoration: none;color: #333;}
			.calif_kinder{margin-right:70px !important;font-size:medium;}
		</style>
		<?php /*
		<link rel="shortcut icon" href="<?php echo base_url(); ?>ico/favicon.png">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url(); ?>ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url(); ?>ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url(); ?>ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo base_url(); ?>ico/apple-touch-icon-57-precomposed.png"> */ ?>
	</head>
	<body>
		<div class="container" id="main_container" data-alumno='<?php echo $data_alumno['id']; ?>'>
			<div class="component">
				<table border=".2" bordercolor="#000000" width="800" bgcolor="#1c2c79">
					<thead>
						<tr>
							<td colspan="4" style="background-color: white !important;border:none;">
								<center><img src="<?php echo base_url(); ?>img/Escudo.png" style="width: 150px;" alt="img"></center>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th style="color:#FFF"><?php echo $data_alumno["nombre"]; ?></th>
							<th style="color:#FFF">Grado: <?php echo $data_alumno["grado"]; ?></th>
							<th style="color:#FFF">Grupo: <?php echo $data_grupo!==FALSE ? $data_grupo[0]["grupo"] : ""; ?> </th>
							<th style="color:#FFF"><?php echo date('d')." ".$meses[intval(date('m'))]." ".date('Y'); ?></th>
						</tr>
					</tbody>
				</table>
				<?php if(substr($data_alumno["grupo"],0,4)=="KIND"){ if(intval(LETRAS_KINDER)>0){ ?>
				<?php /*<h3 style="color:#1c2c79;"><span class="calif_kinder">MB = Muy Bien</span><span class="calif_kinder">B = Bien</span><span class="calif_kinder">P = Promedio</span><span class="calif_kinder">EP = En Proceso</span><span class="calif_kinder">NA = Necesita Ayuda</span></h3>
				<h3 style="color:#1c2c79;"><span class="calif_kinder">VG = Very Good</span><span class="calif_kinder">G = Good</span><span class="calif_kinder">A = Average</span><span class="calif_kinder">IP = In Process</span><span class="calif_kinder">NH = Needs Help</span></h3> */ ?>
				<?php }}

				if(substr($data_alumno["grupo"],0,4)=="KIND"){
					$bloques_if=array("AND is_part_avr=1");
					$i_count=1;
				}else{
					$bloques_if=array("AND is_part_avr=1","AND is_part_avr=0");
					$i_count=2;
				}
				for($master_i=0; $master_i<$i_count; $master_i++){
					$query_bloques="SELECT * FROM bloques WHERE active=1 ".$bloques_if[$master_i]." AND is_in_".$main_grado."=1";
					if($permisos!==FALSE){
						$s=TRUE;
						$ss=TRUE;
						foreach($permisos AS $per=>$permiso){
							if($s){
								$query_bloques.=" AND id IN ( SELECT DISTINCT idBloque FROM materias WHERE id>0 ";
								$s=!$s;
							}
							if(intval($permiso["idMateria"])>0){
								if($ss){
									$query_bloques.=" AND id IN (SELECT DISTINCT idMateria FROM permisos_boletas WHERE idUsuario = '".$this->session->userdata("id")."') ";
									$ss=!$ss;
								}
							}
						}
						$query_bloques.=" ) ";
					}
					$data_bloques_temp=$controller->get_data_from_query($query_bloques);
					if($data_bloques_temp!==FALSE) foreach($data_bloques_temp AS $e => $key){
				?>
				<table border=".2" bordercolor="#000000" width="800">
					<?php if($e===0 && $master_i==0){ ?><h4 style="color:#1c2c79">ASPECTOS ACADÉMICOS</h4><?php } ?>
					<h4 style="color:#1c2c79"><?php echo $key["nombre"]; ?></h4>
					<hr width="800" align="left">
					<p style="font-size:10pt">*Dar click en la calificación de cada materia para conocer el detalle de la calificación obtenida por el alumno.</p>
					<thead>
						<tr bgcolor="#1c2c79">
							<th width="300" colspan="3" style="color:#FFF"><?php echo $key["subtitle"]; ?></th>
							<?php for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){ ?><th width="100" style="color:#FFF">T<?php echo $i; ?></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
							if(isset($data_boleta[0]["grado"])){
								$query="SELECT * FROM materias WHERE idBloque='".$key["id"]."' AND grado='".$data_boleta[0]["grado"]."' AND active=1 AND (has_calif=1 OR has_nivel=1) ";
								if($permisos!==FALSE){
									$ss=TRUE;
									foreach($permisos AS $per=>$permiso){
										if(intval($permiso["idMateria"])>0){
											if($ss){
												$query.=" AND id IN (SELECT DISTINCT idMateria FROM permisos_boletas WHERE idUsuario = '".$this->session->userdata("id")."') ";
												$ss=!$ss;
											}
										}
									}
									$query.=" ORDER BY orden ";
								}
								$data_mat=$controller->get_data_from_query($query);
							}else $data_mat=FALSE;
							$tot_mat=0; $promedios=array(1=>0,2=>0,3=>0,4=>0,5=>0); if($data_mat!==FALSE) foreach($data_mat AS $f => $mat){
								$tot_mat++;
								$has_calif=intval($mat["has_calif"])>0;
								$has_nivel=intval($mat["has_nivel"])>0;
						?>
						<tr>
							<td width="300" colspan="3" <?php echo ($has_calif && $has_nivel) ? 'rowspan="2"' : ''; ?> ><?php echo $mat["nombre"]; ?></td>
							<?php
								for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){
									$existentes=array();
									$value_temp="";
									$value_temp_nivel="";
									$id_temp=0;
									if($data_calif!==FALSE){
										foreach($data_calif AS $c=>$calif){
											// echo intval($calif["bimestre"])." ".intval($i) ." | ". intval($calif["idMateria"])." ".intval($mat["id"])."<br>";
											if(intval($calif["bimestre"])===intval($i) && intval($calif["idMateria"])===intval($mat["id"])){
												$id_temp=$calif["id"];
												$value_temp=$calif["calificacion"];
												$value_temp_nivel=$calif["nivel"];
												if(substr($data_boleta[0]["grupo"],0,4)!="KIND"){
													if(!array_key_exists($mat["id"],$existentes)){
														$promedios[$i]+=floatval($value_temp);
														$existentes[$mat["id"]]=1;
													}
													#$promedios[$i].=$mat["nombre"]." ";
												}
												if(strlen($value_temp)===0) $value_temp=$calif["calificacion_letra"];
											}
										}
									} if($has_calif){ ?>
									<td width="100" align="center">
										<?php if(intval(CONSULTA_DETALLE_BOLETA)>0 && $master_i===0){ ?>
										<a href="<?php echo 1 ? base_url().'consulta_logros/'.$id_temp : 'javascript:;'; ?>"><?php echo $value_temp; ?></a>
										<?php }else{ ?>
											<?php echo $value_temp; ?>
										<?php } ?>
									</td>
									<?php } if($has_nivel && !$has_calif){ ?>
									<td width="100" align="center">
										<?php echo strlen($value_temp_nivel)>0 ? 'NIVEL '.$value_temp_nivel : ''; ?>
									</td>
							<?php }} ?>
						</tr>
						<?php if($has_nivel && $has_calif){ ?>
						<tr>
							<?php
								for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){
									$value_temp_nivel="";
									if($data_calif!==FALSE){
										foreach($data_calif AS $c=>$calif){
											if(intval($calif["bimestre"])===intval($i) && intval($calif["idMateria"])===intval($mat["id"])) $value_temp_nivel=$calif["nivel"];
										}
									}
							?>
							<td width="100" align="center"><?php echo strlen($value_temp_nivel)>0 ? 'NIVEL '.$value_temp_nivel : ''; ?></td>
							<?php } ?>
						</tr>
						<?php }} if(substr($data_alumno["grupo"],0,4)!="KIND" && $master_i===0){ ?>
						<tr>
							<td width="300" colspan="3"><strong><?php echo intval($key["id"])===1 ? "Promedio SEP" : "Promedio"; ?></strong></td>
							<?php for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){ ?>
								<td width="100" align="center">
									<?php echo $tot_mat>0 ? roundDown($promedios[$i]/$tot_mat, 2) : ""; $promedios_gral[$i]+=$tot_mat>0 ? roundDown($promedios[$i]/$tot_mat, 2) : 0; ?>
								</td>
							<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }
				if(substr($data_alumno["grupo"],0,4)!="KIND"){
					$promedios_gral=array(1=>0,2=>0,3=>0,4=>0,5=>0);
					foreach($controller->get_data("*","bloques","active=1 AND is_part_avr=1") AS $e => $key){
						$tot_mat=0;
						$promedios=array(1=>0,2=>0,3=>0,4=>0,5=>0);
						if(isset($data_boleta[0]["grado"])){
							$materias_data_main = $controller->get_data_from_query("SELECT * FROM materias WHERE idBloque='".$key["id"]."' AND grado='".$data_boleta[0]["grado"]."' AND active=1");
							if($materias_data_main!==FALSE){
								foreach($materias_data_main AS $f => $mat){
									$tot_mat++;
									for($i=1;$i<6;$i++){
										$existentes=array();
										$value_temp="";
										$id_temp=0;
										if($data_calif!==FALSE){
											foreach($data_calif AS $c=>$calif){
												if(intval($calif["bimestre"])===intval($i) && intval($calif["idMateria"])===intval($mat["id"])){
													$id_temp=$calif["id"];
													$value_temp=$calif["calificacion"];
													if(substr($data_boleta[0]["grupo"],0,4)!="KIND"){
														if(!array_key_exists($mat["id"],$existentes)){
															$promedios[$i]+=floatval($value_temp);
															$existentes[$mat["id"]]=1;
														}
														#$promedios[$i].=$mat["nombre"]." ";
													}
													if(strlen($value_temp)==0) $value_temp=$calif["calificacion_letra"];
												}
											}
										}
									}
								}
								for($i=1;$i<6;$i++) $promedios_gral[$i]+=$tot_mat>0 ? roundDown($promedios[$i]/$tot_mat, 2) : 0;
							}
						}
					}
					if($master_i===0){
				?>
				<table border=".2" bordercolor="#000000" width="800">
					<h4 style="color:#1c2c79">PROMEDIO GENERAL</h4>
					<hr width="800" align="left">
					<thead>
						<tr bgcolor="#1c2c79">
							<th width="300" colspan="3" style="color:#FFF">Promedio</th>
							<?php for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){ ?><th width="100" style="color:#FFF">T<?php echo $i; ?></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="300" colspan="3"><strong>PROMEDIO GENERAL</strong></td>
							<?php for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){ ?><td width="100" align="center"><?php echo roundDown($promedios_gral[$i]/$tot_bloques,2); ?></td><?php } ?>
						</tr>
					</tbody>
				</table>
				<?php }}} ?>
				<table border=".2" bordercolor="#000000" width="800">
					<h4 style="color:#1c2c79">Comentarios y recomendaciones del docente</h4>
					<hr width="800" align="left">
					<thead><tr bgcolor="#1c2c79"><th width="20" style="color:#FFF"><i class="fa fa-bookmark"></i> Trimestre</th>
					<th style="color:#FFF"><i class="fa fa-bookmark"></i> Comentario</th></tr></thead>
					<tbody>
						<?php
							for($i=1;$i<(intval(BIMESTRE_COUNT)+1);$i++){
								$data_coment=$controller->get_data_from_query("SELECT idComentario AS nombre FROM faltas_boletas WHERE SHA2(idAlumno,256)='$idAlumno' AND bim='$i';");
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $data_coment!==FALSE ? $data_coment[0]["nombre"] : ""; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table><br>
				<table border=".2" bordercolor="#000000" width="800">
					<thead>
						<tr bgcolor="#1c2c79">
							<th width="20" style="color:#FFF"><i class="fa fa-bookmark"></i> Faltas</th>
							<th width="20" style="color:#FFF"><i class="fa fa-bookmark"></i> Retardos</th>
						</tr>
					</thead>
					<tbody>
						<?php $data_faltas=$controller->get_data("SUM(faltas) AS faltas, SUM(retardos) AS retardos", "faltas_boletas", "SHA2(idAlumno,256)='$idAlumno'"); if($data_faltas!==FALSE){ ?>
						<tr>
							<td align="center"><?php echo $data_faltas[0]["faltas"]; ?></td>
							<td align="center"><?php echo $data_faltas[0]["retardos"]; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php if(!$hide){ ?>
					<table>
						<tr><td>
							<center><?php echo $controller->get_module('PDF_DOWNLOAD'); ?></center>
							<div class="row"><div class="col-md-2"><br><img id="loader_img" style="display:none;" src="<?php echo base_url(); ?>img/loader.GIF"></div></div>
						</td></tr>
					</table>
				<?php } ?>
				<div id="create_here"></div>
				<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
				<script>
					//alert("ESTAMOS EN PROCESO DE CARGA DE BOLETAS. LA CALIFICACIÓN ACTUAL PODRÁ SER MODIFICADA.");
					$(document).ready(function(){
						$("#download_pdf").on("click",function(){
							var id=$("#main_container").attr("data-alumno");
							if(id.length>0){
								$("#loader_img").show();
								$.post(window.url.base_url+"home/ctrreportes/create_pdf_from_boleta",{id:id},function(resp){
									resp=JSON.parse(resp);
									$("#loader_img").hide();
									var link = "<a id='download_pdf_file' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
									$("#create_here").html(link);
									jQuery("#download_pdf_file")[0].click();
								});
							}
						});
					});
				</script>
			</div>
		</div>
	</body>
</html>
