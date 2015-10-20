<?php
class Pickuppoint extends CI_Controller {

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
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/pickuppoint'));
		}
		
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_picuppoint'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage car_manage_picuppoint');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Pickup Po List';	
		
		// Paginations
		$this->load->library('pagination');
		
		//$data['divisions'] = $this->mod_registration->get_division();
		
		//$count_members = count($all_members);
		$config = array();
		$config['base_url'] = base_url().'car/pickuppoint/index/';
		$config['total_rows'] = $this->general->number_of_total_rows_in_a_table('car_node');
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
			
		$data['all_pickup_point'] = $this->general->get_list_view('car_pickup_point', $field_name=NULL, $id=NULL, $select=NULL, 'pickup_point_id', 'desc', $page, $config["per_page"]);
		
		$node_select = '`node_id`, `node_name_en`';	
		$data['all_car_node'] = $this->general->get_list_view('car_node', $node_field = NULL, $id=NULL, $node_select, 'node_id', 'desc', NULL, NULL);
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;			
		
		$this->load->view('car/pickup_point', isset($data) ? $data : NULL);		
					
	}

	function save($id=null)
  	{
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
	    // Keep track if this is a new user
	    $is_new = empty($id);
	    
	    // Redirect unauthenticated users to signin page
	    if ( ! $this->authentication->is_signed_in())
	    {
	      redirect('account/sign_in/?continue='.urlencode(base_url().'car/pickuppoint/save'));
	    }

	    // Check if they are allowed to Update Users
	    if ( ! $this->authorization->is_permitted('car_manage_picuppoint') && ! empty($id) )
	    {
			$this->session->set_flashdata('parmission', 'You have no permission to update picuppoint');
	      	redirect(base_url().'dashboard');
	    }

	    // Check if they are allowed to Create Users
	    if ( ! $this->authorization->is_permitted('car_manage_picuppoint') && empty($id) )
	    {
	      $this->session->set_flashdata('parmission', 'You have no permission to add picuppoint');
	      redirect(base_url().'dashboard');
	    }

	    // Retrieve sign in user
	    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

	    $data['action'] = 'create';
	    $node_select = '`node_id`, `node_name_en`';	
	    // $data['all_car_node'] = $this->general->get_list_view('car_node', $node_field = NULL, NULL, $node_select, 'node_id', 'desc', NULL, NULL);
		
	    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
	    $this->form_validation->set_rules(
	      array(
	        array(
	          'field' => 'pickup_point_en',
	          'label' => 'lang:node_name_en',
	          'rules' => 'trim|required|max_length[255]'),
	        array(
	          'field' => 'pickup_point_bn', 
	          'label' => 'lang:node_name_bn', 
	          'rules' => 'trim|required|max_length[255]'),
	        array(
	          'field' => 'distance_to_node', 
	          'label' => 'Distance to node', 
	          'rules' => 'trim|max_length[5]'),
	        array(
	          'field' => 'node_id', 
	          'label' => 'Node', 
	          'rules' => 'trim|required|max_length[255]'),
			array(
	          'field' => 'longitude', 
	          'label' => 'lang:longitude', 
	          'rules' => 'trim|max_length[255]'), 
			array(
	          'field' => 'latitude', 
	          'label' => 'lang:latitude', 
	          'rules' => 'trim|max_length[255]'),  
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
						'pickup_point_en' => $this->input->post('pickup_point_en', TRUE), 
						'pickup_point_bn' => $this->input->post('pickup_point_bn', TRUE),
						'distance_to_node' => $this->input->post('distance_to_node', TRUE),
						'node_id' => $this->input->post('node_id', TRUE),
						'latitude' => $this->input->post('latitude', TRUE),
						'longitude' => $this->input->post('longitude', TRUE),  
						'created_at' => mdate('%Y-%m-%d %H:%i:%s', $now),
						'enable' => $this->input->post('status', TRUE)				
					);
				$route_id = $this->general->save_into_table_and_return_insert_id('car_pickup_point', $data);
				$this->session->set_flashdata('message_success', lang('success_add'));
	      		redirect('car/pickuppoint');
				
	        }
	        // Update existing News
	        else 
	        {
	      		$now = gmt_to_local(now(), 'UP5', TRUE);
				$data = array(
						'pickup_point_en' => $this->input->post('pickup_point_en', TRUE), 
						'pickup_point_bn' => $this->input->post('pickup_point_bn', TRUE),
						'distance_to_node' => $this->input->post('distance_to_node', TRUE),
						'node_id' => $this->input->post('node_id', TRUE),
						'latitude' => $this->input->post('latitude', TRUE),
						'longitude' => $this->input->post('longitude', TRUE),
						'enable' => $this->input->post('status', TRUE),
						'updated_at' => mdate('%Y-%m-%d %H:%i:%s', $now)					
					);
				
				$this->general->update_table('car_pickup_point', $data,'pickup_point_id',$id);
				$this->session->set_flashdata('message_success', lang('success_update'));
	      		redirect('car/pickuppoint');
			}
		}

		// Get the account to update
	    if( ! $is_new )
	    {
	      $data['update_details'] = $this->general->get_all_table_info_by_id('car_pickup_point', 'pickup_point_id', $id);
	      $data['action'] = 'update';
	    }
	    $select_route = ' `route_id`, `route_name_en`';
	    $data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select_route, 'route_id', 'desc', NULL, NULL);
		$data['all_nodes'] = $this->general->get_list_search_view('car_node', NULL, NULL);	
	    $this->load->view('car/pickup_point_save', $data);
  	}

	
	function search_pickuppoint()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'car/pickuppoint'));
		}
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_picuppoint'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage car_manage_picuppoint');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		$data['title'] = 'Search Pickup Point';	
		$node_select = '`node_id`, `node_name_en`';	
	    $data['all_car_node'] = $this->general->get_list_view('car_node', $node_field = NULL, NULL, $node_select, 'node_id', 'desc', NULL, NULL);
			
		$field_name=NULL; 
		$pickuppoint_name=NULL;		
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
			array(
			  'field' => 'pickuppoint_name',
			  'label' => 'lang:car_pickuppoint_name',
			  'rules' => 'trim')
		  ));
		  
		// Search Fields
		$pickuppoint_name = trim($this->input->post('pickuppoint_name'));

		$status = trim($this->input->post('status'));
		$node_id = trim($this->input->post('node_id'));
		$search_fields = NULL;
		if (isset($pickuppoint_name) && $this->form_validation->run())
    	{
    		$node_array = array();
    		$search_fields = array();
    		$field_name=NULL; 
			$pickuppoint_name=$pickuppoint_name;
			//$select = "`news_id`, `news_title_en`, `news_title_bn`, `news_details_en`, `news_details_bn`, `news_publish_date`, `news_image`, `create_user_id`, `enable`";
			if($status!="" && $pickuppoint_name!="")
			{
				$search_fields = array('pickup_point_en'=>$pickuppoint_name, 'enable'=>$status);
			}
			if($status=="" && $pickuppoint_name!="")
			{
				$search_fields = array('pickup_point_en'=>$pickuppoint_name);
			}
			if($status!="" && $pickuppoint_name=="")
			{
				$search_fields = array('enable'=>$status);
			}
			if ($node_id!=="") {
				$node_array = array('node_id'=>$node_id);
				$search_fields = array_merge($search_fields,$node_array);
			}
			$data['all_pickup_point'] = $this->general->get_list_search_view('car_pickup_point', $search_fields, NULL);			
		}
		// print_r(expression)
		
		$this->load->view('car/pickup_point', isset($data) ? $data : NULL);
	}
	
  
  
  
	
  function delete($id)
  {
	  	// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
		  redirect('account/sign_in/?continue='.urlencode(base_url().'car/pickuppoint'));
		}
		
		
		// Check if they are allowed to car_delete_route
		if ( !empty($id) && ! $this->authorization->is_permitted('car_manage_picuppoint'))
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to delete picuppoint');
		  redirect(base_url().'car/pickuppoint');
		}
		
		if (!empty($id))
		{
			$this->general->delete_from_table('car_pickup_point', 'pickup_point_id', $id);
			$this->session->set_flashdata('message_success', 'Your data successfully deleted');
		  	redirect(base_url().'car/pickuppoint');
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete picuppoint');
		  	redirect(base_url().'car/pickuppoint');
		}
  }
	
}// END Class

?>