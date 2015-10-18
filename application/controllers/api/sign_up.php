<?php
/*
 * Sign_up Controller
 */
class Sign_up extends CI_Controller {

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
		$this->load->model(array('account/account_model', 'account/rel_account_role_model', 'account/account_details_model'));
		$this->load->language(array('account/connect_third_party'));
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', $this->config->item("default_language"));
		$this->lang->load('mainmenu', $this->config->item("default_language"));
		$this->lang->load('account/sign_up', $this->config->item("default_language"));
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);
		$this->lang->load('account/sign_up', $language);	
		}
	}

	/**
	 * Account sign up
	 *
	 * @access public
	 * @return void
	 */
	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
		$this->form_validation->set_rules(
		array(
			array(
				'field' => 'user_name', 
				'label' => 'lang:sign_up_username', 
				'rules' => 'trim|required|min_length[2]|max_length[50]'),
			array('field' => 'password', 
				'label' => 'lang:sign_up_password', 
				'rules' => 'trim|required|min_length[6]'),
			array('field' => 'phone', 
				'label' => 'lang:sign_up_password', 
				'rules' => 'trim|required'),
			array('field' => 'email', 
				'label' => 'lang:sign_up_email', 
				'rules' => 'trim|required|max_length[160]')
			)
		);

		// Run form validation
		if ($this->form_validation->run() === TRUE)
		{
			// Check if user name is taken
			if ($this->username_check(base64_decode($this->input->post('user_name', TRUE))) === TRUE)
			{
				$response["success"] = 0;
				$response["message"] = lang('sign_up_username_taken');
				echo json_encode($response);
			}
			// Check if email already exist
			elseif ($this->email_check(base64_decode($this->input->post('email', TRUE))) === TRUE)
			{
				$response["success"] = 0;
				$response["message"] = lang('sign_up_email_exist');
				echo json_encode($response);
			}
			elseif (!filter_var(base64_decode($this->input->post('email', TRUE)), FILTER_VALIDATE_EMAIL)) 
			{
				$response["success"] = 0;
				$response["message"] = " Invalid email format";
				echo json_encode($response);
			}			
			else
			{	
				// Create user
				$user_id = $this->account_model->create(base64_decode($this->input->post('user_name', TRUE)), base64_decode($this->input->post('email', TRUE)), base64_decode($this->input->post('password', TRUE)), base64_decode($this->input->post('phone', TRUE)));
				if ($user_id) 
				{
					$this->account_details_model->update($user_id);				
					// Apply roles
					$roles = array(6);				
					$this->rel_account_role_model->delete_update_batch($user_id, $roles);
					
					// Dending Email
					$this->load->library('email');

					// Set up email preferences
					$config['mailtype'] = 'html';

					// Initialise email lib
					$this->email->initialize($config);

					// Generate reset password url
					$password = $this->input->post('sign_up_password', TRUE);

					// Send reset password email
					//$this->email->from($this->config->item('password_reset_email'), lang('reset_password_email_sender'));
					$this->email->from('no-replay@gramweb.net', 'SSW-Team');
					$this->email->to($this->input->post('sign_up_email', TRUE));
					$this->email->subject('Welcome to SSW');
					$this->email->message($this->load->view('account/sign_up_confirmation_email', array(
						'username' => $this->input->post('sign_up_username', TRUE),
						'password' => $password
					), TRUE));

					if ($this->email->send()) {
						$response["success"] = 1;
						$response['user_id'] = $user_id;
						$response['message'] = 'An email has been sent to your email';
						$response['api_key'] = $this->config->item("api_key");
						// echoing JSON response
						echo json_encode($response);
					}
					else
					{
						$response["success"] = 1;
						$response['user_id'] = $user_id;
						$response['message'] = 'Email can not sent';
						$response['api_key'] = $this->config->item("api_key");
						// echoing JSON response
						echo json_encode($response);
					}
				}
				else
				{
					$response["success"] = 0;
					//$response["message"] = validation_errors();
					$response["message"] = "An unknown error occurred";
					echo json_encode($response);
				}
				
			}						
		}
		else
		{
			$response["success"] = 0;
			//$response["message"] = validation_errors();
			$response["message"] = "Requerd field is empty";
			echo json_encode($response);
		}
		
	}

	/**
	 * Check if a username exist
	 *
	 * @access public
	 * @param string
	 * @return bool
	 */
	function username_check($username)
	{
		return $this->account_model->get_by_username($username) ? TRUE : FALSE;
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


/* End of file sign_up.php */
/* Location: ./application/controllers/account/sign_up.php */
