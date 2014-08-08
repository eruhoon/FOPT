<?php
require_once 'fopt_Controller.php';

class Info extends fopt_Controller {

	function __construct(){
		parent::__construct();

		$this->data['menu'] = 'info';
	}

	public function index(){
		$this->main();
	}

	public function main(){
		$this->load->view('home/information_v');
	}
}
?>