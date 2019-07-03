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
				function open_new_tab(url_){
					var win = window.open(url_, '_self');
  					win.focus();
				}
				function show_wait(timeout,main_class,main_title,main_text){
					timeout=timeout/100;
					$("#wait").fadeOut(timeout,function(){
						$(this).removeClass("box-warning").removeClass("box-success").removeClass('box-danger').addClass(main_class);
						$(this).find(".title").html(main_title);
						$(this).find(".body").html(main_text);
						$(this).find(".overlay").hide();
						$(this).fadeIn(timeout);
					});
				}
				function get_li(nombre="Nueva materia", id=0, calif=0, nivel=0){
					var add="<li class='ui-state-default ui-sortable-handle'>";
						add+="<div class='box box-default collapsed-box'>";
							add+="<div class='box-header with-border'>";
								add+="<input data-id='"+id+"' type='text' style='border:none;width: 60%;font-size: 14px;' value='"+nombre+"'>";
								add+="<div class='box-tools pull-right'>";
									add+="<label style='font-size: 14px !important;margin-right:10px;'><input type='checkbox' "+(calif>0?"checked":"")+" class='has_calif'> Calificación</label>";
									add+="<label style='font-size: 14px !important;'><input type='checkbox' class='has_nivel' "+(nivel>0?"checked":"")+"> N.Desempeño</label>";
									add+="<button type='button' style='margin-left: 5px;' class='btn btn-box-tool edit'><i class='fa fa-pencil'></i></button>";
									add+="<button type='button' style='margin-left: 5px;' class='btn btn-box-tool del'><i class='fa fa-minus'></i></button>";
								add+="</div>";
							add+="</div>";
						add+="</div>";
					add+="</li>";
					return add;
				}
				$("#sortable").sortable();
				var prev_val_grados;
				var prev_val_bloques;
				$("#grados, #bloques").focus(function(){
					prev_val_grados = $("#grados").val();
					prev_val_bloques = $("#bloques").val();
				}).change(function(){
					$("#loader").show();
					$("#save").prop('disabled', true);
					var id=$("#bloques").val();
					var s = true;
					if(parseInt($("#save").attr("data-unsaved"))>0){
						if(confirm("Los cambios que no se han guardado se perderán. ¿Desea continuar?")) s = true;
						else s = false;
					}
					if(s){
						$("#sortable").empty();
						if(id!="0"){
							$.post(window.url.base_url+"home/ctrhome/consulta_materias_por_grado",{idBloque:id, grado:$("#grados").val()},function(resp){
								resp=JSON.parse(resp);
								$("#save").attr("data-unsaved",0);
								$("#loader").hide();
								if(resp.success!==false){
									$.each(resp.result,function(i,item){
										$("#sortable").append(get_li(item.nombre, item.id, item.has_calif, item.has_nivel));
									});
									$("#save").prop('disabled', false);
								}else $("#save").prop('disabled', false);
							});
						}
					}else{
						$("#grados").val(prev_val_grados);
						$("#bloques").val(prev_val_bloques);
						$("#loader").hide();
						return false;
					}
				});
				$("#add").on("click",function(){
					if($("#grados").val()!="0"){
						$("#save").attr("data-unsaved",1);
						$("#sortable").append(get_li());
					}
				});
				$("#sortable").on("click",".edit",function(){
					$(this).parent().parent().find("input[type=text]").focus();
				});
				$("#save").on("click",function(){
					if($("#grados").val()!="0" && !$("#save").prop('disabled')){
						var lis=new Array();
						$.each($("#sortable li"),function(i,item){
							var child=new Array();
							var s=0;
							child[s++]=$(item).find("input[type=text]").val();
							child[s++]=$(item).find("input[type=text]").attr("data-id");
							child[s++]=$(item).find(".has_calif").is(':checked')?"1":"0";
							child[s++]=$(item).find(".has_nivel").is(':checked')?"1":"0";
							lis[i]=child;
						});
						show_wait(1000,"box-warning","Guardando","Por favor espere.");
						$.post(window.url.base_url+"home/ctrhome/save_materias",{data:lis, idBloque:$("#bloques").val(), is_complex:$("#bloques option:selected").attr("data-complex"), grado:$("#grados").val()},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								$.each($("#sortable li input"),function(i,item){
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
					var id_main=parent.find("input").attr("data-id");
					if(parseInt(id_main)>0){
						$.post(window.url.base_url+"home/ctrhome/consult_materias_to_delete",{id:parseInt(id_main)},function(resp){
							resp=JSON.parse(resp);
							var s=false;
							if(resp.result===false){
								if(confirm("¿Realmente desea eliminar este elemento? Todas las calificaciones relacionadas se eliminarán.")) s=true;
							}else var s=true;
							if(s!==false){
								show_wait(1000,"box-warning","Guardando","Por favor espere.");
								$.post(window.url.base_url+"home/ctrhome/delete_materia",{id:parseInt(id_main)},function(result){
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
