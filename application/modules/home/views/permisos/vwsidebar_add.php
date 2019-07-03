		<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="<?php echo base_url(); ?>plugins/select2/dist/js/select2.js"></script>
		<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
		<script src="<?php echo base_url(); ?>js/classie.js"></script>
		<script>
			$(document).ready(function(){
				$("#materias").select2({tags:true});
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
				function add_materia_to_container(name,id,grado){
					$("#materias_container").append('<label class="materia_element" data-id="'+id+'" data-grado="'+grado+'">'+name+ ' <a href="javascript:;" class="delete_materia"><i class="fa fa-close"></i></a></label>');
				}
				
				$("#send_calif").on("click",function(){
					var main_id=$("#main_content").attr("data-id");
					var lis=new Array();
					$.each($(".campo_editable_main"),function(i,item){
						var child=new Array();
						var s=0;
						if($(item).attr("type")=="text"){
							child[s++]=$(item).val();
							child[s++]=$(item).attr("data-field");
						}else if($(item).attr("type")=="checkbox"){
							if($(item).is(":checked")) child[s++]=1;
							else child[s++]=0;
							child[s++]=$(item).attr("data-field");
						}
						lis[i]=child;
					});

					var materias=new Array();
					$.each($("#materias_container").find(".materia_element"),function(i,item){
						var child=new Array();
						var s=0;
						child[s++]=$(item).attr("data-id");
						child[s++]=$(item).attr("data-grado");
						materias[i]=child;
					});
					show_wait("box-warning","Guardando","Por favor espere.",1);
					$.post(window.url.base_url+"home/ctrpermisos/save_permisos_per_user",{data:lis, materias:materias, main_id:main_id},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							show_wait("box-success","Éxito","Usuario guardado exitosamente.",0);
						}else{
							show_wait("box-danger","Error","Hubo un problema. Intente más tarde.",0);
						}
					});
				});

				$("#materias_container").on("click",".delete_materia",function(){
					$(this).parent().remove();
				});
				$("#advanced_search").on("click",function(){
					$("#advanced_options").toggle();
				});
				$("#remove_all").on("click",function(){
					$.each($("#materias_container").find(".materia_element"),function(i,item){
						$(item).remove();
					});
				});

				$("#add_materia").on("click",function(){
					var mat=parseInt($("#materias").val());
					var grado=$("#grado_filter").val();
					var materia=$("#materia_filter").val();
					if(grado != "" || materia != ""){
						$.post(window.url.base_url+"home/ctrpermisos/consultar_materias",{grado:grado,materia:materia},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								$.each(resp.result,function(i,item){
									add_materia_to_container(item.nombre+" - "+item.grado,item.id,item.grado);
								});
								$("#grado_filter").val("");
								$("#materia_filter").val("");
							}
						});
					}else if(mat>0){
						add_materia_to_container($("#materias option:selected").text(),mat,$("#materias option:selected").attr("data-grado"));
					}
				});
			});
		</script>

	</body>
</html>