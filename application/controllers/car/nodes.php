<?php
class Nodes extends CI_Controller {

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
		if ( ! $this->authorization->is_permitted('car_manage_node'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage nodes');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		
		$data['title'] = 'Rout List';	
		
		// Paginations
		$this->load->library('pagination');
		
		$config = array();
		$config['base_url'] = base_url().'car/nodes/index/';
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
		
		//$data['site_list'] = $this->mod_site->get($config["per_page"], $page);
			
		$data['all_nodes'] = $this->general->get_list_view('car_node', $field_name=NULL, $id=NULL, $select=NULL, 'node_id', 'asc', $page, $config["per_page"]);
		
		// echo "<pre>"; print_r($data['all_nodes']); echo "</pre>";
		// exit;	
		$select_route = ' `route_id`, `route_name_en`';
		$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select_route, 'route_id', 'desc', NULL, NULL);
					
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;			
		
		$this->load->view('car/nodes', isset($data) ? $data : NULL);	
	}
	
	function search_node()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));
		
		// Redirect unauthenticated users to signin page
		if ( ! $this->authentication->is_signed_in())
		{
			redirect('account/sign_in/?continue='.urlencode(base_url().'car/nodes'));
		}
		// Redirect unauthorized users to account profile page
		if ( ! $this->authorization->is_permitted('car_manage_node'))
		{
			$this->session->set_flashdata('parmission', 'You have no permission to access manage nodes');
			//redirect(uri_string());
		  	redirect(base_url().'dashboard');
		}
		
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
		$data['title'] = 'Search Node';	
			
		$field_name=NULL; 
		$node_name=NULL;		
		$this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
		$this->form_validation->set_rules(
		  array(
			array(
			  'field' => 'node_name',
			  'label' => 'Node Name',
			  'rules' => 'trim')
		  ));
		  
		// Search Fields
		$node_name = trim($this->input->post('node_name'));
		$route_id = trim($this->input->post('route_id'));
		$status = trim($this->input->post('status'));
		$search_fields = array();
		if (isset($node_name) && $this->form_validation->run())
    	{
			$field_name=NULL; 
			$node_name=$node_name;
			//$select = "`news_id`, `news_title_en`, `news_title_bn`, `news_details_en`, `news_details_bn`, `news_publish_date`, `news_image`, `create_user_id`, `enable`";
			if($status!="" && $node_name!="")
			{
				$search_fields = array('node_name_en'=>$node_name, 'enable'=>$status);
			}
			if($status=="" && $node_name!="")
			{
				$search_fields = array('node_name_en'=>$node_name);
			}
			if($status!="" && $node_name=="")
			{
				$search_fields = array('enable'=>$status);
			}
			if ($route_id!=="") {
				$route_array = array('route_id'=>$route_id);
				$search_fields = array_merge($search_fields,$route_array);
			}			
			$data['all_nodes'] = $this->general->get_list_search_view('car_node', $search_fields, NULL);	

		}
		$select_route = ' `route_id`, `route_name_en`';
		$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select_route, 'route_id', 'desc', NULL, NULL);
		
		$this->load->view('car/nodes', isset($data) ? $data : NULL);
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
      redirect('account/sign_in/?continue='.urlencode(base_url().'car/nodes/save'));
    }

    // Check if they are allowed to Update Users
    if ( ! $this->authorization->is_permitted('car_manage_node') && ! empty($id) )
    {
		$this->session->set_flashdata('parmission', 'You have no permission to update node');
      	redirect(base_url().'dashboard');
    }

    // Check if they are allowed to Create Users
    if ( ! $this->authorization->is_permitted('car_add_node') && empty($id) )
    {
      $this->session->set_flashdata('parmission', 'You have no permission to add node');
      redirect(base_url().'dashboard');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    $data['action'] = 'create';


    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'node_name_en',
          'label' => 'lang:node_name_en',
          'rules' => 'trim|required|max_length[255]'),
        array(
          'field' => 'node_name_bn', 
          'label' => 'lang:node_name_bn', 
          'rules' => 'trim|required|max_length[255]'),
        array(
          'field' => 'distance_previous', 
          'label' => 'Distance Previous', 
          'rules' => 'trim|required|numeric|max_length[20]'),
        array(
          'field' => 'route_id', 
          'label' => 'Route Name', 
          'rules' => 'trim|required|max_length[5]'),
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
          'rules' => 'trim|required'),		  
	 	array(
          'field' => 'time_previous', 
          'label' => 'Time Previous', 
          'rules' => 'trim|required')
      ));

    // Run form validation
    if ($this->form_validation->run())
    {

        if( empty($id) ) {
			$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
					'node_name_en' => $this->input->post('node_name_en', TRUE), 
					'node_name_bn' => $this->input->post('node_name_bn', TRUE),
					'previous_node' => $this->input->post('previous_node',TRUE)==''? NULL: $this->input->post('previous_node',TRUE),
					'distance_previous' => $this->input->post('distance_previous', TRUE),
					'time_previous' => $this->input->post('time_previous', TRUE),
					'route_id' => $this->input->post('route_id', TRUE),
					'latitude' => $this->input->post('latitude', TRUE),
					'longitude' => $this->input->post('longitude', TRUE),  
					'enable' => $this->input->post('status', TRUE),
					'create_user_id' => $data['account']->username,
					'create_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			$route_id = $this->general->save_into_table_and_return_insert_id('car_node', $data);
			$this->session->set_flashdata('message_success', lang('success_add'));
      		redirect('car/nodes');
			
        }
        // Update existing 
        else 
        {
      		$now = gmt_to_local(now(), 'UP5', TRUE);
			$data = array(
					'node_name_en' => $this->input->post('node_name_en', TRUE), 
					'node_name_bn' => $this->input->post('node_name_bn', TRUE),
					'previous_node' => $this->input->post('previous_node',TRUE)==''? NULL: $this->input->post('previous_node',TRUE),
					'distance_previous' => $this->input->post('distance_previous', TRUE),
					'time_previous' => $this->input->post('time_previous', TRUE),
					'route_id' => $this->input->post('route_id', TRUE),
					'latitude' => $this->input->post('latitude', TRUE),
					'longitude' => $this->input->post('longitude', TRUE),
					'enable' => $this->input->post('status', TRUE),
					'update_user_id' => $data['account']->username,
					'update_date' => mdate('%Y-%m-%d %H:%i:%s', $now)					
				);
			
			$this->general->update_table('car_node', $data,'node_id',$id);
			$this->session->set_flashdata('message_success', lang('success_update'));
      		redirect('car/nodes');
		}
	}
	// Get the account to update
    if( ! $is_new )
    {
      $data['update_details'] = $this->general->get_all_table_info_by_id('car_node', 'node_id', $id);
      $data['action'] = 'update';
    }
    $select_route = ' `route_id`, `route_name_en`';
	$data['all_routes'] = $this->general->get_list_view('car_route', $field_name=NULL, $id=NULL, $select_route, 'route_id', 'desc', NULL, NULL);
	$data['all_nodes'] = $this->general->get_list_search_view('car_node', NULL, NULL);	
	
    $this->load->view('car/node_save', $data);
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
		if ( !empty($id) && ! $this->authorization->is_permitted('car_delete_node'))
		{
		  $this->session->set_flashdata('parmission', 'You have no permission to delete node');
		  redirect(base_url().'car/nodes');
		}
		
		if (!empty($id))
		{
			if (!$this->general->get_all_table_info_by_id_custom("car_pickup_point", "node_id", 'node_id', $id)) {
				$this->general->delete_from_table('car_node', 'node_id', $id);
				$this->session->set_flashdata('message_success', 'Your data successfully deleted');
		  		redirect(base_url().'car/nodes');
			}else{
				$this->session->set_flashdata('parmission', 'You have to delete pickp point frist that assigned this node than delete it');
		  		redirect(base_url().'car/nodes');
			}
			
		}
		else
		{
			$this->session->set_flashdata('parmission', 'You have to selecte id for delete route');
		  	redirect(base_url().'car/nodes');
		}
  }

/*  public function get_node()
	{
		
		$table_name=$this->input->post('table_name');
		$where_field=$this->input->post('field_name');
		$where_field_value=$this->input->post('route_id');
		
		//echo "hi----";
		echo "table_name=".$table_name.",field_name=".$where_field.",where_field_value=".$where_field_value;
		
		$select = "node_id, node_name_en";
		$nodes = $this->general->get_list_view($table_name, $where_field, $where_field_value, $select, NULL, NULL, NULL, NULL);

	  	if (count($nodes)>0)
	  	{
		  echo "<option value=''>Select Node </option>";
		  foreach ($nodes as $node)
		  {
			  $val ="";			  
			  $val.="<option value='".$node->node_id."'>".$node->node_name_en."</option>";
			  echo $val;  
		  }
	  	}
	  	else
	  	{
			echo "<option value=''>Not Found</option>";  
	  	}
	}*/
	
}// END Class

?>