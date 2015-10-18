<?php
class Drtsms extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('general_model','car/general','account/account_model', 'car/schedule_model', 'car/booking_model'));	
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
			$this->lang->load('general', $this->config->item("default_language"));
			$this->lang->load('mainmenu', $this->config->item("default_language"));
			$this->lang->load('registration_form', $this->config->item("default_language"));
			$this->lang->load('car', $this->config->item("default_language"));
		}
		else
		{
			$this->lang->load('general', $language);
			$this->lang->load('mainmenu', $language);	
			$this->lang->load('registration_form', $language);
			$this->lang->load('car', $language);
		}		
	}		
	
	
	
	public function index()  
	{
	$currentDate = strtotime(date('H:i:s'));
	$futureDate = $currentDate+(60*120);
	$formatDate = date("H:i:s", $futureDate);
	//echo $formatDate;
	
	$query="Select * FROM  car_booking WHERE sms_notify=0 AND status=3 AND date_of_booking >='".date('Y-m-d')."' AND pickup_time > '".date('H:i:s')."' AND pickup_time < '".$formatDate."'";
	$query_result=$this->general_model->get_all_querystring_result($query);
	echo $query;	
		if($query_result)
		{
			foreach($query_result as $smsresult)
			{
				$user_mobile=$this->general->get_all_table_info_by_id('a3m_account','id', $smsresult->user_id);
				
				if($user_mobile->phone)
				{
				$msg="Hi, ".$user_mobile->username.", Your pickup time is:".$smsresult->pickup_time." from ".$this->booking_model->get_pickup_point_name_from_id($smsresult->start_pickup_point);
				/*************************************************/
				$mydata = array('SSW02301120151012','01974726227',$user_mobile->phone,$msg,date('Y-m-d H:i:s'),'SSW');
				$serialized = rawurlencode(serialize($mydata));	
				//$apisaid =file_get_contents('http://localhost/test/db/index.php?smsdata='.$serialized);
				$apisaid =file_get_contents('http://gramweb.com/gccsmsserver/index.php?smsdata='.$serialized);				
				/*************************************************/
				
				 $table_data = array(
				   'sms_notify' => 1
				  );
				 
				$this->general_model->update_table('car_booking', $table_data,'booking_id', $smsresult->booking_id);
				}
				
				

			}
		}
	//echo $query;
					
	}
	  
	
}// END Class

?>