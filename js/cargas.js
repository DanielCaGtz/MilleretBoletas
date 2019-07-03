$(document).ready(function(){
	$("#form_container").on("submit",function(e){
		e.preventDefault();
		$(".help-block").show();
		uploadFiles();
	});
	$("#form_container_users").on("submit",function(e){
		e.preventDefault();
		$(".help-block").show();
		uploadFilesUsers();
	});
	function uploadFiles(){
		var formData = new FormData(document.getElementById("form_container"));
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
					$.post(window.url.base_url+"home/ctrhome/upload_files",{data:data.result},function(result){
						result=JSON.parse(result);
						$(".help-block").hide();
					});
				}else alert(data.error);
			},
			error: function(data){
				return false;
			}
		});
	}
	function uploadFilesUsers(){
		var formData = new FormData(document.getElementById("form_container_users"));
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
					$.post(window.url.base_url+"home/ctrhome/upload_files_users",{data:data.result},function(result){
						result=JSON.parse(result);
						$(".help-block").hide();
					});
				}else alert(data.error);
			},
			error: function(data){
				return false;
			}
		});
	}
});
$(function () {
	$("#example1").DataTable();
	$('#example2').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"info": true,
		"autoWidth": false
	});
});