		<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
		<script>
			$(document).ready(function(){
				function show_wait(show,title,title2,over){
					$("#wait").fadeOut(800,function(){
						$(this).removeClass("box-danger").removeClass("box-success").removeClass("box-warning").addClass(show);
						$(this).find(".title").html(title);
						$(this).find(".body").html(title2);
						$(this).show();
						$('html, body').animate({scrollTop: $("#wait").offset().top}, 1000);
						if(parseInt(over)>0) $(this).find(".overlay").show(); else $(this).find(".overlay").hide();
						$(this).fadeIn(900);
					});
				}
				function update_grupos(id,grupoid){
					$("#"+grupoid).val("0");
					if(id!=0){
						$.each($("#"+grupoid+" option"),function(i,item){
							if($(item).attr("data-grado")!=id && $(item).attr("data-grado")!="0") $(item).hide();
							else $(item).show();
						});
						$.each($("#alumnos option"),function(i,item){
							if($(item).attr("data-grado")!=id && $(item).attr("data-grado")!="0") $(item).hide();
							else $(item).show();
						});
					}else{
						$.each($("#"+grupoid+" option"),function(i,item){
							$(item).show();
						});
						$.each($("#alumnos option"),function(i,item){
							$(item).show();
						});
					}
				}
				update_grupos($("#grados").val(),"grupos");
				
				$("#grados").on("change",function(){
					var id=$(this).val();
					var grupoid = $(this).attr("data-grupoid");
					update_grupos(id,grupoid);
				});

				$("#grupos").on("change",function(){
					var bim=$("#bimestres").val();
					var grupo=$("#grupos").val();
					var grado=$("#grados").val();
					$.post(window.url.base_url+"home/ctrhome/consulta_detalles_reporte",{bim:bim, grupo:grupo, grado:grado},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							$("#alumnos").val(resp.result.idAlumno);
							$("#director").val(resp.result.dirTec);
							$("#maestro").val(resp.result.prof);
							$("#teacher").val(resp.result.teacher);
						}else{
							$("#alumnos").val("0");
							$("#director").val("0");
							$("#maestro").val("0");
							$("#teacher").val("0");
						}
					});
				});
				$("#save_esfuerzo").on("click",function(){
					var bim=$("#bimestres").val();
					var grupo=$("#grupos").val();
					var grado=$("#grados").val();
					var idAlumno=$("#alumnos").val();
					var dirTec=$("#director").val();
					var prof=$("#maestro").val();
					var teacher=$("#teacher").val();
					show_wait("box-warning","Guardando","Por favor espere.");
					$.post(window.url.base_url+"home/ctrhome/save_detalles_reporte",{bim:bim, grupo:grupo, grado:grado, idAlumno:idAlumno, dirTec:dirTec, prof:prof, teacher:teacher},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							show_wait("box-success","Éxito","Elementos guardados exitosamente.");
						}else{
							show_wait("box-danger","Error","Hubo un problema. Intente más tarde.");
						}
					});
				});
			});
		</script>
	</body>
</html>
