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
		<script src="<?php echo base_url(); ?>js/classie.js"></script>
		<script>
			$(document).ready(function(){
				$('#example1').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false
				});
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
				$("#example1").on("click",".eliminar_registro",function(){
					var id=$(this).attr("data-id");
					var elem=$(this).parent().parent();
					if(confirm("¿Realmente desea eliminar este usuario?")){
						$.post(window.url.base_url+"home/ctrpermisos/eliminar_user",{id:id},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								elem.remove();
							}
						});
					}
				});
				$("#example1").on("click",".editar_registro",function(){
					var id=$(this).attr("data-id");
					window.location.href = window.url.base_url+"editar_usuario/"+id;
				});
				$("#agregar_nuevo").on("click",function(){
					window.location.href = window.url.base_url+"agregar_usuario";
				});
			});
		</script>

	</body>
</html>