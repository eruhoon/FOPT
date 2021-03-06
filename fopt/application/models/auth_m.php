<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_m extends CI_Model
{
	function __construct(){
		parent::__construct();
	}


	/*********************************************************
		LOGIN /w id, pw @ table 'auth'
			$auth : Array(
				- username : id
				- password : pw
			)
		= success : $row (PHP_OBJECT)
			user_idx : INDEX (NUMBER)
			user_id : ID (STRING)
			user_pw : MD5(ID) (STRING)
			email : EMAIL (STRING)
			reg_time : DATE
		= failed : FALSE
	*********************************************************/
	public function login($auth)
	{

		### INPUT DATA ###
		$login_user = array(
			'user_id' => $auth['username'],
			'user_pw' => md5($auth['password'])
		);


		### EXECUTE QUERY ###
		$this->db->from('user')->where($login_user);
		$query = $this->db->get();


		### VALIDATION TEST ###
		if(!$query->num_rows()) return FALSE;


		### OUTPUT FORMAT ###
		return $query->row();

	}




	/*********************************************************
		JOIN /w id, pw, email @ table 'auth'
			$auth : Array(
				- user_id : id (STRING)
				- password : md5(pw) (STRING)
				- email : STRING
			)
		= success : TRUE
		= failed : FALSE
	*********************************************************/
	public function join($auth)
	{

		### INPUT DATA ###
		$new_user = array(
			'user_id' => $auth['user_id'],
			'user_pw' => md5($auth['password']),
			'email' => $auth['email'],
			'reg_time' => date("Y-m-d H:i:s")
		);


		### EXCUTE QUERY ###
		$result = $this->db->insert('user', $new_user);


		### VALIDATION TEST ###
		if(!$result) return FALSE;


		### OUTPUT FORMAT ###
		return $result;

	}



	/*********************************************************
		GET INFORMATION /key, value user_idx @ table 'auth'
			$key : KEY (STRING)
			$value : VALUE (STRING)
		= success : $row (PHP_OBJECT)
			user_idx : INDEX (NUMBER)
			user_id : ID (STRING)
			user_pw : MD5(ID) (STRING)
			email : EMAIL (STRING)
			reg_time : DATE
		= failed : FALSE
	*********************************************************/
	private function get_info_kv($key, $value)
	{

		### INPUT DATA ###
		$user_info = array(
			$key => $value
		);


		### EXECUTE QUERY ###
		$this->db->from('user');
		$this->db->where($user_info);
		$query = $this->db->get();


		### VALIDATION TEST ###
		if(!$query->num_rows()) return FALSE;

		### OUTPUT FORMAT ###
		return $query->row();
	}
	public function get_info($user_idx) { return $this->get_info_kv('user_idx', $user_idx); }
	public function get_info_from_id($user_id) { return $this->get_info_kv('user_id', $user_id); }

	

}

?>