<?php 

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->Model('Msms');
			$this->load->Model('Mrule');
			$this->load->Model('Mfwstatus');
			$this->load->Model('Msubiz');
			$this->load->helper('url');
			$this->load->library('session');
			
		}
		public function index($login = TRUE)
		{
			if($login)
			{
				// session check
				if($this->session->userdata('loggedin')==NULL) redirect('login');
			}
			$rules = array();
			$smses = array();
			$temp_smses = $this->Msms->getallsmses();
			$temp_rules = $this->Mrule->getallrules();
			foreach ($temp_smses as $sms) {
				$sms['fwstatus'] = $this->Mfwstatus->getstatusbysmsid($sms['ID']);
				array_push($smses, $sms);
			}
			$data['rules'] = $this->Mrule->getallrules();
			$data['smses'] = $smses;
			$this->load->view('view_layout', $data);
		}

		public function homecontent()
		{
			$rules = array();
			$smses = array();
			$temp_smses = $this->Msms->getallsmses();
			$temp_rules = $this->Mrule->getallrules();
			foreach ($temp_smses as $sms) {
				$sms['fwstatus'] = $this->Mfwstatus->getstatusbysmsid($sms['ID']);
				array_push($smses, $sms);
			}
			$data['rules'] = $this->Mrule->getallrules();
			$data['smses'] = $smses;
			$this->load->view('home', $data);
		}

		public function rulecontroler()
		{
			//Get detail view
			if($this->input->is_ajax_request()){

				if(!isset($_POST['action']))
					return;

				$action = $_POST['action'];
				switch ($action) {
					case 'getdetail':
						$data['rule'] = $this->Mrule->getrulebyid($_POST['id']);
						$this->load->view('edit_rule', $data);
						break;
					case 'delete':
						$this->Mrule->deleterule($_POST['id']);
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
						$this->Mrule->updaterule($rule, $_POST['id']);
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
						$this->Mrule->addrule($rule);
					}
					redirect('', 'refresh');
				}
			}
		}
		public function is_filtered($sms, $rule){
			$flag = FALSE;
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
			return $flag;
		}

		public function triggerincommingsms()
		{

			$sms = $this->Msms->getincommingsms();
			$rules = $this->Mrule->getallrules();
			if(!$sms || !$rules)
				exit();
			foreach ($rules as $rule) {
				if(!$rule['IsActive'])
					continue;
				$flag = FALSE;
				if($this->is_filtered($sms, $rule) !== FALSE){
					$this->send_sms_to_mailgun($sms, $rule);
					$flag = TRUE;
				}
				error_log('----------IcommingMessage------------');
				error_log('SMS: '.json_encode($sms));
				error_log('Rule: '.json_encode($rule));
				error_log('Is filtered: '.$flag);
				error_log('----------//IcommingMessage----------');
			}
			$this->check_resend_sms();
		}

		public function check_resend_sms()
		{
			$rules = $this->Mrule->getallrules();
			$smses = array();
			$temp_smses = $this->Msms->getallsmses();
			foreach ($temp_smses as $sms) {
				$sms['fwstatus'] = $this->Mfwstatus->getstatusbysmsid($sms['ID']);
				array_push($smses, $sms);
			}
			foreach ($smses as $sms) {
				foreach ($rules as $rule) {
					$flag = FALSE;
					if(!$rule['IsActive'])
						continue;
					if($this->is_filtered($sms, $rule) !== FALSE && isset($sms['fwstatus']) && $sms['fwstatus'] == 0){
						$this->send_sms_to_mailgun($sms, $rule);
						error_log('----------ResendMessage----------');
						error_log('SMS: '.json_encode($sms));
						error_log('Rule: '.json_encode($rule));
						error_log('----------//ResendMessage----------');
					}
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
			$this->Mfwstatus->addfwstatus($status);
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
			$data['devices'] = $this->Msubiz->getalldeviceinfo();
			$data['gammu'] = $this->Msubiz->getgammuinfo();
			$stt = array();
			foreach($data['devices'] as $device){
				$stt[$device['IMEI']] = $this->Msubiz->getmodemstatus($device['IMEI']);
			}
			$data['status'] = $stt;
			//$data['status'] = $this->Msubiz->getmodemstatus('viettel1');
			$this->load->view('gammu_info', $data);
		}

		public function deleteoldsms($date = 15)
		{
			header('Content-Type: application/json');
			echo json_encode($this->Msms->deleteOldSms($date));
		}
	}

?>
