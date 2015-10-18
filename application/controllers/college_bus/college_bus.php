<?php
class College_bus extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','college_bus_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
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
			if($this->authorization->is_permitted('view_college_bus_services'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar college bus services';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(1);	// 1= college_bus
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "college_bus/college_bus/index/";
			$config["total_rows"] = $this->college_bus_model->get_all_college_bus_registration_count();
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
			$data['all_college_bus'] = $this->college_bus_model->get_all_college_bus_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('college_bus/view_college_bus', isset($data) ? $data : NULL);		
					
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
	
	
	public function search_college_bus_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_information_services'))
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
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) AND (gramcar_registration_for_services.services_id=1)";
			
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
				$data['title'] = 'GramCar college_bus';
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(1);	// 5= internet and information services
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "college_bus/college_bus/search_college_bus_list/";
			$config["total_rows"] = $this->college_bus_model->all_college_bus_count_query_string($searchterm);
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
			$data['all_college_bus'] = $this->college_bus_model->get_all_college_bus_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('college_bus/view_college_bus', isset($data) ? $data : NULL);
				
			
			
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
	
	
	public function add_college_bus($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_college_bus_services'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('services_receive_date', 'Services receive date', 'required');
			$this->form_validation->set_rules('note', 'Note');
			$this->form_validation->set_rules('phone_country_code', 'Phone (Country Code)','required|min_length[3]|max_length[4]');
			$this->form_validation->set_rules('phone_part1', 'Phone (Operator)','required|is_natural|min_length[5]|max_length[5]');
			$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','required|is_natural|min_length[6]|max_length[6]');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar college_bus';
				$data['registration_info'] = $this->college_bus_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('college_bus/view_add_college_bus', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->college_bus_model->get_registration_info_by_reg_services_id($reg_services_id);	
				
								
				$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
				$phone  = strlen($phone)<14 ? NULL : $phone;								
				
				if($this->college_bus_model->reg_services_id_already_exits($reg_services_id))
				{
				$college_bus_data=array(
						'reg_for_service_id'=>$reg_services_id,
						'registration_no'=>$this->input->post('registration_no'),
						'services_receive_date'=>$this->input->post('services_receive_date'),						
						'note'=>$this->input->post('note'),
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					
				$success_or_fail=$this->college_bus_model->save_college_bus($college_bus_data);								
				
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
				
				$this->college_bus_model->update_college_bus_services_status($update_services_status,$reg_services_id); 
				
				/******************** Update the registration info with Date of birth and phone **************************/	
				$reg_data_table_registration=array(
						'phone'=>$phone,						   						
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
				
				$success_or_fail=$this->registration_model->update_registration($reg_data_table_registration,$this->input->post('registration_no'));
				}
				else
				{
				$data['error_msg']="Save Unsuccessfull! Duplicate entry";								
				}
				/******************************* Blood grouping API *************************************************/
				
				
				/******************************* Blood grouping API END *********************************************/
				
				$data['title'] = 'GramCar college_bus';
				$data['registration_info'] = $this->college_bus_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('college_bus/view_add_college_bus', isset($data) ? $data : NULL);								
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
	
	public function view_single_college_bus($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_information_services'))
			{				
						
				$data['title'] = 'GramCar college_bus';
				$data['registration_info'] = $this->college_bus_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['college_bus_info'] = $this->college_bus_model->get_college_bus_info_by_reg_services_id($reg_services_id);
				$this->load->view('college_bus/view_single_college_bus', isset($data) ? $data : NULL);
				
			
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
	
	public function edit_single_college_bus($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('edit_college_bus_services'))
			{				
						
								
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('services_receive_date', 'Services receive date', 'required');
			$this->form_validation->set_rules('note', 'Note');
			$this->form_validation->set_rules('phone_country_code', 'Phone (Country Code)','required|min_length[3]|max_length[4]');
			$this->form_validation->set_rules('phone_part1', 'Phone (Operator)','required|is_natural|min_length[5]|max_length[5]');
			$this->form_validation->set_rules('phone_part2', 'Phone last 6 digit','required|is_natural|min_length[6]|max_length[6]');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar college_bus';
				$data['registration_info'] = $this->college_bus_model->get_registration_info_by_reg_services_id($reg_services_id);

				$data['college_bus_info'] = $this->college_bus_model->get_college_bus_info_by_reg_services_id($reg_services_id);
				$this->load->view('college_bus/view_edit_single_college_bus', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->college_bus_model->get_registration_info_by_reg_services_id($reg_services_id);		
								
				$phone=$this->input->post('phone_country_code').$this->input->post('phone_part1').$this->input->post('phone_part2');	
				$phone  = strlen($phone)<14 ? NULL : $phone;							
				
				$college_bus_data=array(						
						'services_receive_date'=>$this->input->post('services_receive_date'),						
						'note'=>$this->input->post('note'),
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					
				$success_or_fail=$this->college_bus_model->update_college_bus($college_bus_data,$reg_services_id);								
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull for ".$this->input->post('registration_no');
				else
				$data['success_msg']="Update Unsuccessfull! Please try again";							
								
				
				/******************** Update the registration info with Date of birth and phone **************************/	
				$reg_data_table_registration=array(
						'phone'=>$phone,						   					
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
				
				$success_or_fail=$this->registration_model->update_registration($reg_data_table_registration,$this->input->post('registration_no'));
				
				
				$data['title'] = 'GramCar college_bus';
				$data['college_bus_info'] = $this->college_bus_model->get_college_bus_info_by_reg_services_id($reg_services_id);
				$this->load->view('college_bus/view_edit_single_college_bus', isset($data) ? $data : NULL);							
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