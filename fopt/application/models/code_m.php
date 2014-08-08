<?php if(!defined('BASEPATH')) exit('No Direct script access allowed');

class Code_m extends CI_Model
{
	

	function __construct()
	{
		parent::__construct();
	}



	/*********************************************************
		UPLOAD code @ table 'code'
			$open_level : Auth Level 
				- 0 : PRIVATE
				- 1 : MEMBER ONLY
				- 2 : OPEN
		= success : $code_idx (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function upload($open_level=1)
	{

		### INPUT FORMAT ###
		$new_code = array(
			'reg_user_idx' => $this->session->userdata['idx'],
			'state_id' => 'wait',
			'open_level' => $open_level,
			'reg_time' => date("Y-m-d H:i:s"),
			'update_time' => date("Y-m-d H:i:s")
		);


		### EXECUTE QUERY ###
		$result = $this->db->insert('code', $new_code);


		### VALIDATION TEST ###
		if(!$result) return FALSE;


		### OUTPUT FORMAT ###
		return $this->code_m->get_idx($new_code);
	}



	/*********************************************************
		UPDATE code /w "CODE_IDX" @ table 'code'  
		= success : $code_idx (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function update($code_idx, $open_level)
	{

		### INPUT FORMAT ###
		$update_code = array(
			'state_id' => 'wait',
			'open_level' => $open_level,
			'update_time' => date("Y-m-d H:i:s")
		);

		### EXECUTE QUERY ###
		$this->db->where('code_idx', $code_idx);
		$result = $this->db->update('code', $update_code);


		### VALIDATION TEST ###
		if(!$result) return FALSE;


		### OUTPUT FORMAT ###
		return $code_idx;
	}



	/*********************************************************
		DELETE code /w "CODE_IDX" @ table 'code'  
		= success : $code_idx (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function delete($code_idx){

		### EXECUTE QUERY ###
		$this->db->where('code_idx', $code_idx);
		$result = $this->db->delete('code');


		### VALIDATION TEST ###
		if(!$result) return FALSE;
		

		### OUTPUT FORMAT ###
		return $code_idx;
	}



	/*********************************************************
		Get Sample Code
		= success : source code (TEXT)
		= failed : FALSE
	**********************************************************/
	public function get_sample_code()
	{
		
		### INCLUDE MODULE ###
		$this->load->helper('file');
		
		
		### READ FILE ###
		$result = read_file(ASSET_FILE_DIR.'/code/sample_code.txt');
		if(!$result) return FALSE;
		
		
		### RETURN ###
		return $result;
	}  
	


	/*********************************************************
		Get Current Code of table 'code' By "USER_IDX"
		= success : code_idx (NUBMER)
	 	= failed : FALSE
	**********************************************************/
	public function get_processing_code($reg_user_idx=false)
	{
		
		### VALIDATION TEST - USER_IDX ###
		if(!$reg_user_idx) return FALSE;
		
		
		### EXECUTE QUERY ####
		$this->db->from('code');
		$this->db->where('reg_user_idx', $reg_user_idx);
		$this->db->where('state_id <>', 'complete');
		$this->db->where('state_id <>', 'compile error');
		$this->db->where('state_id <>', 'runtime error');
		$this->db->select_max('code_idx');
		$query = $this->db->get();
		
		
		### VALIDATION TEST ###
		$result = $query->result();
		if(!$result[0]->code_idx) return FALSE;
		
		
		### RETURN ###
		return $result[0]->code_idx;
	}
	
	
	

