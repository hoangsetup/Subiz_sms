<?php 
 /**
 * 
 */
 class Msms extends CI_model
 {
 	
 	function __construct()
 	{
 		parent::__construct();
 		$this->load->database();
 	}

 	function getallsmses(){
 		$this->db->order_by('ID DESC');
 		$query = $this->db->get("inbox");
 		return $query->result_array();
 	}

 	function getincommingsms(){
 		$this->db->order_by('ID DESC');
 		$query = $this->db->get('inbox');
 		$sms = $query->row_array();
 		if($sms['UDH'] != ''){
 			// is multipart inbox
 			$this->db->order_by('ID ASC');
 			$this->db->where('UDH', $sms['UDH']);
 			$arr_sms = $this->db->get('inbox')->result_array();
 			$sms['TextDecoded'] = '';
 			foreach ($arr_sms as $sm) {
 				$sms['TextDecoded'] .= $sm['TextDecoded'].' ';
 			}

 		}
 		return $sms;
 	}

 	function deleteOldSms($date = 15){
 		$res = array();
 		$res['fw_status'] = array();
 		$res['user_inbox'] = array();
 		$res['inbox'] = array();
 		$where = sprintf('UpdatedInDB <= (CURDATE() - INTERVAL %u DAY)', $date);
 		$this->db->where($where, NULL, FALSE);
 		foreach ($this->db->get('inbox')->result_array() as $inbox) {
 			$this->db->where('id_inbox', $inbox['ID']);
 			array_push($res['fw_status'], $this->db->get('fw_status')->row_array());

 			$this->db->where('id_inbox', $inbox['ID']);
 			$this->db->delete('fw_status');

 			$this->db->where('id_inbox', $inbox['ID']);
 			array_push($res['user_inbox'], $this->db->get('user_inbox')->row_array());

 			$this->db->where('id_inbox', $inbox['ID']);
 			$this->db->delete('user_inbox');
 		}
 		$this->db->where($where, NULL, FALSE);
 		$res['inbox'] = $this->db->get('inbox')->result_array();

 		$this->db->where($where, NULL, FALSE);
 		$this->db->delete('inbox');
 		return $res;
 	}
 }
?>