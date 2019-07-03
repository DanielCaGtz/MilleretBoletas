<?php

class ctrModules extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		date_default_timezone_set('America/Mexico_City');
	}

	public function get_self(){return $this;}

	public function get_module_PDF_DOWNLOAD(){
		return '<div class="col-md-2"><br><button type="button" id="download_pdf" class="btn btn-block btn-warning btn-lg">Descargar PDF</button></div>';
	}

}

?>