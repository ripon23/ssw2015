<?php
class Sdrt_schedules extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('general_model','car/general','account/account_model','car/schedule_model','car/booking_model'));	
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
		
		$data['title'] = 'sDRT Car Schedule List';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/sdrt_schedules/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_sdrt_schedule');
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
		
			
		$data['schedules'] = $this->general->get_list_view('car_sdrt_schedule', $field_name=NULL, $id=NULL, $select=NULL, 'schedule_date', 'desc', $page, $config["per_page"]);

		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;
		$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);
		$data['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select, 'route_id', 'desc', NULL, NULL);
		$this->load->view('car/car_sdrt_schedules', isset($data) ? $data : NULL);
	}
	
	
	public function ajax_get_sdrt_schedule()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/schedules'));
		}
		
		$route_id=$this->input->post("route_id");
		if($route_id)
		{
		$query="SELECT * FROM car_sdrt_schedule WHERE route_id=".$route_id." AND schedule_date>='".date('Y-m-d')."'";
		$data['schedule_array']=$this->general_model->get_all_querystring_result($query);
		$data['all_pickup_point']=$this->booking_model->get_all_pickup_point_in_a_route($route_id);
		echo $this->load->view('car/ajax_view_sdrt_schedule', $data, TRUE);
		//print_r($schedule_array);
		}
		else
		{
		echo "Please select a route";
		}
	
	}
	

	public function search_sdrtschedule()
	{
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
		
		$data['title'] = 'sDRT Car Schedule List';
	
	
		$data['sschedule_date']    	= $this->input->post('sschedule_date');
		$data['scar_id']     		= $this->input->post('scar_id');
		$data['sstatus']     		= $this->input->post('sstatus');
		$data['sroute']     		= $this->input->post('sroute');
		
		if($this->input->post("search_submit"))
			{
				$query_string="SELECT * FROM car_sdrt_schedule";								
				$query_string=$query_string." WHERE (schedule_id > 0)";
			
				if($this->input->post("sschedule_date"))	
				{
					$sschedule_date=$this->input->post('sschedule_date');
					$query_string=$query_string." AND (schedule_date = '$sschedule_date')";
				}
				
				if($this->input->post("scar_id"))	
				{
					$scar_id=$this->input->post('scar_id');
					$query_string=$query_string." AND (car_id = $scar_id)";
				}
				
				if($this->input->post("sroute"))	
				{
					$sroute=$this->input->post('sroute');
					$query_string=$query_string." AND (route_id = $sroute)";
				}
				
				if($this->input->post("sstatus"))	
				{
					$sstatus=$this->input->post('sstatus');
					$query_string=$query_string." AND (schedule_status = $sstatus)";
				}
				
				
				$query_string=$query_string." ORDER BY schedule_date DESC";	
				$searchterm = $this->general->searchterm_handler($query_string);

			}
			else
			{
			$searchterm = $this->session->userdata('searchterm');

			}
		
		//echo "---------".$searchterm;
		
		$data['title'] = 'sDRT Car Schedule List';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/sdrt_schedules/search_sdrtschedule/';
		$config['total_rows'] = $this->general->total_count_query_string($searchterm);
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
		
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;
		
		$data['schedules'] =$this->general->get_all_result_by_limit_querystring($searchterm, $config["per_page"], $page);
		
		//$data['schedules'] = $this->general->get_list_view('car_sdrt_schedule', $field_name=NULL, $id=NULL, $select=NULL, 'schedule_date', 'desc', 1, 10);
	
			
	$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);
	$data['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select, 'route_id', 'desc', NULL, NULL);
	$this->load->view('car/car_sdrt_schedules', isset($data) ? $data : NULL);
	
	}



  function add_sdrtschedule()
  {
  	
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
          'field' => 'car_id', 
          'label' => 'Car', 
          'rules' => 'trim|required'),
		array(
          'field' => 'route_id', 
          'label' => 'Route', 
          'rules' => 'trim|required'),
		array(
          'field' => 'start_node', 
          'label' => 'Start node', 
          'rules' => 'trim|required'),
		array(
          'field' => 'drop_node', 
          'label' => 'Drop node', 
          'rules' => 'trim|required'),
        array(
          'field' => 'sdate', 
          'label' => 'Start Date', 
          'rules' => 'trim|required'),
        array(
          'field' => 'stime', 
          'label' => 'Strat Time', 
          'rules' => 'trim|required'), 
        array(
          'field' => 'etime', 
          'label' => 'End Time', 
          'rules' => 'trim|required'), 
		array(
          'field' => 'status', 
          'label' => 'lang:status', 
          'rules' => 'trim|required')
      ));
    // Run form validation
    if ($this->form_validation->run())
    {
		$data = array(					
			'route_id' => $this->input->post('route_id', TRUE),
			'car_id' => $this->input->post('car_id', TRUE),
			'schedule_date' => $this->input->post('sdate', TRUE),
			'start_time' => $this->input->post('stime', TRUE),
			'arrival_time' => $this->input->post('etime', TRUE),
			'start_node' => $this->input->post('start_node', TRUE),
			'destination_node' => $this->input->post('drop_node', TRUE),
			'per_seat_fare' => $this->input->post('per_seat_fare', TRUE),
			'schedule_status' => $this->input->post('status', TRUE),
			'create_user_id' => $this->session->userdata('account_id'),
			'create_date' => mdate('%Y-%m-%d %H:%i:%s', now())
		);

		$schedule_id = $this->general->save_into_table_and_return_insert_id('car_sdrt_schedule', $data);
		
		$this->session->set_flashdata('message_success', lang('success_add'));			
      	redirect('car/sdrt_schedules');			
       
	}
	
	$data['all_routes'] = $this->general->get_list_view('car_route', 'enable', 1, $select=NULL, 'route_id', 'desc', NULL, NULL);
	$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
    $this->load->view('car/car_sdrtschedules_save', $data);
}




public function validate_date()
{
	$date = $this->input->post('sdate');
	//$date="2012-09-12";
	//Some date fields are not compulsory
	//If all of them are compulsory then just remove this check
	if ($date == '')
	{
		return true;
	}

	//If in dd/mm/yyyy format
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
	{
		//Extract date parts
		$date_array = explode('/', $date);

		//If it is not a date
		if (! checkdate($date_array[1], $date_array[0], $date_array[2]))
		{
		$this->form_validation->set_message('validate_date', 'The %s field must contain a valid date.');
		return false;
		}
	}
	//If not in dd/mm/yyyy format
	else
	{
		$this->form_validation->set_message('validate_date', 'The %s field must contain a valid date.');
		return false;
	}
	return true;
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
			$this->general->delete_from_table('car_schedule', 'schedule_id', $id);
			$this->session->set_flashdata('message_success', 'Your data successfully deleted');
			redirect(base_url().'car/schedules');
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete route');
			redirect(base_url().'car/schedules');
		}
  }

  function check_schedule($schedule_date, $car_id, $stime, $etime){
  	return $this->schedule_model->get_car_schedule($schedule_date, $car_id, $stime, $etime)? TRUE:FALSE;
  }

  function check_schedule_update($schedule_date, $car_id, $stime, $etime, $schedule_id){
  	return $this->schedule_model->check_schedule_update($schedule_date, $car_id, $stime, $etime, $schedule_id)? TRUE:FALSE;
  }
}// END Class

?>