<?php 

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home extends CI_Controller {
		public function __construct($login = TRUE){
			parent::__construct();
			$this->load->Model('Msms');
			$this->load->Model('Mrule');
			$this->load->Model('Mfwstatus');
			$this->load->Model('Msubiz');
			$this->load->helper('url');
			$this->load->library('session');
			
		}
		public function index()
		{
			if($login)
			{
				// session check
				if($this->session->userdata('loggedin')==NULL) redirect('login');
			}
			$rules = array();
			$smses = array();
			$temp_smses = $this->Msms->getAllSmses();
			$temp_rules = $this->Mrule->getAllRules();
			foreach ($temp_smses as $sms) {
				$sms['fwstatus'] = $this->Mfwstatus->getStatusBySmsId($sms['ID']);
				array_push($smses, $sms);
			}
			$data['rules'] = $this->Mrule->getAllRules();
			$data['smses'] = $smses;
			$this->load->view('view_layout', $data);
		}

		public function homecontent()
		{
			$rules = array();
			$smses = array();
			$temp_smses = $this->Msms->getAllSmses();
			$temp_rules = $this->Mrule->getAllRules();
			foreach ($temp_smses as $sms) {
				$sms['fwstatus'] = $this->Mfwstatus->getStatusBySmsId($sms['ID']);
				array_push($smses, $sms);
			}
			$data['rules'] = $this->Mrule->getAllRules();
			$data['smses'] = $smses;
			$this->load->view('home', $data);
		}

		public function RuleControler()
		{
			//Get detail view
			if($this->input->is_ajax_request()){

				if(!isset($_POST['action']))
					return;

				$action = $_POST['action'];
				switch ($action) {
					case 'getdetail':
						$data['rule'] = $this->Mrule->getRuleById($_POST['id']);
						$this->load->view('edit_rule', $data);
						break;
					case 'delete':
						$this->Mrule->deleteRule($_POST['id']);
						echo 'Done!';
						break;
					default:
						exit();
						break;
				}
			}else{ // Edit. Addnew
				if(isset($_POST['submit'])){

					if(isset($_POST['id'])){ // Edit
						$rule = array(
							'TimeUpdate' => date("Y-m-d H:i:s"),
							'SenderNumber' => $_POST['SenderNumber'],
							'HasTheWords' => $_POST['HasTheWords'],
							'MailGunDomain' => $_POST['MailGunDomain'],
							'MailGunApiKey' => $_POST['MailGunApiKey'],
							'From' => $_POST['From'],
							'To' => $_POST['To'],
							'Subject' => $_POST['Subject'],
							'IsActive' => ($_POST['IsActive'] == 'on')?'1': '0'
						);
						$this->Mrule->updateRule($rule, $_POST['id']);
					}else{ // Addnew
						$rule = array(
							'TimeUpdate' => date("Y-m-d H:i:s"),
							'SenderNumber' => $_POST['SenderNumber'],
							'HasTheWords' => $_POST['HasTheWords'],
							'MailGunDomain' => $_POST['MailGunDomain'],
							'MailGunApiKey' => $_POST['MailGunApiKey'],
							'From' => $_POST['From'],
							'To' => $_POST['To'],
							'Subject' => $_POST['Subject'],
							'IsActive' => ($_POST['IsActive'] == 'on')?'1': '0'
						);
						$this->Mrule->addRule($rule);
					}
					redirect('', 'refresh');
				}
			}
		}

		public function triggerIcommingSms()
		{

			$sms = $this->Msms->getIncommingSms();
			$rules = $this->Mrule->getAllRules();
			if(!$sms || !$rules)
				exit();
			$flag = FALSE;
			foreach ($rules as $rule) {
				if(!$rule['IsActive'])
					continue;
				if($rule['SenderNumber'] === $sms['SenderNumber']){
					if((!isset($rule['HasTheWords']) || trim($rule['HasTheWords']) === '')){
						$flag = true;
					}else if(strpos($sms['TextDecoded'], $rule['HasTheWords']) !== FALSE){
						$flag = true;
					}
				}else if((!isset($rule['SenderNumber']) || trim($rule['SenderNumber']) === '')){
					if((!isset($rule['HasTheWords']) || trim($rule['HasTheWords']) === '')){
						$flag = true;
					}else if(strpos($sms['TextDecoded'], $rule['HasTheWords']) !== FALSE){
						$flag = true;
					}
				}
				if($flag !== FALSE){
					$this->send_sms_to_mailgun($sms, $rule);
				}
			}
		}

		public function send_sms_to_mailgun($sms, $rule)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$rule['MailGunApiKey']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, $rule['MailGunDomain']);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 
			            array('from' => $rule['From'],
			                  'to' => $rule['To'],
			                  'subject' => $rule['Subject'],
			                  'text' => $sms['TextDecoded']));
			$result = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			$status;
			if($info['http_code'] != 200){
				$status = array(
					'id_inbox' => $sms['ID'],
					'status' => 0,
					'message' => 'http_code '.$info['http_code']. ' '.$result
				);
			}else{
				$status = array(
					'id_inbox' => $sms['ID'],
					'status' => 1,
					'message' => $result
				);
			}
			$this->Mfwstatus->addFwstatus($status);
		}

		public function checkmailgun()
		{
			if(isset($_POST['api']) && isset($_POST['domain']) && $this->input->is_ajax_request()){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$_POST['api']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_URL, $_POST['domain']);
				curl_setopt($ch, CURLOPT_POSTFIELDS, 
			            array('from' =>'from@gmail.com',
			                  'to' => 'to@gmail.com',
			                  'subject' => 'Check api key!',
			                  'text' => 'text'));
				$result = curl_exec($ch);
				$info = curl_getinfo($ch);
				curl_close($ch);
				$dic = array(
					'message' => $result,
					'http_code' => $info['http_code']
				);
				echo json_encode($dic);
			}else{
				echo "Hack me!";
			}
		}


		public function gammuinfo()
		{
			$data['devices'] = $this->Msubiz->getAllDeviceInfo();
			$data['gammu'] = $this->Msubiz->getGammuInfo();
			$stt = array();
			foreach($data['devices'] as $device){
				$stt[$device['ID']] = $this->Msubiz->getModemStatus($device['ID']);
			}
			$data['status'] = $stt;
			//$data['status'] = $this->Msubiz->getModemStatus('viettel1');
			$this->load->view('gammu_info', $data);
		}
	}

?>