	/*********************************************************
		Getting ROW ARRAY of table 'code' By "CODE_IDX"
			$table : Table
			$offset : Index to start
			$limit : Length of Array
			$user_id : User Index @ TABLE 'user'
		= success : $result (TUPLE[ARRAY]; PHP OBJECT)
		= failed : FALSE
	*********************************************************/
	public function get_list($offset='', $limit='', $user_id=false)
	{

		### INCLUDE MODULE ###
		$this->load->helper('file');
		$this->load->model('auth_m');


		### IF GETTING PARAMETER "user_id" ###
		if($user_id){
			$user_info = $this->auth_m->get_info_from_id($user_id);

			if(!$user_info) return FALSE;

			$user_idx = $user_info->user_idx;
			$this->db->where('reg_user_idx', $user_idx);
		}

	
		### EXECUTE QUERY ###
		$this->db->from('code');
		$this->db->order_by("code_idx", "DESC");
		if($offset!='' || $limit != '') $this->db->limit($limit, $offset);
		$query = $this->db->get();


		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;
		

		### OUTPUT FORMAT ###
		$result = $query->result();
		foreach ($result as $result_row) {
			@$result_row->reg_user_id = $this->auth_m->get_info($result_row->reg_user_idx)->user_id; 
			@$result_row->state_mark = $this->get_state_label($result_row->state_id);
			
			if($result_row->state_id != 'complete'){
				$result_row->encode_rate = null;
				$result_row->correct_ratio = null;
				$result_row->correct_packet_ratio = null;
				$result_row->diff_ratio = null;
				$result_row->result_ce = null;
				$result_row->result_cpe = null;
				$result_row->result_ce_std = null;
				$result_row->result_cpe_std = null;
			}else{
				@$result_row->encode_rate = $this->get_result_encode_rate($result_row->code_idx);
				@$result_row->correct_ratio = $this->get_result_correct_ratio($result_row->code_idx);
				@$result_row->correct_packet_ratio = $this->get_result_correct_packet_ratio($result_row->code_idx);
				@$result_row->diff_ratio = $this->get_result_diff_ratio($result_row->code_idx);
				@$result_row->result_ce = $this->get_result_ce($result_row->code_idx);
				@$result_row->result_cpe = $this->get_result_cpe($result_row->code_idx);
				@$result_row->result_ce_std = $this->get_result_ce_std($result_row->code_idx);
				@$result_row->result_cpe_std = $this->get_result_cpe_std($result_row->code_idx);
			}
			
			
			@$result_row->is_open = $this->is_open($result_row->reg_user_idx, $result_row->open_level);
			@$result_row->is_mine = $this->is_mine($result_row->reg_user_idx);
			@$result_row->result_report = $this->get_result_report($result_row->code_idx);
			@$result_row->result_runtime_report = $this->get_result_runtime_report($result_row->code_idx);
		}
	
		return $result;
	
	}



