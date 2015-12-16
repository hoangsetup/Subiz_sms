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

 	function getAllSmses(){
 		$this->db->order_by('ID DESC');
 		$query = $this->db->get("inbox");
 		return $query->result_array();
 	}

 	function getIncommingSms(){
 		$this->db->order_by('ID DESC');
 		$query = $this->db->get('inbox');
 		return $query->row_array();
 	}
 }
?>