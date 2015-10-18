<?php

class Home extends CI_Controller {

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
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		}
		$this->load->view('home', isset($data) ? $data : NULL);
	}			
	
	

}


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */