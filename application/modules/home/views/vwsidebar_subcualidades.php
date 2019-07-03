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
		<?php /*<script src="<?php echo base_url(); ?>js/jquery.ui.touch-punch.min.js"></script> */ ?>
		<script>
			$(document).ready(function(){
				var abilities = parseInt(window.url.abilities);
				function open_new_tab(url_){
					var win = window.open(url_, '_self');
  					win.focus();
				}
				function show_wait(timeout,main_class,main_title,main_text){
					$("#wait").fadeOut(timeout,function(){
						$(this).removeClass("box-warning").removeClass("box-success").removeClass('box-danger').addClass(main_class);
						$(this).find(".title").html(main_title);
						$(this).find(".body").html(main_text);
						$(this).find(".overlay").hide();
						$(this).fadeIn(timeout);
					});
				}
				function get_li(nombre, id, number){
					var add="<li class='ui-state-default ui-sortable-handle'>";
						add+="<div class='box box-default collapsed-box'>";
							add+="<div class='box-header with-border'>";
								add+="<input data-id='"+id+"' type='text' class='input' style='border:none;width: 85%;font-size: 14px;' value='"+nombre+"'>";
								add+="<input data-id='"+id+"' type='number' class='number' step='0.1' min='1' max='100' style='float:left;width:80px;margin-right:5px;' value='"+number+"'>";
								add+="<div class='box-tools pull-right'>";
									add+="<button type='button' style='margin-left: 5px;' class='btn btn-box-tool edit'><i class='fa fa-pencil'></i></button>";
									add+="<button type='button' style='margin-left: 5px;' class='btn btn-box-tool del'><i class='fa fa-minus'></i></button>";
								add+="</div>";
							add+="</div>";
						add+="</div>";
					add+="</li>";
					return add;
				}
				$("#bimestres").on("change",function(){
					var id = parseInt($(this).val());
					var s = true;
					if (parseInt($("#save").attr("data-unsaved")) > 0) {
						if (confirm("Los cambios que no se han guardado se perderán. ¿Desea continuar?")) s = true;
						else s = false;
					}
					if (s)
						window.location.href = window.url.base_url + "edicion_sub" + "?bimestre=" + id;
					// if(id>1) $("#repetir").show();
					// else $("#repetir").hide();
				});

				$("#sortable").sortable();

				var prev_val_materias;
				var prev_val_bim;

				$("#repetir").focus(function () {
					prev_val_materias = $("#materias").val();
					prev_val_bim = $("#bimestres").val();
				}).click(function () {
					$("#loader").show();
					$("#save").prop('disabled', true);
					var id = $("#materias").val();
					var s = true;
					if (parseInt($("#save").attr("data-unsaved")) > 0) {
						if (confirm("Los cambios que no se han guardado se perderán. ¿Desea continuar?")) s = true;
						else s = false;
					}
					if (s) {
						//$("#sortable").empty();
						const bim_val = parseInt($("#bimestre_a_repetir").val());
						var idCualidad = parseInt($("#aspectos").val());
						if(id !== "0" && bim_val > 0 && idCualidad > 0) {
							$.post (window.url.base_url + "home/ctrhome/consulta_subcualidades_por_nombre_cualidad", {idMateria: id, idCualidad: idCualidad, bim: bim_val}, function (resp) {
								resp=JSON.parse(resp);
								$("#save").attr("data-unsaved",1);
								$("#loader").hide();
								if(resp.success!==false){
									$.each(resp.result,function(i,item){
										$("#sortable").append(get_li(item.nombre, 0, item.valor));
									});
									$("#save").prop('disabled', false);
								}else $("#save").prop('disabled', false);
							});
						}
					}else{
						$("#materias").val(prev_val_materias);
						$("#bimestres").val(prev_val_bim);
						// if(parseInt(prev_val_bim)>1) $("#repetir").show();
						// else $("#repetir").hide();
						$("#loader").hide();
						return false;
					}
				});

				$("#materias").on("change",function(){
					var id = $(this).val();
					if(id !== "0") {
						var bim_val = parseInt($("#bimestres").val());
						$.post (window.url.base_url + "home/ctrhome/consulta_cualidades_por_materia", {idMateria: id, bim: bim_val}, function (resp) {
							resp = JSON.parse(resp);
							$("#save").attr("data-unsaved",0);
							$("#loader").hide();
							$("#aspectos").find('option:not(:first)').remove();
							if(resp.success!==false){
								$.each(resp.result,function(i,item){
									$("#aspectos").append("<option value='"+item.id+"'>"+item.nombre+"</option>");
								});
								$("#save").prop('disabled', false);
							}else $("#save").prop('disabled', false);
						});
					}
				});

				$("#aspectos, #bimestres").focus(function(){
					prev_val_materias = $("#aspectos").val();
					prev_val_bim = $("#bimestres").val();
				}).change(function(){
					$("#loader").show();
					$("#save").prop('disabled', true);
					var idMat = $("#materias").val();
					var id = $("#aspectos").val();
					var s = true;
					if(parseInt($("#save").attr("data-unsaved"))>0){
						if(confirm("Los cambios que no se han guardado se perderán. ¿Desea continuar?")) s = true;
						else s = false;
					}
					if(s){
						$("#sortable").empty();
						if(id!="0"){
							var bim_val=parseInt($("#bimestres").val());
							$.post(window.url.base_url+"home/ctrhome/consulta_subcualidades_por_cualidad",{idCualidad:id, idMateria:idMat, bim:bim_val},function(resp){
								resp=JSON.parse(resp);
								$("#save").attr("data-unsaved",0);
								$("#loader").hide();
								if(resp.success!==false){
									$.each(resp.result,function(i,item){
										$("#sortable").append(get_li(item.nombre, item.id, item.valor));
									});
									$("#save").prop('disabled', false);
								}else $("#save").prop('disabled', false);
							});
						}
					}else{
						$("#aspectos").val(prev_val_materias);
						$("#bimestres").val(prev_val_bim);
						// if(parseInt(prev_val_bim)>1) $("#repetir").show();
						// else $("#repetir").hide();
						$("#loader").hide();
						return false;
					}
				});
				$("#grados").on("change",function(){
					var id=$(this).val();
					$("#materias").val("0");
					if(id!=0){
						$.each($("#materias option"),function(i,item){
							if($(item).attr("data-grado")!=id && $(item).attr("data-grado")!="0") $(item).hide();
							else $(item).show();
						});
					}else{
						$.each($("#materias option"),function(i,item){
							$(item).show();
						});
					}
				});
				$("#add").on("click",function(){
					if($("#materias").val()!="0"){
						$("#save").attr("data-unsaved",1);
						$("#sortable").append(get_li("Nuevo subaspecto",0,0));
					}
				});
				$("#sortable").on("click",".edit",function(){
					$(this).parent().parent().find(".input").focus();
				});
				$("#save").on("click",function(){
					if($("#materias").val()!="0" && $("#aspectos").val()!="0" && !$("#save").prop('disabled')){
						var lis=new Array();
						$.each($("#sortable li"),function(i,item){
							var child=new Array();
							var s=0;
							child[s++]=$(item).find(".input").val();
							child[s++]=$(item).find(".input").attr("data-id");
							if(abilities>0)
								child[s++]=$(item).find(".number").val();
							lis[i]=child;
						});
						show_wait(1000,"box-warning","Guardando","Por favor espere.");
						$.post(window.url.base_url+"home/ctrhome/save_subcualidades",{data:lis, idCualidad:$("#aspectos").val(), idMateria:$("#materias").val(), grado:$("#materias option:selected").attr("data-grado"), bim:$("#bimestres").val()},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								$.each($("#sortable li .input"),function(i,item){
									$(item).attr("data-id",resp.result[i]);
								});
								setTimeout(function(){
									show_wait(1600,"box-success","Éxito","Elementos guardados exitosamente.");
								},1200);
								$("#save").attr("data-unsaved",0);
							}else{
								setTimeout(function(){
									show_wait(1600,"box-danger","Error","Hubo un problema. Intente más tarde.");
								},1200);
							}
						});
					}
				});
				$("#sortable").on("click",".del",function(){
					var parent=$(this).parent().parent();
					var elem=parent.parent().parent();
					var id_main=parent.find(".input").attr("data-id");
					if(parseInt(id_main)>0){
						$.post(window.url.base_url+"home/ctrhome/consult_subcualidad_to_delete",{id:parseInt(id_main)},function(resp){
							resp=JSON.parse(resp);
							var s=false;
							if(resp.result===false){
								if(confirm("¿Realmente desea eliminar este elemento? Todas las calificaciones relacionadas se eliminarán.")) s=true;
							}else var s=true;
							if(s!==false){
								show_wait(1000,"box-warning","Guardando","Por favor espere.");
								$.post(window.url.base_url+"home/ctrhome/delete_cualidad",{id:parseInt(id_main)},function(result){
									result=JSON.parse(result);
									if(result.success!==false){
										elem.remove();
										show_wait(1600,"box-success","Éxito","Elemento eliminado exitosamente.");
									}else{
										show_wait(1600,"box-danger","Error","Hubo un problema. Intente más tarde.");
									}
								});
							}
						});
					}else elem.remove();
				});
				$(".content .callout .close").on("click",function(){
					$(this).parent().remove();
				});
				$(".close_wait").on("click",function(){
					$(this).parent().parent().parent().hide();
				});			
			});
  		</script>
	</body>
</html>
