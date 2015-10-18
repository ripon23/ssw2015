<?php
class Registration extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model','general_model' ));	
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

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('create_registration'))
			{
				
				$data['site'] = $this->ref_site_model->get_all_site();
				$data['services'] = $this->ref_services_model->get_all_services();
				$data['all_division'] = $this->ref_location_model->get_all_division();
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				//$this->form_validation->set_rules('registration_no', 'Registration no', 'required|min_length[9]|max_length[9]|callback_registration_no_check');
				$this->form_validation->set_rules('registration_no_part2', 'Registration serial no', 'required|is_natural|min_length[10]|max_length[10]|callback_registration_no_check');
				$this->form_validation->set_rules('firstname', 'Firstname', 'required');
				$this->form_validation->set_rules('middlename', 'Middlaname');
				$this->form_validation->set_rules('lastname', 'Lastname');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('registration_site', 'Registration site', 'required');
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
					$data['title'] = 'GramCar Registration';					
					$this->load->view('registration/view_create_registration', isset($data) ? $data : NULL);
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
					$reg_site_id=$this->input->post('registration_site');	   		$reg_site_id  = empty($reg_site_id) ? NULL : $reg_site_id;
					
					$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
					$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
					$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
					$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;
					$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
					$phone  = strlen($phone)<14 ? NULL : $phone;
					
					if($this->input->post('gender')=='M')
					$reg_no_gender=1;
					else
					$reg_no_gender=2;
					
					$full_registration_no=$this->input->post('registration_no_part2');
					if($family_id)
					{
						$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $family_id);
					}
					else
					{
					$family_id=NULL;	
					}
					
					
					$reg_data_table_registration=array(
						'registration_no'=>$full_registration_no,
						'family_id'=>$family_id,
						'first_name'=>$this->input->post('firstname'),
						'middle_name'=>$middle_name,
						'last_name'=>$last_name,
						'guardian_name'=>$guardian_name,
						'dob'=>$dob,
						'gender'=>$this->input->post('gender'),
						'site_id'=>$reg_site_id,
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
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'status'=>1
						);
					
					
					$reg_data_table_registration_for_services=array(
						'registration_no'=>$full_registration_no,
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'services_status'=>0
						);
				
					
					
					$success_or_fail=$this->registration_model->save_registration($reg_data_table_registration);
					
					if($reg_services_point && $reg_services)
					{
					$this->registration_model->save_registration_services($reg_data_table_registration_for_services);	
					}
					
					if($this->input->post('received_payment')==1)
					{
						$reg_payment=array(
						'registration_no'=>$full_registration_no,
						'payment_received_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'received_amount'=>$this->input->post('received_amount'),
						'free_or_paid'=>$this->input->post('payment_status'),
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					$this->registration_model->save_registration_payment($reg_payment);		
					}
					
					if($success_or_fail)
					$data['success_msg']="Registration Successfull for ".$full_registration_no;
					else
					$data['success_msg']="Registration Unsuccessfull! Please try again";
					
					$this->load->view('registration/view_create_registration', isset($data) ? $data : NULL);
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
	
	public function new_registration($val=NULL,$family_id=NULL)  
	{

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			
			if($this->authorization->is_permitted('create_registration'))
			{
			
				
				$data['site'] = $this->ref_site_model->get_all_site();
				$data['services'] = $this->ref_services_model->get_all_services();
				$data['all_division'] = $this->ref_location_model->get_all_division();
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('registration_no_part2', 'Registration serial no', 'required|is_natural|min_length[10]|max_length[10]|callback_registration_no_check');
				$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_dash|min_length[6]|max_length[20]|callback_username_check|xss_clean');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
				$this->form_validation->set_rules('firstname', 'Firstname', 'required');
				$this->form_validation->set_rules('middlename', 'Middlaname');
				$this->form_validation->set_rules('lastname', 'Lastname');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('registration_site', 'Registration site', 'required');
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
					$data['title'] = 'GramCar Registration';
					
					/*$data['reg_no_part1']=substr($val,0,2);
					$data['reg_no_part2']=substr($val,6,1);
					$data['reg_no_part3']=substr($val,7,6);
					
					$data['reg_no_part1_1']=substr($val,2,2);
					$data['reg_no_part1_2']=substr($val,4,2);*/
					$data['reg_no']=$val;
					//$data['temp_reg']=$val;
					if($family_id)
					{
						$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $family_id);
					}
					else
					{
					$family_id=NULL;	
					}
					$this->load->view('registration/view_create_registration_urban', isset($data) ? $data : NULL);
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
					$reg_site_id=$this->input->post('registration_site');	   		$reg_site_id  = empty($reg_site_id) ? NULL : $reg_site_id;
					$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
					$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
					$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
					$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;
					$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
					$phone  = strlen($phone)<14 ? NULL : $phone;
					
					if($this->input->post('gender')=='M')
					{ $reg_no_gender=1; $gender_a3m='m';}
					else
					{$reg_no_gender=2; $gender_a3m='f';}
					
					$this->load->helper('account/phpass');
					$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
					$new_hashed_password = $hasher->HashPassword($this->input->post('password'));
		
					$a3m_account_data=array(
						'username'=>$this->input->post('username'),
						'email'=>$this->input->post('email'),
						'password'=>$new_hashed_password,						
						'createdon'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
					$a3m_account_id=$this->general_model->save_into_table_and_return_insert_id('a3m_account', $a3m_account_data);
					
					$a3m_account_details_data=array(
						'account_id'=>$a3m_account_id,
						'fullname'=>$this->input->post('firstname')." ".$middle_name." ".$last_name,
						'firstname'=>$this->input->post('firstname'),
						'lastname'=>$last_name,
						'dateofbirth'=>$dob,
						'gender'=>$gender_a3m						
						);
				
					$success_or_fail1=$this->general_model->save_into_table('a3m_account_details', $a3m_account_details_data);
					
					$a3m_rel_account_role_data=array(
						'account_id'=>$a3m_account_id,
						'role_id'=>$this->config->item("customer_role_id")
						);
				
					$success_or_fail3=$this->general_model->save_into_table('a3m_rel_account_role', $a3m_rel_account_role_data);
					
					
										
					$full_registration_no=$this->input->post('registration_no_part2');
					
					if($family_id)
					{
						$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $family_id);
					}
					else
					{
					$family_id=NULL;	
					}
					$reg_data_table_registration=array(
						'registration_no'=>$full_registration_no,
						'user_id'=>$a3m_account_id,
						'family_id'=>$family_id,
						'first_name'=>$this->input->post('firstname'),
						'middle_name'=>$middle_name,
						'last_name'=>$last_name,
						'guardian_name'=>$guardian_name,
						'dob'=>$dob,
						'gender'=>$this->input->post('gender'),
						'site_id'=>$reg_site_id,
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
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'status'=>1
						);
					
					
					$reg_data_table_registration_for_services=array(
						'registration_no'=>$full_registration_no,
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'services_status'=>0
						);
				
					
					$success_or_fail=$this->registration_model->save_registration($reg_data_table_registration);
					
					if($reg_services_point && $reg_services)
					{
					$this->registration_model->save_registration_services($reg_data_table_registration_for_services);	
					}
					
					if($success_or_fail)
					$data['success_msg']="Registration Successfull for ".$full_registration_no;
					else
					$data['success_msg']="Registration Unsuccessfull! Please try again";
					
					
					$this->load->view('registration/view_create_registration_urban', isset($data) ? $data : NULL);
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
	
	
	public function view_registration()
	{

		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
		if($this->authorization->is_permitted('view_registration'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar Registration List';	
			
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "registration/registration/view_registration/";
			$config["total_rows"] = $this->registration_model->all_registration_count();
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
			//$config['num_links'] = round($choice);
			$config['num_links'] = 5;
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_registration'] = $this->registration_model->get_all_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			
			
			
			$this->load->view('registration/view_view_registration', isset($data) ? $data : NULL);
			
			
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
	
	
	public function search_view_registration()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_registration'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
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
				$query_string="SELECT * FROM gramcar_registration";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_registration.status = 1)";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_registration.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("sfirstname"))	
				{
					$sfirstname=$this->input->post("sfirstname");	
					$query_string=$query_string." AND(gramcar_registration.first_name LIKE '%$sfirstname%')";
				}
				
				if($this->input->post("smiddlename"))	
				{
					$smiddlename=$this->input->post("smiddlename");	
					$query_string=$query_string." AND(gramcar_registration.middle_name LIKE '%$smiddlename%')";
				}
				
				if($this->input->post("slastname"))	
				{
					$slastname=$this->input->post("slastname");	
					$query_string=$query_string." AND(gramcar_registration.last_name LIKE '%$slastname%')";
				}
				
				if($this->input->post("sguardian"))	
				{
					$sguardian=$this->input->post("sguardian");	
					$query_string=$query_string." AND(gramcar_registration.guardian_name LIKE '%$sguardian%')";
				}
				
				if($this->input->post("sgender"))	
				{
					$sgender=$this->input->post("sgender");	
					$query_string=$query_string." AND(gramcar_registration.gender LIKE '%$sgender%')";
				}
				
				if($this->input->post("sunion"))	
				{
					$sunion=$this->input->post("sunion");	
					$query_string=$query_string." AND(gramcar_registration.union_name LIKE '%$sunion%')";
				}
				
				if($this->input->post("svillage"))	
				{
					$svillage=$this->input->post("svillage");	
					$query_string=$query_string." AND(gramcar_registration.village_name LIKE '%$svillage%')";
				}
				
				if($this->input->post("sphone"))	
				{
					$sphone=$this->input->post("sphone");	
					$query_string=$query_string." AND(gramcar_registration.phone LIKE '%$sphone%')";
				}
				
				if($this->input->post("snationalid"))	
				{
					$snationalid=$this->input->post("snationalid");	
					$query_string=$query_string." AND(gramcar_registration.national_id LIKE '%$snationalid%')";
				}
				
				if($this->input->post("slandmark"))	
				{
					$slandmark=$this->input->post("slandmark");	
					$query_string=$query_string." AND(gramcar_registration.landmark LIKE '%$slandmark%')";
				}
				
			
				$query_string=$query_string." ORDER BY gramcar_registration.create_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar Registration List';	
			
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "registration/registration/search_view_registration/";
			$config["total_rows"] = $this->registration_model->all_registration_count_query_string($searchterm);
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
			$data['all_registration'] = $this->registration_model->get_all_registration_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('registration/view_view_registration', isset($data) ? $data : NULL);
				
			
			
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
	
	public function view_single_registration($reg_no)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_registration'))
			{
			//echo $reg_no;
			
			$data['title'] = 'GramCar Registration info: '.$reg_no;
			
			$data['single_registration'] = $this->registration_model->get_all_registration_info_by_id($reg_no);
			$data['single_services_list'] = $this->registration_model->get_all_services_by_regid($reg_no);
			$this->load->view('registration/view_single_registration', isset($data) ? $data : NULL);
			
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
		
	
	
	public function edit_single_registration($reg_no)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_registration'))
			{
			//echo $reg_no;
			
			//$data['title'] = 'GramCar Registration info: '.$reg_no;
			
								
			
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('firstname', 'Firstname', 'required');
				$this->form_validation->set_rules('middlename', 'Middlaname');
				$this->form_validation->set_rules('lastname', 'Lastname');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
				$this->form_validation->set_rules('registration_site', 'registration site', 'required');
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
					$data['title'] = 'GramCar Registration info Update: '.$reg_no;
					$data['single_registration'] = $this->registration_model->get_all_registration_info_by_id($reg_no);
					$data['single_services_list'] = $this->registration_model->get_all_services_by_regid($reg_no);
					
					//$data['all_division'] = $this->ref_location_model->get_all_division();					
					
					$data['site'] = $this->ref_site_model->get_all_site();
					$data['gramcar_services'] = $this->ref_services_model->get_all_services();	
					
					$this->load->view('registration/edit_single_registration', isset($data) ? $data : NULL);
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
					
					$reg_site_id=$this->input->post('registration_site');	   		$reg_site_id  = empty($reg_site_id) ? NULL : $reg_site_id;
					
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
						'site_id'=>$reg_site_id,
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
													
					
					$success_or_fail=$this->registration_model->update_registration($reg_data_table_registration,$this->input->post('registration_no'));
					
					$data['single_registration'] = $this->registration_model->get_all_registration_info_by_id($reg_no);
					$data['single_services_list'] = $this->registration_model->get_all_services_by_regid($reg_no);
					
					$data['site'] = $this->ref_site_model->get_all_site();
					$data['gramcar_services'] = $this->ref_services_model->get_all_services();
				
					if($success_or_fail)
					$data['success_msg']="Registration Successfull";				
					else
					$data['success_msg']="Registration Unsuccessfull! Please try again";
					
					$this->load->view('registration/edit_single_registration', isset($data) ? $data : NULL);
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
	
	
	
	public function registration_no_check($reg_no)
	{
		
		/*$dt=$this->input->post('site_district');
		$up=$this->input->post('site_upazila');
		$un=$this->input->post('site_union');
		if($this->input->post('gender')=='M')
		$gender=1;
		else
		$gender=2;
		
		$fullreg_no=$dt.$up.$un.$gender.$reg_no;*/
		
		$is_exist=$this->registration_model->registration_no_exits($reg_no);
		
		if ($is_exist > 0)
		{
			$this->form_validation->set_message('registration_no_check', ' The registration number '.$reg_no .' is already exits');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/**** Ajax function *****/
	public function delete_registration()
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('delete_registration'))
			{
			$success_or_fail=$this->registration_model->delete_registration($this->input->post('reg_no'));
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
	function add_new_services()
	{
	//$reg_no=$this->input->post('reg_no');							//$reg_no  = empty($reg_no) ? NULL : $reg_no;
	$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
	$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
	$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
	$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;	
	//var_dump($_POST);
	$reg_data_table_registration_for_services=array(
						'registration_no'=>$this->input->post('reg_no'),
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'services_status'=>$this->input->post('statusvalue')
						);
	
	$success_or_fail=$this->registration_model->add_new_services($reg_data_table_registration_for_services);
					
		if($success_or_fail)
		{
		echo "Successfull";				
		//$this->load->view('registration/edit_single_registration', isset($data) ? $data : NULL);
		}
		else
		{
		echo "Unsuccessfull";
		}
	
	}
	
	
	/**** Ajax function *****/
	function update_services()
	{

		$reg_services_point=$this->input->post('reg_services_point');	$reg_services_point  = empty($reg_services_point) ? NULL : $reg_services_point;
		$reg_services=$this->input->post('reg_services');				$reg_services  = empty($reg_services) ? NULL : $reg_services;
		$services_package=$this->input->post('reg_services_package');	$services_package  = empty($services_package) ? NULL : $services_package;
		$services_date=$this->input->post('services_date');				$services_date  = empty($services_date) ? NULL : $services_date;
		
		$reg_data_table_registration_for_services=array(
						'registration_no'=>$this->input->post('reg_no'),
						'services_point_id'=>$reg_services_point,
						'services_id'=>$reg_services,
						'services_package_id'=>$services_package,
						'services_date'=>$services_date,
						'services_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
		
		$success_or_fail=$this->registration_model->update_services_by_reg_for_service_id($reg_data_table_registration_for_services,$this->input->post('reg_for_service_id'));
					
		if($success_or_fail)
		echo "Successfull";				
		else
		echo "Unsuccessfull";
					
		//$this->load->view('registration/view_create_registration', isset($data) ? $data : NULL);
		
	}
	

	/**** Ajax function *****/
	function set_registration_status($reg_no,$status)
	{
		return $this->registration_model->set_registration_status($reg_no,$status);
	}
	
	/**** Ajax function *****/
	function load_servicespoint($siteid)
	{		
		$data['services_point']=$this->ref_site_model->get_all_services_point_by_id($siteid);
		
		echo '<option value="">'.lang('settings_select').'</option>';
		foreach ($data['services_point'] as $services_point1) : 
		?>
            <option value="<?php echo $services_point1->site_id; ?>">
				<?php echo $this->session->userdata('site_lang')=='bangla'? $services_point1->site_name_bn:$services_point1->site_name; ?>
            </option>
		<?php endforeach; ?> 
		
	<?php	
	}
	
	/**** Ajax function *****/
	function load_services_pacakge($services_id)
	{		
		$data['services_point']=$this->ref_services_model->get_all_services_package_by_id($services_id);
		
		echo '<option value="">'.lang('settings_select').'</option>';
		foreach ($data['services_point'] as $services_package1) : 
		?>
            <option value="<?php echo $services_package1->package_id; ?>">
				<?php echo $this->session->userdata('site_lang')=='bangla'? $services_package1->package_name_bn:$services_package1->package_name; ?>
            </option>
		<?php endforeach; ?> 
		
	<?php	
	}
	
	/**** Ajax function *****/
	function get_all_child_location()
	{
	$dvid=$this->input->post('dvid');				$dvid  = empty($dvid) ? NULL : $dvid;
	$dtid=$this->input->post('dtid');				$dtid  = empty($dtid) ? NULL : $dtid;
	$upid=$this->input->post('upid');				$upid  = empty($upid) ? NULL : $upid;
	$unid=$this->input->post('unid');				$unid  = empty($unid) ? NULL : $unid;
	$maid=$this->input->post('maid');				$maid  = empty($maid) ? NULL : $maid;
	$ltype=$this->input->post('ltype');				$ltype  = empty($ltype) ? NULL : $ltype;
	
	$this->ref_location_model->get_child_location($dvid,$dtid,$upid,$unid,$maid,$ltype);				
	}
	
	/* add code after februaru 11 2015 */
	
	public function new_family_registration()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('create_family'))
			{
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('reg_site', 'Site', 'required');
				$this->form_validation->set_rules('reg_services_point', 'Services point', 'required');
				$this->form_validation->set_rules('household_name', 'Household Name', 'required');
										
				
				$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');	
				$data['services'] = $this->ref_services_model->get_all_services();
				if ($this->form_validation->run() == FALSE)
				{
					
				$data['title'] = lang('menu_family_registration');								
				$this->load->view('registration/view_new_family_registration', isset($data) ? $data : NULL);
				}
				else
				{
				$data['title'] = lang('menu_family_registration');								
				$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');	
				$data['services'] = $this->ref_services_model->get_all_services();
				
				$family_data=array(
						'site_id'=>$this->input->post('reg_site'),
						'sp_id'=>$this->input->post('reg_services_point'),
						'household_name'=>$this->input->post('household_name'),
						'apartment_name'=>$this->input->post('apartment_name'),
						'apartment_number'=>$this->input->post('apartment_number'),
						'primary_contact_person'=>$this->input->post('primary_contact_person'),
						'note'=>$this->input->post('family_note'),						
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
				$family_id=$this->general_model->save_into_table_and_return_insert_id('gramcar_family', $family_data);				
								
				if($this->input->post('reg_services_package'))
				{				
				$family_health_package=array(
						'family_id'=>$family_id,
						'services_id'=>$this->input->post('reg_services'),
						'package_id'=>$this->input->post('reg_services_package'),												
						'create_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'create_date'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);	
				$this->general_model->save_into_table('urban_family_health_package', $family_health_package);
				}				
					
				if($family_id)
					$data['success_msg']="Saved successfully";
				else
					$data['error_msg']="Save unsuccessfull";	
				
				//echo $this->config->item("phc_api_base_url");
				
				$phc_site_id_array=$this->general_model->get_all_table_info_by_id('gramcar_phc_site_map', 'gramcar_site', $this->input->post('reg_site'));
				$phc_site_id=$phc_site_id_array->phc_site;								
				
				$mydata = array("EHEALTH02301120150301",$phc_site_id, $this->input->post('household_name'), $this->input->post('primary_contact_person'), $this->input->post('family_note'));
				

				$familydata = base64_encode(serialize($mydata));  // Encoding
				$apisaid =file_get_contents($this->config->item("phc_api_base_url").'api_family_insert/'.$familydata);
				$data['success_msg']="Saved successfully, ".$apisaid;
				
				$this->load->view('registration/view_new_family_registration', isset($data) ? $data : NULL);
				}
			}
			else
			{
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}	
	}
	
	
	public function edit_family_registration($family_id)
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('edit_family'))
			{												
																							
					$this->load->helper(array('form', 'url'));
					$this->load->library('form_validation');
					
					$this->form_validation->set_rules('reg_site', 'Site', 'required');
					$this->form_validation->set_rules('reg_services_point', 'Services point', 'required');
					$this->form_validation->set_rules('household_name', 'Household Name', 'required');										
					
					if ($this->form_validation->run() == FALSE)
					{
					$data['title'] = lang('action_edit')." ".lang('family');
					$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');
					$data['services'] = $this->ref_services_model->get_all_services();
					$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $family_id);
					$data['family_package_info']=$this->general_model->get_all_table_info_by_id('urban_family_health_package', 'family_id', $family_id);
					$this->load->view('registration/view_edit_single_family', isset($data) ? $data : NULL);
					}
					else
					{
					$data['title'] = lang('action_edit')." ".lang('family');
	
					$table_data=array(						
							'site_id'=>$this->input->post('reg_site'),
							'sp_id'=>$this->input->post('reg_services_point'),
							'household_name'=>$this->input->post('household_name'),
							'apartment_name'=>$this->input->post('apartment_name'),
							'apartment_number'=>$this->input->post('apartment_number'),
							'primary_contact_person'=>$this->input->post('primary_contact_person'),
							'note'=>$this->input->post('family_note'),	
							'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())					
							);
				
					$success_or_fail=$this->general_model->update_table('gramcar_family', $table_data, 'family_id', $family_id);													
					
					if($this->input->post('reg_services_package'))
					{				
					$family_health_package=array(
							'services_id'=>$this->input->post('reg_services'),
							'package_id'=>$this->input->post('reg_services_package'),												
							'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
							'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())					
							);	
					$this->general_model->update_table('urban_family_health_package', $family_health_package, 'family_id', $family_id);
					}
					
					if($success_or_fail)
						$data['success_msg']="Saved successfully";
					else
						$data['error_msg']="Save unsuccessfull";					
						
					$data['title'] = lang('action_edit')." ".lang('menu_family_list');
					$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');	
					$data['services'] = $this->ref_services_model->get_all_services();
					$data['family_info']=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $family_id);	
					$data['family_package_info']=$this->general_model->get_all_table_info_by_id('urban_family_health_package', 'family_id', $family_id);
					$this->load->view('registration/view_edit_single_family', isset($data) ? $data : NULL);
								
					}
										
			}
			else
			{
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}		
	}
	
	
	public function family_list()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('view_family'))
			{
			
			$this->load->helper("url");	
			$data['title'] = lang('action_view')." ".lang('menu_family_list');	
						
			$this->load->library('pagination');
						
 		  	$searchterm='SELECT * FROM gramcar_family ORDER BY create_date DESC';
	   
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "registration/registration/family_list/";
			$config["total_rows"] = $this->general_model->total_count_query_string($searchterm); 
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config['num_links'] = 3;
			
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
			$config['full_tag_close'] = '</ul></nav><!--pagination-->';
			
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
			
			$this->pagination->initialize($config);
 
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_family'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);					
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');				
			$this->load->view('registration/view_family_list', isset($data) ? $data : NULL);
				
			}
			else
			{
			redirect('./dashboard');  // if not permitted "create_project_site" redirect to home page
			}		
		
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	
	public function family_list_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_family'))
			{
				
				
				// assign posted valued
				$data['family_title']    		= $this->input->post('family_title');
				$data['primary_contact_person'] = $this->input->post('primary_contact_person');
						
				
				if($this->input->post("search_submit"))
				{				
				$query_string='SELECT * FROM gramcar_family Where family_id > 0';
				
				//$query_string='SELECT * FROM eh_family WHERE family_id >0'	;		   						
			
				if($this->input->post("family_title"))	
				{
					$household_name =$this->input->post("family_title"); 
					$query_string=$query_string." AND (household_name Like '%$household_name%')";
				}				
				
				if($this->input->post("primary_contact_person"))	
				{
					$primary_contact_person=$this->input->post("primary_contact_person");	
					$query_string=$query_string." AND(primary_contact_person = $primary_contact_person)";
				}
				
				if($this->input->post("reg_site"))	
				{
					$site_id  =$this->input->post("reg_site"); 
					$query_string=$query_string." AND (site_id  = $site_id)";
				}	
				
				if($this->input->post("reg_services_point"))	
				{
					$reg_services_point  =$this->input->post("reg_services_point"); 
					$query_string=$query_string." AND (sp_id  = $reg_services_point)";
				}
				
				if($this->input->post("apartment_name"))	
				{
					$apartment_name  =$this->input->post("apartment_name"); 
					$query_string=$query_string." AND (apartment_name  Like '%$apartment_name%')";
				}
				
				if($this->input->post("apartment_number"))	
				{
					$apartment_number  =$this->input->post("apartment_number"); 
					$query_string=$query_string." AND (apartment_number  Like '%$apartment_number%')";
				}
				
				$query_string=$query_string." ORDER BY create_date DESC";													
				
				$searchterm = $this->general_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				
				//echo $searchterm;
				
				$data['title'] = lang('action_view')." ".lang('menu_family_list');									
				
				$this->load->helper("url");
				$this->load->library('pagination');
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "registration/registration/family_list_search/";
				$config["total_rows"] = $this->general_model->total_count_query_string($searchterm);
				$config["per_page"] = $this->config->item("pagination_perpage");
				$data['site'] = $this->general_model->get_all_table_info_by_id_asc_desc('gramcar_site', 'rural_or_urban', 2, 'site_name', 'ASC');
				$config['num_links'] = 3;
				
				$config["uri_segment"] = 4;
				$config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
				$config['full_tag_close'] = '</ul></nav><!--pagination-->';
				
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
				
				$this->pagination->initialize($config);
				
				$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
				$data['all_family'] = $this->general_model->get_all_result_by_limit_querystring($searchterm,$config["per_page"], $page);	
				$data["links"] = $this->pagination->create_links();
				$data["page"]=$page;								
				$this->load->view('registration/view_family_list', isset($data) ? $data : NULL);						
			
			}
			else
			{
			redirect('./dashboard');  // if not permitted "edit_project_site" redirect to home page
			}			
		
		}
		else
		{
			redirect('account/sign_in');
		}
	}
	
	public function username_check($username)
	{				
		$is_exist=$this->general_model->is_exist_in_a_table('a3m_account','username',$username);
		
		if ($is_exist > 0)
		{
			$this->form_validation->set_message('username_check', ' The username '.$username .' is already exits');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function email_check($email)
	{				
		$is_exist=$this->general_model->is_exist_in_a_table('a3m_account','email',$email);
		
		if ($is_exist > 0)
		{
			$this->form_validation->set_message('email_check', ' The email '.$email .' is already exits');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>