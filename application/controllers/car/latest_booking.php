<?php
class Latest_booking extends CI_Controller {

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
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Latest Booking';	
		$data['route_list'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select=NULL, 'route_id', 'desc', NULL, NULL);
		$data['car_list'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);
		// Paginations
		$this->load->library('pagination');
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/latest_booking/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_booking');
		$config['num_links'] = 3;
		$config['per_page'] = $this->config->item("pagination_perpage");
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
		// All Routes

		
		$latest_booking = $this->general->get_list_view('car_booking', $field_name=NULL, $id=NULL, $select=NULL, 'booking_id', 'desc', $page, $config["per_page"]);
		
		$route_select = '`route_name_en`, `route_name_bn`';
		$schedule_select = '`car_id`, `start_time`';
		$data['latest_booking'] = array();
		foreach( $latest_booking as $latest_book)
		{	
			$current_booking = array();
			$current_booking['booking_id'] = $latest_book->booking_id;
			$current_booking['username'] = $this->account_model->get_by_id($latest_book->user_id)->username;
			$current_booking['schedule_id'] = $latest_book->schedule_id;
			$current_booking['pickup_time'] = $latest_book->pickup_time;
			$current_booking['arrival_time'] = $latest_book->arrival_time;
			//$current_booking['schedule_details'] = $this->general->get_all_table_info_by_id_custom('car_schedule', $schedule_select, 'schedule_id', $latest_book->schedule_id);
			$current_booking['route_details'] = $this->general->get_all_table_info_by_id_custom('car_route', $route_select, 'route_id', $latest_book->route_id);
			$current_booking['date_of_booking'] = $latest_book->date_of_booking;
			$current_booking['pickup_point'] = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select = NULL, 'pickup_point_id', $latest_book->start_pickup_point)->pickup_point_en;
			$current_booking['drop_point'] = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select = NULL, 'pickup_point_id', $latest_book->end_pickup_point)->pickup_point_en;
			$current_booking['drt_cost'] = $this->general->get_all_table_info_by_id_custom('car_drt_cost',$sche_select = NULL, 'booking_id', $latest_book->booking_id)->fare_cost;
			$current_booking['no_of_set'] = $latest_book->no_of_set;
			$current_booking['booking_date'] = $latest_book->create_date;
			$current_booking['status'] = $latest_book->status;
			$data['latest_booking'][] = $current_booking;
		}
						
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;
		
		$this->load->view('car/latest_booking', isset($data) ? $data : NULL);				
	}

	function search()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Latest Booking';	
		$data['route_list'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select=NULL, 'route_id', 'desc', NULL, NULL);
		$data['car_list'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);
				
		$field_name=NULL; 	
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
			array(
			  'field' => 'sdate',
			  'label' => 'Start Date',
			  'rules' => 'trim'),
			array(
			  'field' => 'edate',
			  'label' => 'End Date',
			  'rules' => 'trim'),
			array(
			  'field' => 'booking_id',
			  'label' => 'booking_id',
			  'rules' => 'trim'),
			array(
			  'field' => 'username',
			  'label' => 'username',
			  'rules' => 'trim'),
			array(
			  'field' => 'route_id',
			  'label' => 'route_id',
			  'rules' => 'trim')
		  ));
		  
		// Search Fields
		$sdate = (trim($this->input->post('sdate'))!=="")? $this->input->post('sdate',true) : NULL;
		$edate = (trim($this->input->post('edate'))!=="")? $this->input->post('edate',true) : NULL;
		$booking_id = trim($this->input->post('booking_id'));
		$username = trim($this->input->post('username'));
		$car_id = trim($this->input->post('car_id'));
		$route_id = trim($this->input->post('route_id'));
		$search_fields = array();
		$user_id = NULL;
		if ($this->form_validation->run())
		{

			$booking_array = array();
			$username_array = array();
			$route_array = array();
			$car_array = array();
			
			if($sdate=="" && $edate !=="")
			{
				$sdate = $edate;
			}
			if($sdate!=="" && $edate =="")
			{
				$edate = $sdate;
			}

			if ($booking_id !=="") {
				$booking_array = array('booking_id'=>$booking_id);
				//$search_fields = array_merge($search_fields, $booking_array);
			}

			if ($username !=="") {
				$query = mysql_query("SELECT `id` FROM `a3m_account` WHERE `username` = '$username'");
				$user = mysql_fetch_assoc($query);
				if ($user['id']) 
				{
					$user_id = array('user_id'=>$user['id']);
				}
				else
				{
					$user_id = array('user_id'=>'none');;
				}
			}
			if ($route_id !=="") {
				$route_array = array('route_id'=>$route_id);
				//$search_fields = array_merge($search_fields, $route_id);
			}
			if ($car_id !=="") {
				$car_array = array('car_id'=>$car_id);
				//$search_fields = array_merge($search_fields, $route_id);
			}
			$search_fields = array_merge($booking_array, $route_array, $car_array);
		}
		if (empty($search_fields)) {
			$search_fields = NULL;
		}
		//echo "Search Fields = "; print_r($search_fields);
		// Paginations
		$this->load->library('pagination');
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/latest_booking/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_booking');
		$config['num_links'] = 3;
		$config['per_page'] = 20;
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
				
		$latest_booking = $this->booking_model->get_list_view_with_date_range('car_booking', $search_fields, $id=NULL, $select=NULL, 'booking_id', 'desc', $page, $config["per_page"], $sdate, $edate, $user_id);
		
		$route_select = '`route_name_en`, `route_name_bn`';
		$schedule_select = '`car_id`, `start_time`';
		$data['latest_booking'] = array();
		foreach( $latest_booking as $latest_book)
		{	
			$current_booking = array();
			$current_booking['booking_id'] = $latest_book->booking_id;
			$current_booking['username'] = $this->account_model->get_by_id($latest_book->user_id)->username;
			$current_booking['schedule_id'] = $latest_book->schedule_id;
			$current_booking['pickup_time'] = $latest_book->pickup_time;
			$current_booking['arrival_time'] = $latest_book->arrival_time;
			$current_booking['route_details'] = $this->general->get_all_table_info_by_id_custom('car_route', $route_select, 'route_id', $latest_book->route_id);
			$current_booking['date_of_booking'] = $latest_book->date_of_booking;
			$current_booking['pickup_point'] = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select = NULL, 'pickup_point_id', $latest_book->start_pickup_point)->pickup_point_en;
			$current_booking['drop_point'] = $this->general->get_all_table_info_by_id_custom('car_pickup_point',$sche_select = NULL, 'pickup_point_id', $latest_book->end_pickup_point)->pickup_point_en;
			$current_booking['drt_cost'] = $this->general->get_all_table_info_by_id_custom('car_drt_cost',$sche_select = NULL, 'booking_id', $latest_book->booking_id)->fare_cost;
			
			$current_booking['no_of_set'] = $latest_book->no_of_set;
			$current_booking['booking_date'] = $latest_book->create_date;
			$current_booking['status'] = $latest_book->status;
			$data['latest_booking'][] = $current_booking;
		}
						
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;
		
		$this->load->view('car/latest_booking', isset($data) ? $data : NULL);
		//$this->load->view('car/car_schedules', isset($data) ? $data : NULL);
	}
	
	function booking_processing($booking_id)
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			redirect(base_url().'dashboard');
		}

		$table_data = array('status'=>3);
		$this->general->update_table('car_booking', $table_data,'booking_id', $booking_id);
		$this->session->set_flashdata('message_success', lang('success_update'));
		redirect('car/latest_booking/');
	}
	
	function booking_accepted($booking_id)
	{
		
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			redirect(base_url().'dashboard');
		}
		$table_data = array('status'=>0);
		$this->general->update_table('car_booking', $table_data,'booking_id', $booking_id);
		$this->session->set_flashdata('message_success', lang('success_update'));
		redirect('car/latest_booking/');
	}
	
	function booking_availed($booking_id)
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			redirect(base_url().'dashboard');
		}
		$table_data = array('status'=>1);
		$this->general->update_table('car_booking', $table_data,'booking_id', $booking_id);
		$this->session->set_flashdata('message_success', lang('success_update'));
		redirect('car/latest_booking/');
	}
	
	function booking_cancelled($booking_id)
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			redirect(base_url().'dashboard');
		}
		$table_data = array('status'=>2);
		$this->general->update_table('car_booking', $table_data,'booking_id', $booking_id);
		$this->session->set_flashdata('message_success', lang('success_update'));
		redirect('car/latest_booking/');
	}

	function delete_booking($booking_id){
		
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/latest_booking'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_booking_management'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage booking');
			redirect(base_url().'dashboard');
		}
		if($this->general->delete_from_table('car_booking', 'booking_id', $booking_id)):
		$this->general->delete_from_table('car_drt_cost', 'booking_id', $booking_id);
		$this->session->set_flashdata('message_success', 'Your data successfully deleted');
		endif;
		redirect('car/latest_booking/');	
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