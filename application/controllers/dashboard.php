<?php

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization'));
		$this->load->model(array('account/account_model','payment_model','registration_model','social_goods_model'));
		//$this->load->language(array('mainmenu'));
		
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

	function index()
	{
		maintain_ssl();

		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			$this->load->view('dashboard', isset($data) ? $data : NULL);	
			
		}
		else
		{
			//$this->load->view('dashboard', isset($data) ? $data : NULL);
			redirect(base_url());
		}		
		
	}
	
	
	function barcode_search()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			
			if($this->authorization->is_permitted('view_registration'))
			{
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');

				$this->form_validation->set_rules('registration_no', 'Registration no', 'required|min_length[10]|max_length[15]');
				if ($this->form_validation->run() == FALSE)
				{					
					$this->load->view('dashboard', isset($data) ? $data : NULL);	
				}
				else
				{					
					
					$is_exist=$this->registration_model->registration_no_exits($this->input->post('registration_no'));
					
					if($is_exist)
					{
						redirect('registration/registration/view_single_registration/'.$this->input->post('registration_no'));
					}
					else
					{						
						if($this->authorization->is_permitted('create_registration'))
						{
						redirect('registration/registration/new_registration/'.$this->input->post('registration_no'));	
						}
						else
						{
						$data['permission_msg'] = "The registration no is not exits but you haven't the registration permission";
						$this->load->view('dashboard', isset($data) ? $data : NULL);		
						}
					}
					
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
	
	

}


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */