<?php
require_once 'fopt_Controller.php';

class test extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->page();
	}

	public function page(){
		
		/*
		 * EXEC TEST
		 * 
			exec("ls /var/www/html/ci_test -al", $output);
			$data['output'] = $output;
		 * 
		 */	
		
		$this->load->view('test_v');//, $data);
	}


	public function page2(){
		$this->load->view('test2_v');
	}
}

?>