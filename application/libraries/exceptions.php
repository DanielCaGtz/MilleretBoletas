<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class exceptions extends CI_Model {

	public $CI;
	
	public function __construct(){
		$this->CI = & get_instance();
	}

	public function checkForError() {
		$this->CI->load->database();
		$error = $this->CI->db->_error_message();
		//print_r($error);
		if ($error)
			throw new MySQLException($error);
	}
}

abstract class UserException extends Exception {
	public abstract function getUserMessage();
}

class MySQLException extends UserException {
	//private $errorNumber;
	private $errorMessage;

	public function __construct($error) {
		//$this->errorNumber = "Error Code(" . $error['code'] . ")";
		$this->errorMessage = $error;
	}

	public function getUserMessage() {
		return array(
			"error" => array (
				//"code" => $this->errorNumber,
				"message" => $this->errorMessage
			)
		);
	}

}

?>