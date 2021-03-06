<?php
/*
 * Manage_users Controller
 */
class Manage_users extends CI_Controller {

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
    $this->load->model(array('account/account_model', 'account/account_details_model', 'account/acl_permission_model', 'account/acl_role_model', 'account/rel_account_permission_model', 'account/rel_account_role_model', 'account/rel_role_permission_model'));
    //$this->load->language(array('account/account_settings', 'account/account_profile', 'account/sign_up', 'account/account_password'));
	
	$language = $this->session->userdata('site_lang');
		if(!$language)
		{
		$this->lang->load('general', 'english');
		$this->lang->load('mainmenu', 'english');
		$this->lang->load('account/manage_users', 'english');
		$this->lang->load('account/account_settings', 'english');
		$this->lang->load('account/account_profile', 'english');
		$this->lang->load('account/sign_up', 'english');
		$this->lang->load('account/account_password', 'english');
		}
		else
		{
		$this->lang->load('general', $language);
		$this->lang->load('mainmenu', $language);
		$this->lang->load('account/manage_users', $language);
		$this->lang->load('account/account_settings', $language);
		$this->lang->load('account/account_profile', $language);
		$this->lang->load('account/sign_up', $language);
		$this->lang->load('account/account_password', $language);	
		}
  }

  /**
   * Manage Users
   */
  function index()
  {
    // Enable SSL?
    maintain_ssl($this->config->item("ssl_enabled"));

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_users'));
    }

    // Redirect unauthorized users to account profile page
    if ( ! $this->authorization->is_permitted('retrieve_users'))
    {
      redirect('account/account_profile');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
    $data['all_roles'] = $this->acl_role_model->get();

    // Setup form validation
      $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
      $this->form_validation->set_rules(
        array(
          array(
            'field' => 'user_name',
            'label' => 'Username',
            'rules' => 'trim'),
          array(
            'field' => 'role_id', 
            'label' => 'Role Type', 
            'rules' => 'trim'), 
        array(
            'field' => 'email', 
            'label' => 'Email', 
            'rules' => 'trim|valid_email'), 
          array(
            'field' => 'fullname', 
            'label' => 'Name', 
            'rules' => 'trim')
        ));
    // Search Peramiters
    $this->form_validation->run();
    //$whereClause = array();
    if ($this->input->post('search_submit'))    
    {
      $username = array();
      if(trim($this->input->post('user_name'))):
      $username = array('username'=>$this->input->post('user_name'));
      endif;
        
      $user_type = array();
      if(trim($this->input->post('role_id'))):
      $user_type = array('role_id'=>$this->input->post('role_id'));
      endif;
      
      $email = array();
      if(trim($this->input->post('email'))):
      $email = array('email'=>$this->input->post('email'));
      endif;
      $whereClause = array_merge($username, $user_type, $email);    
      $this->session->set_userdata('userClause',$whereClause);
      
      $fullname =NULL; 
      if(trim($this->input->post('fullname'))):
      $fullname = $this->input->post('fullname');   
      endif;    
      $this->session->set_userdata('fullname',$fullname);
      
    }
    elseif($this->session->userdata('userClause'))
    { 
      $whereClause = $this->session->userdata('userClause');
    }
    else
    {
      $whereClause = NULL;  
    }
    if (!$fullname = $this->session->userdata('fullname'))
    {
      $fullname=NULL; 
    }

    $data['total_donor'] =  $this->account_model->no_of_users($fullname, $whereClause);

    // Paginations
    $this->load->library('pagination');

    $config = array();
    $config['base_url'] = base_url().'account/manage_users/index/';

    $config['total_rows'] = $this->account_model->no_of_users($fullname, $whereClause);
    //exit;
    $config['num_links'] = 3;
    $config['per_page'] = $this->config->item("pagination_perpage");
    $config['uri_segment'] = 4;

    $config['full_tag_open'] = '<nav class="pagination pagination-small"><ul class="">';
    $config['full_tag_close'] = '</ul></nav><!--pagination-->';

    $config['first_link'] = '&laquo; First';
    $config['first_tag_open'] = '<li class="prev page">';
    $config['first_tag_close'] = '</li>';

    $config['last_link'] = 'Last &raquo;';
    $config['last_tag_open'] = '<li class="next page">';
    $config['last_tag_close'] = '</li>';

    $config['next_link'] = 'Next &rarr;';
    $config['next_tag_open'] = '<li class="next page">';
    $config['next_tag_close'] = '</li>';

    $config['prev_link'] = '&larr; Previous';
    $config['prev_tag_open'] = '<li class="prev page">';
    $config['prev_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="active"><a>';
    $config['cur_tag_close'] = '</a></li>';

    $config['num_tag_open'] = '<li class="page">';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    $page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;


      // Get all user information
      $all_accounts = $this->account_model->get($fullname, $whereClause, $page, $config['per_page']);

      // Compile an array for the view to use
      $data['all_accounts'] = array();
      foreach ( $all_accounts as $acc )
      {
        $current_user = array();
        $current_user['id'] = $acc->id;
        $current_user['username'] = $acc->username;
        $current_user['email'] = $acc->email;
        $current_user['fullname'] = ucwords($acc->fullname);
        $current_user['is_banned'] = isset( $acc->suspendedon );
        $current_user['role'] = $this->acl_role_model->get_by_account_id($acc->id);

        $data['all_accounts'][] = $current_user;
      }
    $data['active'] = 'admin';
    $data["links"] = $this->pagination->create_links();
    $this->load->view('account/manage_users', $data);
  }

  /**
   * Create/Update Users
   */
  function save($id=null)
  {
    // Keep track if this is a new user
    $is_new = empty($id);

    // Enable SSL?
    maintain_ssl($this->config->item("ssl_enabled"));

    // Redirect unauthenticated users to signin page
    if ( ! $this->authentication->is_signed_in())
    {
      redirect('account/sign_in/?continue='.urlencode(base_url().'account/manage_users'));
    }

    // Check if they are allowed to Update Users
    if ( ! $this->authorization->is_permitted('update_users') && ! empty($id) )
    {
      redirect('account/manage_users');
    }

    // Check if they are allowed to Create Users
    if ( ! $this->authorization->is_permitted('create_users') && empty($id) )
    {
      redirect('account/manage_users');
    }

    // Retrieve sign in user
    $data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));

    // Get all the roles
    $data['roles'] = $this->acl_role_model->get();

    // Set action type (create or update user)
    $data['action'] = 'create';

    // Get the account to update
    if( ! $is_new )
    {
      $data['update_account'] = $this->account_model->get_by_id($id);
      $data['update_account_details'] = $this->account_details_model->get_by_account_id($id);
      $data['update_account_roles'] = $this->acl_role_model->get_by_account_id($id);
      $data['action'] = 'update';
    }

    // Setup form validation
    $this->form_validation->set_error_delimiters('<div class="field_error">', '</div>');
    $this->form_validation->set_rules(
      array(
        array(
          'field' => 'users_username',
          'label' => 'lang:profile_username',
          'rules' => 'trim|required|alpha_dash|min_length[2]|max_length[24]'),
        array(
          'field' => 'users_email', 
          'label' => 'lang:settings_email', 
          'rules' => 'trim|required|valid_email|max_length[160]'),
        array(
          'field' => 'mobile', 
          'label' => 'Mobile', 
          'rules' => 'trim|max_length[11]|min_length[11]'),
        array(
          'field' => 'users_fullname', 
          'label' => 'lang:settings_fullname', 
          'rules' => 'trim|max_length[160]'), 
        array(
          'field' => 'users_firstname', 
          'label' => 'lang:settings_firstname', 
          'rules' => 'trim|max_length[80]'), 
        array(
          'field' => 'users_lastname', 
          'label' => 'lang:settings_lastname', 
          'rules' => 'trim|max_length[80]'),
        array(
          'field' => 'users_new_password', 
          'label' => 'lang:password_new_password', 
          'rules' => 'trim|'.($is_new?'required':'optional').'|min_length[6]'),
        array(
          'field' => 'users_retype_new_password', 
          'label' => 'lang:password_retype_new_password', 
          'rules' => 'trim|'.($is_new?'required':'optional').'|matches[users_new_password]')
      ));

    // Run form validation
    if ($this->form_validation->run())
    {

      $email_taken = $this->email_check($this->input->post('users_email', TRUE));
      $username_taken = $this->username_check($this->input->post('users_username'));

      // If user is changing email and new email is already taken OR
      // if this is a new user, just check if it's been taken already.
      if ( (! empty($id) && strtolower($this->input->post('users_email', TRUE)) != strtolower($data['update_account']->email) && $email_taken) || (empty($id) && $email_taken) )
      {
        $data['users_email_error'] = lang('settings_email_exist');
      }
      // Check if user name is taken
      elseif ( (! empty($id) && strtolower($this->input->post('users_username', TRUE)) != strtolower($data['update_account']->username) && $username_taken) || (empty($id) && $username_taken) )
      {
        $data['users_username_error'] = lang('sign_up_username_taken');
      }
      else
      {

        // Create a new user
        if( empty($id) ) {
          $id = $this->account_model->create(
            $this->input->post('users_username', TRUE), 
            $this->input->post('users_email', TRUE), 
            $this->input->post('users_new_password', TRUE),
            $this->input->post('mobile', TRUE));
        }
        // Update existing user information
        else 
        {
          // Update account username
          $this->account_model->update_username($id, 
            $this->input->post('users_username', TRUE) ? $this->input->post('users_username', TRUE) : NULL);

          // Update account email
          $this->account_model->update_email($id, $this->input->post('users_email', TRUE) ? $this->input->post('users_email', TRUE) : NULL);

          // Update account Phone
          $this->account_model->update_phone($id, $this->input->post('mobile', TRUE) ? $this->input->post('mobile', TRUE) : NULL);

          // Update password
          $pass = $this->input->post('users_new_password', TRUE) ? $this->input->post('users_new_password', TRUE) : NULL;
          if( ! empty($pass) )
          {
            $this->account_model->update_password($id, $pass);
          }

          // Check if the user should be suspended
          if( $this->authorization->is_permitted('ban_users') ) 
          {
            if( $this->input->post('manage_user_ban', true) )
            {
              $this->account_model->update_suspended_datetime($id);
            }
            elseif( $this->input->post('manage_user_unban', true) )
            {
              $this->account_model->remove_suspended_datetime($id);
            }
          }
        }



        $lastname = NULL;
        $lastname_array = $this->input->post('users_lastname', TRUE);
        $lastname_array = explode(" ",$lastname_array);
        
        if (count($lastname_array)>0) {
          for ($i=0; $i < count($lastname_array); $i++) { 
            $lastname .= $lastname_array[$i]." ";
          }         
        }    

        $fullname = ($this->input->post('users_firstname', TRUE)." ".$lastname)? $this->input->post('users_firstname', TRUE)." ".$lastname:NULL;


        // Update account details
        $attributes = array();
        $attributes['fullname'] = $fullname;
        $attributes['firstname'] = $this->input->post('users_firstname', TRUE) ? $this->input->post('users_firstname', TRUE) : NULL;
        $attributes['lastname'] = $lastname;
        $this->account_details_model->update($id, $attributes);

        // Apply roles
        $roles = array();
        foreach($data['roles'] as $r)
        {
          if( $this->input->post("account_role_{$r->id}", TRUE) )
          {
            $roles[] = $r->id;
          }
        }
        $this->rel_account_role_model->delete_update_batch($id, $roles);

        redirect("account/manage_users");
      }
    }

    // Load manage users view
    $this->load->view('account/manage_users_save', $data);
  }

  /**
   * Filter the user list by permission or role.
   *
   * @access public
   * @param string $type (permission, role)
   * @param int $id (permission_id, role_id)
   * @return void
   */
  function filter($type=null,$id=null)
  {
    $this->index();
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

/* End of file manage_users.php */
/* Location: ./application/account/controllers/manage_users.php */