	/*********************************************************
		Getting SORTED ROW ARRAY of table 'code' By FILTER
			$offset : Index to start
			$limit : Length of Array
			$filter : enum ('EA', 'ES', 'PEA', 'PES')
				- EA : Correct Error Average
				- ES : Correct Error Std. D
				- PEA : Correct packet Error Average
				- PES : Correct packet Error Std. D
		= success : $result (TUPLE[ARRAY]; PHP OBJECT)
		= failed : FALSE
	*********************************************************/
	public function get_rank($offset='', $limit='', $filter='EA')
	{

		### INCLUDE MODULE ###
		$this->load->model('auth_m');

		
		### EXECUTE QUERY ###
		$this->db->where('state_id', 'complete');
		$this->db->from('code');
		switch($filter){
			case 'EA': $this->db->order_by('result_ce', 'DESC'); break;
			case 'ES': $this->db->order_by('result_ce_std', 'DESC'); break;
			case 'PEA': $this->db->order_by('result_cpe', 'DESC'); break;
			case 'PES': $this->db->order_by('result_cpe_std', 'DESC'); break;
			case 'encoding_rate': $this->db->order_by('encode_rate', 'DESC'); break;
			case 'correct_ratio': $this->db->order_by('correct_ratio', 'DESC'); break;
			case 'correct_packet_ratio': $this->db->order_by('correct_packet_ratio', 'DESC'); break;
			case 'diff_ratio': $this->db->order_by('diff_ratio', 'DESC'); break;
			default: return FALSE;
		}
		$query = $this->db->get();


		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;


		### OUTPUT FORMAT ###
		$result = $query->result();
		foreach ($result as $result_row) {
			@$result_row->reg_user_id = $this->auth_m->get_info($result_row->reg_user_idx)->user_id; 
			@$result_row->state_mark = $this->get_state_label($result_row->state_id);
			
			if($result_row->state_id != 'complete'){
				$result_row->encode_rate = null;
				$result_row->correct_ratio = null;
				$result_row->correct_packet_ratio = null;
				$result_row->diff_ratio = null;
				$result_row->result_ce = null;
				$result_row->result_cpe = null;
				$result_row->result_ce_std = null;
				$result_row->result_cpe_std = null;
			}else{
				@$result_row->encode_rate = $this->get_result_encode_rate($result_row->code_idx);
				@$result_row->correct_ratio = $this->get_result_correct_ratio($result_row->code_idx);
				@$result_row->correct_packet_ratio = $this->get_result_correct_packet_ratio($result_row->code_idx);
				@$result_row->diff_ratio = $this->get_result_diff_ratio($result_row->code_idx);
				@$result_row->result_ce = $this->get_result_ce($result_row->code_idx);
				@$result_row->result_cpe = $this->get_result_cpe($result_row->code_idx);
				@$result_row->result_ce_std = $this->get_result_ce_std($result_row->code_idx);
				@$result_row->result_cpe_std = $this->get_result_cpe_std($result_row->code_idx);
			}
			
			@$result_row->is_open = $this->is_open($result_row->reg_user_idx, $result_row->open_level);
			@$result_row->is_mine = $this->is_mine($result_row->reg_user_idx);
		}
		return $result;
	}



	/*********************************************************
		Getting TOTAL ROWS of table 'code' By 'USER_ID'
			$user_id : User Index @ TABLE 'user'
		= success : $result (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function get_total_rows($user_id=false)
	{

		### INCLUDE MODULE ###
		$this->load->model('auth_m');


		### IF GETTING PARAMETER "user_id" ###
		if($user_id){
			$user_info = $this->auth_m->get_info_from_id($user_id);

			if(!$user_info) return FALSE;

			$user_idx = $user_info->user_idx;
			$this->db->where('reg_user_idx', $user_idx);
		}


		### EXECUTE QUERY ###
		$this->db->from('code');
		$result = $this->db->count_all_results();


		### OUTPUT FORMAT ###
		return $result;
	}



	/*********************************************************
		Getting EACH ROW of table 'code' By "CODE_IDX"
		= success : $result (TUPLE; PHP OBJECT)
		= failed : FALSE
	*********************************************************/
	public function get_view($code_idx)
	{

		### INCLUDE MODULE ###
		$this->load->model('auth_m');


		### EXECUTE QUERY ###
		$code_info = array(
			'code_idx' => $code_idx
		);
		$this->db->from('code');
		$this->db->where($code_info);
		$query = $this->db->get();


		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;


		### OUTPUT FORMAT ###
		$result = $query->row();
		$result->reg_user_id = $this->auth_m->get_info($result->reg_user_idx)->user_id;
		$result->state_mark = $this->get_state_label($result->state_id);
		
		if($result->state_id != 'complete'){
			$result->encode_rate = null;
			$result->correct_ratio = null;
			$result->correct_packet_ratio = null;
			$result->diff_ratio = null;
			$result->result_ce = null;
			$result->result_cpe = null;
			$result->result_ce_std = null;
			$result->result_cpe_std = null;
		}else{
			@$result->encode_rate = $this->get_result_encode_rate($result->code_idx);
			@$result->correct_ratio = $this->get_result_correct_ratio($result->code_idx);
			@$result->correct_packet_ratio = $this->get_result_correct_packet_ratio($result->code_idx);
			@$result->diff_ratio = $this->get_result_diff_ratio($result->code_idx);
			@$result->result_ce = $this->get_result_ce($result->code_idx);
			@$result->result_cpe = $this->get_result_cpe($result->code_idx);
			@$result->result_ce_std = $this->get_result_ce_std($result->code_idx);
			@$result->result_cpe_std = $this->get_result_cpe_std($result->code_idx);
		}
		
		$result->is_open = $this->is_open($result->reg_user_idx, $result->open_level);
		$result->is_mine = $this->is_mine($result->reg_user_idx);
		$result->result_report = $this->get_result_report($result->code_idx);
		$result->result_runtime_report = $this->get_result_runtime_report($result->code_idx);
		if(!$result->reg_user_id) $result->reg_user_id = "None";
		return $result;
	}




