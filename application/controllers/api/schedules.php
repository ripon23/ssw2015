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
				$response['account'] = $this->account_model->get_by_id($driver_id);
				$response['schedules'] = $this->schedule_model->get_drt_driver_schedule($driver_id, $schedule_date);
				// echo "<pre>"; print_r($response); echo "</pre>"; 
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
	
	
	function save($id=null)
	{
		// Keep track if this is a new user
		$is_new = empty($id);

		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/schedules'));
		}

		// Check if they are allowed to Update Users
		if ( ! $this->authorization->is_permitted('car_schedule_manage') && ! empty($id) )
		{
			$this->session->set_flashdata('parmission', 'You have no permission to update Schedule');
		  	redirect(base_url().'dashboard');
		}

		// Check if they are allowed to Create Users
		if ( ! $this->authorization->is_permitted('car_schedule_add') && empty($id) )
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to add Schedule');
		  redirect(base_url().'dashboard');
		}

		// Retrieve sign in user
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

		$data['action'] = 'create';


		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
		    array(
		      'field' => 'route_id',
		      'label' => 'lang:select_route',
		      'rules' => 'trim|required'),
		    array(
		      'field' => 'car_id', 
		      'label' => 'lang:select_car', 
		      'rules' => 'trim|required'), 
		    array(
		      'field' => 'type', 
		      'label' => 'lang:schedule_type', 
		      'rules' => 'trim|required'),		  
		 	array(
		      'field' => 'time', 
		      'label' => 'lang:schedule_time', 
		      'rules' => 'trim|required'),
			array(
		      'field' => 'status', 
		      'label' => 'lang:status', 
		      'rules' => 'trim|required')
		  ));

		// Run form validation
		if ($this->form_validation->run())
		{
		    if( empty($id)) {
				$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'route_id' => $this->input->post('route_id', TRUE), 
						'car_id' => $this->input->post('car_id', TRUE),
						'schedule_type' => $this->input->post('type', TRUE),
						'start_time' => $this->input->post('time', TRUE),
						'enable' => $this->input->post('status', TRUE),
						'create_user_id' => $data['account']->username,
						'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				//print_r($data);
				$schedule_id = $this->general->save_into_table_and_return_insert_id('car_schedule', $data);
				$this->session->set_flashdata('message_success', lang('success_add'));
				
		  		//redirect('car/schedules');
				
		    }
		    // Update existing News
		    else 
		    {
		  		$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'route_id' => $this->input->post('route_id', TRUE), 
						'car_id' => $this->input->post('car_id', TRUE),
						'schedule_type' => $this->input->post('type', TRUE),
						'start_time' => $this->input->post('time', TRUE),
						'enable' => $this->input->post('status', TRUE),
						'update_user_id' => $data['account']->username,
						'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				
				$this->general->update_table('car_schedule', $data,'schedule_id',$id);
				$this->session->set_flashdata('message_success', lang('success_update'));
		  		redirect('car/schedules');
			}
		}
		// Get the account to update
		if( ! $is_new )
		{
		  $data['update_details'] = $this->general->get_all_table_info_by_id('car_schedule', 'schedule_id', $id);
		  $data['action'] = 'update';
		}
		$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
		redirect('car/schedules');
		// $this->load->view('car/car_schedules', $data);
	}
  
	
  function delete($id)
  {
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/schedules'));
		}
		
		
		// Check if they are allowed to car_delete_route
		if ( !empty($id) && ! $this->authorization->is_permitted('car_schedule_delete') && empty($id))
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to delete route');
		  redirect(base_url().'car/schedules');
		}
		
		if (!empty($id))
		{
			$this->general->delete_from_table('car_route', 'route_id', $id);
			$this->session->set_flashdata('message_success', 'Your data successfully deleted');
			redirect(base_url().'car/schedules');
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete route');
			redirect(base_url().'car/schedules');
		}
  }
}// END Class

?>