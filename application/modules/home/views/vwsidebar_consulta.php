		<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
		<script src="<?php echo base_url(); ?>js/js-sha256.js"></script>
		<script>
			$(document).ready(function(){
				function open_new_tab(url_){
					var win = window.open(url_, '_blank');
  					win.focus();
				}
				$("#download_pdf").on("click",function(){
					var id=$("#alumnos").val();
					$("#loader_img").show();
					if(parseInt(id)>0){
						$.post(window.url.base_url+"home/ctrreportes/create_pdf_from_boleta",{id:id},function(resp){
							resp=JSON.parse(resp);
							$("#loader_img").hide();
							var link = "<a id='download_pdf_file' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
							$("#create_here").html(link);
							jQuery("#download_pdf_file")[0].click();
						});
					}
				});
				update_grupos($("#grados2").val(),"grupos2");
				$("#grados2").val($("#grados2 option:first").val());
				/*
				$("#reporte_sencillo").on("click",function(){
					var grado=$("#grados2").val();
					var bim=$("#bimestres").val();
					$("#loader_img_2").show();
					$.post(window.url.base_url+"home/ctrhome/get_reporte_full_sencillo",{grado:grado, bim:bim},function(resp){
						resp=JSON.parse(resp);
						$("#loader_img_2").hide();
						var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
						$("#create_here").html(link);
						jQuery("#download_xlsx")[0].click();
					});
				});
				/* */
				$("#reporte_full").on("click",function(){
					var grado=$("#grados2").val();
					var grupo=$("#grupos2").val();
					var bim=$("#bimestres").val();
					$("#loader_img_2").show();
					$.post(window.url.base_url+"home/ctrhome/get_reporte_full",{grado:grado, bim:bim, grupo:grupo},function(resp){
						resp=JSON.parse(resp);
						$("#loader_img_2").hide();
						var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
						$("#create_here").html(link);
						jQuery("#download_xlsx")[0].click();
					});
				});
				/*
				$("#reporte_individual").on("click",function(){
					var id=$("#alumnos").val();
					if(parseInt(id)>0){
						$("#loader_img").show();
						$.post(window.url.base_url+"home/ctrhome/get_reporte_individual",{id:id},function(resp){
							resp=JSON.parse(resp);
							$("#loader_img").hide();
							var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
							$("#create_here").html(link);
							jQuery("#download_xlsx")[0].click();
						});
					}
				});
				$("#reporte_completo").on("click",function(){
					var grupo=$("#grupos").val();
					var grado=$("#grados").val();
					if(grado!="0"){
						$("#loader_img").show();
						$.post(window.url.base_url+"home/ctrhome/get_reporte_completo",{grupo:grupo, grado:grado},function(resp){
							resp=JSON.parse(resp);
							$("#loader_img").hide();
							var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
							$("#create_here").html(link);
							jQuery("#download_xlsx")[0].click();
						});
					}
				});
				/* */
				$("#consulta_alumno").on("click",function(){
					var id=$("#alumnos").val();
					if(parseInt(id)>0) open_new_tab(window.url.base_url+"consulta_boleta/"+sha256(id));
				});
				$("#grados,#grados2").on("change",function(){
					var id=$(this).val();
					var grupoid = $(this).attr("data-grupoid");
					update_grupos(id,grupoid);
				});
				$("#grupos").on("change",function(){
					var id=$("#grupos").val();
					var id_1=$('#grupos').find(":selected").attr("data-grado");
					$("#alumnos").val("0");
					if(id!=0){
						$.each($("#alumnos option"),function(i,item){
							if(($(item).attr("data-grupo")==id && $(item).attr("data-grado")==id_1) || $(item).attr("data-grupo")=="0") $(item).show();
							else $(item).hide();
						});
					}else{
						$.each($("#alumnos option"),function(i,item){
							if(($(item).attr("data-grado")==id_1) || $(item).attr("data-grupo")=="0") $(item).show();
							else $(item).hide();
						});
					}
				});
				$(".content .callout .close").on("click",function(){
					$(this).parent().remove();
				});
				$(".close_wait").on("click",function(){
					$(this).parent().parent().parent().hide();
				});
				function update_grupos(id,grupoid){
					if(grupoid=="grupos") $("#alumnos").val("0");
					$("#"+grupoid).val("0");
					if(id!=0){
						if(grupoid=="grupos")
							$.each($("#alumnos option"),function(i,item){
								if($(item).attr("data-grado")!=id && $(item).attr("data-grado")!="0") $(item).hide();
								else $(item).show();
							});
						$.each($("#"+grupoid+" option"),function(i,item){
							if($(item).attr("data-grado")!=id && $(item).attr("data-grado")!="0") $(item).hide();
							else $(item).show();
						});
					}else{
						if(grupoid=="grupos")
							$.each($("#alumnos option"),function(i,item){
								$(item).show();
							});
						$.each($("#"+grupoid+" option"),function(i,item){
							$(item).show();
						});
					}
				}
			});
  		</script>
	</body>
</html>
