<?php 
	/**
	* 
	*/
	class Mfwstatus extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		function addfwstatus($status){
			$this->db->insert('fw_status', $status);
		}

		function getstatusbysmsid($smsid = -1){
			$this->db->where('id_inbox', $smsid);
			$query = $this->db->get('fw_status');
			return $query->row_array();
		}
	}
?>