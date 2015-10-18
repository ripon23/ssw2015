<?php
class Routes extends CI_Controller {

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
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_route'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage route');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Route List';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$data['divisions'] = $this->mod_registration->get_division();
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/routes/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_route');
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
			
		$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select=NULL, 'route_id', 'desc', $page, $config["per_page"]);
		//($config["per_page"], $page);
		//print_r($data['all_routes']);
		//exit;				
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;			
		
		$this->load->view('car/routes', isset($data) ? $data : NULL);		
					
	}
	
	function search_route()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes'));
		}
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_route'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage route');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		$data['title'] = 'Rout List';	
			
		$field_name=NULL; 
		$news_id=NULL;		
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
			array(
			  'field' => 'route_name',
			  'label' => 'Route Name',
			  'rules' => 'trim')
		  ));
		  
		// Search Fields
		$route_name = trim($this->input->post('route_name'));
		$status = trim($this->input->post('status'));
		$search_fields = NULL;
		if (isset($route_name) && $this->form_validation->run())
    	{
			$field_name=NULL; 
			$route_name=$route_name;
			//$select = "`news_id`, `news_title_en`, `news_title_bn`, `news_details_en`, `news_details_bn`, `news_publish_date`, `news_image`, `create_user_id`, `enable`";
			if($status!="" && $route_name!="")
			{
				$search_fields = array('route_name_en'=>$route_name, 'enable'=>$status);
			}
			if($status=="" && $route_name!="")
			{
				$search_fields = array('route_name_en'=>$route_name);
			}
			if($status!="" && $route_name=="")
			{
				$search_fields = array('enable'=>$status);
			}

			$data['all_routes'] = $this->general->get_list_search_view('car_route', $search_fields, NULL);			
		}
		$this->load->view('car/routes', isset($data) ? $data : NULL);
	}
	
  
  function save($id=null)
  {
    // Keep track if this is a new user
    $is_new = empty($id);

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes/save'));
    }

    // Check if they are allowed to Update Users
    if ( ! $this->authorization->is_permitted('car_manage_route') && ! empty($id) )
    {
		$this->session->set_flashdata('parmission', 'You have no permission to update route');
      	redirect(base_url().'dashboard');
    }

    // Check if they are allowed to Create Users
    if ( ! $this->authorization->is_permitted('car_add_route') && empty($id) )
    {
      $this->session->set_flashdata('parmission', 'You have no permission to add route');
      redirect(base_url().'dashboard');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    $data['action'] = 'create';


    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'route_name_en',
          'label' => 'lang:route_name_en',
          'rules' => 'trim|required|max_length[255]'),
        array(
          'field' => 'fcost',
          'label' => 'Fixed Cost',
          'rules' => 'trim|required|numeric|max_length[11]'),
        array(
          'field' => 'route_name_bn', 
          'label' => 'lang:route_name_bn', 
          'rules' => 'trim|max_length[255]'), 
        array(
          'field' => 'status', 
          'label' => 'lang:status', 
          'rules' => 'trim|required'),		  
	 	array(
          'field' => 'note', 
          'label' => 'lang:note', 
          'rules' => 'trim')
      ));

    // Run form validation
    if ($this->form_validation->run())
    {
        if( empty($id) ) {
			$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
					'route_name_en' => $this->input->post('route_name_en', TRUE), 
					'route_name_bn' => $this->input->post('route_name_bn', TRUE),
					'car_id' => $this->input->post('car', TRUE),
					'fixed_cost' => $this->input->post('fcost', TRUE),
					'enable' => $this->input->post('status', TRUE),
					'create_user_id' => $data['account']->username,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			$route_id = $this->general->save_into_table_and_return_insert_id('car_route', $data);
			$this->session->set_flashdata('message_success', lang('success_add'));
      		redirect('car/routes');
			
        }
        // Update existing News
        else 
        {
      		$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
					'route_name_en' => $this->input->post('route_name_en', TRUE), 
					'route_name_bn' => $this->input->post('route_name_bn', TRUE), 
					'car_id' => $this->input->post('car', TRUE), 
					'fixed_cost' => $this->input->post('fcost', TRUE),
					'enable' => $this->input->post('status', TRUE),
					'update_user_id' => $data['account']->username,
					'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			
			$this->general->update_table('car_route', $data,'route_id',$id);
			$this->session->set_flashdata('message_success', lang('success_update'));
      		redirect('car/routes');
		}
	}
	// Get the account to update
    if( ! $is_new )
    {
      $data['update_details'] = $this->general->get_all_table_info_by_id('car_route', 'route_id', $id);
      $data['action'] = 'update';
    }
	$data['all_car'] = $this->general->get_list_view('car_info', $field_name=NULL, $id=NULL, $select=NULL, 'car_id', 'desc', NULL, NULL);	
    $this->load->view('car/route_save', $data);
  }
  
	
  public function delete($id)
  {
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes'));
		}
		
		
		// Check if they are allowed to car_delete_route
		if ( !empty($id) && ! $this->authorization->is_permitted('car_delete_route') && empty($id))
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to delete route');
		  redirect(base_url().'car/routes');
		}
		
		if (!empty($id))
		{

            if (!$this->general->get_all_table_info_by_id_custom("car_node", "route_id", 'route_id', $id)) {
            	$this->general->delete_from_table('car_route', 'route_id', $id);
				$this->session->set_flashdata('message_success', 'Your data successfully deleted');
			  	redirect(base_url().'car/routes');
			}else{
				$this->session->set_flashdata('parmission', 'You have to delete node frist that assigned this route than delete it');
		  		redirect(base_url().'car/routes');
			}
			
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete route');
		  	redirect(base_url().'car/routes');
		}
  }
  
	function add_mapping($id=null)
	{
		// Keep track if this is a new user
    	$is_new = empty($id);
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'car/routes'));
		}
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_route'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage route');
			//redirect(uri_string());
			redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		$data['title'] = 'Route Mapping';
		$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select=NULL, 'route_id', 'asc', NULL, NULL);
		$data['all_nodes'] = $this->general->get_list_view('car_node', $field_name=NULL, $id=NULL, $select=NULL, 'node_id', 'desc', NULL, NULL);
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'route_id',
          'label' => 'lang:select_route',
          'rules' => 'trim|required'),
        array(
          'field' => 'node_id', 
          'label' => 'lang:select_node', 
          'rules' => 'trim|required'), 
        array(
          'field' => 'prev_node_id', 
          'label' => 'lang:select_prev_node', 
          'rules' => 'trim|required'),
		array(
          'field' => 'next_node_id', 
          'label' => 'lang:select_next_node', 
          'rules' => 'trim|required')
      ));
	  
	  	// Run form validation
		if ($this->form_validation->run())
		{
			if( empty($id) ) {
				$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'route_id' => $this->input->post('route_id', TRUE), 
						'node_id' => $this->input->post('node_id', TRUE), 
						'prv_node_id' => $this->input->post('prev_node_id', TRUE),
						'next_node_id' => $this->input->post('next_node_id', TRUE),
						'duration_to_next' => $this->input->post('duration_to_next', TRUE),
						'create_user_id' => $data['account']->username,
						'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				$mapping_id = $this->general->save_into_table_and_return_insert_id('car_route_node_mapping', $data);
				$this->session->set_flashdata('message_success', lang('success_add'));
				redirect('car/routes/add_mapping');
				
			}
			// Update existing News
			else 
			{
				$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'route_id' => $this->input->post('route_id', TRUE), 
						'node_id' => $this->input->post('node_id', TRUE), 
						'prv_node_id' => $this->input->post('prev_node_id', TRUE),
						'next_node_id' => $this->input->post('next_node_id', TRUE),
						'duration_to_next' => $this->input->post('duration_to_next', TRUE),
						'update_user_id' => $data['account']->username,
						'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				
				$this->general->update_table('car_route_node_mapping', $data,'map_id',$id);
				$this->session->set_flashdata('message_success', lang('success_update'));
				redirect('car/routes/add_mapping');
			}
		}
		// Get the account to update
		if( ! $is_new )
		{
		  $data['update_details'] = $this->general->get_all_table_info_by_id('car_route_node_mapping', 'map_id', $id);
		  $data['action'] = 'update';
		}
		$this->load->view('car/mapping_save', $data);	  
	}
	
}// END Class

?>