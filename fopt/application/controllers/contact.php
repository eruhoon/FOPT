<?php
require_once 'fopt_Controller.php';

class Contact extends fopt_Controller {

	function __construct(){
		parent::__construct();

		$this->data['menu'] = 'contact';
	}

	public function index(){
		$this->main();
	}

	public function main(){
		$this->load->view('home/contact_v');
	}
}

?>