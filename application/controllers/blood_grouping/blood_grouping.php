<?php
class Blood_grouping extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','blood_grouping_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
		$GLOBALS["overall_health_status"]=0;
		
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
			if($this->authorization->is_permitted('view_blood_grouping'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar Generel Health checkup List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(3);	// 2= General health checkup
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "blood_grouping/blood_grouping/index/";
			$config["total_rows"] = $this->blood_grouping_model->get_all_blood_grouping_registration_count();
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
			$data['all_blood_grouping'] = $this->blood_grouping_model->get_all_blood_grouping_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('blood_grouping/view_blood_grouping', isset($data) ? $data : NULL);		
					
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
	
	
	public function search_blood_grouping_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_blood_grouping'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
				$data['sservices_point']     	= $this->input->post('sservices_point');
				$data['spackage']     			= $this->input->post('spackage');
				$data['sservices_status']     	= $this->input->post('sservices_status');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_registration_for_services";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) AND (gramcar_registration_for_services.services_id=3)";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_registration_for_services.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("sservices_point"))	
				{
					$sservices_point=$this->input->post("sservices_point");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_point_id = $sservices_point)";
				}
				
				if($this->input->post("spackage"))	
				{
					$spackage=$this->input->post("spackage");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_package_id = $spackage)";				
				}								
				
				if($this->input->post("sservices_status"))	
				{
					if($this->input->post("sservices_status")=="zero")
					$sservices_status=0;	
					else
					$sservices_status=$this->input->post("sservices_status");
					
					$query_string=$query_string." AND(gramcar_registration_for_services.services_status = $sservices_status)";				
				}								
				
				
				if($this->input->post("sdate1"))
				{
				$sdate1=$this->input->post("sdate1"); $sdate2=$this->input->post("sdate2");
	
				if(($sdate1!='')&& ($sdate2==''))
				$sdate2=$sdate1;
				
				if(strlen($sdate1)<12) 
				$sdate1=$sdate1." 00:00:00";	
				
				if(strlen($sdate2)<12) 
				$sdate2=$sdate2." 23:59:59";
			
				$query_string= $query_string."  AND services_date BETWEEN '".$sdate1."' AND '".$sdate2."'";
				}
				
				
				$query_string=$query_string." ORDER BY gramcar_registration_for_services.services_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar General Health Check-up List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(3);	// 2= General health checkup	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "blood_grouping/blood_grouping/search_blood_grouping_list/";
			$config["total_rows"] = $this->blood_grouping_model->all_blood_grouping_count_query_string($searchterm);
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
			$data['all_blood_grouping'] = $this->blood_grouping_model->get_all_blood_grouping_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('blood_grouping/view_blood_grouping', isset($data) ? $data : NULL);
				
			
			
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
	
	public function date_of_birth_check($dob)
	{

      if ( preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $dob) ) {
      list($year , $month , $day) = explode('-',$dob);
      return checkdate($month , $day , $year);
   		} else {
      	$this->form_validation->set_message('date_of_birth_check', ' The date '.$dob.' is not valid. Please enter YYYY-MM-DD format');
       	return FALSE;
   		}
		
	}
	
	public function add_blood_grouping($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_blood_grouping'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('dob', 'Date of birth', 'regex_match[/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/]');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'required|callback_date_of_birth_check');
			$this->form_validation->set_rules('phone_country_code', 'Phone (Country Code)','required|min_length[3]|max_length[4]');
			$this->form_validation->set_rules('phone_part1', 'Phone (Operator)','required|is_natural|min_length[5]|max_length[5]');
			$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','required|is_natural|min_length[6]|max_length[6]');
			$this->form_validation->set_rules('blood_group', 'Blood group','required');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Registration';
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('blood_grouping/view_add_blood_grouping', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);	
				
				
				
				$blood_group=$this->input->post('blood_group');	
				
				$dob=$this->input->post('dob');
				$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
				$phone  = strlen($phone)<14 ? NULL : $phone;								
				
				$blood_grouping_data=array(
						'reg_for_service_id'=>$reg_services_id,
						'registration_no'=>$this->input->post('registration_no'),
						'checkup_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'blood_group'=>$this->input->post('blood_group'),						
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'upload_status'=>0
						);
					
				$success_or_fail=$this->blood_grouping_model->save_blood_grouping($blood_grouping_data);								
				
				if($success_or_fail)
				$data['success_msg']="Save Successfull for ".$this->input->post('registration_no');
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";								
				
				/******************** Update the status of the services. Mark it as Taken *******************************/	
				$update_services_status=array(
						'services_status'=>2,						
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);
				
				$this->blood_grouping_model->update_blood_grouping_services_status($update_services_status,$reg_services_id); 
				
				/******************** Update the registration info with Date of birth and phone **************************/	
				$reg_data_table_registration=array(
						'phone'=>$phone,						   
						'dob'=>$dob,						
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
				
				$success_or_fail=$this->registration_model->update_registration($reg_data_table_registration,$this->input->post('registration_no'));
				
				/******************************* Blood grouping API *************************************************/
				//$fullname=$data['registration_info']->first_name." ".$data['registration_info']->middle_name." ".$data['registration_info']->last_name;
				 	 	 	 	 	
				$sex= $data['registration_info']->gender=="M" ? "Male" : "Female";
				
				$mydata = array("GRAMCAR-BLOODBANK-02301120140710",$this->input->post('registration_no'),$data['registration_info']->first_name,$data['registration_info']->middle_name,$data['registration_info']->last_name, $phone, $this->input->post('blood_group'), $dob,$sex,$data['registration_info']->division_id,$data['registration_info']->district_id,$data['registration_info']->upazila_id,$data['registration_info']->union_id,$data['registration_info']->mouza_id,$data['registration_info']->village_id );
				

				$bloodgroupingdata = base64_encode(serialize($mydata));  // Encoding
				$apisaid =file_get_contents('http://www.gramweb.net/projects/vbb/api/vbb_api.php?bloodgroupingdata='.$bloodgroupingdata);
				//$apisaid =file_get_contents('http://localhost/vbb/api/vbb_api.php?bloodgroupingdata='.$bloodgroupingdata);
				//echo "Return Value=".$apisaid;   //Success or Fail
				
				if(substr_compare($apisaid, "Success", 0, 6) == 0)
				{
				$upload_status=1;
				$bloog_grouping_status=array(						
						'upload_status'=>$upload_status
						);
				$success_or_fail=$this->blood_grouping_model->update_blood_grouping($bloog_grouping_status,$reg_services_id);												 				//echo $success_or_fail;				
				}
				
				if(substr_compare($apisaid, "Fail", 0, 3) == 0)
				{
				$upload_status=2;
				$health_checkup_data=array(						
						'upload_status'=>$upload_status
						);
				$this->blood_grouping_model->update_blood_grouping($bloog_grouping_status,$reg_services_id);
				}
				
				
				
				/******************************* Blood grouping API END *********************************************/
				
				$data['title'] = 'GramCar Blood Grouping';
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('blood_grouping/view_add_blood_grouping', isset($data) ? $data : NULL);								
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
	
	public function view_single_blood_grouping($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_blood_grouping'))
			{				
						
				$data['title'] = 'GramCar Blood Grouping';
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['blood_grouping_info'] = $this->blood_grouping_model->get_blood_grouping_info_by_reg_services_id($reg_services_id);
				$this->load->view('blood_grouping/view_single_blood_grouping', isset($data) ? $data : NULL);
				
			
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
	
	public function edit_single_blood_grouping($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('edit_blood_grouping'))
			{				
						
								
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('dob', 'Date of birth', 'regex_match[/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/]');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'required|callback_date_of_birth_check');
			$this->form_validation->set_rules('phone_country_code', 'Phone (Country Code)','required|min_length[3]|max_length[4]');
			$this->form_validation->set_rules('phone_part1', 'Phone (Operator)','required|is_natural|min_length[5]|max_length[5]');
			$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','required|is_natural|min_length[6]|max_length[6]');
			$this->form_validation->set_rules('blood_group', 'Blood group','required');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Registration';
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['blood_grouping_info'] = $this->blood_grouping_model->get_blood_grouping_info_by_reg_services_id($reg_services_id);
				$this->load->view('blood_grouping/view_edit_single_blood_grouping', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);									
				
				$blood_group=$this->input->post('blood_group');	
				
				$dob=$this->input->post('dob');
				$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
				$phone  = strlen($phone)<14 ? NULL : $phone;								
				
				$blood_grouping_data=array(
						'blood_group'=>$this->input->post('blood_group'),						
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'upload_status'=>0
						);
					
				$success_or_fail=$this->blood_grouping_model->update_blood_grouping($blood_grouping_data,$reg_services_id);								
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull for ".$this->input->post('registration_no');
				else
				$data['success_msg']="Update Unsuccessfull! Please try again";							
								
				
				/******************** Update the registration info with Date of birth and phone **************************/	
				$reg_data_table_registration=array(
						'phone'=>$phone,						   
						'dob'=>$dob,						
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
				
				$success_or_fail=$this->registration_model->update_registration($reg_data_table_registration,$this->input->post('registration_no'));
				
				/******************************* Blood grouping API *************************************************/
				
				
				/******************************* Blood grouping API END *********************************************/
				
				$data['title'] = 'GramCar General Health Checkup';
				$data['registration_info'] = $this->blood_grouping_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['blood_grouping_info'] = $this->blood_grouping_model->get_blood_grouping_info_by_reg_services_id($reg_services_id);
				$this->load->view('blood_grouping/view_edit_single_blood_grouping', isset($data) ? $data : NULL);							
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
	
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>