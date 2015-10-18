<?php
class Report extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','social_goods_model','report_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
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
			
			$this->load->helper("url");	
			$data['title'] = 'GramCar report';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(5);	// 5= Internet Service/ Learning
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "report/report/report_home/";
			$config["total_rows"] = $this->report_model->get_all_reg_services_registration_count();
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
			$data['all_reg_services'] = $this->report_model->get_all_reg_services_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('report/view_report_home', isset($data) ? $data : NULL);										
			
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	
	public function report_home()  
	{

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			$this->load->helper("url");	
			$data['title'] = 'GramCar report';							
			
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->ref_services_model->get_all_services();	
			
			$data['number_of_services'] = $this->report_model->get_all_reg_services_registration_count();
			$data['total_amount_received'] = $this->report_model->get_total_amount_received_without_searchterm();
			
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "report/report/report_home/";
			$config["total_rows"] = $this->report_model->get_all_reg_services_registration_count();
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
			$data['all_reg_services'] = $this->report_model->get_all_reg_services_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$sql="SELECT gramcar_registration_for_services.reg_for_service_id,
       gramcar_registration_for_services.registration_no,
       gramcar_registration.first_name,
       gramcar_registration.middle_name,
       gramcar_registration.last_name,
       gramcar_registration_for_services.services_point_id,
       gramcar_site.site_name,
       gramcar_registration_for_services.services_id,
       gramcar_services.services_name,
       gramcar_registration_for_services.services_package_id,
       gramcar_services_package.package_name,
       gramcar_registration_for_services.services_date,
       gramcar_registration_for_services.services_status,
       gramcar_payment_received.payment_received_date,
       gramcar_payment_received.received_amount,
       gramcar_payment_received.approved_status
  FROM    (   (   (   (   gramcar_registration_for_services gramcar_registration_for_services
                       INNER JOIN
                          gramcar_services gramcar_services
                       ON (gramcar_registration_for_services.services_id =
                              gramcar_services.services_id))
                   INNER JOIN
                      gramcar_registration gramcar_registration
                   ON (gramcar_registration_for_services.registration_no =
                          gramcar_registration.registration_no))
               INNER JOIN
                  gramcar_site gramcar_site
               ON (gramcar_registration_for_services.services_point_id =
                      gramcar_site.site_id))
           LEFT OUTER JOIN
              gramcar_services_package gramcar_services_package
           ON (gramcar_registration_for_services.services_package_id =
                  gramcar_services_package.package_id))
       LEFT OUTER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id) ORDER BY gramcar_registration_for_services.services_date Desc";
			
			$searchterm = $this->registration_model->searchterm_handler($sql);
			
			$this->load->view('report/view_report_home', isset($data) ? $data : NULL);										
			
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
	public function export_to_excel() 
	{	
	//$data['query_string']=$this->session->userdata('searchterm');
	$data['all_reg_services'] = $this->report_model->get_all_report_querystring_for_excel($this->session->userdata('searchterm'));
	$this->load->view('report/view_export', isset($data) ? $data : NULL);	
	}

	
	
	
	
	
	public function report_home_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
				$data['sreg_site'] 		    	= $this->input->post('reg_site');
				$data['sservices_point']     	= $this->input->post('sservices_point');
				$data['sreg_services'] 			= $this->input->post('reg_services');
				$data['spackage']     			= $this->input->post('spackage');
				$data['sservices_status']     	= $this->input->post('sservices_status');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT gramcar_registration_for_services.reg_for_service_id,
       gramcar_registration_for_services.registration_no,
       gramcar_registration.first_name,
       gramcar_registration.middle_name,
       gramcar_registration.last_name,
       gramcar_registration_for_services.services_point_id,
       gramcar_site.site_name,
       gramcar_registration_for_services.services_id,
       gramcar_services.services_name,
       gramcar_registration_for_services.services_package_id,
       gramcar_services_package.package_name,
       gramcar_registration_for_services.services_date,
       gramcar_registration_for_services.services_status,
       gramcar_payment_received.payment_received_date,
       gramcar_payment_received.received_amount,
       gramcar_payment_received.approved_status
  FROM    (   (   (   (   gramcar_registration_for_services gramcar_registration_for_services
                       INNER JOIN
                          gramcar_services gramcar_services
                       ON (gramcar_registration_for_services.services_id =
                              gramcar_services.services_id))
                   INNER JOIN
                      gramcar_registration gramcar_registration
                   ON (gramcar_registration_for_services.registration_no =
                          gramcar_registration.registration_no))
               INNER JOIN
                  gramcar_site gramcar_site
               ON (gramcar_registration_for_services.services_point_id =
                      gramcar_site.site_id))
           LEFT OUTER JOIN
              gramcar_services_package gramcar_services_package
           ON (gramcar_registration_for_services.services_package_id =
                  gramcar_services_package.package_id))
       LEFT OUTER JOIN
          gramcar_payment_received gramcar_payment_received
       ON (gramcar_registration_for_services.reg_for_service_id =
              gramcar_payment_received.reg_for_service_id)";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 5)";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_registration_for_services.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("reg_site"))	
				{
					$reg_site=$this->input->post("reg_site");	
					$query_string=$query_string." AND (gramcar_registration_for_services.services_point_id IN(Select site_id from gramcar_site where site_parent_id=$reg_site and site_type='SP'))";
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
				
				if($this->input->post("pdate1"))
				{
				$pdate1=$this->input->post("pdate1"); $pdate2=$this->input->post("pdate2");
	
				if(($pdate1!='')&& ($pdate2==''))
				$pdate2=$pdate1;
				
				if(strlen($pdate1)<12) 
				$pdate1=$pdate1." 00:00:00";	
				
				if(strlen($pdate2)<12) 
				$pdate2=$pdate2." 23:59:59";
			
				$query_string= $query_string."  AND payment_received_date BETWEEN '".$pdate1."' AND '".$pdate2."'";
				$data['pdate1']=$pdate1;
				$data['pdate2']=$pdate2;
				}
				
				$query_string=$query_string." ORDER BY gramcar_registration_for_services.services_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar Learning';
			
			//echo $query_string;
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->ref_services_model->get_all_services();
			$data['number_of_services'] = $this->report_model->all_report_count_query_string($searchterm);
			$data['total_amount_received'] = $this->report_model->get_total_amount_received($searchterm);
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "report/report/report_home_search/";
			$config["total_rows"] = $this->report_model->all_report_count_query_string($searchterm);
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
			$data['all_reg_services'] = $this->report_model->get_all_report_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
						
			
			$this->load->view('report/view_report_home', isset($data) ? $data : NULL);
				
			
			
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
	
	
	public function report_daily_services()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
			$this->load->view('report/view_report_daily_services', isset($data) ? $data : NULL);	
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
	
	
	
	public function report_daily_services_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			// assign posted valued
			
				$data['sreg_site'] 		    	= $this->input->post('sreg_site');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');				
			
			$this->form_validation->set_rules('sreg_site', 'Site', 'required');
			$this->form_validation->set_rules('sdate1', 'Date field 1', 'required');
			$this->form_validation->set_rules('sdate2', 'Date field 2', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar daily services report';					
				$this->load->view('report/view_report_daily_services', isset($data) ? $data : NULL);	
				}
				else
				{
				$data['all_possible_date']=$this->report_model->createDateRangeArray($this->input->post('sdate1'), $this->input->post('sdate2') );				
				$this->load->view('report/view_report_daily_services', isset($data) ? $data : NULL);	
				}
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
	
	
	public function report_daily_revenue()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			$data['title'] = 'GramCar daily revenue report';
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
			$this->load->view('report/view_report_daily_revenue', isset($data) ? $data : NULL);	
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
	
	
	public function report_daily_revenue_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			// assign posted valued
			
				$data['sreg_site'] 		    	= $this->input->post('sreg_site');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');				
			
			$this->form_validation->set_rules('sreg_site', 'Site', 'required');
			$this->form_validation->set_rules('sdate1', 'Date field 1', 'required');
			$this->form_validation->set_rules('sdate2', 'Date field 2', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar daily revenue report';					
				$this->load->view('report/view_report_daily_revenue', isset($data) ? $data : NULL);	
				}
				else
				{
				$data['all_possible_date']=$this->report_model->createDateRangeArray($this->input->post('sdate1'), $this->input->post('sdate2') );				
				$this->load->view('report/view_report_daily_revenue', isset($data) ? $data : NULL);	
				}
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
	
	
	public function report_expense()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
			$this->load->view('report/view_report_expense', isset($data) ? $data : NULL);	
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
	
	
	public function report_expense_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			// assign posted valued
			
				$data['sreg_site'] 		    	= $this->input->post('sreg_site');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');				
			
			$this->form_validation->set_rules('sreg_site', 'Site', 'required');
			$this->form_validation->set_rules('sdate1', 'Date field 1', 'required');
			$this->form_validation->set_rules('sdate2', 'Date field 2', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar daily services report';					
				$this->load->view('report/view_report_expense', isset($data) ? $data : NULL);	
				}
				else
				{				
				$data['all_expense']=$this->report_model->get_all_expense_by_site_and_date_range($this->input->post('sreg_site'),$this->input->post('sdate1'), $this->input->post('sdate2') );				
				$this->load->view('report/view_report_expense', isset($data) ? $data : NULL);	
				}
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
	
	
	public function export_to_excel_expense($sreg_site,$sdate1,$sdate2)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			// assign posted valued
			
			
				$data['sreg_site'] 		    	= $sreg_site;
				$data['sdate1']     			= $sdate1;
				$data['sdate2']					= $sdate2;
				
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
		
				$data['all_expense']=$this->report_model->get_all_expense_by_site_and_date_range($sreg_site,$sdate1, $sdate2 );
				$this->load->view('report/view_export_expense', isset($data) ? $data : NULL);	
				
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
	
	public function export_to_excel_daily_services($sreg_site,$sdate1,$sdate2)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			// assign posted valued
			
			
				$data['sreg_site'] 		    	= $sreg_site;
				$data['sdate1']     			= $sdate1;
				$data['sdate2']					= $sdate2;
				
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
				
				
				$data['all_possible_date']=$this->report_model->createDateRangeArray($sdate1, $sdate2 );			
				$this->load->view('report/view_export_daily_services', isset($data) ? $data : NULL);	
				
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
	
	public function export_to_excel_daily_revenue($sreg_site,$sdate1,$sdate2)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_general_report'))
			{
			// assign posted valued
			
			
				$data['sreg_site'] 		    	= $sreg_site;
				$data['sdate1']     			= $sdate1;
				$data['sdate2']					= $sdate2;
				
			$data['site'] = $this->ref_site_model->get_all_site();
			$data['services'] = $this->report_model->get_all_services();	
			
				
				
				$data['all_possible_date']=$this->report_model->createDateRangeArray($sdate1, $sdate2 );			
				$this->load->view('report/view_export_daily_revenue', isset($data) ? $data : NULL);	
				
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
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>