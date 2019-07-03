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

				$("#main_content").on("click",".add_alumno",function(){
					$.post(window.url.base_url+"home/ctrplaneacion/get_new_alumno_row",{},function(resp){
						$("#alumnos_seguimiento").find("tbody").append(resp);
					});
				});
				$("#alumnos_seguimiento").on("click",".remove_alumno",function(){$(this).parent().parent().parent().remove();});
				$("#main_content").on("click",".add_secuencia",function(){
					$.post(window.url.base_url+"home/ctrplaneacion/get_new_secuencia_row",{},function(resp){
						$("#secuencias_didacticas").find("#table_body").append(resp);
					});
				});
				$("#secuencias_didacticas").on("click",".remove_secuencia",function(){$(this).parent().parent().parent().remove();});

				$("#main_form").on("submit",function(e){
					e.preventDefault();
					show_wait("box-warning","Guardando","Por favor espere",1);
					var alumnos = new Array();
					var formData = new FormData(document.getElementById("main_form"));
					$.each($(".campo_editable_main"),function(i,item){
						formData.append($(item).attr("id"),$(item).val());
					});
					$.each($(".parent_data_container"),function(i,item){
						if($(item).attr("data-type")=="multiple"){
							$.each($(item).find(".campo_editable"),function(j,jitem){
								if($(jitem).is(':checked'))
									formData.append($(jitem).attr("data-name"),'1');
							});
						}else if($(item).attr("data-type")=="single"){
							$.each($(item).find(".second_container"),function(k,jitem){
								var name = $(jitem).attr("data-name");
								var val = 0;
								$.each($(jitem).find("input[name='"+name+"']:checked"),function(e,key){
									val+=parseInt($(key).val());
								});
								if(parseInt(val)>0)
									formData.append(name,val);
							});
						}
					});
					$.each($("#alumnos_seguimiento").find(".second_container"),function(i,item){
						var temp_array = new Array();
						var x=0;
						$.each($(item).find(".get_raw_data"),function(j,jitem){
							if($(jitem).attr("data-type")=="select")
								temp_array[x++]=$(jitem).val();
							else{
								if($(jitem).is(':checked'))
									temp_array[x++]="1";
								else
									temp_array[x++]="0";
							}
						});
						alumnos[i]=temp_array;
					});
					formData.append('alumnos',JSON.stringify(alumnos));

					var secuencias = new Array();
					$.each($("#secuencias_didacticas").find(".second_container"),function(i,item){
						var temp_array = new Array();
						var x=0;
						$.each($(item).find(".get_raw_data"),function(j,jitem){
							if($(jitem).attr("data-type")=="text"){
								var temp = new Array();
								temp[0] = $(jitem).attr("data-id");
								temp[1] = $(jitem).val();
								temp[2] = $(jitem).attr("data-name");
								temp[3] = i;
								temp_array[x++]=temp;
							}else{
								if($(jitem).is(':checked')){
									var temp = new Array();
									temp[0] = $(jitem).attr("data-id");
									temp[1] = "1";
									temp[2] = $(jitem).attr("data-name");
									temp[3] = i;
									temp_array[x++]=temp;
								}else{
									var temp = new Array();
									temp[0] = $(jitem).attr("data-id");
									temp[1] = "0";
									temp[2] = $(jitem).attr("data-name");
									temp[3] = i;
									temp_array[x++]=temp;
								}
							}
						});
						secuencias[i]=temp_array;
					});
					formData.append('secuencias',JSON.stringify(secuencias));

					//*
					$.ajax({
						url: window.url.base_url+'home/ctrplaneacion/save_planeacion_SEC',
						type: 'POST',
						data:  formData,
						mimeType:"multipart/form-data",
						contentType: false,
						cache: false,
						processData:false,
						success : function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								show_wait("box-success","Éxito","La planeación se ha guardado exitosamente",0);
							}else show_wait("box-danger","ERROR",resp.msg,0);
						},
						error: function(data){
							show_wait("box-danger","ERROR",resp.msg,0);
							return false;
						}
					});
					/* */
				});
			});
		</script>
	</body>
</html>
