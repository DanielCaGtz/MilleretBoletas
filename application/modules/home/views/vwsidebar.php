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
				$("#publicar_boleta").on("click",function(){
					$("#publicar_img").show();
					$.post(window.url.base_url+"home/ctrhome/publish_boleta",{},function(resp){
						$("#publicar_img").hide();
						if(resp.success!==false) alert("Las boletas serán publicadas a la mayor brevedad.");
						else alert("Hubo un error. Favor de contactar al administrador del sistema.");
					});
				});
			});
		</script>
	</body>
</html>
