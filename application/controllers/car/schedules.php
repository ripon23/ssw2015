<?php
class Schedules extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('car/general','account/account_model','car/schedule_model'));	
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
		
		$data['title'] = 'Car Schedule';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/schedules/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_schedule');
		$config['num_links'] = 3;
		$config["per_page"] = $this->config->item("pagination_perpage");
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
		
			
		$data['schedules'] = $this->general->get_list_view('car_schedule', $field_name=NULL, $id=NULL, $select=NULL, 'schedule_date', 'desc', $page, $config["per_page"]);

		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;
		$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
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
          'field' => 'car_id', 
          'label' => 'lang:select_car', 
          'rules' => 'trim|required'),
        array(
          'field' => 'sdate', 
          'label' => 'Start Date', 
          'rules' => 'trim|required'),
        array(
          'field' => 'edate', 
          'label' => 'End Date', 
          'rules' => 'trim'), 
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
    	
      	// $time_taken = $this->check_time($this->input->post('users_username'));
        if(empty($id)) {
			$now = gmt_to_local(now(), 'UP5', TRUE);
			$end_date = $this->input->post('edate', TRUE);
			$end_date = $end_date ==""? $this->input->post('sdate', TRUE):$this->input->post('edate', TRUE);
			
			$begin = new DateTime( $this->input->post('sdate', TRUE) );
			$end = new DateTime( $end_date );
			$end = $end->modify( '+1 day' ); 

			$interval = new DateInterval('P1D');
			$daterange = new DatePeriod($begin, $interval ,$end);

			$car_id = $this->input->post('car_id', TRUE);
			$stime = $this->input->post('stime', TRUE);
			$etime = $this->input->post('etime', TRUE);
			foreach($daterange as $date){
				$sc_date = $date->format("Y-m-d");
				$schedule_taken = $this->check_schedule($date->format("Y-m-d"), $car_id, $stime, $etime);

				if ($schedule_taken) {
					$this->session->set_flashdata('message_error', "This time already busy the car");			
      				redirect('car/schedules/save');
				}
				else
				{
					$data = array(					
						'car_id' => $this->input->post('car_id', TRUE),
						'schedule_date' => $date->format("Y-m-d"),
						'start_time' => $stime,
						'end_time' => $etime,
						'enable' => $this->input->post('status', TRUE),
						'create_user_id' => $this->session->userdata('account_id'),
						'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
					$schedule_id = $this->general->save_into_table_and_return_insert_id('car_schedule', $data);
				}				
			    //echo $date->format("Y-m-d") . "<br>";
			}			
			$this->session->set_flashdata('message_success', lang('success_add'));			
      		redirect('car/schedules');
			
        }
        // Update existing News
        else 
        {
        	$car_id = $this->input->post('car_id', TRUE);
        	$sc_date = $this->input->post('sdate', TRUE);
			$stime = $this->input->post('stime', TRUE);
			$etime = $this->input->post('etime', TRUE);
        	
        	$schedule_taken_update = $this->check_schedule_update($sc_date, $this->input->post('car_id', TRUE), $this->input->post('stime', TRUE), $this->input->post('etime', TRUE), $id);
        	
        	if ($schedule_taken_update) {
        		$this->session->set_flashdata('message_error', "Schedule of this time already taken by this car");
				redirect(uri_string());
        	}
        	else
        	{
        		$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
					'car_id' => $this->input->post('car_id', TRUE),
					'schedule_date' => $this->input->post('sdate', TRUE),
					'start_time' => $this->input->post('stime', TRUE),
					'end_time' => $this->input->post('etime', TRUE),
					'enable' => $this->input->post('status', TRUE),
					'update_user_id' => $this->session->userdata('account_id'),
					'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			
				$this->general->update_table('car_schedule', $data,'schedule_id',$id);
				$this->session->set_flashdata('message_success', lang('success_update'));
				redirect('car/schedules');
        	}
      	}
	}
	// Get the account to update
    if( ! $is_new )
    {
      $data['update_details'] = $this->general->get_all_table_info_by_id('car_schedule', 'schedule_id', $id);
      $data['action'] = 'update';
    }
	$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
	//redirect('car/schedules');
    $this->load->view('car/car_schedules_save', $data);
}

function search_schedule()
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
		$this->session->set_flashdata('parmission', 'You have no permission to access manage route');
		//redirect(uri_string());
	  	redirect(base_url().'dashboard');
	}
	
	$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
	$data['title'] = 'Search Schedule';	
		
	$field_name=NULL; 
	$news_id=NULL;		
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
		  'field' => 'car_id',
		  'label' => 'Car',
		  'rules' => 'trim'),
	  ));
	  
	// Search Fields
	$sdate = (trim($this->input->post('sdate'))!=="")? $this->input->post('sdate',true) : NULL;
	$edate = (trim($this->input->post('edate'))!=="")? $this->input->post('edate',true) : NULL;
	$car_id = trim($this->input->post('car_id'));
	$status = trim($this->input->post('status'));
	$search_fields = array();
	if ($this->form_validation->run())
	{
		$car = array();
		$status_array = array();
		
		if($sdate=="" && $edate !=="")
		{
			$sdate = $edate;
		}
		if($sdate!=="" && $edate =="")
		{
			$edate = $sdate;
		}

		if($status!="")
		{
			$search_fields = array('enable'=>$status);
		}
		if ($car_id !=="") {
			$car = array('car_id'=>$car_id);
			$search_fields = array_merge($search_fields, $car);
		}
		// echo "sdate ".$sdate;
		// echo "Edate".$edate;
		// exit();
		$data['schedules'] = $this->general->get_list_search_date_range('car_schedule', $search_fields, NULL, $sdate, $edate);	
	}
	// print_r($data['schedules']);
	// exit();
	$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
	$this->load->view('car/car_schedules', isset($data) ? $data : NULL);
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