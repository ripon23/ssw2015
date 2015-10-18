<?php
/*
 * Account_settings Controller
 */
class Account_settings extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('date', 'language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'form_validation'));
		$this->load->model(array('account/account_model', 'account/account_details_model', 'account/ref_country_model', 'account/ref_language_model', 'account/ref_zoneinfo_model'));
		//$this->load->language(array('general', 'account/account_settings'));
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', $this->config->item("default_language"));
		$this->lang->load('account/account_settings', $this->config->item("default_language"));
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('account/account_settings', $language);
		}
	}

	/**
	 * Account settings
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));		

		// Setup form validation
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
			array(
				/*array(
					'field' => 'email', 
					'label' => 'lang:settings_email', 
					'rules' => 'trim|required|valid_base64|max_length[160]'),*/
				array(
					'field' => 'fullname', 
					'label' => 'lang:settings_fullname', 
					'rules' => 'trim|max_length[160]|valid_base64'), 
				array(
					'field' => 'firstname', 
					'label' => 'lang:settings_firstname', 
					'rules' => 'trim|max_length[80]|valid_base64'), 
				array(
					'field' => 'lastname', 
					'label' => 'lang:settings_lastname', 
					'rules' => 'trim|max_length[80]|valid_base64'), 
				array(
					'field' => 'postalcode', 
					'label' => 'lang:settings_postalcode', 
					'rules' => 'trim|max_length[40]|valid_base64')
				)
			);

		// Run form validation
		if ($this->form_validation->run())
		{
			if ($this->config->item("api_key")=== base64_decode($this->input->post('api_key', TRUE)))
			{
				// Retrieve sign in user
				$data['account'] = $this->account_model->get_by_id(base64_decode($this->input->post('account_id', TRUE)));
				$data['account_details'] = $this->account_details_model->get_by_account_id(base64_decode($this->input->post('account_id', TRUE)));
				
				
				$attributes['dateofbirth'] = mdate('%Y-%m-%d', strtotime(base64_decode($this->input->post('dateofbirth', TRUE))));
					
				$attributes['fullname'] = $this->input->post('fullname', TRUE) ? base64_decode($this->input->post('fullname', TRUE)) : NULL;
				$attributes['firstname'] = $this->input->post('firstname', TRUE) ? base64_decode($this->input->post('firstname', TRUE)) : NULL;
				$attributes['lastname'] = $this->input->post('lastname', TRUE) ? base64_decode($this->input->post('lastname', TRUE)) : NULL;
				$attributes['gender'] = $this->input->post('gender', TRUE) ? base64_decode($this->input->post('gender', TRUE)) : NULL;
				$attributes['postalcode'] = $this->input->post('postalcode', TRUE) ? base64_decode($this->input->post('postalcode', TRUE)) : NULL;
				$attributes['country'] = $this->input->post('country', TRUE) ? base64_decode($this->input->post('country', TRUE)) : NULL;
				$attributes['language'] = $this->input->post('language', TRUE) ? base64_decode($this->input->post('language', TRUE)) : NULL;
				$attributes['timezone'] = $this->input->post('timezone', TRUE) ? base64_decode($this->input->post('timezone', TRUE)) : NULL;
				$this->account_details_model->update($data['account']->id, $attributes);

				$response["success"] = 1;
				$response["message"] = lang('settings_details_updated');
				echo json_encode($response);
				
			}
			else
			{
				$response["success"] = 0;
				$response["message"] = "API key is wrong";
				echo json_encode($response);
			}
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "Your input validation is error";
			echo json_encode($response);
		}
	}

	/**
	 * Check if an email exist
	 *
	 * @access public
	 * @param string
	 * @return bool
	 */
	function email_check($email)
	{
		return $this->account_model->get_by_email($email) ? TRUE : FALSE;
	}
}


/* End of file account_settings.php */
/* Location: ./application/account/controllers/account_settings.php */