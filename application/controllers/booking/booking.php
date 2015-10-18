<?php
class Booking extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','booking_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
		//$this->load->language(array( 'account/account_settings'));
		
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', 'english');
		$this->lang->load('mainmenu', 'english');
		$this->lang->load('registration_form', 'english');
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);	
		$this->lang->load('registration_form', $language);
		}
		
	}
	
	public function index()  
	{
	
		maintain_ssl();

				
				$data['site'] = $this->ref_site_model->get_all_site();
				$data['services'] = $this->ref_services_model->get_all_services();
				$data['all_division'] = $this->ref_location_model->get_all_division();
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');


				$this->form_validation->set_rules('firstname', 'Firstname', 'required');
				$this->form_validation->set_rules('middlename', 'Middlaname');
				$this->form_validation->set_rules('lastname', 'Lastname');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('site_division', 'Division', 'required');
				$this->form_validation->set_rules('site_district', 'District', 'required');
				$this->form_validation->set_rules('site_upazila', 'Upazila', 'required');
				$this->form_validation->set_rules('site_union', 'Union', 'required');
				$this->form_validation->set_rules('reg_landmark', 'Landmark');
				$this->form_validation->set_rules('phone_country_code', 'Country Code','required|min_length[3]|max_length[4]');
				$this->form_validation->set_rules('phone_part1', 'Phone Operator','required|is_natural|min_length[5]|max_length[5]');
				$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','required|is_natural|min_length[6]|max_length[6]');
				$this->form_validation->set_rules('reg_national_id', 'National Id');
				$this->form_validation->set_rules('services_date', 'Services Date');
				$this->form_validation->set_rules('reg_note', 'Note');
				$this->form_validation->set_rules('settings_dob_month', 'settings_dob_month');
				
				$this->form_validation->set_rules('reg_site', 'Site', 'required');
				$this->form_validation->set_rules('reg_services_point', 'Services point', 'required');
				$this->form_validation->set_rules('reg_services', 'Services name', 'required');
				$this->form_validation->set_rules('schedule_date', 'Schedule date', 'required');
				$this->form_validation->set_rules('schedule_time', 'Schedule time', 'required');				
				
				
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar Booking';					
					$this->load->view('booking/view_create_booking', isset($data) ? $data : NULL);
				}
				else
				{
					
					$middle_name=$this->input->post('middlename');					$middle_name  = empty($middle_name) ? NULL : $middle_name;
					$last_name=$this->input->post('lastname');						$last_name  = empty($last_name) ? NULL : $last_name;
					
					if(($this->input->post('settings_dob_year')) && ($this->input->post('settings_dob_month')) && ($this->input->post('settings_dob_day')))
					$dob=$this->input->post('settings_dob_year').'-'.$this->input->post('settings_dob_month').'-'.$this->input->post('settings_dob_day');
					else
					$dob=NULL;
					
					$mouza_id=$this->input->post('site_mouza');						$mouza_id  = empty($mouza_id) ? NULL : $mouza_id;
					$village_id=$this->input->post('site_village');					$village_id  = empty($village_id) ? NULL : $village_id;
					
					$guardian_name=$this->input->post('guardian_name');				$guardian_name  = empty($guardian_name) ? NULL : $guardian_name;
					$landmark=$this->input->post('reg_landmark');					$landmark  = empty($landmark) ? NULL : $landmark;
					$phone=$this->input->post('reg_phone');							$phone  = empty($phone) ? NULL : $phone;
					$national_id=$this->input->post('reg_national_id');				$national_id  = empty($national_id) ? NULL : $national_id;
					$note=$this->input->post('reg_note');							$note  = empty($note) ? NULL : $note;
					
					$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
					$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
					$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
					
					$services_date=$this->input->post('schedule_date')." ".$this->input->post('schedule_time').":00";
					
					$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
					$phone  = strlen($phone)<14 ? NULL : $phone;
					
					
					$reg_data_table_registration=array(
						'first_name'=>$this->input->post('firstname'),
						'middle_name'=>$middle_name,
						'last_name'=>$last_name,
						'guardian_name'=>$guardian_name,
						'dob'=>$dob,
						'gender'=>$this->input->post('gender'),
						'division_id'=>$this->input->post('site_division'),
						'district_id'=>$this->input->post('site_district'),
						'upazila_id'=>$this->input->post('site_upazila'),
						'union_id'=>$this->input->post('site_union'),
						'mouza_id'=>$mouza_id,
						'village_id'=>$village_id,
						'landmark'=>$landmark,
						'phone'=>$phone,
						'national_id'=>$national_id,
						'note'=>$note,
						//'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'status'=>0
						);
					$booking_id=$this->booking_model->save_booking($reg_data_table_registration);
					
					$reg_data_table_booking_for_services=array(
						'booking_id'=>$booking_id,
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						//'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'services_status'=>0
						);
				
					
															
					$success_or_fail=$this->booking_model->save_booking_services($reg_data_table_booking_for_services);	
					
					
					if($success_or_fail)
					$data['success_msg']="Booking Successfull";
					else
					$data['success_msg']="Booking Unsuccessfull! Please try again";
					
					$this->load->view('booking/view_create_booking', isset($data) ? $data : NULL);
				}
	}
	
	
	/**** Ajax function ****/
	public function load_services_point_schedule_date($services_point_id)
	{
	$data['services_point_schedule']=$this->booking_model->get_all_schedule_date_by_services_point_id($services_point_id);
		
		
		if($data['services_point_schedule'])
		{
		echo '<option value="">'.lang('settings_select').'</option>';	
		foreach ($data['services_point_schedule'] as $services_point_schedule1) : 
		?>
            <option value="<?php echo $services_point_schedule1->schedule_date; ?>">
				<?php echo $services_point_schedule1->schedule_date; ?>
            </option>
		<?php endforeach; 
		}
		else
		{
		echo "No schedule found";
		}
		?> 	
	
	<?php			
	}
	
	
	
	
	public function new_registration($val)  
	{

		
	}			
	
	
	public function booking_list()
	{

		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
		if($this->authorization->is_permitted('edit_booking'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar Booking List';	
			
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "booking/booking/booking_list/";
			$config["total_rows"] = $this->booking_model->all_booking_count();
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			//$config['anchor_class'] = 'follow_link';
			
			$choice = $config['total_rows']/$config['per_page'];
			$config['num_links'] = round($choice);
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_booking'] = $this->booking_model->get_all_booking_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			
			
			
			$this->load->view('booking/view_view_booking', isset($data) ? $data : NULL);
			
			
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	public function search_view_booking()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_booking'))
			{
				
				// assign posted valued
				$data['sbooking_id']    	= $this->input->post('sbooking_id');
				$data['sfirstname']     		= $this->input->post('sfirstname');
				$data['smiddlename']     		= $this->input->post('smiddlename');
				$data['slastname']     			= $this->input->post('slastname');
				$data['sguardian']     			= $this->input->post('sguardian');
				$data['sgender']				= $this->input->post('sgender');
				$data['sunion']					= $this->input->post('sunion');
				$data['svillage']				= $this->input->post('svillage');
				$data['sphone']					= $this->input->post('sphone');
				$data['snationalid']			= $this->input->post('snationalid');
				$data['slandmark']				= $this->input->post('slandmark');
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_booking";	
		
				$sbooking_id=$this->input->post("sbooking_id");
				
				$query_string=$query_string." WHERE (gramcar_booking.status = 0)";
			
				if($this->input->post("sbooking_id"))	
				{
					$sregistration_no=$this->input->post("sbooking_id"); 
					$query_string=$query_string." AND (gramcar_booking.booking_id = '$sbooking_id')";
				}
				
				if($this->input->post("sfirstname"))	
				{
					$sfirstname=$this->input->post("sfirstname");	
					$query_string=$query_string." AND(gramcar_booking.first_name LIKE '%$sfirstname%')";
				}
				
				if($this->input->post("smiddlename"))	
				{
					$smiddlename=$this->input->post("smiddlename");	
					$query_string=$query_string." AND(gramcar_booking.middle_name LIKE '%$smiddlename%')";
				}
				
				if($this->input->post("slastname"))	
				{
					$slastname=$this->input->post("slastname");	
					$query_string=$query_string." AND(gramcar_booking.last_name LIKE '%$slastname%')";
				}
				
				if($this->input->post("sguardian"))	
				{
					$sguardian=$this->input->post("sguardian");	
					$query_string=$query_string." AND(gramcar_booking.guardian_name LIKE '%$sguardian%')";
				}
				
				if($this->input->post("sgender"))	
				{
					$sgender=$this->input->post("sgender");	
					$query_string=$query_string." AND(gramcar_booking.gender LIKE '%$sgender%')";
				}
				
				if($this->input->post("sunion"))	
				{
					$sunion=$this->input->post("sunion");	
					$query_string=$query_string." AND(gramcar_booking.union_name LIKE '%$sunion%')";
				}
				
				if($this->input->post("svillage"))	
				{
					$svillage=$this->input->post("svillage");	
					$query_string=$query_string." AND(gramcar_booking.village_name LIKE '%$svillage%')";
				}
				
				if($this->input->post("sphone"))	
				{
					$sphone=$this->input->post("sphone");	
					$query_string=$query_string." AND(gramcar_booking.phone LIKE '%$sphone%')";
				}
				
				if($this->input->post("snationalid"))	
				{
					$snationalid=$this->input->post("snationalid");	
					$query_string=$query_string." AND(gramcar_booking.national_id LIKE '%$snationalid%')";
				}
				
				if($this->input->post("slandmark"))	
				{
					$slandmark=$this->input->post("slandmark");	
					$query_string=$query_string." AND(gramcar_booking.landmark LIKE '%$slandmark%')";
				}
				
			
				$query_string=$query_string." ORDER BY gramcar_booking.create_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->booking_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar Booking List';	
			
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "booking/booking/search_view_booking/";
			$config["total_rows"] = $this->booking_model->all_booking_count_query_string($searchterm);
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			//$config['anchor_class'] = 'follow_link';
			
			$choice = $config['total_rows']/$config['per_page'];
			$config['num_links'] = round($choice);
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_booking'] = $this->booking_model->get_all_booking_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;						
			
			$this->load->view('booking/view_view_booking', isset($data) ? $data : NULL);										
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}	
		
		
		}
		else
		{
			redirect('account/sign_in');
		}
		
	}
	
	
	public function create_registration_from_booking($booking_id)
	{
		
		
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_booking'))
			{											
			
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('registration_no', 'Registration no', 'required|is_natural|min_length[10]|max_length[10]');
				$this->form_validation->set_rules('firstname', 'Firstname', 'required');
				$this->form_validation->set_rules('middlename', 'Middlaname');
				$this->form_validation->set_rules('lastname', 'Lastname');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('site_division', 'Division', 'required');
				$this->form_validation->set_rules('site_district', 'District', 'required');
				$this->form_validation->set_rules('site_upazila', 'Upazila', 'required');
				$this->form_validation->set_rules('site_union', 'Union', 'required');
				$this->form_validation->set_rules('reg_landmark', 'Landmark');
				$this->form_validation->set_rules('phone_country_code', 'Country Code','min_length[3]|max_length[4]');
				$this->form_validation->set_rules('phone_part1', 'Phone Operator','is_natural|min_length[5]|max_length[5]');
				$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','is_natural|min_length[6]|max_length[6]');
				$this->form_validation->set_rules('reg_national_id', 'National Id');
				$this->form_validation->set_rules('services_date', 'Services Date');
				$this->form_validation->set_rules('reg_note', 'Note');
				$this->form_validation->set_rules('settings_dob_month', 'settings_dob_month');
				
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar Registration from booking: '.$booking_id;
					$data['single_booking'] = $this->booking_model->get_all_booking_info_by_id($booking_id);
					$data['single_services_list'] = $this->booking_model->get_all_services_by_bookingid($booking_id);
					
					//$data['all_division'] = $this->ref_location_model->get_all_division();					
					
					$data['site'] = $this->ref_site_model->get_all_site();
					$data['gramcar_services'] = $this->ref_services_model->get_all_services();	
					
					$this->load->view('booking/view_create_registration_from_booking', isset($data) ? $data : NULL);
				}
				else
				{
					
					
					$middle_name=$this->input->post('middlename');					$middle_name  = empty($middle_name) ? NULL : $middle_name;
					$last_name=$this->input->post('lastname');						$last_name  = empty($last_name) ? NULL : $last_name;
					
					if(($this->input->post('settings_dob_year')) && ($this->input->post('settings_dob_month')) && ($this->input->post('settings_dob_day')))
					$dob=$this->input->post('settings_dob_year').'-'.$this->input->post('settings_dob_month').'-'.$this->input->post('settings_dob_day');
					else
					$dob=NULL;
					
					$mouza_id=$this->input->post('site_mouza');						$mouza_id  = empty($mouza_id) ? NULL : $mouza_id;
					$village_id=$this->input->post('site_village');					$village_id  = empty($village_id) ? NULL : $village_id;
					
					$guardian_name=$this->input->post('guardian_name');				$guardian_name  = empty($guardian_name) ? NULL : $guardian_name;
					$landmark=$this->input->post('reg_landmark');					$landmark  = empty($landmark) ? NULL : $landmark;					
					$national_id=$this->input->post('reg_national_id');				$national_id  = empty($national_id) ? NULL : $national_id;
					$note=$this->input->post('reg_note');							$note  = empty($note) ? NULL : $note;
					
					$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
					$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
					$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
					$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;
					$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
					$phone  = strlen($phone)<14 ? NULL : $phone;															
					
					$reg_data_table_registration=array(	
						'registration_no'=>$this->input->post('registration_no'),													   
						'first_name'=>$this->input->post('firstname'),
						'middle_name'=>$middle_name,
						'last_name'=>$last_name,
						'guardian_name'=>$guardian_name,
						'dob'=>$dob,
						'gender'=>$this->input->post('gender'),
						'division_id'=>$this->input->post('site_division'),
						'district_id'=>$this->input->post('site_district'),
						'upazila_id'=>$this->input->post('site_upazila'),
						'union_id'=>$this->input->post('site_union'),
						'mouza_id'=>$mouza_id,
						'village_id'=>$village_id,
						'landmark'=>$landmark,
						'phone'=>$phone,
						'national_id'=>$national_id,
						'note'=>$note,
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
					
					
					$reg_data_table_registration_for_services=array(
						'registration_no'=>$this->input->post('registration_no'),
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'services_status'=>0
						);
					
					$update_booking_status=array(						
						'status'=>1
						);
					
					$this->booking_model->update_booking($update_booking_status, $this->input->post('booking_id'));
					
					
					
					$success_or_fail=$this->registration_model->save_registration($reg_data_table_registration);
					
					if($reg_services_point && $reg_services)
					{
					$this->registration_model->save_registration_services($reg_data_table_registration_for_services);
					
					
					}
					
					if($success_or_fail)
					$data['success_msg']="Registration Successfull for ".$this->input->post('registration_no');
					else
					$data['success_msg']="Registration Unsuccessfull! Please try again";
					
					
					$data['single_booking'] = $this->booking_model->get_all_booking_info_by_id($booking_id);
					$data['single_services_list'] = $this->booking_model->get_all_services_by_bookingid($booking_id);
					
					$data['site'] = $this->ref_site_model->get_all_site();
					$data['gramcar_services'] = $this->ref_services_model->get_all_services();
					$this->load->view('booking/view_create_registration_from_booking', isset($data) ? $data : NULL);
				}									
			
			
			
			}
			else
			{
			redirect('');  // if not permitted "create_registration" redirect to home page
			}
		}
		else
		{
			redirect('account/sign_in');
		}	
			
	
	}
	
	
	
	
	public function view_single_booking($reg_no)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_booking'))
			{
			//echo $reg_no;
			
			$data['title'] = 'GramCar Booking info: '.$reg_no;
			
			$data['single_booking'] = $this->booking_model->get_all_booking_info_by_id($reg_no);
			$data['single_services_list'] = $this->booking_model->get_all_services_by_bookingid($reg_no);
			$this->load->view('booking/view_single_booking', isset($data) ? $data : NULL);
			
			}
			else
			{
			redirect('');  // if not permitted "create_registration" redirect to home page
			}
		}
		else
		{
			redirect('account/sign_in');
		}	
			
	}
	
	
	
	
	
	
	public function edit_single_booking($reg_no)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_booking'))
			{
			//echo $reg_no;
			
			//$data['title'] = 'GramCar Registration info: '.$reg_no;
			
								
			
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('firstname', 'Firstname', 'required');
				$this->form_validation->set_rules('middlename', 'Middlaname');
				$this->form_validation->set_rules('lastname', 'Lastname');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('site_division', 'Division', 'required');
				$this->form_validation->set_rules('site_district', 'District', 'required');
				$this->form_validation->set_rules('site_upazila', 'Upazila', 'required');
				$this->form_validation->set_rules('site_union', 'Union', 'required');
				$this->form_validation->set_rules('reg_landmark', 'Landmark');
				$this->form_validation->set_rules('phone_country_code', 'Country Code','min_length[3]|max_length[4]');
				$this->form_validation->set_rules('phone_part1', 'Phone Operator','is_natural|min_length[5]|max_length[5]');
				$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','is_natural|min_length[6]|max_length[6]');
				$this->form_validation->set_rules('reg_national_id', 'National Id');
				$this->form_validation->set_rules('services_date', 'Services Date');
				$this->form_validation->set_rules('reg_note', 'Note');
				$this->form_validation->set_rules('settings_dob_month', 'settings_dob_month');
				
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar Booking info update: '.$reg_no;
					$data['single_booking'] = $this->booking_model->get_all_booking_info_by_id($reg_no);
					$data['single_services_list'] = $this->booking_model->get_all_services_by_bookingid($reg_no);
					
					//$data['all_division'] = $this->ref_location_model->get_all_division();					
					
					$data['site'] = $this->ref_site_model->get_all_site();
					$data['gramcar_services'] = $this->ref_services_model->get_all_services();	
					
					$this->load->view('booking/edit_single_booking', isset($data) ? $data : NULL);
				}
				else
				{
					
					
					$middle_name=$this->input->post('middlename');					$middle_name  = empty($middle_name) ? NULL : $middle_name;
					$last_name=$this->input->post('lastname');						$last_name  = empty($last_name) ? NULL : $last_name;
					
					if(($this->input->post('settings_dob_year')) && ($this->input->post('settings_dob_month')) && ($this->input->post('settings_dob_day')))
					$dob=$this->input->post('settings_dob_year').'-'.$this->input->post('settings_dob_month').'-'.$this->input->post('settings_dob_day');
					else
					$dob=NULL;
					
					$mouza_id=$this->input->post('site_mouza');						$mouza_id  = empty($mouza_id) ? NULL : $mouza_id;
					$village_id=$this->input->post('site_village');					$village_id  = empty($village_id) ? NULL : $village_id;
					
					$guardian_name=$this->input->post('guardian_name');				$guardian_name  = empty($guardian_name) ? NULL : $guardian_name;
					$landmark=$this->input->post('reg_landmark');					$landmark  = empty($landmark) ? NULL : $landmark;					
					$national_id=$this->input->post('reg_national_id');				$national_id  = empty($national_id) ? NULL : $national_id;
					$note=$this->input->post('reg_note');							$note  = empty($note) ? NULL : $note;
					
					$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
					$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
					$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
					$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;
					$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
					$phone  = strlen($phone)<14 ? NULL : $phone;															
					
					$reg_data_table_registration=array(						
						'first_name'=>$this->input->post('firstname'),
						'middle_name'=>$middle_name,
						'last_name'=>$last_name,
						'guardian_name'=>$guardian_name,
						'dob'=>$dob,
						'gender'=>$this->input->post('gender'),
						'division_id'=>$this->input->post('site_division'),
						'district_id'=>$this->input->post('site_district'),
						'upazila_id'=>$this->input->post('site_upazila'),
						'union_id'=>$this->input->post('site_union'),
						'mouza_id'=>$mouza_id,
						'village_id'=>$village_id,
						'landmark'=>$landmark,
						'phone'=>$phone,
						'national_id'=>$national_id,
						'note'=>$note,
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
													
					
					$success_or_fail=$this->booking_model->update_booking($reg_data_table_registration,$this->input->post('booking_id'));
					
					$data['single_booking'] = $this->booking_model->get_all_booking_info_by_id($reg_no);
					$data['single_services_list'] = $this->booking_model->get_all_services_by_bookingid($reg_no);
					
					$data['site'] = $this->ref_site_model->get_all_site();
					$data['gramcar_services'] = $this->ref_services_model->get_all_services();
				
					if($success_or_fail)
					$data['success_msg']="Booking Successfull";				
					else
					$data['success_msg']="Booking Unsuccessfull! Please try again";
					
					$this->load->view('booking/edit_single_booking', isset($data) ? $data : NULL);
				}									
			
			
			
			}
			else
			{
			redirect('');  // if not permitted "create_registration" redirect to home page
			}
		}
		else
		{
			redirect('account/sign_in');
		}	
			
	}
	
	
	

	
	/**** Ajax function *****/
	public function delete_booking()
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_booking'))
			{
			$success_or_fail=$this->booking_model->delete_booking($this->input->post('booking_id'));
				if($success_or_fail)
				{
				echo "Successfull";								
				}
				else
				{
				echo "Unsuccessfull";
				}
			}
			else
			{
			redirect('');  // if not permitted "delete_registration" redirect to home page
			}
		}
		else
		{
			redirect('account/sign_in');
		}	
	}
	
		
	
	
	/**** Ajax function *****/
	function update_services()
	{

		$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
		$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
		$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
		$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;
		
		$reg_data_table_booking_for_services=array(
						'booking_id'=>$this->input->post('booking_id'),
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						'services_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
		
		$success_or_fail=$this->booking_model->update_services_by_booking_for_service_id($reg_data_table_booking_for_services,$this->input->post('booking_for_service_id'));
					
		if($success_or_fail)
		echo "Successfull";				
		else
		echo "Unsuccessfull";
					
		//$this->load->view('registration/view_create_registration', isset($data) ? $data : NULL);
		
	}
	

	/**** Ajax function *****/
	function set_booking_status($booking_id,$status)
	{
		return $this->booking_model->set_booking_status($booking_id,$status);
	}
	
	
	
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>