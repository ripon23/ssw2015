<?php
class Emergency extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','emergency_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
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
	
	public function charge_calculator()
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
						
				$data['title'] = 'GramCar Emergency: Charge Calculator';
				$data['charge_calculator_info'] = $this->emergency_model->get_charge_calculator_info();
				$this->load->view('emergency/view_charge_calculator', isset($data) ? $data : NULL);							
			
		}
		else
		{
		redirect('account/sign_in');
		}	
	}
	
	
	
	public function index()  
	{
		maintain_ssl();

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_emergency_services'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar: Emergency Services List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(5);	// 5= Internet Service/ Learning
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "emergency/emergency/emergency_services_list/";
			$config["total_rows"] = $this->emergency_model->get_all_emergency_registration_count();
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
			$data['all_emergency'] = $this->emergency_model->get_all_emergency_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('emergency/view_emergency_list', isset($data) ? $data : NULL);		
					
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
	
	public function emergency_services_list()  
	{

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_emergency_services'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar: Emergency Services List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(5);	// 5= Internet Service/ Learning
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "emergency/emergency/emergency_services_list/";
			$config["total_rows"] = $this->emergency_model->get_all_emergency_registration_count();
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
			$data['all_emergency'] = $this->emergency_model->get_all_emergency_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('emergency/view_emergency_list', isset($data) ? $data : NULL);		
					
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
	
	
	
	public function emergency_services_list_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_emergency_services'))
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
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) AND (gramcar_registration_for_services.services_id=6)";
			
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
				$data['title'] = 'GramCar Emergency Services';
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(6);	// 5= internet and information services
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "emergency/emergency/emergency_services_list_search/";
			$config["total_rows"] = $this->emergency_model->all_emergency_count_query_string($searchterm);
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
			$data['all_emergency'] = $this->emergency_model->get_all_emergency_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;									
			$this->load->view('emergency/view_emergency_list', isset($data) ? $data : NULL);										
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
	
	public function charge_calculator_setting()
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('charge_calculator_setting'))
			{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('per_km_price', 'Per Km price', 'required');
			$this->form_validation->set_rules('per_min_price', 'Per minute price','required|integer');
			$this->form_validation->set_rules('minimum_charge', 'Minimum charge','required');
			if ($this->form_validation->run() == FALSE)
				{			
				$data['title'] = 'GramCar Emergency: Charge Calculator';
				$data['charge_calculator_info'] = $this->emergency_model->get_charge_calculator_info();
				$this->load->view('emergency/view_charge_calculator_setting', isset($data) ? $data : NULL);
				}
				else
				{
				$setting_data=array(
						'per_km_price'=>$this->input->post('per_km_price'),
						'per_min_price'=>$this->input->post('per_min_price'),
						'minimum_price'=>$this->input->post('minimum_charge'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),	
						'update_time '=>mdate('%Y-%m-%d %H:%i:%s', now())											
						);	
				$success_or_fail=$this->emergency_model->update_charge_calculator_setting($setting_data);								
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull";
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";								
				
							
				$data['title'] = 'GramCar Emergency: Charge Calculator';
				$data['charge_calculator_info'] = $this->emergency_model->get_charge_calculator_info();
				$this->load->view('emergency/view_charge_calculator_setting', isset($data) ? $data : NULL);
				}
				
			}
			else
			{
			redirect('');  // if not permitted "charge_calculator_setting" redirect to home page
			}
		}
		else
		{
		redirect('account/sign_in');
		}	
	}
	
	public function add_emergency($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_emergency_services'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('total_travel_distance', 'Total travel distance', 'required');
			$this->form_validation->set_rules('total_travel_time', 'Total travel','required|integer');
			$this->form_validation->set_rules('total_bill', 'Total bill','required');
			$this->form_validation->set_rules('note', 'note','');
				
				$note=$this->input->post('note');					$note  = empty($note) ? NULL : $note;
				
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar emgegency services';
				$data['registration_info'] = $this->emergency_model->get_registration_info_by_reg_services_id($reg_services_id);				
				$this->load->view('emergency/view_add_emergency', isset($data) ? $data : NULL);				
				}
				else
				{								
				$emergency_data=array(
						'reg_for_service_id'=>$reg_services_id,
						'registration_no'=>$this->input->post('registration_no'),
						'services_receive_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'total_travel_distance'=>$this->input->post('total_travel_distance'),
						'total_travel_time'=>$this->input->post('total_travel_time'),
						'total_bill'=>$this->input->post('total_bill'),
						'note'=>$note,
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					
				$success_or_fail=$this->emergency_model->save_emergency($emergency_data);								
				
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
				
				$this->emergency_model->update_emergency_services_status($update_services_status,$reg_services_id);
				
				$data['title'] = 'GramCar emergency services';
				$data['registration_info'] = $this->emergency_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('emergency/view_add_emergency', isset($data) ? $data : NULL);								
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
	
	public function view_single_emergency($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_emergency_services'))
			{				
						
				$data['title'] = 'GramCar emergency services';
				$data['registration_info'] = $this->emergency_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['emergency_info'] = $this->emergency_model->get_emergency_info_by_reg_services_id($reg_services_id);
				$this->load->view('emergency/view_single_emergency', isset($data) ? $data : NULL);				
			
			}
			else
			{
			redirect('');  // if not permitted "view_emergency_services" redirect to home page
			}			
		}
		else
		{
		redirect('account/sign_in');
		}
	
	
	}
	
	public function edit_single_emergency($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('edit_information_services'))
			{				
						
								
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('total_travel_distance', 'Total travel distance', 'required');
			$this->form_validation->set_rules('total_travel_time', 'Total travel','required|integer');
			$this->form_validation->set_rules('total_bill', 'Total bill','required');
			$this->form_validation->set_rules('note', 'note','');
				
				$note=$this->input->post('note');					$note  = empty($note) ? NULL : $note;
				
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar emgegency services';
				$data['registration_info'] = $this->emergency_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['emergency_info'] = $this->emergency_model->get_emergency_info_by_reg_services_id($reg_services_id);
				$this->load->view('emergency/view_edit_single_emergency', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->emergency_model->get_registration_info_by_reg_services_id($reg_services_id);																	
				
				$emergency_data=array(
						'reg_for_service_id'=>$reg_services_id,
						'registration_no'=>$this->input->post('registration_no'),
						'services_receive_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'total_travel_distance'=>$this->input->post('total_travel_distance'),
						'total_travel_time'=>$this->input->post('total_travel_time'),
						'total_bill'=>$this->input->post('total_bill'),
						'note'=>$note,
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					
				$success_or_fail=$this->emergency_model->update_emergency($emergency_data,$reg_services_id);								
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull for ".$this->input->post('registration_no');
				else
				$data['success_msg']="Update Unsuccessfull! Please try again";							
												
				$data['title'] = 'GramCar emergency services';
				$data['emergency_info'] = $this->emergency_model->get_emergency_info_by_reg_services_id($reg_services_id);
				$this->load->view('emergency/view_edit_single_emergency', isset($data) ? $data : NULL);							
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