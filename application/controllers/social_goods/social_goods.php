<?php
class Social_goods extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation','cart'));
		$this->load->model(array('registration_model','social_goods_model','account/account_model', 'ref_site_model','ref_location_model', 'ref_services_model' ));	
		$GLOBALS["overall_health_status"]=0;
		
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
			redirect('social_goods/social_goods/view_product_list');
			
		}
		else
		{
		redirect('account/sign_in');
		}
		
	}
	
		
	
	public function add_product()
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('enter_product'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('product_name', 'Product name', 'required');
			$this->form_validation->set_rules('product_name_bn', 'Product name bangla', 'required');
			$this->form_validation->set_rules('product_description', 'Description','required');
			$this->form_validation->set_rules('product_price', 'Price','required');
			$this->form_validation->set_rules('product_categories', 'Product categories','required');
			$this->form_validation->set_rules('product_type', 'Product type','required');
			
			
			$data['error'] ='';			
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_weight_unit'] = $this->social_goods_model->get_all_weight_unit();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar Social Goods';				
				$this->load->view('social_goods/view_add_product', isset($data) ? $data : NULL);				
				}
				else
				{
				/******************** Insert form data ************************/
				$product_description=$this->input->post('product_description');	$product_description  = empty($product_description) ? NULL : $product_description;
				$product_weight=$this->input->post('product_weight');			$product_weight  = empty($product_weight) ? NULL : $product_weight;
				$product_weight_unit=$this->input->post('product_weight_unit');	$product_weight_unit  = empty($product_weight_unit) ? NULL : $product_weight_unit;
				$product_size=$this->input->post('product_size');				$product_size  = empty($product_size) ? NULL : $product_size;
				$product_color=$this->input->post('product_color');				$product_color  = empty($product_color) ? NULL : $product_color;
				$product_brand=$this->input->post('product_brand');				$product_brand  = empty($product_brand) ? NULL : $product_brand;

				$product_data=array(
						'product_name'=>$this->input->post('product_name'),
						'product_name_bn'=>$this->input->post('product_name_bn'),
						'product_description'=>$product_description,
						'product_category_id'=>$this->input->post('product_categories'),
						'product_type_id'=>$this->input->post('product_type'),
						'product_price'=>$this->input->post('product_price'),
						'product_weight'=>$product_weight,
						'product_weight_unit'=>$product_weight_unit,
						'product_size'=>$product_size,
						'product_color'=>$product_color,
						'product_brand'=>$product_brand,						
						'last_edit_data'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),						
						'status'=>1
						);
				
				$product_id=$this->social_goods_model->save_product_info($product_data);	// Save the info and get the product id
				
				/******************** Insert form data end ************************/
				
				
				/********** Image upload	********************/
				$config['upload_path'] = RES_DIR."/img/products/";
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= '100';
				$config['overwrite'] = 'TRUE';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['file_name']  = $product_id;
			
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('product_image'))
					{
						
						$data['error'] = $this->upload->display_errors();								
					}
					else
					{
						$image_data = $this->upload->data();												
						$original_image= $image_data['raw_name'].$image_data['file_ext'];
						
						$config2 = array(
						'source_image' => $image_data['full_path'],
						'new_image' => RES_DIR."/img/products/thumbnils",
						'image_name'=> $product_id,
						'maintain_ratio' => true,
						'overwrite' => true,
						'width' => 80,
						'height' => 80
						);
						$this->load->library('image_lib');
						$this->image_lib->initialize($config2);
						if ( !$this->image_lib->resize()){
							$data['error'] = $this->image_lib->display_errors('', '');
              				}						
						
						$config3 = array(
						'source_image' => $image_data['full_path'],
						'new_image' => RES_DIR."/img/products/medium",
						'image_name'=> $product_id,
						'maintain_ratio' => true,
						'overwrite' => true,
						'width' => 164,
						'height' => 200
						);
						$this->load->library('image_lib');
						$this->image_lib->initialize($config3);
						if ( !$this->image_lib->resize()){
							$data['error'] = $this->image_lib->display_errors('', '');
              				}
						
						
						$data['upload_data'] = $this->upload->data();
						
					}							
				
				$product_data_with_image=array(						
						'thumbnil_image'=>$original_image,
						'original_image'=>$original_image						
						);				
		
				$success_or_fail=$this->social_goods_model->update_product_info($product_data_with_image,$product_id);	// update the product info with image name							
				
				
				if($product_id && $success_or_fail)
				$data['success_msg']="Save Successfull for ".$this->input->post('product_name');
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";								
												
				
				$data['title'] = 'GramCar Social Goods';
				$this->load->view('social_goods/view_add_product', isset($data) ? $data : NULL);								
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
	
	
	public function view_product_list()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();						
									
				
				$this->load->library('pagination');
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "social_goods/social_goods/view_product_list/";
				$config["total_rows"] = $this->social_goods_model->get_all_product_count();
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
				$data['all_product'] = $this->social_goods_model->get_all_product_by_limit($config["per_page"], $page);	
				$data['links'] = $this->pagination->create_links();
				$data['page']=$page;
				
			$this->load->view('social_goods/view_product_list', isset($data) ? $data : NULL);		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	}
	
	public function product_management()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();						
									
				
				$this->load->library('pagination');
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "social_goods/social_goods/product_management/";
				$config["total_rows"] = $this->social_goods_model->get_all_product_count_with_inactive();
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
				$data['all_product'] = $this->social_goods_model->get_all_product_by_limit_with_inactive($config["per_page"], $page);	
				$data['links'] = $this->pagination->create_links();
				$data['page']=$page;
				
			$this->load->view('social_goods/view_product_management', isset($data) ? $data : NULL);		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	}
	
	
	public function show_cart()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['title'] = 'GramCar: Social Goods cart';
			$this->load->view('social_goods/view_cart', isset($data) ? $data : NULL);
		
		}
		else
		{
		redirect('account/sign_in');
		}				
	
	}
	
	public function update_cart()
	{	
    $this->cart->update($_POST);  
    redirect('social_goods/social_goods/show_cart');		
	}
	
	public function clear_cart()
	{
		if ($this->authentication->is_signed_in())
		{
    	$this->cart->destroy();
    	redirect('social_goods/social_goods/show_cart');		
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	public function place_order()
	{	
		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));												
		$data['title'] = 'GramCar: Social Goods place order';
		//$data['product_info'] = $this->social_goods_model->get_product_info_by_id($product_id);
		$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
		//$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(5);	// 5= Internet Service/ Social Goods
		$this->load->library('pagination');
		//pagination
		$config = array();
		$config["base_url"] = base_url() . "social_goods/social_goods/place_order/";
		$config["total_rows"] = $this->social_goods_model->get_all_social_goods_registration_count();
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
		$data['all_social_goods'] = $this->social_goods_model->get_all_social_goods_registration_by_limit($config["per_page"], $page);				
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;	
		
		
    	$this->load->view('social_goods/view_place_order', isset($data) ? $data : NULL);
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	
	public function order_list()
	{	
		if ($this->authentication->is_signed_in())
		{
		$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));												
		$data['title'] = 'GramCar: Social goods order list';
		//$data['product_info'] = $this->social_goods_model->get_product_info_by_id($product_id);
		$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
		//$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(5);	// 5= Internet Service/ Social Goods
		$this->load->library('pagination');
		//pagination
		$config = array();
		$config["base_url"] = base_url() . "social_goods/social_goods/order_list/";
		$config["total_rows"] = $this->social_goods_model->get_all_social_goods_order_count();
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
		$data['all_social_goods_order'] = $this->social_goods_model->get_all_social_goods_order_by_limit($config["per_page"], $page);				
		$data["links"] = $this->pagination->create_links();
		$data["page"]=$page;	
		
		
    	$this->load->view('social_goods/view_order_list', isset($data) ? $data : NULL);
		}
		else
		{
		redirect('account/sign_in');
		}
	}
	
	public function order_list_search()
	{
	
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('entry_order'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
				$data['sservices_point']     	= $this->input->post('sservices_point');
				$data['sservices_status']     	= $this->input->post('sservices_status');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT gramcar_registration_for_services.services_point_id,
       gramcar_registration_for_services.services_id,
       gramcar_registration_for_services.services_date,
       gramcar_registration_for_services.services_status,
       gramcar_social_goods_order.*,
       gramcar_social_goods_order.registration_no
  FROM    gramcar_social_goods_order gramcar_social_goods_order
       INNER JOIN
          gramcar_registration_for_services gramcar_registration_for_services
       ON (gramcar_social_goods_order.reg_services_id =
              gramcar_registration_for_services.reg_for_service_id)";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_social_goods_order.order_status < 4)";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_social_goods_order.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("sservices_point"))	
				{
					$sservices_point=$this->input->post("sservices_point");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_point_id = $sservices_point)";
				}
															
				
				if($this->input->post("sservices_status"))	
				{
					if($this->input->post("sservices_status")=="zero")
					$sservices_status=0;	
					else
					$sservices_status=$this->input->post("sservices_status");
					
					$query_string=$query_string." AND( gramcar_social_goods_order.order_status  = $sservices_status)";				
				}								
				
				
				if($this->input->post("sdate1"))
				{
				$sdate1=$this->input->post("sdate1"); $sdate2=$this->input->post("sdate2");
	
				if(($sdate1!='')&& ($sdate2==''))
				$sdate2=$sdate1;
				
				if(strlen($sdate1)<12) 
				$sdate1=$sdate1." 00:00:00";	
				
				if(strlen($sdate2)<12) 
				$sdate2=$sdate2." 23:59:59";
			
				$query_string= $query_string."  AND gramcar_social_goods_order.order_time BETWEEN '".$sdate1."' AND '".$sdate2."'";
				}
				
				
				$query_string=$query_string." ORDER BY gramcar_social_goods_order.order_status ASC, gramcar_social_goods_order.order_time DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar Social Goods';
			
			//echo $searchterm;
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(4);	// 5= internet and information services
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "social_goods/social_goods/order_list_search/";
			$config["total_rows"] = $this->social_goods_model->all_social_goods_count_query_string($searchterm);
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
			$data['all_social_goods_order'] = $this->social_goods_model->get_all_social_goods_by_limit_querystring($searchterm,$config["per_page"], $page);				
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
									
			$this->load->view('social_goods/view_order_list', isset($data) ? $data : NULL);										
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
	
	
	public function social_goods_order_details($order_id)
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('entry_order'))
			{
			$data['registration_info'] = $this->social_goods_model->get_registration_info_by_order_id($order_id);	
			$data['order_info']=$this->social_goods_model->get_order_info_by_id($order_id);
			$data['order_info_details']=$this->social_goods_model->get_order_details_info_by_id($order_id);
			
			$this->load->view('social_goods/view_single_order', isset($data) ? $data : NULL);	
			}
			else
			{
			redirect('');  // if not permitted "entry_order" redirect to home page
			}
	
		}
		else
		{
			redirect('account/sign_in');
		}	
	}
	
	
	public function check_out($reg_services_id)
	{
		if($this->authentication->is_signed_in())
		{			
			if($this->authorization->is_permitted('entry_order'))
			{
				$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));		
				$data['registration_info'] = $this->social_goods_model->get_registration_info_by_reg_services_id($reg_services_id);
				
				
				if($this->input->post("registration_no"))
				{
					//var_dump($this->cart->contents());
					if($this->social_goods_model->check_reg_services_id_is_exits($reg_services_id))
					{
						$order_data=array(
							'registration_no'=>$this->input->post("registration_no"),
							'reg_services_id'=>$reg_services_id,
							'order_time'=>mdate('%Y-%m-%d %H:%i:%s', now()),
							'total_price'=>$this->cart->total(),
							'order_status'=>0,
							'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
							'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
							'order_note'=>$this->input->post("order_note")	
							);
						
						$order_id=$this->social_goods_model->insert_order_info($order_data);
						
						if($order_id)
						{
							foreach ($this->cart->contents() as $items): 					
							$cart_data=array(
								'order_id'=>$order_id,
								'product_id'=>$items['id'],
								'product_qty'=>$items['qty'],
								'product_price'=>$items['price'],
								'subtotal'=>$items['subtotal']
								);
							$success_or_fail=$this->social_goods_model->insert_order_cart_info($cart_data,$order_id);					
							endforeach;
							$data['success_msg']="Order successfull for ".$this->input->post('registration_no');
							$this->cart->destroy();
					    	//redirect('social_goods/social_goods/show_cart');
							
							/******************** Update the status of the services. Mark it as Taken *******************************/	
							$update_services_status=array(
									'services_status'=>2,						
									'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
									'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
									);
							
							$this->social_goods_model->update_social_goods_services_status($update_services_status,$reg_services_id); 
						}
						else
						{
							$data['error_msg']="Save Unsuccessfull! Please try again";	
						}
					}// End if check_reg_services_id_is_exits
					else
					{
					$data['error_msg']="There is already a order place using this services id (".$reg_services_id.")";
					}
				} // End $this->input->post("registration_no")
				
				
				$this->load->view('social_goods/view_checkout', isset($data) ? $data : NULL);
				
			}
			else
			{
			redirect('');  // if not permitted "entry_order" redirect to home page
			}
		}
		else
		{
			redirect('account/sign_in');
		}	
	}
	
	
	public function search_social_goods_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('entry_order'))
			{
				
				// assign posted valued
				$data['sregistration_no']    	= $this->input->post('sregistration_no');
				$data['sservices_point']     	= $this->input->post('sservices_point');
				$data['spackage']     			= $this->input->post('spackage');
				$data['sservices_status']     	= $this->input->post('sservices_status');
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_registration_for_services";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				//$query_string=$query_string." WHERE (gramcar_registration.registration_no Like '%')";
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) AND (gramcar_registration_for_services.services_id=4)";
			
				if($this->input->post("sregistration_no"))	
				{
					$sregistration_no=$this->input->post("sregistration_no"); 
					$query_string=$query_string." AND (gramcar_registration_for_services.registration_no = '$sregistration_no')";
				}
				
				if($this->input->post("sservices_point"))	
				{
					$sservices_point=$this->input->post("sservices_point");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_point_id = $sservices_point)";
				}
				
				if($this->input->post("spackage"))	
				{
					$spackage=$this->input->post("spackage");	
					$query_string=$query_string." AND(gramcar_registration_for_services.services_package_id = $spackage)";				
				}								
				
				if($this->input->post("sservices_status"))	
				{
					if($this->input->post("sservices_status")=="zero")
					$sservices_status=0;	
					else
					$sservices_status=$this->input->post("sservices_status");
					
					$query_string=$query_string." AND(gramcar_registration_for_services.services_status = $sservices_status)";				
				}								
				
				
				if($this->input->post("sdate1"))
				{
				$sdate1=$this->input->post("sdate1"); $sdate2=$this->input->post("sdate2");
	
				if(($sdate1!='')&& ($sdate2==''))
				$sdate2=$sdate1;
				
				if(strlen($sdate1)<12) 
				$sdate1=$sdate1." 00:00:00";	
				
				if(strlen($sdate2)<12) 
				$sdate2=$sdate2." 23:59:59";
			
				$query_string= $query_string."  AND services_date BETWEEN '".$sdate1."' AND '".$sdate2."'";
				}
				
				
				$query_string=$query_string." ORDER BY gramcar_registration_for_services.services_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				$data['title'] = 'GramCar Social Goods';
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(4);	// 5= internet and information services
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "social_goods/social_goods/search_social_goods_list/";
			$config["total_rows"] = $this->social_goods_model->all_social_goods_count_query_string($searchterm);
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
			$data['all_social_goods'] = $this->social_goods_model->get_all_social_goods_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('social_goods/view_place_order', isset($data) ? $data : NULL);
				
			
			
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
	
	
	/***** Ajax function **********/
	public function add_to_cart()
	{
	$product_id=$this->input->post('product_id');
	$product_info= $this->social_goods_model->get_product_info_by_id($product_id);	
	$data = array(
               'id'      => $product_id,
               'qty'     => 1,
               'price'   => $product_info->product_price,
               'name'    => $product_info->product_name               
            );

	$this->cart->insert($data); 
	echo $this->cart->total_items();	
	//$this->cart->destroy();
	//$data['total_item'] = $this->cart->total_items();	
	}
	
	public function view_product_grid()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();						
									
				
				$this->load->library('pagination');
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "social_goods/social_goods/view_product_grid/";
				$config["total_rows"] = $this->social_goods_model->get_all_product_count();
				$config["per_page"] = $this->config->item("pagination_perpage")-2;
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
				$data['all_product'] = $this->social_goods_model->get_all_product_by_limit($config["per_page"], $page);	
				$data['links'] = $this->pagination->create_links();
				$data['page']=$page;
				
			$this->load->view('social_goods/view_product_grid', isset($data) ? $data : NULL);		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	}
	
	public function view_product_list_search()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();
			
			$data['sproduct_name']    		= $this->input->post('sproduct_name');
			$data['sproduct_category']     	= $this->input->post('sproduct_categories');
			$data['sproduct_type']     		= $this->input->post('sproduct_type');
			$data['sproduct_brand']     	= $this->input->post('sproduct_brand');
			
			
			if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_product";			
				
				$query_string=$query_string." WHERE (gramcar_product.status = 1)";
			
				if($this->input->post("sproduct_name"))	
				{
					$sproduct_name=$this->input->post("sproduct_name"); 
					$query_string=$query_string." AND (gramcar_product.product_name LIKE '%$sproduct_name%')";
					
				}
				
				if($this->input->post("sproduct_categories"))	
				{
					$sproduct_category=$this->input->post("sproduct_categories");	
					$query_string=$query_string." AND(gramcar_product.product_category_id  = $sproduct_category)";
				}
				
				if($this->input->post("sproduct_type"))	
				{
					$sproduct_type=$this->input->post("sproduct_type");	
					$query_string=$query_string." AND(gramcar_product.product_type_id = $sproduct_type)";
				}
				
				if($this->input->post("sproduct_brand"))	
				{
					$sproduct_brand=$this->input->post("sproduct_brand");	
					$query_string=$query_string." AND(gramcar_product.product_brand = $sproduct_brand )";
				}								
				
			
				$query_string=$query_string." ORDER BY gramcar_product.last_edit_data DESC";					
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				
				//echo $searchterm;
				$this->load->library('pagination');
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "social_goods/social_goods/view_product_list_search/";
				$config["total_rows"] = $this->social_goods_model->all_product_count_query_string($searchterm);
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
				$data['all_product'] = $this->social_goods_model->get_all_product_by_limit_querystring($searchterm, $config["per_page"], $page);	
				$data['links'] = $this->pagination->create_links();
				$data['page']=$page;
				
			$this->load->view('social_goods/view_product_list_search', isset($data) ? $data : NULL);		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	}
	
	public function view_product_grid_search()
	{
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();
			
			$data['sproduct_name']    		= $this->input->post('sproduct_name');
			$data['sproduct_category']     	= $this->input->post('sproduct_categories');
			$data['sproduct_type']     		= $this->input->post('sproduct_type');
			$data['sproduct_brand']     	= $this->input->post('sproduct_brand');
			
			
			if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_product";			
				
				$query_string=$query_string." WHERE (gramcar_product.status = 1)";
			
				if($this->input->post("sproduct_name"))	
				{
					$sproduct_name=$this->input->post("sproduct_name"); 
					$query_string=$query_string." AND (gramcar_product.product_name LIKE '%$sproduct_name%')";
					
				}
				
				if($this->input->post("sproduct_categories"))	
				{
					$sproduct_category=$this->input->post("sproduct_categories");	
					$query_string=$query_string." AND(gramcar_product.product_category_id  = $sproduct_category)";
				}
				
				if($this->input->post("sproduct_type"))	
				{
					$sproduct_type=$this->input->post("sproduct_type");	
					$query_string=$query_string." AND(gramcar_product.product_type_id = $sproduct_type)";
				}
				
				if($this->input->post("sproduct_brand"))	
				{
					$sproduct_brand=$this->input->post("sproduct_brand");	
					$query_string=$query_string." AND(gramcar_product.product_brand = $sproduct_brand )";
				}								
				
			
				$query_string=$query_string." ORDER BY gramcar_product.last_edit_data DESC";					
				$searchterm = $this->registration_model->searchterm_handler($query_string);
				}
				else
				{
				$searchterm = $this->session->userdata('searchterm');
				}
				
				//echo $searchterm;
				$this->load->library('pagination');
				//pagination
				$config = array();
				$config["base_url"] = base_url() . "social_goods/social_goods/view_product_grid_search/";
				$config["total_rows"] = $this->social_goods_model->all_product_count_query_string($searchterm);
				$config["per_page"] = $this->config->item("pagination_perpage")-2;
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
				$data['all_product'] = $this->social_goods_model->get_all_product_by_limit_querystring($searchterm, $config["per_page"], $page);	
				$data['links'] = $this->pagination->create_links();
				$data['page']=$page;
				
			$this->load->view('social_goods/view_product_grid_search', isset($data) ? $data : NULL);		
		}
		else
		{
		redirect('account/sign_in');
		}
	
	}
	
	
	public function view_single_product($product_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));						
						
				$data['title'] = 'GramCar: Social Goods';
				$data['product_info'] = $this->social_goods_model->get_product_info_by_id($product_id);				
				$this->load->view('social_goods/view_single_product', isset($data) ? $data : NULL);							
						
		}
		else
		{
		redirect('account/sign_in');
		}	
	
	}
	
	public function edit_single_product($product_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('edit_product'))
			{				
				
								
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('product_name', 'Product name', 'required');
			$this->form_validation->set_rules('product_name_bn', 'Product name bangla', 'required');
			$this->form_validation->set_rules('product_description', 'Description','required');
			$this->form_validation->set_rules('product_price', 'Price','required');
			$this->form_validation->set_rules('product_categories', 'Product categories','required');
			$this->form_validation->set_rules('product_type', 'Product type','required');
			
			
			$data['error'] ='';			
			$data['all_categories'] = $this->social_goods_model->get_all_product_categories();
			$data['all_product_type'] = $this->social_goods_model->get_all_product_type();
			$data['all_weight_unit'] = $this->social_goods_model->get_all_weight_unit();
			$data['all_product_brand'] = $this->social_goods_model->get_all_product_brand();
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar: Social Goods';
				$data['product_info'] = $this->social_goods_model->get_product_info_by_id($product_id);				
				$this->load->view('social_goods/view_edit_single_product', isset($data) ? $data : NULL);				
				}
				else
				{
				/******************** Insert form data ************************/
				$product_description=$this->input->post('product_description');	$product_description  = empty($product_description) ? NULL : $product_description;
				$product_weight=$this->input->post('product_weight');			$product_weight  = empty($product_weight) ? NULL : $product_weight;
				$product_weight_unit=$this->input->post('product_weight_unit');	$product_weight_unit  = empty($product_weight_unit) ? NULL : $product_weight_unit;
				$product_size=$this->input->post('product_size');				$product_size  = empty($product_size) ? NULL : $product_size;
				$product_color=$this->input->post('product_color');				$product_color  = empty($product_color) ? NULL : $product_color;
				$product_brand=$this->input->post('product_brand');				$product_brand  = empty($product_brand) ? NULL : $product_brand;

				$product_data=array(
						'product_name'=>$this->input->post('product_name'),
						'product_name_bn'=>$this->input->post('product_name_bn'),
						'product_description'=>$product_description,
						'product_category_id'=>$this->input->post('product_categories'),
						'product_type_id'=>$this->input->post('product_type'),
						'product_price'=>$this->input->post('product_price'),
						'product_weight'=>$product_weight,
						'product_weight_unit'=>$product_weight_unit,
						'product_size'=>$product_size,
						'product_color'=>$product_color,
						'product_brand'=>$product_brand,						
						'last_edit_data'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),						
						'status'=>$this->input->post('product_status_option')
						);
				
				$success_or_fail=$this->social_goods_model->update_product_info($product_data,$product_id);	// Save the info and get the product id
				
				/******************** Insert form data end ************************/
				
				
				if($_FILES['product_image']['name'])
				{
				/******************** Delete previous image ***************************/	
				//no need to unlink because overwrite = True
				//unlink(base_url().RES_DIR."/img/products/".$product_id);
				//unlink(base_url().RES_DIR."/img/products/thumbnils/".$product_id);
				//unlink(base_url().RES_DIR."/img/products/medium/".$product_id);
				/******************** Delete previous image end ***********************/	
				
				/********** Image upload	********************/
				$config['upload_path'] = RES_DIR."/img/products/";
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= '256';
				$config['overwrite'] = 'TRUE';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['file_name']  = $product_id;
			
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('product_image'))
					{
						
						$data['error'] = $this->upload->display_errors();								
					}
					else
					{
						$image_data = $this->upload->data();												
						$original_image= $image_data['raw_name'].$image_data['file_ext'];
						
						$config2 = array(
						'source_image' => $image_data['full_path'],
						'new_image' => RES_DIR."/img/products/thumbnils",
						'image_name'=> $product_id,
						'maintain_ratio' => true,
						'overwrite' => true,
						'width' => 80,
						'height' => 80
						);
						$this->load->library('image_lib');
						$this->image_lib->initialize($config2);
						if ( !$this->image_lib->resize()){
							$data['error'] = $this->image_lib->display_errors('', '');
              				}						
						
						$config3 = array(
						'source_image' => $image_data['full_path'],
						'new_image' => RES_DIR."/img/products/medium",
						'image_name'=> $product_id,
						'maintain_ratio' => true,
						'overwrite' => true,
						'width' => 164,
						'height' => 200
						);
						$this->load->library('image_lib');
						$this->image_lib->initialize($config3);
						if ( !$this->image_lib->resize()){
							$data['error'] = $this->image_lib->display_errors('', '');
              				}
						
						
						$data['upload_data'] = $this->upload->data();
						
					}							
				
				/*$product_data_with_image=array(						
						'thumbnil_image'=>$original_image,
						'original_image'=>$original_image						
						);				
		
				$success_or_fail=$this->social_goods_model->update_product_info($product_data_with_image,$product_id);	// update the product info with image name							
				echo $success_or_fail." Fail or success";*/
				}
				//else
				//echo "Not posted".$_FILES['product_image']['name'];
				
				if($success_or_fail)
				$data['success_msg']="Save Successfull for ".$this->input->post('product_name');
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";								
												
				
				$data['title'] = 'GramCar: Social Goods';
				$data['product_info'] = $this->social_goods_model->get_product_info_by_id($product_id);	
				//redirect('social_goods/social_goods/edit_single_product/'.$product_id, 'refresh');  
				$this->load->view('social_goods/view_edit_single_product', isset($data) ? $data : NULL);								
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
	function set_social_goods_order_status($order_id,$status)
	{
		return $this->social_goods_model->set_social_goods_order_status($order_id,$status);
	}
	
	/**** Ajax function *****/
	public function delete_product()
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('edit_product'))
			{
			
				$eligible_for_delete=$this->social_goods_model->product_exits_in_order_list($this->input->post('product_id'));
			
				if($eligible_for_delete)
				{
				echo "You can not delete this product because its use in a order";	
				}
				else
				{			
					$success_or_fail=$this->social_goods_model->delete_product_by_id($this->input->post('product_id'));
					if($success_or_fail)
					{
					echo "Successfull";								
					}
					else
					{
					echo "Unsuccessfull";
					}
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