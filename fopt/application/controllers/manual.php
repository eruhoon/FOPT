<?php
require_once 'fopt_Controller.php';

class Manual extends fopt_Controller {

	function __construct(){
		parent::__construct();

		$this->data['menu'] = 'manual';
	}

	public function index(){
		$this->main();
	}

	public function main(){
		$this->load->view('home/manual_v');
	}
}
?>