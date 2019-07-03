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
			var ModalEffects = (function() {

				function init() {

					var overlay = document.querySelector( '.md-overlay' );
					var overlay_2 = document.querySelector( '.cerrar_logros' );
					var abilities = parseInt(window.url.abilities);
					var logros_decimales = parseInt(window.url.logros_decimales);

					[].slice.call( document.querySelectorAll( '.md-trigger' ) ).forEach( function( el, i ) {

						var modal = document.querySelector( '#' + el.getAttribute( 'data-modal' ) ),
							close = modal.querySelector( '.md-close' );

						function removeModal( hasPerspective ) {
							classie.remove( modal, 'md-show' );

							if( hasPerspective ) {
								classie.remove( document.documentElement, 'md-perspective' );
							}
						}

						function removeModalHandler() {
							removeModal( classie.has( el, 'md-setperspective' ) );
						}

						el.addEventListener( 'click', function( ev ) {
							$("#logros_ul").empty();
							$("#eval").empty();
							classie.add( modal, 'md-show' );
							var id=$(this).attr("data-materia");
							var bim=$(this).attr("data-bim");
							var idBloque=$(this).attr("data-bloque");
							//var eval1=$(this).attr("data-eval1");
							//var eval2=$(this).attr("data-eval2");
							//var eval3=$(this).attr("data-eval3");
							$(".md-close").attr("data-materia",id);
							$(".md-close").attr("data-bim",bim);
							var logros=$("#calificacion-"+id+"-"+bim).attr("data-logros");
							var logros_sub=$("#calificacion-"+id+"-"+bim).attr("data-subaspectos");
							var eval=$("#calificacion-"+id+"-"+bim).attr("data-eval");
							var is_complex=parseFloat($("#calificacion-"+id+"-"+bim).attr("data-iscomplex"));

							$.post(window.url.base_url+"home/ctrhome/consulta_cualidades_por_materia_con_subcualidades",{idMateria:id, bim:bim},function(resp){
								resp=JSON.parse(resp);
								var logros_temp = logros.split(",");
								var logros_sub_temp = logros_sub.split(",");
								//var eval_temp = eval.split(",");
								var grado=$("#grado").val();
								$("#logros_ul").empty();
								$("#eval").empty();
								if(resp.success!==false){
									$.each(resp.result,function(i,item){
										var temp='<li class="cualidad_element" data-id="'+item.id+'">';

										var value_ = 0;
										$.each(logros_temp,function(e,key){
											var cual_temp=key.split("@");
											if(parseFloat(cual_temp[0])===parseFloat(item.id)) value_=parseFloat(cual_temp[1]);
										});
										var valor_ = 'data-valor="'+item.valor+'"';

										temp+=	'<div class="box box-default collapsed-box">';
										temp+=		'<div class="box-header with-border">';
										temp+=			'<h3 class="box-title">'+item.nombre+': <span '+valor_+' class="calif_cualidad">'+value_+'</span></h3>';
										temp+=			'<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div></div>';
										temp+=		'<div class="box-body childs_container" style="display: none;color:#333;">';
										//*
										if(item.childs.length>0){
											$.each(item.childs, function(j,jitem){
												var value_temp = 0;
												$.each(logros_sub_temp,function(e,key){
													var cual_temp=key.split("@");
													if(parseFloat(cual_temp[0])===parseFloat(jitem.id)) value_temp=parseFloat(cual_temp[1]);
												});
												temp+='<p><strong id="text_details_container">'+jitem.nombre+'</strong><br>';
												var valor_temp = 'data-id="'+jitem.id+'" data-valor="'+jitem.valor+'"';
												temp+='<input '+valor_temp+' class="select" style="color:black;width: 70px;" type="number" min="0" max="10" step="0.1" value="'+value_temp+'" ></p>';
											});
										}
										/* */
										temp+=			'<button data-materia="'+id+'" data-bim="'+bim+'" class="md-subclose" style="background:#2bb2c0">Guardar Subaspectos</button>';
										temp+=		'</div>';
									  	temp+=	'</div>';
										temp+='</li>';
										$("#logros_ul").append(temp);
									});
								}
								overlay.removeEventListener( 'click', removeModalHandler );
								overlay_2.addEventListener( 'click', removeModalHandler );

								if( classie.has( el, 'md-setperspective' ) ) {
									setTimeout( function() {
										classie.add( document.documentElement, 'md-perspective' );
									}, 25 );
								}
							});

						});

						$("#logros_ul").on('click','.md-subclose',function(ev){
							ev.stopPropagation();
							var tot=0;
							var bim=$(this).attr("data-bim");
							var materia=$(this).attr("data-materia");
							if($("#calificacion-"+materia+"-"+bim).attr("data-subaspectos")!=undefined) var result=$("#calificacion-"+materia+"-"+bim).attr("data-subaspectos");
							else var result="";
							$.each($(this).parent().find(".select"),function(i,item){
								if(result!="")result+=",";
								tot+=parseFloat($(item).val())*parseFloat($(item).attr("data-valor"))/100;
								result+=$(item).attr("data-id")+"@"+$(item).val();
							});
							$(this).parent().parent().find(".calif_cualidad").html(tot.toFixed(1));
							console.log(result,materia,bim);
							$("#calificacion-"+materia+"-"+bim).attr("data-subaspectos",result);
						});

						close.addEventListener( 'click', function( ev ) {
							ev.stopPropagation();
							var bim=$(this).attr("data-bim");
							var materia=$(this).attr("data-materia");
							var result="";
							var result_eval="";
							var promedio=0;
							var grado=$("#grupo").val();
							var tot=0;
							var calif_total=0;
							$.each($("#logros_ul li"),function(i,item){
								if(result!="")result+=",";
								//if(parseFloat($(item).find(".select").val())>0){
									if(abilities==0){
										promedio+=parseFloat($(item).find(".calif_cualidad").val());
										tot++;
									}else{
										var valor_temp = parseFloat($(item).find(".calif_cualidad").attr("data-valor"))/10;
										var temp_result = (parseFloat($(item).find(".calif_cualidad").html()) * valor_temp / 10);
										//console.log(valor_temp,$(item).find(".calif_cualidad").html(),temp_result);
										if(parseFloat(temp_result)==0) temp_result=10.0;
										promedio += temp_result;
										tot++;
									}
								//}
								result+=$(item).attr("data-id")+"@"+$(item).find(".calif_cualidad").html();
							});
							//console.log(promedio);
							$("#calificacion-"+materia+"-"+bim).attr("data-logros",result);
							var idBloque=$("#calificacion-"+materia+"-"+bim).attr("data-bloque");
							//if(abilities==0){
							if(grado.substring(0,3)==="KIN"){
								$("#calificacion-"+materia+"-"+bim).attr("data-eval",result_eval);
								var is_complex=parseFloat($("#calificacion-"+materia+"-"+bim).attr("data-iscomplex"));
								if(grado.substring(0,3)!=="KIN" && is_complex>0){
									calif_total+=parseFloat(promedio/tot)*4/10;
									if(isNaN(calif_total)) calif_total=0;
									if(parseFloat(calif_total)<5) calif_total=5;
									$("#calificacion-"+materia+"-"+bim).val(parseFloat(calif_total).toFixed(1));
								}else if(grado.substring(0,3)!=="KIN" && is_complex==0){
									calif_total+=parseFloat(promedio/tot);
									if(isNaN(calif_total)) calif_total=0;
									if(parseFloat(calif_total)<5) calif_total=5;
									$("#calificacion-"+materia+"-"+bim).val(parseFloat(calif_total).toFixed(1));
								}else if(grado.substring(0,3)=="KIN"){
									if(parseInt(idBloque)===2){ //INGLÉS
										if(parseFloat(promedio/tot)<7.0) $("#calificacion-"+materia+"-"+bim).val("NH");
										else if(parseFloat(promedio/tot)>=7.0 && parseFloat(promedio/tot)<8.0) $("#calificacion-"+materia+"-"+bim).val("IP");
										else if(parseFloat(promedio/tot)>=8.0 && parseFloat(promedio/tot)<9.0) $("#calificacion-"+materia+"-"+bim).val("A");
										else if(parseFloat(promedio/tot)>=9.0 && parseFloat(promedio/tot)<10.0) $("#calificacion-"+materia+"-"+bim).val("G");
										else if(parseFloat(promedio/tot)>=10.0) $("#calificacion-"+materia+"-"+bim).val("VG");
									}else{
										if(parseFloat(promedio/tot)<7.0) $("#calificacion-"+materia+"-"+bim).val("NA");
										else if(parseFloat(promedio/tot)>=7.0 && parseFloat(promedio/tot)<8.0) $("#calificacion-"+materia+"-"+bim).val("EP");
										else if(parseFloat(promedio/tot)>=8.0 && parseFloat(promedio/tot)<9.0) $("#calificacion-"+materia+"-"+bim).val("P");
										else if(parseFloat(promedio/tot)>=9.0 && parseFloat(promedio/tot)<10.0) $("#calificacion-"+materia+"-"+bim).val("B");
										else if(parseFloat(promedio/tot)>=10.0) $("#calificacion-"+materia+"-"+bim).val("MB");
									}
								}
							}else{
								$("#calificacion-"+materia+"-"+bim).val(parseFloat(promedio).toFixed(1));
							}
							removeModalHandler();
						});

					} );

				}

				init();

			})();

			$(document).ready(function(){
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
				function save_all(){
					show_wait(1000,"box-warning","Guardando","Por favor espere.");
					var lis=new Array();
					$.each($(".calificaciones_container"),function(i,item){
						var child=new Array();
						var s=0;
						child[s++]=parseInt($(item).attr("data-calif"));//0
						child[s++]=parseInt($(item).attr("data-nivel"));//1
						child[s++]=$(item).attr("data-id");//2
						child[s++]=$(item).attr("data-materia");//3
						child[s++]=$(item).attr("data-bim");//4
						if(parseInt($(item).attr("data-calif"))>0){
							child[s++]=$(item).find(".calificacion").val();//5
							child[s++]=$(item).find(".calificacion").attr("data-logros");//6
							child[s++]=$(item).find(".calificacion").attr("data-subaspectos");//7
						}
						if(parseInt($(item).attr("data-nivel"))>0){
							child[s++]=$(item).find(".nivel_aprendizaje").val();//5/8
						}
						lis[i]=child;
					});
					$.post(window.url.base_url+"home/ctrhome/save_calificaciones",{data:lis, idBoleta:$("#idBoleta").val(), idAlumno:$("#idAlumno").val(), grado:$("#grado").val(), comentario:$("#comentarios").val(), comment:$("#comentarios_en").val()},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							$.each($(".calificacion"),function(i,item){
								$(item).attr("data-id",resp.result[i]);
							});
							setTimeout(function(){
								show_wait(1600,"box-success","Éxito","Elementos guardados exitosamente.");
							},1200);
						}else{
							setTimeout(function(){
								show_wait(1600,"box-danger","Error","Hubo un problema. Intente más tarde.");
							},1200);
						}
					});
				}
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
				function uploadFiles(){
					var formData = new FormData(document.getElementById("form_container"));
					var grado = $("#grados").val();
					var grupo = $("#grupo").val();
					var bim = $("#bimestre").val();
					$.ajax({
						url: window.url.base_url+'tools/ctrtools/doupload/file-5',
						type: 'POST',
						data:  formData,
						mimeType:"multipart/form-data",
						contentType: false,
						cache: false,
						processData:false,
						success : function(data){
							data=JSON.parse(data);
							if(data.success!==false){
								$.post(window.url.base_url+"home/ctrfiles/upload_calificaciones",{data:data.result,grado:grado,grupo:grupo,bim:bim},function(result){
									result=JSON.parse(result);
									$(".help-block").hide();
									if(result.success!==false){
										alert("Las calificaciones se han guardado exitosamente.");
									}
								});
							}else{
								alert(data.error);
							}
						},
						error: function(data){
							return false;
						}
					});
				}
				$("#form_container").on("submit",function(e){
					e.preventDefault();
					$(".help-block").show();
					uploadFiles();
				});
				$("#swap_method").on("click",function(){
					$(this).find("span").toggle();
					$(".toggle_this").toggle();
				});
				$("#main_content .just_read").on("keydown",function(e){
					e.preventDefault();
					return false;
				});
				$("#main_content").on("focusout focusin",".calificacion",function(){
					var val=$(this).val();
					if(val!=""){
						var grado=$(this).attr("data-grado");
						if(grado.substring(0,3)!="KIN"){
							var num=$(this).val();
							$(this).val(parseFloat(num).toFixed(1));
						}
						//save_all();
					}
				});				
				$("#consulta_alumno").on("click",function(){
					var id=$("#alumnos").val();
					if(parseInt(id)>0) open_new_tab(window.url.base_url+"editar_boleta/"+id);
				});
				$("#alumnos").on("change",function(){
					var id=$(this).val();
					if(parseInt(id)>0) open_new_tab(window.url.base_url+"editar_boleta/"+id);
				});
				$("#grados").on("change",function(){
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
				$("#send_calif").on("click",function(){
					save_all();
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
