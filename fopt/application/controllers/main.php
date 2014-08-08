<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('code_m'); // user
		$this->load->helper('url');
		$this->load->helper('date');
	}

	public function index(){
		$this->intro();
	}

	public function intro(){
		$this->load->view('header_v');
		$this->load->view('navbar_v');
		$this->load->view('footer_v');
	}

	public function test(){
		$data['list'] = $this->code_m->get_list();
		$this->load->view('code/list_v', $data);
	}

	public function _remap($method)
	{
		$this->load->view('header_v');
		$this->load->view('navbar_v');
		if(method_exists($this, $method))
		{
			$this->{"{$method}"}();
		}
		$this->load->view('footer_v');
	}

}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>