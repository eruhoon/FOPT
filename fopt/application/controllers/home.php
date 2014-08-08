<?php
require_once 'fopt_Controller.php';

class Home extends fopt_Controller {

	function __construct(){
		parent::__construct();

		$this->data['menu'] = 'home';
		//$this->output->enable_profiler(TRUE);
	}

	public function index(){
		$this->main();
	}

	public function main(){
		$this->load->view('home/introduce_v');
	}
	
	public function how_to_use(){
		$this->load->view('home/manual_v');
	}
	
	public function info(){
		$this->load->view('home/information_v');
	}
}

?>