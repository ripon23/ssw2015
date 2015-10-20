<?php
class Attendance extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('car/general','account/account_model', 'car/booking_model'));	
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
		          'rules' => 'trim|required')
		      	)
			);
			// Run form validation
		    if ($this->form_validation->run())
		    {
		    	$driver_id = base64_decode($this->input->post('user_id', TRUE));
				$response['account'] = $this->account_model->get_by_id($driver_id);
				$response['today_bookings'] = $this->booking_model->get_driver_wise_booking($driver_id);
				$response["success"] = 1;
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
	
	
	function save()
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
		          	'field' => 'booking_id',
		          	'label' => 'Booking ID',
		          	'rules' => 'trim|required'),
		          	array(
		          	'field' => 'customer_id',
		          	'label' => 'Customer ID',
		          	'rules' => 'trim|required'),
		          	array(
		          	'field' => 'drt_type',
		          	'label' => 'Drt Type',
		          	'rules' => 'trim|required')
		      	)
			);

			// Run form validation
		    if ($this->form_validation->run())
		    {
		    	$driver_id = base64_decode($this->input->post('user_id', TRUE));
		    	$booking_id = base64_decode($this->input->post('booking_id', TRUE));
		    	$customer_id = base64_decode($this->input->post('customer_id', TRUE));
		    	$drt_type = base64_decode($this->input->post('drt_type', TRUE));

		    	// Pickup time and drop time
		    	$time = date('h:i:s', now());   	
				$response['account'] = $this->account_model->get_by_id($driver_id);
				$customer = $this->account_model->get_by_id($customer_id);
				$response["success"] = 1;
				
				if ($this->check_pickuptime($booking_id) === TRUE) {
					$attendance_array = array(
						'drop_time' => $time
					);
					$this->general->update_table('car_availed_actual_time', $attendance_array, 'booking_id', $booking_id);
					$this->general->update_table('car_booking', array('status' => 1), 'booking_id', $booking_id);
					$response["message"] = $customer->username." has been successfully availed";
				}
				else
				{
					$attendance_array = array(
						'booking_id' => $booking_id,
						'drt_type' => $drt_type,
						'pickup_time' => $time,
						'driver_id' => $driver_id
					);
					$this->general->save_into_table('car_availed_actual_time', $attendance_array);	
				}
				$response['today_bookings'] = $this->booking_model->get_driver_wise_booking($driver_id);
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

	function check_pickuptime($booking_id)
	{
		return $this->general->get_all_table_info_by_id('car_availed_actual_time', 'booking_id', $booking_id)? TRUE : FALSE;
	}	
}// END Class

?>