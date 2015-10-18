<?php
class Expenses extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('expenses_model','account/account_model', 'ref_site_model','ref_location_model' ));	
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
		if($this->authorization->is_permitted('add_expenses'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar expenses list';	
			
			//$data['all_registration'] = $this->registration_model->get_all_registration();	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "expenses/expenses/";
			$config["total_rows"] = $this->expenses_model->all_expenses_count();
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
			$data['all_expenses'] = $this->expenses_model->get_all_expenses_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;
			$data['site'] = $this->ref_site_model->get_all_site();
			
			
			$this->load->view('expenses/view_view_expenses', isset($data) ? $data : NULL);
			
			
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
		
	
	
	public function search_view_expenses()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('add_expenses'))
			{
				
				// assign posted valued
				$data['sexpense_id']    		= $this->input->post('sexpense_id');
				$data['sexpense_purpose']   	= $this->input->post('sexpense_purpose');
				$data['sexpense_amount']   		= $this->input->post('smiddlename');
				$data['sexpense_voucher_no']   	= $this->input->post('sexpense_voucher_no');
				$data['sexpense_site']     		= $this->input->post('sexpense_site');
				
				$data['sdate1']     			= $this->input->post('sdate1');
				$data['sdate2']					= $this->input->post('sdate2');
				
						
				
				if($this->input->post("search_submit"))
				{
				$query_string="SELECT * FROM gramcar_expenses";	
		
				$sregistration_no=$this->input->post("sregistration_no");
				
				$query_string=$query_string." WHERE (gramcar_expenses.expense_id Like '%')";
				//$query_string=$query_string." WHERE (gramcar_registration.status = 1)";
			
				if($this->input->post("sexpense_id"))	
				{
					$sexpense_id=$this->input->post("sexpense_id"); 
					$query_string=$query_string." AND (gramcar_expenses.expense_id = '$sexpense_id')";
				}
				
				if($this->input->post("sexpense_purpose"))	
				{
					$sexpense_purpose=$this->input->post("sexpense_purpose");	
					$query_string=$query_string." AND(gramcar_expenses.expense_purpose LIKE '%$sexpense_purpose%')";
				}
				
				if($this->input->post("sexpense_amount"))	
				{
					$sexpense_amount=$this->input->post("sexpense_amount");	
					$query_string=$query_string." AND(gramcar_expenses.expense_amount LIKE '%$sexpense_amount%')";
				}
				
				if($this->input->post("sexpense_voucher_no"))	
				{
					$sexpense_voucher_no=$this->input->post("sexpense_voucher_no");	
					$query_string=$query_string." AND(gramcar_expenses.expense_voucher_no LIKE '%$sexpense_voucher_no%')";
				}
				
				if($this->input->post("sexpense_site"))	
				{
					$sexpense_site=$this->input->post("sexpense_site");	
					$query_string=$query_string." AND(gramcar_expenses.expense_site_id = '$sexpense_site')";
				}
				
				if($this->input->post("sdate1"))
				{
				$sdate1=$this->input->post("sdate1"); 
				$sdate2=$this->input->post("sdate2");
	
				if(($sdate1!='')&& ($sdate2==''))
				$sdate2=$sdate1;
				
				/*if(strlen($sdate1)<12) 
				$sdate1=$sdate1." 00:00:00";	
				
				if(strlen($sdate2)<12) 
				$sdate2=$sdate2." 23:59:59";*/
			
				$query_string= $query_string."  AND expense_date BETWEEN '".$sdate1."' AND '".$sdate2."'";
				}
				
				
				
				if($this->input->post("expense_in_income_statement"))	
				{
					$expense_in_income_statement=$this->input->post("expense_in_income_statement");	
					if ($expense_in_income_statement=='zero') $expense_in_income_statement='0';
					$query_string=$query_string." AND(gramcar_expenses.expense_in_income_statement 	 = '$expense_in_income_statement')";
				}
				
				
				
			
				$query_string=$query_string." ORDER BY gramcar_expenses.expense_date DESC";	
				//$query_string=$query_string." LIMIT $start, $limit"; 
				
				$searchterm = $this->expenses_model->searchterm_handler($query_string);
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
			$config["base_url"] = base_url() . "expenses/expenses/search_view_expenses/";
			$config["total_rows"] = $this->expenses_model->all_expenses_count_query_string($searchterm);
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
			$data['all_expenses'] = $this->expenses_model->get_all_expenses_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			$data['site'] = $this->ref_site_model->get_all_site();
			
			
			$this->load->view('expenses/view_view_expenses', isset($data) ? $data : NULL);
				
			
			
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
	
	
	
	public function add_expenses()  
	{

		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));			
			
			if($this->authorization->is_permitted('add_expenses'))
			{
			
				
				$data['site'] = $this->ref_site_model->get_all_site();
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');				

				$this->form_validation->set_rules('expense_purpose', 'Expense purpose', 'required');
				$this->form_validation->set_rules('expense_amount', 'Expense amount', 'required');
				$this->form_validation->set_rules('expense_date', 'Expense date', 'required');				
				$this->form_validation->set_rules('expense_site', 'Expense site', 'required');
				
				$this->form_validation->set_rules('expense_payee', 'Payee');
				$this->form_validation->set_rules('expense_voucher_no', 'Voucher');
				
				$this->form_validation->set_rules('expense_note', 'Expense note');
				
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar add expenses';														
					$this->load->view('expenses/view_add_expenses', isset($data) ? $data : NULL);
				}
				else
				{
					
					$expense_purpose=$this->input->post('expense_purpose');		$expense_purpose  = empty($expense_purpose) ? NULL : $expense_purpose;
					$expense_amount=$this->input->post('expense_amount');		$expense_amount  = empty($expense_amount) ? NULL : $expense_amount;	
					
					$expense_payee=$this->input->post('expense_payee');			$expense_payee  = empty($expense_payee) ? NULL : $expense_payee;
					$expense_voucher_no=$this->input->post('expense_voucher_no');$expense_voucher_no  = empty($expense_voucher_no) ? NULL : $expense_voucher_no;
					
					$expense_date=$this->input->post('expense_date');			$expense_date  = empty($expense_date) ? NULL : $expense_date;
					$expense_site=$this->input->post('expense_site');			$expense_site  = empty($expense_site) ? NULL : $expense_site;					
					$expense_note=$this->input->post('expense_note');			$expense_note  = empty($expense_note) ? NULL : $expense_note;
					
					
					
					$expense_data_table=array(
						'expense_purpose'=>$expense_purpose,
						'expense_amount'=>$expense_amount,
						'expense_payee'=>$expense_payee,
						'expense_voucher_no'=>$expense_voucher_no,
						'expense_date'=>$expense_date,
						'expense_site_id'=>$expense_site,
						'expense_in_income_statement'=>$this->input->post('expense_in_income_statement'),
						'expense_note'=>$expense_note,												
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);				
				
					
					$success_or_fail=$this->expenses_model->save_expenses($expense_data_table);
					
					
					
					if($success_or_fail)
					$data['success_msg']="Expense save successfull";
					else
					$data['success_msg']="Expense save unsuccessfull! Please try again";
					
					$this->load->view('expenses/view_add_expenses', isset($data) ? $data : NULL);
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
	
	
	
	public function view_single_expense($expense_id)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('add_expenses'))
			{
			//echo $reg_no;
			
			$data['title'] = 'GramCar expenses:';
			
			$data['single_expense'] = $this->expenses_model->get_all_expense_info_by_id($expense_id);			
			$this->load->view('expenses/view_single_expense', isset($data) ? $data : NULL);
			
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
	
	
	
	public function edit_single_expense($expense_id)
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('add_expenses'))
			{
											
				$data['site'] = $this->ref_site_model->get_all_site();
				
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');				

				$this->form_validation->set_rules('expense_purpose', 'Expense purpose', 'required');
				$this->form_validation->set_rules('expense_amount', 'Expense amount');
				$this->form_validation->set_rules('expense_payee', 'Payee');
				$this->form_validation->set_rules('expense_voucher_no', 'Voucher');
				$this->form_validation->set_rules('expense_date', 'Expense date', 'required');				
				$this->form_validation->set_rules('expense_site', 'Expense site', 'required');
				
				$this->form_validation->set_rules('expense_note', 'Expense note');
				
		
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'GramCar add expenses';
					$data['single_expense'] = $this->expenses_model->get_all_expense_info_by_id($expense_id);			
					$this->load->view('expenses/view_edit_single_expense.php', isset($data) ? $data : NULL);
				}
				else
				{
					
					$expense_purpose=$this->input->post('expense_purpose');		$expense_purpose  = empty($expense_purpose) ? NULL : $expense_purpose;
					$expense_amount=$this->input->post('expense_amount');		$expense_amount  = empty($expense_amount) ? NULL : $expense_amount;		
					$expense_payee=$this->input->post('expense_payee');			$expense_payee  = empty($expense_payee) ? NULL : $expense_payee;
					$expense_voucher_no=$this->input->post('expense_voucher_no');$expense_voucher_no  = empty($expense_voucher_no) ? NULL : $expense_voucher_no;
					$expense_date=$this->input->post('expense_date');			$expense_date  = empty($expense_date) ? NULL : $expense_date;
					$expense_site=$this->input->post('expense_site');			$expense_site  = empty($expense_site) ? NULL : $expense_site;					
					$expense_note=$this->input->post('expense_note');			$expense_note  = empty($expense_note) ? NULL : $expense_note;
					
					
					$expense_data_table=array(
						'expense_purpose'=>$expense_purpose,
						'expense_amount'=>$expense_amount,
						'expense_payee'=>$expense_payee,
						'expense_voucher_no'=>$expense_voucher_no,
						'expense_date'=>$expense_date,
						'expense_site_id'=>$expense_site,
						'expense_in_income_statement'=>$this->input->post('expense_in_income_statement'),
						'expense_note'=>$expense_note,												
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);				
				
					
					$success_or_fail=$this->expenses_model->update_expenses($expense_data_table,$expense_id);
										
					
					if($success_or_fail)
					$data['success_msg']="Expense update successfull";
					else
					$data['success_msg']="Expense update unsuccessfull! Please try again";
					$data['single_expense'] = $this->expenses_model->get_all_expense_info_by_id($expense_id);			
					$this->load->view('expenses/view_edit_single_expense.php', isset($data) ? $data : NULL);
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
	public function delete_expense()
	{
	if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('delete_expense'))
			{
			$success_or_fail=$this->expenses_model->delete_expense($this->input->post('expense_id'));
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