<?php if(!defined('BASEPATH')) exit('No Direct Script Access Allowed');

class Fopt_Controller extends CI_Controller {

	public $data = null;

	function __construct()
	{
		parent::__construct();
		//$this->load->database();

		$this->data['menu'] = 'home';
	}

	public function index(){
		$this->error();
	}

	public function _remap($method, $params = array())
	{
		
		if(method_exists($this, $method)){
			$this->load->model('code_m');
			$user_idx = @$this->session->userdata['idx'];
			
			$this->data['processing_data'] = $processing_code_idx = $this->code_m->get_processing_code($user_idx);
			
			$this->load->view('header_v');
			$this->load->view('navbar_v', $this->data);
			call_user_func_array(array($this, $method), $params);
			$this->load->view('footer_v');
		}
		else{
			$this->error();
		}
	}

	public function error()
	{
		$this->load->view('40x_v');
	}
}

?>