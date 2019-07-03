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
				
				$('#example1').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false
				});
				$("#example1").on("click",".ver_planeacion",function(){
					var id=$(this).attr("data-id");
					window.location.href = window.url.base_url+"consultar_planeacion/"+id;
				});

				$("#main_form").on("submit",function(e){
					e.preventDefault();
					var com=$("#comentarios").val();
					var id=$("#comentarios").attr("data-id");
					if(com!==""){
						show_wait("box-warning","Guardando","Por favor espere",1);
						$.post(window.url.base_url+"home/ctrplaneacion/save_comentarios_direccion",{com:com,id:id},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								show_wait("box-success","Éxito","La planeación se ha guardado exitosamente",0);
							}else show_wait("box-danger","ERROR",resp.msg,0);
						});
					}
				});
			});
		</script>
	</body>
</html>
