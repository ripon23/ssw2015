<?php
class Schedules extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('car/general','account/account_model', 'car/schedule_model'));	
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
		if ($this->config->item("api_key") === base64_decode($this->input->post('api_key', TRUE)))
		{
			$this->form_validation->set_rules(
		      array(
			  	array(
		          'field' => 'user_id',
		          'label' => 'User Id',
		          'rules' => 'trim|required'),
		        array(
		          'field' => 'schedule_date',
		          'label' => 'Schedule Date',
		          'rules' => 'trim|required')        
		      	)
			);
			// Run form validation
		    if ($this->form_validation->run())
		    {
		    	$driver_id = base64_decode($this->input->post('user_id', TRUE));
		    	$schedule_date = base64_decode($this->input->post('schedule_date', TRUE));
		    	$response["success"] = 1;
				$response['on_drt_schedules'] = $this->schedule_model->get_drt_driver_schedule($driver_id, $schedule_date);
				$response['s_drt_schedules'] = $this->schedule_model->get_sche_drt_driver_schedule($driver_id, $schedule_date);
				
				echo json_encode($response);
			}
			else
			{
				$response["success"] = 0;
				$response["message"] = "Form validation error";
				echo json_encode($response);
			}
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "API key is wrong";
			echo json_encode($response);
		}					
	}
	
	
	function ondrt_trip_summery($schedule_id)
	{
		if ($this->config->item("api_key") === base64_decode($this->input->post('api_key', TRUE)))
		{
			$this->form_validation->set_rules(
		      array(
			  	array(
		          'field' => 'schedule_id',
		          'label' => 'Sschedule Id',
		          'rules' => 'trim|required')
			));
			// Run form validation
		    if ($this->form_validation->run())
		    {
		    	$response["success"] = 1;
				$response['srip_summary'] = $this->schedule_model->get_on_drt_trip_summary($schedule_id);				
				echo json_encode($response);
			}
			else
			{
				$response["success"] = 0;
				$response["message"] = "Form validation error";
				echo json_encode($response);
			}
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "API key is wrong";
			echo json_encode($response);
		}
	}
  
	
	function sdrt_trip_summery($schedule_id)
	{
		
	}
}// END Class

?>