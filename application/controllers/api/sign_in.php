<?php
/*
 * Sign_in Controller
 */
class Sign_in extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('language', 'account/ssl', 'url'));
		$this->load->library(array('account/authentication', 'account/authorization', 'account/recaptcha', 'form_validation'));
		$this->load->model(array('account/account_model', 'account/account_details_model', 'account/acl_role_model'));
		$this->load->language(array('account/connect_third_party'));
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', $this->config->item("default_language"));
		$this->lang->load('mainmenu', $this->config->item("default_language"));
		$this->lang->load('registration_form', $this->config->item("default_language"));
		$this->lang->load('account/sign_in', $this->config->item("default_language"));
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);	
		$this->lang->load('registration_form', $language);
		$this->lang->load('account/sign_in', $language);
		}
	}

	/**
	 * Account sign in
	 *
	 * @access public
	 * @return void
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		// Setup form validation
		$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
		$this->form_validation->set_rules(array(
			array(
				'field' => 'user_name',
				'label' => 'lang:sign_in_username_email',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'password',
				'label' => 'lang:sign_in_password',
				'rules' => 'trim|required'
			)
		));

		// Run form validation
		if ($this->form_validation->run() === TRUE)
		{	
			// Get user by username / email
			if ( ! $user = $this->account_model->get_by_username_email(base64_decode($this->input->post('user_name', TRUE))))
			{
				//echo base64_decode($this->input->post('user_name', TRUE));
				$response["success"] = 0;
				$response["message"] = lang('sign_in_username_email_does_not_exist');
				echo json_encode($response);
			}
			else
			{
				if ( ! $this->authentication->check_password($user->password, base64_decode($this->input->post('password', TRUE))))
				{
					// Increment sign in failed attempts
					$this->session->set_userdata('sign_in_failed_attempts', (int)$this->session->userdata('sign_in_failed_attempts') + 1);
					
					$response["success"] = 0;
					$response["message"] = lang('sign_in_combination_incorrect');
					echo json_encode($response);
					
				}
				else
				{
					$myarray=$this->acl_role_model->get_by_account_id($user->id);
					$response['account_role'] = $myarray[0]->id;
					if ($response['account_role'] == 5) 
					{
						$response['account'] = $this->account_model->get_by_id($user->id);
						$response['account_details'] = $this->account_details_model->get_by_account_id($user->id);
						
						$response["success"] = 1;
						$response["driver"] = 1;
						$response['user_id'] = $user->id;
						$response['api_key'] = $this->config->item("api_key");
						echo json_encode($response);
					}
					else
					{
						$response['account'] = $this->account_model->get_by_id($user->id);
						$response['account_details'] = $this->account_details_model->get_by_account_id($user->id);
						
						$response["success"] = 1;
						$response["driver"] = 0;
						$response['user_id'] = $user->id;
						$response['api_key'] = $this->config->item("api_key");
						echo json_encode($response);
					}
					
				}				
			}
			
		}
		else
		{
			$response["success"] = 0;
			$response["message"] = "Requerd field is empty";
			echo json_encode($response);
		}
	}

}


/* End of file sign_in.php */
/* Location: ./application/account/controllers/sign_in.php */
?>