	/*********************************************************
		Getting ROW "CODE_IDX" /w CONDITION @ table 'code'
			$condition : condition (PHP_OBJECT)
		= success : $code_idx (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function get_idx($condition)
	{

		### EXECUTE QUERY ###
		$this->db->from('code');
		$this->db->where($condition);
		$query = $this->db->get();

		
		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;


		### OUTPUT FORMAT ###
		return $query->row('code_idx');
	}



	/*********************************************************
		Getting LABEL MARK /w STATE_ID  
			$state : enum ('wait', 'compiling', 'compile error', 'simulation', 'runtime error', 'complete')
				- wait
				- compiling
				- compile error
				- simulating
				- runtime error
				- complete
		= success : $code_idx (NUMBER)
		= failed : FALSE
	*********************************************************/
	private function get_state_label($state)
	{

		### OUTPUT FORMAT ###
		switch($state){ 
			case "wait":
			case "compiling":
			case "simulating":
				return '<span class="label label-warning">'.$state.'</span>';
			case "compile error":
			case "runtime error":
				return '<span class="label label-danger">'.$state.'</span>';
			case "complete":
				return '<span class="label label-success">'.$state.'</span>';
			default:
				return FALSE;
		}
	}



	/*********************************************************
		Testing CODE /w REG_USER_IDX is mine (need LOGIN)
			$reg_user_idx : USER_INDEX
		= success : TRUE
		= failed : FALSE
	*********************************************************/
	public function is_mine($reg_user_idx)
	{	

		### LOGIN TEST ###
		if(!@$this->session->userdata['logged_in']) return FALSE; 


		### IDX CHECK ###
		return $reg_user_idx == @$this->session->userdata['idx'];
	}



	/*********************************************************
		Testing CODE /w REG_USER_IDX, OPEN_LEVEL have AUTHORIZATION (need LOGIN)
			$reg_user_idx : USER_INDEX
			$open_level : Auth Level 
				- 0 : PRIVATE
				- 1 : MEMBER ONLY
				- 2 : OPEN
		= success : TRUE
		= failed : FALSE
	*********************************************************/
	private function is_open($reg_user_idx, $open_level)
	{

		### OPEN LEVEL CHECK ###
		switch($open_level){

			### PRIVATE ###
			case 0: if(!$this->is_mine($reg_user_idx)) {return FALSE;} else {return TRUE;}

			### ONLY MEMBER ###
			case 1: if(!@$this->session->userdata['logged_in']) {return FALSE;} else {return TRUE;}

			### OPEN ###
			case 2: return TRUE;

			### ERROR ###
			default: return FALSE;
		}
	}



