<?php
class Add_car extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('car/general','account/account_model'));	
		
		date_default_timezone_set('Asia/Dhaka');  // set the time zone UTC+6
		
		$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', $this->config->item("default_language"));
		$this->lang->load('mainmenu', $this->config->item("default_language"));
		$this->lang->load('registration_form', $this->config->item("default_language"));
		$this->lang->load('car', $this->config->item("default_language"));
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);	
		$this->lang->load('registration_form', $language);
		$this->lang->load('car', $language);
		}		
	}
	
	public function index()  
	{	
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/nodes'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage car');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Car List';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$data['divisions'] = $this->mod_registration->get_division();
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/add_car/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_info');
		$config['num_links'] = 3;
		$config['per_page'] = 10;
		$config['uri_segment'] = 4;
		
		$config['full_tag_open'] = '<div class="pagination pagination-small"><ul>';
		$config['full_tag_close'] = '</ul></div><!--pagination-->';
		$config['display_pages'] = TRUE;
		$config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		
		$config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		
		$config['next_link'] = 'Next &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_link'] = '&larr; Prev';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		
		//$data['site_list'] = $this->mod_site->get($config["per_page"], $page);
			
		$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', $page, $config["per_page"]);
		//($config["per_page"], $page);
		//print_r($data['all_nodes']);
		//exit;				
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;			
		
		$this->load->view('car/car_list', isset($data) ? $data : NULL);		
					
	}
	
	function search()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'car/add_car'));
		}
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage car');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		$data['title'] = 'Search Car';	
			
		$field_name=NULL; 
		$car_licence=NULL;		
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
			array(
			  'field' => 'car_licence',
			  'label' => 'Car Licence No.',
			  'rules' => 'trim')
		  ));
		  
		// Search Fields
		$car_licence = trim($this->input->post('car_licence'));
		$status = trim($this->input->post('status'));
		$search_fields = NULL;
		if (isset($car_licence) && $this->form_validation->run())
    	{
			$field_name=NULL; 
			$car_licence=$car_licence;
			//$select = "`news_id`, `news_title_en`, `news_title_bn`, `news_details_en`, `news_details_bn`, `news_publish_date`, `news_image`, `create_user_id`, `enable`";
			if($status!="" && $car_licence!="")
			{
				$search_fields = array('licence_no'=>$car_licence, 'enable'=>$status);
			}
			if($status=="" && $car_licence!="")
			{
				$search_fields = array('licence_no'=>$car_licence);
			}
			if($status!="" && $car_licence=="")
			{
				$search_fields = array('enable'=>$status);
			}
			$data['all_car'] = $this->general->get_list_search_view('car_info', $search_fields, NULL);			
		}
		
		$this->load->view('car/car_list', isset($data) ? $data : NULL);
	}
	
  
  function save($id=null)
  {
    // Keep track if this is a new user
    $is_new = empty($id);

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'car/nodes/save'));
    }

    // Check if they are allowed to Update Users
    if ( ! $this->authorization->is_permitted('car_manage') && ! empty($id) )
    {
		$this->session->set_flashdata('parmission', 'You have no permission to update car');
      	redirect(base_url().'dashboard');
    }

    // Check if they are allowed to Create Users
    if ( ! $this->authorization->is_permitted('car_add') && empty($id) )
    {
      $this->session->set_flashdata('parmission', 'You have no permission to add car');
      redirect(base_url().'dashboard');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    $data['action'] = 'create';

    $data['drivers'] = $this->general->get_driver();

    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'model',
          'label' => 'lang:car_model',
          'rules' => 'trim|required|max_length[255]'),
        array(
          'field' => 'car_licence', 
          'label' => 'lang:car_licence', 
          'rules' => 'trim|required|max_length[255]'),
		array(
          'field' => 'no_of_set', 
          'label' => 'lang:no_of_set', 
          'rules' => 'trim|required|max_length[255]'), 
		array(
          'field' => 'hot_line', 
          'label' => 'lang:hot_line', 
          'rules' => 'trim|required|max_length[255]'),  
        array(
          'field' => 'status', 
          'label' => 'lang:status', 
          'rules' => 'trim|required')
      ));

    // Run form validation
    if ($this->form_validation->run())
    {
        if( empty($id) ) {
			$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
					'model' => $this->input->post('model', TRUE), 
					'licence_no' => $this->input->post('car_licence', TRUE),
					'driver_id' => $this->input->post('driver_id', TRUE),
					'brand' => $this->input->post('brand', TRUE),
					'no_of_set' => $this->input->post('no_of_set', TRUE),
					'hot_line' => $this->input->post('hot_line', TRUE), 
					'parking_address' => $this->input->post('parking_address'),  
					'enable' => $this->input->post('status', TRUE),
					'create_user_id' => $data['account']->username,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			$route_id = $this->general->save_into_table_and_return_insert_id('car_info', $data);
			$this->session->set_flashdata('message_success', lang('success_add'));
      		redirect('car/add_car');
			
        }
        // Update existing News
        else 
        {
      		$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
					'model' => $this->input->post('model', TRUE), 
					'licence_no' => $this->input->post('car_licence', TRUE),
					'driver_id' => $this->input->post('driver_id', TRUE),
					'brand' => $this->input->post('brand', TRUE),
					'no_of_set' => $this->input->post('no_of_set', TRUE),
					'hot_line' => $this->input->post('hot_line', TRUE), 
					'parking_address' => $this->input->post('parking_address'),  
					'enable' => $this->input->post('status', TRUE),
					'update_user_id' => $data['account']->username,
					'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			
			$this->general->update_table('car_info', $data,'car_id',$id);
			$this->session->set_flashdata('message_success', lang('success_update'));
      		redirect('car/add_car');
		}
	}
	// Get the account to update
    if( ! $is_new )
    {
      $data['update_details'] = $this->general->get_all_table_info_by_id('car_info', 'car_id', $id);
      $data['action'] = 'update';
    }	
    $this->load->view('car/car_save', $data);
  }
  
	
  function delete($id)
  {
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/nodes'));
		}
		
		
		// Check if they are allowed to car_delete_route
		if ( !empty($id) && ! $this->authorization->is_permitted('car_delete'))
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to delete node');
		  redirect(base_url().'car/nodes');
		}
		
		if (!empty($id))
		{
			$this->general->delete_from_table('car_info', 'car_id', $id);
			$this->session->set_flashdata('message_success', 'Your data successfully deleted');
		  	redirect(base_url().'car/add_car');
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete route');
		  	redirect(base_url().'car/add_car');
		}
  }
	
}// END Class

?>