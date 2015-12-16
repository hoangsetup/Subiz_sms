<?php 
/**
* 
*/
class Msubiz extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getAllDeviceInfo()
	{
		$query = $this->db->get('phones');
		return $query->result_array();
	}

	public function getGammuInfo()
	{
		$query = $this->db->get('gammu');
		return $query->row_array();
	}

	public function getModemStatus($id_modem)
	{

		//$this->db->from('phones');
		$tolerant = 1;
		$this->db->select('UpdatedInDB');
		$this->db->order_by('UpdatedInDB', 'DESC');
		$this->db->where('ID', $id_modem);
		$this->db->limit('1');
		$last_active = $this->db->get('phones')->row_array();
		if($last_active['UpdatedInDB'] != NULL){
			list($date, $time) = explode(' ', $last_active['UpdatedInDB']);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			$timestamp = mktime($hour, $minute + $tolerant, $second, $month, $day, $year);
			$now = time();
			if($timestamp>$now)
			{
				return 1; //"Connect";
			}
			else 
			{
				return 0; //"Disconnect";
			}
		}
		return -1; //'Unknown';
	}
}

 ?>