	/*********************************************************
		Getting Result CORRECT ERROR STD.D By "CODE_IDX" (DB, FILE)
		= success : $result (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function get_result_ce_std($code_idx)
	{
		
		### EXECUTE QUERY ###
		$this->db->from('code');
		$this->db->where('code_idx', $code_idx);
		$query = $this->db->get();

		
		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;

		### OUTPUT FORMAT ###
		$result_ce_std_from_db = $query->row('result_ce_std');

		### IF NOT EXIST 'RESULT_CE_STD' ###
		if(!$result_ce_std_from_db){
	
			### INCLUDE MODULE ###
			$this->load->helper('file');

			### GET RESULT XML ###
			$result_xml = $this->get_result_xml_object($code_idx);
			if(!$result_xml){
				return FALSE;
			}
			
			### CALCULATE 'RESULT_CE_STD' ###
			$result_arr = array();
			foreach($result_xml->BER as $ber){
				foreach($ber->Data as $case){
					array_push($result_arr, (double)($case->CorrectBER));
				}
			}
			$mean = array_sum($result_arr) / count($result_arr);
			$carry = 0.0;
	        foreach ($result_arr as $val) {
	            $d = ((double) $val) - $mean;
	            $carry += $d * $d;
	        };
			$result_ce_std_from_file = sqrt($carry/count($result_arr));

			### UPDATE DB ###
			$this->db->where('code_idx', $code_idx);
			$update_code = array( 'result_ce_std' => $result_ce_std_from_file );
			$query = $this->db->update('code', $update_code);

			### OUTPUT FORMAT ###
			return $result_ce_std_from_file;
		}

		### RETURN ###
		return $result_ce_std_from_db;
	}



	/*********************************************************
		Getting Result CORRECT PACKET ERROR STD.D By "CODE_IDX" (DB, FILE)
		= success : $result (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function get_result_cpe_std($code_idx)
	{
		
		### EXECUTE QUERY ###
		$this->db->from('code');
		$this->db->where('code_idx', $code_idx);
		$query = $this->db->get();

		
		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;

		### OUTPUT FORMAT ###
		$result_cpe_std_from_db = $query->row('result_cpe_std');

		### IF NOT EXIST 'RESULT_CPE_STD' ###
		if(!$result_cpe_std_from_db){
	
			### INCLUDE MODEL ###
			$this->load->helper('file');

			### GET RESULT XML ###
			$result_xml = $this->get_result_xml_object($code_idx);
			if(!$result_xml){
				return FALSE;
			}
			
			### CALCULATE 'RESULT_CPE_STD' ###
			$result_arr = array();
			foreach($result_xml->BER as $ber){
				foreach($ber->Data as $case){
					array_push($result_arr, (double)($case->CorrectPER));
				}
			}
			$mean = array_sum($result_arr) / count($result_arr);
			$carry = 0.0;
	        foreach ($result_arr as $val) {
	            $d = ((double) $val) - $mean;
	            $carry += $d * $d;
	        };
			$result_cpe_std_from_file = sqrt($carry/count($result_arr));

			### UPDATE DB ###
			$this->db->where('code_idx', $code_idx);
			$update_code = array( 'result_cpe_std' => $result_cpe_std_from_file );
			$query = $this->db->update('code', $update_code);

			### OUTPUT FORMAT ###
			return $result_cpe_std_from_file;
		}
		
		### RETURN ###
		return $result_cpe_std_form_db;
	}



	public function get_result_diff_ratio($code_idx)
	{
		### EXECUTE QUERY ###
		$this->db->from('code');
		$this->db->where('code_idx', $code_idx);
		$query = $this->db->get();
		
		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;

		### OUTPUT FORMAT ###
		$result_diff_ratio_from_db = $query->row('result_cpe_std');

		### IF NOT EXIST 'RESULT_CPE_STD' ###
		if(!$result_diff_ratio_from_db){
	
			### INCLUDE MODEL ###
			$this->load->helper('file');

			### GET RESULT XML ###
			$result_xml = $this->get_result_xml_object($code_idx);
			if(!$result_xml){
				return FALSE;
			}
			
			### CALCULATE 'DIFF_RATIO' ###
			$start_index = 0;
			$start_correct_packet_ratio = array();
			$start_ebno = 10*log10((double)($result_xml->BER[$start_index]->Data[0]->EBNO));
			for($j=0; $j<count($result_xml->BER[$start_index]->Data); $j++)
			{
				$case = $result_xml->BER[$start_index]->Data[$j];
				array_push($start_correct_packet_ratio, (double)($case->CorrectPacketRatio));
			}
			$start_correct_packet_ratio = array_sum($start_correct_packet_ratio) / count($start_correct_packet_ratio);
			
			$end_index = count($result_xml->BER)-1;
			$end_correct_packet_ratio = array();
			$end_ebno = 10*log10((double)($result_xml->BER[$end_index]->Data[0]->EBNO));
			for($j=0; $j<count($result_xml->BER[$end_index]->Data); $j++)
			{
				$case = $result_xml->BER[$end_index]->Data[$j];
				array_push($end_correct_packet_ratio, (double)($case->CorrectPacketRatio));
			}
			$end_correct_packet_ratio = array_sum($end_correct_packet_ratio) / count($end_correct_packet_ratio);
			
			
			$diff_correct_packet_ratio = $end_correct_packet_ratio - $start_correct_packet_ratio;
			$diff_ebno = $end_ebno - $start_ebno;
			
			$result_diff_ratio_from_file = $diff_correct_packet_ratio / $diff_ebno;
			
			### UPDATE DB ###
			$this->db->where('code_idx', $code_idx);
			$update_code = array( 'diff_ratio' => $result_diff_ratio_from_file );
			$query = $this->db->update('code', $update_code);

			### OUTPUT FORMAT ###
			return $result_diff_ratio_from_file;
		}

		### OUTPUT FORMAT ###
		return $result_diff_ratio_from_db;		
	}



	/*********************************************************
		Getting Result Attribute (avg) By "CODE_IDX" (DB, FILE)
		= success : $result (NUMBER)
		= failed : FALSE
	*********************************************************/
	public function get_result_average($code_idx, $db_attr, $xml_attr)
	{
		
		### EXECUTE QUERY ###
		$this->db->from('code');
		$this->db->where('code_idx', $code_idx);
		$query = $this->db->get();

		
		### VALIDATION TEST ###
		if(!$query->num_rows) return FALSE;

		### OUTPUT FORMAT ###
		$result_average_from_db = $query->row($db_attr);

		### IF NOT EXIST 'correct_ratio' ###
		if(!$result_average_from_db){
	
			### INCLUDE MODEL ###
			$this->load->helper('file');

			### GET RESULT XML ###
			$result_xml = $this->get_result_xml_object($code_idx);
			if(!$result_xml){
				return FALSE;
			}
			
			### CALCULATE 'AVERAGE' ###
			$result_arr = array();
			foreach($result_xml->BER as $ber){
				foreach($ber->Data as $case){
					array_push($result_arr, (double)($case->{$xml_attr}));
				}
			}
			$result_average_from_file = array_sum($result_arr) / count($result_arr);
			
			### UPDATE DB ###
			$this->db->where('code_idx', $code_idx);
			$update_code = array( $db_attr => $result_average_from_file );
			$query = $this->db->update('code', $update_code);

			### OUTPUT FORMAT ###
			return $result_average_from_file;
		}

		### RETURN ###
		return $result_average_from_db;
	}

