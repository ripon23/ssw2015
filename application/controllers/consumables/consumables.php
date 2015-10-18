<?php
class Consumables extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('consumables_model','account/account_model', 'ref_site_model','ref_location_model' ));	
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
	

		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
		if($this->authorization->is_permitted('add_consumables'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar consumables list';	
			
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "consumables/consumables/";
			$config["total_rows"] = $this->consumables_model->all_consumables_count();
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			
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
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			//$config['anchor_class'] = 'follow_link';
			
			$choice = $config['total_rows']/$config['per_page'];
			$config['num_links'] = round($choice);
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_consumables'] = $this->consumables_model->get_all_consumables_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['consumable_categorys'] = $this->consumables_model->get_all_consumable_categorys();
			$data['site'] = $this->ref_site_model->get_all_site();
			
			$this->load->view('consumables/view_view_consumable', isset($data) ? $data : NULL);
			
			
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}
		}
		else
		{
		redirect('account/sign_in');
		}
					
	}	
		
	
	
	public function search_view_consumables()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('add_consumables'))
			{
				
				// assign posted valued
				$data['sconsumable_id']    		= $this->input->post('sconsumable_id');
				$data['sconsumable_name']   	= $this->input->post('sconsumable_name');
				$data['sconsumable_stock']   	= $this->input->post('sconsumable_stock');
				$data['sconsumable_category']  	= $this->input->post('sconsumable_category');
				$data['sconsumable_site']  		= $this->input->post('sconsumable_site');
				
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_consumables";	
		
				$sconsumable_id=$this->input->post("sconsumable_id");
				
				$query_string=$query_string." WHERE (gramcar_consumables.consumable_id Like '%')";
				//$query_string=$query_string." WHERE (gramcar_registration.status = 1)";
			
				if($this->input->post("sconsumable_id"))	
				{
					$sconsumable_id=$this->input->post("sconsumable_id"); 
					$query_string=$query_string." AND (gramcar_consumables.consumable_id = '$sconsumable_id')";
				}
				
				if($this->input->post("sconsumable_name"))	
				{
					$sconsumable_name=$this->input->post("sconsumable_name");	
					$query_string=$query_string." AND(gramcar_consumables.consumable_name LIKE '%$sconsumable_name%')";
				}
				
				if($this->input->post("sconsumable_stock"))	
				{
					$sconsumable_stock=$this->input->post("sconsumable_stock");	
					$query_string=$query_string." AND(gramcar_consumables.consumable_stock LIKE '%$sconsumable_stock%')";
				}
				
				if($this->input->post("sconsumable_category"))	
				{
					$sconsumable_category=$this->input->post("sconsumable_category");	
					$query_string=$query_string." AND(gramcar_consumables.consumable_category_id = '$sconsumable_category')";
				}
				
				if($this->input->post("sconsumable_site"))	
				{
					$sconsumable_site=$this->input->post("sconsumable_site");	
					$query_string=$query_string." AND(gramcar_consumables.consumable_site_id = '$sconsumable_site')";
				}
				
				
			
				$query_string=$query_string." ORDER BY gramcar_consumables.update_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->consumables_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar Registration List';	
			
			
			//echo $query_string;
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "consumables/consumables/search_view_consumables/";
			$config["total_rows"] = $this->consumables_model->all_consumables_count_query_string($searchterm);
			$config["per_page"] = $this->config->item("pagination_perpage");
			$config["uri_segment"] = 4;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			
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
			
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			//$config['anchor_class'] = 'follow_link';
			
			$choice = $config['total_rows']/$config['per_page'];
			$config['num_links'] = round($choice);
			
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4))? $this->uri->segment(4) : 0;		
			$data['all_consumables'] = $this->consumables_model->get_all_consumables_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			$data['consumable_categorys'] = $this->consumables_model->get_all_consumable_categorys();
			$data['site'] = $this->ref_site_model->get_all_site();
			
			$this->load->view('consumables/view_view_consumable', isset($data) ? $data : NULL);
				
			
			
			}
			else
			{
			redirect('');  // if not permitted "view_registration" redirect to home page
			}	
		
		
		}
		else
		{
			redirect('account/sign_in');
		}
		
	}
	
	
	
	public function add_consumables()  
	{

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			
			if($this->authorization->is_permitted('add_consumables'))
			{
			
				
				$data['site'] = $this->ref_site_model->get_all_site();
				$data['consumable_categorys'] = $this->consumables_model->get_all_consumable_categorys();

				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');				

				$this->form_validation->set_rules('consumable_name', 'Consumable name', 'required');
				$this->form_validation->set_rules('consumable_category', 'Consumable category', 'required');
				$this->form_validation->set_rules('consumable_site', 'Consumable site', 'required');
				$this->form_validation->set_rules('consumable_stock', 'Consumable stock', 'required');							
				$this->form_validation->set_rules('consumable_note', 'Consumable note');
				
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar add consumables';														
					$this->load->view('consumables/view_add_consumable', isset($data) ? $data : NULL);
				}
				else
				{
					
					$consumable_name=$this->input->post('consumable_name');			$consumable_name  = empty($consumable_name) ? NULL : $consumable_name;
					$consumable_category=$this->input->post('consumable_category');	$consumable_category  = empty($consumable_category) ? NULL : $consumable_category;	
					$consumable_site=$this->input->post('consumable_site');			$consumable_site  = empty($consumable_site) ? NULL : $consumable_site;
					$consumable_stock=$this->input->post('consumable_stock');		$consumable_stock  = empty($consumable_stock) ? NULL : $consumable_stock;
					$consumable_note=$this->input->post('consumable_note');			$consumable_note  = empty($consumable_note) ? NULL : $consumable_note;					

					
					
					$consumable_data_table=array(
						'consumable_name'=>$consumable_name,
						'consumable_category_id'=>$consumable_category,
						'consumable_site_id'=>$consumable_site,
						'consumable_stock'=>$consumable_stock,						
						'consumable_note'=>$consumable_note,
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);				
				
					
					$success_or_fail=$this->consumables_model->save_consumables($consumable_data_table);
					
					
					
					if($success_or_fail)
					$data['success_msg']="Consumable save successfull";
					else
					$data['success_msg']="Consumable save unsuccessfull! Please try again";
					
					$this->load->view('consumables/view_add_consumable', isset($data) ? $data : NULL);
				}
				
									
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
	
	
	
	public function view_single_consumable($consumable_id)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('add_consumables'))
			{
			//echo $reg_no;
			
			$data['title'] = 'GramCar consumables:';
			
			$data['single_consumable'] = $this->consumables_model->get_all_consumable_info_by_id($consumable_id);			
			$this->load->view('consumables/view_single_consumable', isset($data) ? $data : NULL);
			
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
	
	
	
	public function edit_single_consumable($consumable_id)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('add_consumables'))
			{
			
				
				$data['site'] = $this->ref_site_model->get_all_site();
				$data['consumable_categorys'] = $this->consumables_model->get_all_consumable_categorys();

				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');				

				$this->form_validation->set_rules('consumable_name', 'Consumable name', 'required');
				$this->form_validation->set_rules('consumable_category', 'Consumable category', 'required');
				$this->form_validation->set_rules('consumable_site', 'Consumable site', 'required');
				$this->form_validation->set_rules('consumable_per_price', 'Per consumable price');
				$this->form_validation->set_rules('consumable_stock', 'Consumable stock', 'required');							
				$this->form_validation->set_rules('consumable_note', 'Consumable note');
				
						
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar edit consumables';												
					$data['single_consumable'] = $this->consumables_model->get_all_consumable_info_by_id($consumable_id);
					$this->load->view('consumables/view_edit_single_consumable', isset($data) ? $data : NULL);
				}
				else
				{
					
					$consumable_name=$this->input->post('consumable_name');			$consumable_name  = empty($consumable_name) ? NULL : $consumable_name;
					$consumable_category=$this->input->post('consumable_category');	$consumable_category  = empty($consumable_category) ? NULL : $consumable_category;
					$consumable_site=$this->input->post('consumable_site');			$consumable_site  = empty($consumable_site) ? NULL : $consumable_site;
					$consumable_per_price=$this->input->post('consumable_per_price');$consumable_per_price  = empty($consumable_per_price) ? NULL : $consumable_per_price;
					$consumable_stock=$this->input->post('consumable_stock');		$consumable_stock  = empty($consumable_stock) ? NULL : $consumable_stock;
					$consumable_note=$this->input->post('consumable_note');			$consumable_note  = empty($consumable_note) ? NULL : $consumable_note;					

					
					
					$consumable_data_table=array(
						'consumable_name'=>$consumable_name,
						'consumable_category_id'=>$consumable_category,
						'consumable_site_id'=>$consumable_site,
						'consumable_per_price'=>$consumable_per_price,
						'consumable_stock'=>$consumable_stock,						
						'consumable_note'=>$consumable_note,
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now())						
						);				
				
					
					$success_or_fail=$this->consumables_model->update_consumables($consumable_data_table,$consumable_id);
					
					
					
					if($success_or_fail)
					$data['success_msg']="Consumable update successfull";
					else
					$data['success_msg']="Consumable update unsuccessfull! Please try again";
					
					$data['single_consumable'] = $this->consumables_model->get_all_consumable_info_by_id($consumable_id);
					$this->load->view('consumables/view_edit_single_consumable', isset($data) ? $data : NULL);
				}								

			
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
	public function delete_consumable()
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('delete_consumables'))
			{
			$success_or_fail=$this->consumables_model->delete_consumable($this->input->post('consumable_id'));
				if($success_or_fail)
				{
				echo "Successfull";								
				}
				else
				{
				echo "Unsuccessfull";
				}
			}
			else
			{
			redirect('');  // if not permitted "delete_registration" redirect to home page
			}
		}
		else
		{
			redirect('account/sign_in');
		}	
	}
	
	
	
	
	
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>