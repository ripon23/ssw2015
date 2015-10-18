<?php
class Basic_setting extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','basic_setting_model','account/account_model', 'ref_site_model', 'ref_services_model' ));	
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
			if($this->authorization->is_permitted('add_edit_delete_gramcar_services'))
			{				
				$data['title'] = 'GramCar Basic Setting';
				$data['services_list'] = $this->basic_setting_model->get_all_services();
				$this->load->view('basic_setting/view_basic_setting', isset($data) ? $data : NULL);				
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
	
	function package_list()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_package'))
			{
				
				$data['title'] = 'GramCar Package List';
				$data['gramcar_services'] = $this->ref_services_model->get_all_services();
				$data['packages_list'] = $this->basic_setting_model->get_all_packages();
				$this->load->view('basic_setting/view_package_list', isset($data) ? $data : NULL);
				
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
	
	
	function site_list()
	{
	if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_edit_delete_services_point'))
			{
				
				$data['title'] = 'GramCar Site / Services Points';
				$data['gramcar_services_points'] = $this->ref_site_model->get_all_site_info();
				$data['gramcar_site'] = $this->ref_site_model->get_all_site();
				$this->load->view('basic_setting/view_site_list', isset($data) ? $data : NULL);
				
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
	function update_gramcar_site()
	{
		$gramcar_site=array(		
						'site_name'=>$this->input->post('site_name_english'),
						'site_name_bn'=>$this->input->post('site_name_bangla'),
						'site_description'=>$this->input->post('site_description'),
						'site_parent_id'=>$this->input->post('site'),
						'site_type'=>$this->input->post('site_or_services_point'),
						'site_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
		$success_or_fail=$this->basic_setting_model->update_site_by_id($gramcar_site,$this->input->post('site_id'));
					
		if($success_or_fail)
		echo "Successfull";				
		else
		echo "Unsuccessfull";
		
	}
	
	/**** Ajax function *****/
	function update_gramcar_package()
	{
		$gramcar_package=array(
						'services_id '=>$this->input->post('package_services'),		
						'package_name'=>$this->input->post('package_name_english'),
						'package_name_bn'=>$this->input->post('package_name_bangla'),
						'package_description'=>$this->input->post('package_description'),
						'package_price'=>$this->input->post('package_price'),
						'package_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
		$success_or_fail=$this->basic_setting_model->update_package_by_id($gramcar_package,$this->input->post('package_id'));
					
		if($success_or_fail)
		echo "Successfull";				
		else
		echo "Unsuccessfull";
		
	}
	
	function add_new_gramcar_site()
	{
		$gramcar_site=array(
						'site_name'=>$this->input->post('site_name_english'),
						'site_name_bn'=>$this->input->post('site_name_bangla'),
						'site_description'=>$this->input->post('site_description'),
						'site_parent_id'=>$this->input->post('site'),
						'site_type'=>$this->input->post('site_or_services_point'),
						'site_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);		
	
	$success_or_fail=$this->basic_setting_model->add_new_site($gramcar_site);
					
		if($success_or_fail)
		{
		echo "Successfull";						
		}
		else
		{
		echo "Unsuccessfull";
		}
	}
	
	
	/**** Ajax function *****/
	function add_new_gramcar_package()
	{
		
	$gramcar_package=array(
						'services_id '=>$this->input->post('package_services'),		
						'package_name'=>$this->input->post('package_name_english'),
						'package_name_bn'=>$this->input->post('package_name_bangla'),
						'package_description'=>$this->input->post('package_description'),
						'package_price'=>$this->input->post('package_price'),
						'package_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);		
	
	$success_or_fail=$this->basic_setting_model->add_new_package($gramcar_package);
					
		if($success_or_fail)
		{
		echo "Successfull";						
		}
		else
		{
		echo "Unsuccessfull";
		}
		
	}
	
	/**** Ajax function *****/
	function add_new_gramcar_services()
	{
		
	$new_gramcar_services=array(
						'services_name'=>$this->input->post('services_name_english'),
						'services_name_bn'=>$this->input->post('services_name_bangla'),
						'services_description'=>$this->input->post('services_description'),
						'services_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);		
	
	$success_or_fail=$this->basic_setting_model->add_new_services($new_gramcar_services);
					
		if($success_or_fail)
		{
		echo "Successfull";						
		}
		else
		{
		echo "Unsuccessfull";
		}
		
	}
	
	/**** Ajax function *****/
	function update_gramcar_services()
	{
		$gramcar_services=array(
						'services_name'=>$this->input->post('services_name_english'),
						'services_name_bn'=>$this->input->post('services_name_bangla'),
						'services_description'=>$this->input->post('services_description'),
						'services_status'=>$this->input->post('statusvalue'),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())
						);
		$success_or_fail=$this->basic_setting_model->update_services_by_id($gramcar_services,$this->input->post('services_id'));
					
		if($success_or_fail)
		echo "Successfull";				
		else
		echo "Unsuccessfull";
		
	}
	

}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>