	public function get_result_ce($code_idx) { return $this->get_result_average($code_idx, 'result_ce', 'CorrectBER'); }
	public function get_result_cpe($code_idx) { return $this->get_result_average($code_idx, 'result_cpe', 'CorrectPER'); }
	public function get_result_correct_ratio($code_idx) { return $this->code_m->get_result_average($code_idx, 'correct_ratio', 'CorrectRatio'); }
	public function get_result_correct_packet_ratio($code_idx) { return $this->code_m->get_result_average($code_idx, 'correct_packet_ratio', 'CorrectPacketRatio'); }
	public function get_result_encode_rate($code_idx) { return $this->get_result_average($code_idx, 'encode_rate', 'EncodingRate'); }



	/*********************************************************
		Getting Result XML By "CODE_IDX"
		= success : $result (TEXT)
		= failed : FALSE
	*********************************************************/
	private function get_result_xml($code_idx)
	{
		### INCLUDE MODULE ###
		$this->load->helper('file');

		### READ FILE ###
		$result = read_file(RESULT_DIR.'./'.$code_idx.'/simulation_result.xml');
		
		if(!$result) return FALSE;

		### RETURN ###
		return $result;
	}
	
	
	
	/*********************************************************
		Getting Result XML OBJECT By "CODE_IDX"
		= success : $result (PHP OBJECT SimpleXMLElement)
		= failed : FALSE
	*********************************************************/
	public function get_result_xml_object($code_idx)
	{
		### READ RAW TEXT ###
		$raw_xml = $this->get_result_xml($code_idx);
		if(!$raw_xml) return FALSE;

		### INSTANCIATE ###
		$result = new SimpleXMLELement($raw_xml);

		### RETURN ###
		return $result;
	}



