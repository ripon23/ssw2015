<?php
class Schedules extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('car/general','account/account_model'));	
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
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/schedules'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_schedule_manage'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage Schedule');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Route List';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/schedules/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_schedule');
		$config['num_links'] = 3;
		$config['per_page'] = 10;
		$config['uri_segment'] = 4;
		
		$config['full_tag_open'] = '<div class="pagination pagination-small"><ul>';
		$config['full_tag_close'] = '</ul></div><!--pagination-->';
		$config['display_pages'] = TRUE;
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		
		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_link'] = '&larr; Prev';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		
		//$data['site_list'] = $this->mod_site->get($config["per_page"], $page);
			
		$schedules = $this->general->get_list_view('car_schedule', $field_name=NULL, $id=NULL, $select=NULL, 'schedule_id', 'asc', $page, $config["per_page"]);
		$route_mappings = $this->general->get_all('car_route_node_mapping');
		// Combine all these elements for display
		
		$data['schedules'] = array();
		foreach( $schedules as $schedule )
		{
			
			$current_schedule = array();
			$current_schedule['schedule_id'] = $schedule->schedule_id;
			$current_schedule['car_id'] = $schedule->car_id;
			$current_schedule['schedule_car'] = $this->general->get_all_table_info_by_id('car_info', 'car_id', $schedule->car_id);
			$current_schedule['hot_line'] = $this->general->get_all_table_info_by_id('car_info', 'car_id', $schedule->car_id)->hot_line;
			$current_schedule['route_name'] = $this->general->get_all_table_info_by_id('car_route', 'route_id', $schedule->route_id);
			$current_schedule['schedule_type'] = $schedule->schedule_type;
			$current_schedule['start_time'] = $schedule->start_time;
			$current_schedule['end_time'] = $schedule->end_time;
			foreach( $route_mappings as $route_mapping )
			{
				if( $route_mapping->route_id == $schedule->route_id )
				{
					$current_schedule['node_list'][] = array(
					'node_id' => $route_mapping->node_id, 
					'node_details' => $this->general->get_all_table_info_by_id('car_node', 'node_id', $route_mapping->node_id),
					'prv_node_id' => $route_mapping->prv_node_id,
					'next_node_id' => $route_mapping->next_node_id,
					'duration_to_next' => $route_mapping->duration_to_next);
					
				}
			}
			$data['schedules'][] = $current_schedule;
		}
		
		//echo json_encode($data['schedules']);
				
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;
					
		$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
		$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select=NULL, 'route_id', 'asc', NULL, NULL);
		
		$this->load->view('car/car_schedules', isset($data) ? $data : NULL);		
					
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