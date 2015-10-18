<?php
class Payment extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','payment_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
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
			if($this->authorization->is_permitted('view_payment'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar Payment';	
			
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->ref_services_model->get_all_services();			
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "payment/payment/index/";
			$config["total_rows"] = $this->payment_model->get_all_payment_registration_count();
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
			$data['all_payment'] = $this->payment_model->get_all_payment_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('payment/view_payment', isset($data) ? $data : NULL);		
					
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
	
	
	public function registration_payment()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_payment'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar Registration Payment';	
			
					
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "payment/payment/registration_payment/";
			$config["total_rows"] = $this->payment_model->all_registration_count();
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
			$data['all_payment'] = $this->payment_model->get_all_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('payment/view_registration_payment', isset($data) ? $data : NULL);		
			
			
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
	
	
	public function search_payment_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_payment'))
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
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) ";
			
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
				
				if($this->input->post("reg_services"))	
				{
					$reg_services=$this->input->post("reg_services");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_id = $reg_services)";				
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
			
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->ref_services_model->get_all_services();
						
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "payment/payment/search_payment_list/";
			$config["total_rows"] = $this->payment_model->all_payment_count_query_string($searchterm);
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
			$data['all_payment'] = $this->payment_model->get_all_payment_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('payment/view_payment', isset($data) ? $data : NULL);
				
			
			
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
	
	
	public function search_registration_payment_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_payment'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
				$data['spayment_status']     	= $this->input->post('spayment_status');				
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT gramcar_registration.registration_no,
       gramcar_registration.first_name,
       gramcar_registration.middle_name,
       gramcar_registration.last_name,
       gramcar_registration_payment.received_amount,
       gramcar_registration_payment.free_or_paid
  FROM    gramcar_registration gramcar_registration
       LEFT OUTER JOIN
          gramcar_registration_payment gramcar_registration_payment
       ON (gramcar_registration.registration_no =
              gramcar_registration_payment.registration_no)";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				//$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) ";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_registration.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("spayment_status"))	
				{
					$spayment_status=$this->input->post("spayment_status");	
					$query_string=$query_string." AND(gramcar_registration_payment.free_or_paid = '$spayment_status')";
				}
								
				
				
				$query_string=$query_string." ORDER BY gramcar_registration.update_date ASC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar General Health Check-up List';	
			
			
						
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "payment/payment/search_registration_payment_list/";
			$config["total_rows"] = $this->payment_model->all_payment_count_query_string($searchterm);
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
			$data['all_payment'] = $this->payment_model->get_all_payment_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('payment/view_registration_payment', isset($data) ? $data : NULL);
				
			
			
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
	
	
	
	public function add_payment($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('received_payment'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			//$this->form_validation->set_rules('dob', 'Date of birth', 'regex_match[/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/]');
			$this->form_validation->set_rules('received_amount', 'Received amount', 'required|numeric');
			$this->form_validation->set_rules('note', 'Note');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Payment';
				$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['reg_services_info'] = $this->payment_model->get_reg_services_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_add_payment', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);	
				$data['reg_services_info'] = $this->payment_model->get_reg_services_info_by_reg_services_id($reg_services_id);
				
				$note=$this->input->post('note');	$note  = empty($note) ? NULL : $note;
				
				$payment_data=array(
						'reg_for_service_id'=>$reg_services_id,
						'registration_no'=>$this->input->post('registration_no'),
						'payment_received_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'received_amount'=>$this->input->post('received_amount'),
						'note'=>$note,
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'approved_status'=>0
						);
					
				$success_or_fail=$this->payment_model->save_payment($payment_data);								
				
				if($success_or_fail)
				$data['success_msg']="Save Successfull for ".$this->input->post('registration_no').". &nbsp;&nbsp;Go to <a href=".base_url()."payment/payment>".lang('menu_payment_received')."</a>" ;
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";								
								
				
				$data['title'] = 'GramCar Payment';
				//$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_add_payment', isset($data) ? $data : NULL);								
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
	
	
	public function add_registration_payment($registration_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('received_payment'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			//$this->form_validation->set_rules('dob', 'Date of birth', 'regex_match[/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/]');
			$this->form_validation->set_rules('received_amount', 'Received amount', 'numeric');			
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Registration Payment';
				$data['registration_info'] = $this->registration_model->get_all_registration_info_by_id($registration_id);				
				$this->load->view('payment/view_add_registration_payment', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->registration_model->get_all_registration_info_by_id($registration_id);	
				
				
				
				
				$reg_payment=array(
						'registration_no'=>$registration_id,
						'payment_received_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'received_amount'=>$this->input->post('received_amount'),
						'free_or_paid'=>$this->input->post('payment_status'),
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					
				$success_or_fail=$this->payment_model->save_registration_payment($reg_payment);								
				
				if($success_or_fail)
				$data['success_msg']="Save Successfull for ".$registration_id.". &nbsp;&nbsp;Go to <a href=".base_url()."payment/payment/registration_payment/>".lang('menu_payment_received')."</a>" ;
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";								
								
				
				$data['title'] = 'GramCar Payment';
				//$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_add_registration_payment', isset($data) ? $data : NULL);								
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
	
	public function edit_single_registration_payment($registration_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('received_payment'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			//$this->form_validation->set_rules('dob', 'Date of birth', 'regex_match[/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/]');
			$this->form_validation->set_rules('received_amount', 'Received amount', 'numeric');			
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Registration Payment';
				$data['registration_info'] = $this->registration_model->get_all_registration_info_by_id($registration_id);	
				$data['registration_payment_info'] = $this->payment_model->get_all_registration_payment_info_by_id($registration_id);	
				$this->load->view('payment/view_edit_single_registration_payment', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->registration_model->get_all_registration_info_by_id($registration_id);					
				$data['registration_payment_info'] = $this->payment_model->get_all_registration_payment_info_by_id($registration_id);					
				
				$reg_payment=array(						
						'received_amount'=>$this->input->post('received_amount'),
						'free_or_paid'=>$this->input->post('payment_status'),
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))						
						);
					
				$success_or_fail=$this->payment_model->update_registration_payment($reg_payment,$registration_id);								
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull for ".$registration_id.". &nbsp;&nbsp;Go to <a href=".base_url()."payment/payment/registration_payment/>".lang('menu_payment_received')."</a>" ;
				else
				$data['success_msg']="Update Unsuccessfull! Please try again";								
								
				
				$data['title'] = 'GramCar Registration Payment';
				//$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_edit_single_registration_payment', isset($data) ? $data : NULL);								
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
	
	public function view_single_payment($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_payment'))
			{				
			
				$data['title'] = 'GramCar View Payment';
				$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['reg_services_info'] = $this->payment_model->get_reg_services_info_by_reg_services_id($reg_services_id);
				$data['payment_info'] = $this->payment_model->get_payment_received_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_view_single_payment', isset($data) ? $data : NULL);								
			
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
	
	public function edit_single_payment($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('received_payment'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			//$this->form_validation->set_rules('dob', 'Date of birth', 'regex_match[/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/]');
			$this->form_validation->set_rules('received_amount', 'Received amount', 'required|numeric');
			$this->form_validation->set_rules('note', 'Note');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Payment';
				$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['reg_services_info'] = $this->payment_model->get_reg_services_info_by_reg_services_id($reg_services_id);
				$data['payment_info'] = $this->payment_model->get_payment_received_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_edit_single_payment', isset($data) ? $data : NULL);				
				}
				else
				{
																		
				$note=$this->input->post('note');	$note  = empty($note) ? NULL : $note;
				
				$payment_data=array(						
						'registration_no'=>$this->input->post('registration_no'),
						'payment_received_date'=>$this->input->post('payment_received_date'),
						'received_amount'=>$this->input->post('received_amount'),						
						'note'=>$note,
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'approved_status'=>0
						);
					
				$success_or_fail=$this->payment_model->update_payment($payment_data,$reg_services_id);								
				
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull for ".$this->input->post('registration_no').". &nbsp;&nbsp;Go to <a href=".base_url()."payment/payment>".lang('menu_payment_received')."</a>" ;
				else
				$data['success_msg']="Update Unsuccessfull! Please try again";								
				
				
				$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);	
				$data['reg_services_info'] = $this->payment_model->get_reg_services_info_by_reg_services_id($reg_services_id);
				$data['payment_info'] = $this->payment_model->get_payment_received_info_by_reg_services_id($reg_services_id);	
				$data['title'] = 'GramCar Payment';
				//$data['registration_info'] = $this->payment_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('payment/view_edit_single_payment', isset($data) ? $data : NULL);								
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
	
	public function payment_approval()
	{
	
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('approved_payment'))
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
				$query_string="SELECT gramcar_registration_for_services.*,
       gramcar_payment_received.payment_received_date,
       gramcar_payment_received.received_amount,
       gramcar_payment_received.last_edit_date,
       gramcar_payment_received.edit_user_id,
       gramcar_payment_received.approved_status
  FROM    gramcar_registration_for_services gramcar_registration_for_services
       INNER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id)";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_payment_received.approved_status < 3) ";  // <3 means all
			
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
			
				$query_string= $query_string."  AND payment_received_date BETWEEN '".$sdate1."' AND '".$sdate2."'";
				}
				
				
				$query_string=$query_string." ORDER BY gramcar_payment_received.approved_status ASC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				//$searchterm = $this->session->userdata('searchterm');
				$searchterm = "SELECT gramcar_registration_for_services.*,
       gramcar_payment_received.payment_received_date,
       gramcar_payment_received.received_amount,
       gramcar_payment_received.last_edit_date,
       gramcar_payment_received.edit_user_id,
       gramcar_payment_received.approved_status
  FROM    gramcar_registration_for_services gramcar_registration_for_services
       INNER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id) WHERE (gramcar_payment_received.approved_status < 3) ORDER BY gramcar_payment_received.approved_status ASC";
				}
				$data['title'] = 'GramCar General Health Check-up List';	
			
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->ref_services_model->get_all_services();
						
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "payment/payment/payment_approval/";
			$config["total_rows"] = $this->payment_model->all_payment_count_query_string($searchterm);
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
			$data['all_payment'] = $this->payment_model->get_all_payment_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
									
			$this->load->view('payment/view_payment_approval', isset($data) ? $data : NULL);				
						
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
	
	/***** ajax function ************/
	public function approved_payment($reg_services_id)
	{
		$payment_data=array(						
						'approved_status'=>1,
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);
					
				$success_or_fail=$this->payment_model->approved_unapproved_payment($payment_data,$reg_services_id);								
				
				if($success_or_fail)
				echo "Approved payment for services id ".$reg_services_id;
				else
				echo "Action unsuccessfull for services id ".$reg_services_id." please try again later";
	}
	
	/***** ajax function ************/
	public function unapproved_payment($reg_services_id)
	{
		$payment_data=array(						
						'approved_status'=>2,
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);
					
				$success_or_fail=$this->payment_model->approved_unapproved_payment($payment_data,$reg_services_id);								
				
				if($success_or_fail)
				echo "Unapproved payment for services id ".$reg_services_id;
				else
				echo "Action unsuccessfull for services id ".$reg_services_id." please try again later";
	}
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>