	/*********************************************************
		Getting Result COMPILE Report FILE By "CODE_IDX"
		= success : $result (TEXT)
		= failed : FALSE
	*********************************************************/
	private function get_result_report($code_idx)
	{
		### INCLUDE MODULE ###
		$this->load->helper('file');

		### READ FILE ###
		$result = read_file(RESULT_DIR.'./'.$code_idx.'/res_compile.txt');
		if(!$result) return FALSE;

		### RETURN ###
		return $result;
	}



	/**********************************************************
		Getting Result RUNTIME Report FILE By "CODE_IDX"
		= success : $result (TEXT)
		= failed : FALSE
	**********************************************************/
	private function get_result_runtime_report($code_idx)
	{
		### INCLUDE MODULE ###
		$this->load->helper('file');

		### READ FILE ###
		$result = read_file(RESULT_DIR.'./'.$code_idx.'/res_error_report.txt');
		if(!$result) return FALSE;

		### RETURN ###
		return $result;
	}



	/**********************************************************
		Getting SOURCE FILE By "CODE_IDX"
		= success : $result (TEXT)
		= failed : FALSE
	**********************************************************/
	public function get_raw_code($code_idx)
	{
		### INCLUDE MODULE ###
		$this->load->helper('file');

		### READ FILE ###
		$result = read_file(RESULT_DIR.'./'.$code_idx.'/input.c');
		if(!$result) return FALSE;

		### RETURN ###
		return $result;
	}
	
	
	
	/**********************************************************
		Getting XML By "CODE_IDX"
		= success : $result (XML)
		= failed : FALSE
	**********************************************************/
	public function get_xml($code_idx)
	{
		$this->load->helper('array_xml');
		$this->load->helper('xml');
		
		### GET VIEW ###
		$views = $this->get_view($code_idx);
		$result = json_decode(json_encode((array)simplexml_load_string($this->get_result_xml($code_idx))), 1);

		
		### XML AFTER PROCESS ###
		for($i=0; $i<count($result['BER']); $i++){
			unset($result['BER'][$i]['@attributes']);
			for($j=0; $j<count($result['BER'][$i]['Data']); $j++){
				unset($result['BER'][$i]['Data'][$j]['@attributes']);
			}
		}
		
		### VALIDATION VIEW ### 
		if($views == null){
			$xml['resultxml'] = array(
				'error' => true,
				'msg' => "잘못된 접근 입니다."	
			);
		}else if($views->is_open!=true){
			$xml['result'] = array(
				'error' => true,
				'msg' => "비공개 된 코드정보 입니다."	
			);
		}else{
			$xml['resultxml'] = get_object_vars($views);
			unset($xml['resultxml']['state_mark']);
			unset($xml['resultxml']['state_mark']);
			unset($xml['resultxml']['is_mine']);
			unset($xml['resultxml']['is_open']);
			$xml['resultxml']['result_report'] = htmlspecialchars($xml['resultxml']['result_report']);
			$xml['resultxml']['result_runtime_report'] = htmlspecialchars($xml['resultxml']['result_runtime_report']);
			$xml['resultxml']['result'] = $result;
		}
		
		### OUTPUT ###
		return array_xml($xml);
		
	}
	
	
}