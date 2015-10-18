<?php
class Health_checkup extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl','date'));
		$this->load->library(array('account/authentication', 'account/authorization','form_validation'));
		$this->load->model(array('registration_model','health_checkup_model','account/account_model', 'general_model','ref_site_model','ref_location_model', 'ref_services_model' ));	
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
			if($this->authorization->is_permitted('view_health_checkup'))
			{
			$this->load->helper("url");	
			$data['title'] = 'GramCar Generel Health checkup List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(2);	// 2= General health checkup
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "health_checkup/health_checkup/index/";
			$config["total_rows"] = $this->health_checkup_model->get_all_health_checkup_registration_count();
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
			$data['all_health_checkup'] = $this->health_checkup_model->get_all_health_checkup_registration_by_limit($config["per_page"], $page);				
			$data["links"] = $this->pagination->create_links();
			$data["page"]=$page;			
			
			$this->load->view('health_checkup/view_health_checkup', isset($data) ? $data : NULL);		
					
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
	
	
	public function search_health_checkup_list()
	{
		if($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));	
			if($this->authorization->is_permitted('view_health_checkup'))
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
				$query_string=$query_string." WHERE (gramcar_registration_for_services.services_status < 3) AND (gramcar_registration_for_services.services_id=2)";
			
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
				$data['title'] = 'GramCar General Health Check-up List';	
			
			$data['all_services_point']= $this->ref_site_model->get_all_services_point();	
			$data['all_package'] = $this->ref_services_model->get_all_services_package_by_id(2);	// 2= General health checkup	
			$this->load->library('pagination');
			//pagination
			$config = array();
			$config["base_url"] = base_url() . "health_checkup/health_checkup/search_health_checkup_list/";
			$config["total_rows"] = $this->health_checkup_model->all_health_checkup_count_query_string($searchterm);
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
			$data['all_health_checkup'] = $this->health_checkup_model->get_all_health_checkup_by_limit_querystring($searchterm, $config["per_page"], $page);	
			$data['links'] = $this->pagination->create_links();
			$data['page']=$page;
			
			
			
			$this->load->view('health_checkup/view_health_checkup', isset($data) ? $data : NULL);
				
			
			
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
	
	public function add_health_checkup($reg_services_id)
	{
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('add_health_checkup'))
			{				
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('age', 'Age', 'required');
			$this->form_validation->set_rules('height', 'Height');
			$this->form_validation->set_rules('weight', 'weight');
			$this->form_validation->set_rules('bmi', 'bmi');
			$this->form_validation->set_rules('waist_circumference', 'waist_circumference');
			$this->form_validation->set_rules('hip', 'hip');
			$this->form_validation->set_rules('waist_hip_ratio', 'waist_hip_ratio');
			$this->form_validation->set_rules('temperature', 'temperature');
			$this->form_validation->set_rules('blood_sys', 'blood_sys');
			$this->form_validation->set_rules('blood_dia', 'blood_dia');
			$this->form_validation->set_rules('blood_sugar', 'blood_sugar');
			$this->form_validation->set_rules('blood_hemoglobin', 'blood_hemoglobin');
			$this->form_validation->set_rules('oxigen_blood_hemoglobin', 'oxigen_blood_hemoglobin');
			$this->form_validation->set_rules('pulse_ratio', 'pulse_ratio');
			$this->form_validation->set_rules('urinary_ph', 'urinary_ph');			
			$this->form_validation->set_rules('unine_sugar', 'unine_sugar');
			$this->form_validation->set_rules('urine_protein', 'urine_protein');
			$this->form_validation->set_rules('urinary_urobilinogen', 'urinary_urobilinogen');
			$this->form_validation->set_rules('urine_protein', 'urine_protein');
			$this->form_validation->set_rules('blood_glucose_unit', 'blood_glucose_unit');
			$this->form_validation->set_rules('blood_glucose_status', 'blood_glucose_status');
			$this->form_validation->set_rules('rhythm', 'rhythm');
			$this->form_validation->set_rules('cholesterol', 'cholesterol');
			$this->form_validation->set_rules('uric_acid', 'uric_acid');
			$this->form_validation->set_rules('hbsag', 'hbsag');
			
			
				if ($this->form_validation->run() == FALSE)
				{
				//$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);	
				//$user_reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $data['registration_info']->registration_no);	
				//$user_family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $user_reg_info->family_id);
				//$household_name=$user_family_info->household_name;
				//echo $household_name;
				//$use_and_checkup_data=1;
				//$apisaid2 =file_get_contents($this->config->item("phc_api_base_url").'api_user_and_checkup_insert/'.$use_and_checkup_data);
				//echo "Saved successfully, ".$apisaid2;
				//$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);	
				//$user_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $data['registration_info']->registration_no);	
				//print_r($user_info);
				$data['title'] = 'GramCar Registration';
				$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('health_checkup/view_add_health_checkup', isset($data) ? $data : NULL);				
				}
				else
				{
				$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);	
				
				/************** For old registration we need to make him user *************/
				if(!$data['registration_info']->user_id)
					{
					//echo "----hi----".$data['registration_info']->registration_no;
					$this->load->helper('account/phpass');
					$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
					$new_hashed_password = $hasher->HashPassword('123456');
					
					$a3m_account_data=array(
						'username'=>$data['registration_info']->registration_no,
						'email'=>$data['registration_info']->registration_no."@domain.com",
						'password'=>$new_hashed_password,						
						'createdon'=>mdate('%Y-%m-%d %H:%i:%s', now())					
						);
			
					$a3m_account_id=$this->general_model->save_into_table_and_return_insert_id('a3m_account', $a3m_account_data);
					
					$a3m_account_details_data=array(
						'account_id'=>$a3m_account_id,
						'fullname'=>$data['registration_info']->first_name." ".$data['registration_info']->middle_name." ".$data['registration_info']->last_name,
						'firstname'=>$data['registration_info']->first_name,
						'lastname'=>$data['registration_info']->last_name,
						'dateofbirth'=>$data['registration_info']->dob,
						'gender'=>strtolower($data['registration_info']->gender)						
						);
				
					$success_or_fail1=$this->general_model->save_into_table('a3m_account_details', $a3m_account_details_data);
					
					$a3m_rel_account_role_data=array(
						'account_id'=>$a3m_account_id,
						'role_id'=>$this->config->item("customer_role_id")
						);
				
					$success_or_fail3=$this->general_model->save_into_table('a3m_rel_account_role', $a3m_rel_account_role_data);
					
					$reg_data=array(
						'user_id'=>$a3m_account_id						
						);
				
					$success_or_fail3=$this->general_model->update_table('gramcar_registration', $reg_data,'registration_no', $data['registration_info']->registration_no);
					
						if(!$data['registration_info']->site_id)
						{
						$query="SELECT * FROM gramcar_registration_for_services WHERE registration_no=".$data['registration_info']->registration_no." Limit 1";
						$services_point=$this->general_model->get_all_single_row_querystring($query);
						$services_point_id=$services_point->services_point_id;
						$site_id=$this->ref_site_model->get_site_id_by_sp_id($services_point_id);
						
						$reg_data2=array(
						'site_id '=>$site_id						
						);
				
						$success_or_fail3=$this->general_model->update_table('gramcar_registration', $reg_data2,'registration_no', $data['registration_info']->registration_no);
						
						}
					
					}
				/************** For old registration we need to make him user END *********/
				
				$GLOBALS["overall_health_status"]=0;
				$sex=$this->input->post('sex');
				$height=$this->input->post('height');			$height  = empty($height) ? NULL : $height;
				$weight=$this->input->post('weight');			$weight  = empty($weight) ? NULL : $weight;
				
				$bmi=$this->input->post('bmi');					$bmi  = empty($bmi) ? NULL : $bmi; 
				if($bmi) $this->health_checkup_model->get_result_status("BMI",$bmi,$sex);
				
				$waist_circumference=$this->input->post('waist_circumference');	$waist_circumference  = empty($waist_circumference) ? NULL : $waist_circumference;
				if($waist_circumference) $this->health_checkup_model->get_result_status("waist",$waist_circumference,$sex);
				
				$hip=$this->input->post('hip');					$hip  = empty($hip) ? NULL : $hip;
				$waist_hip_ratio=$this->input->post('waist_hip_ratio');			$waist_hip_ratio  = empty($waist_hip_ratio) ? NULL : $waist_hip_ratio;
				if($waist_hip_ratio) $this->health_checkup_model->get_result_status("waist_hip_ratio",$waist_hip_ratio,$sex);
				
				$temperature=$this->input->post('temperature');					$temperature  = empty($temperature) ? NULL : $temperature;
				
				if($temperature) 
				{				
				$temperature=($temperature*1.8)+32; //	temperature in Fahrenheit
				$temperature=round($temperature, 2);    								
				$this->health_checkup_model->get_result_status("temperature",$temperature,$sex);					
				}
				
				
				$unine_sugar=$this->input->post('unine_sugar');	$unine_sugar  = empty($unine_sugar) ? NULL : $unine_sugar;
				if($unine_sugar) $this->health_checkup_model->get_result_status("urine_sugar",$unine_sugar,$sex);
				
				$urine_protein=$this->input->post('urine_protein');	$urine_protein  = empty($urine_protein) ? NULL : $urine_protein;
				if($urine_protein) $this->health_checkup_model->get_result_status("urine_protein",$urine_protein,$sex);
				
				$urinary_urobilinogen=$this->input->post('urinary_urobilinogen');$urinary_urobilinogen  = empty($urinary_urobilinogen) ? NULL : $urinary_urobilinogen;
				if($urinary_urobilinogen) $this->health_checkup_model->get_result_status("urinary_urobilinogen",$urinary_urobilinogen,$sex);
				
				$urinary_ph=$this->input->post('urinary_ph');		$urinary_ph  = empty($urinary_ph) ? NULL : $urinary_ph;
				if($urinary_ph) $this->health_checkup_model->get_result_status("urinary_ph",$urinary_ph,$sex);
				
				$oxigen_blood_hemoglobin=$this->input->post('oxigen_blood_hemoglobin');	$oxigen_blood_hemoglobin  = empty($oxigen_blood_hemoglobin) ? NULL : $oxigen_blood_hemoglobin;
				if($oxigen_blood_hemoglobin) $this->health_checkup_model->get_result_status("oxygen_of_blood_hemoglobin",$oxigen_blood_hemoglobin,$sex);
				
				$blood_sys=$this->input->post('blood_sys');		$blood_sys  = empty($blood_sys) ? NULL : $blood_sys;
				if($blood_sys) $this->health_checkup_model->get_result_status("bp_sys",$blood_sys,$sex);
				$blood_dia=$this->input->post('blood_dia');		$blood_dia  = empty($blood_dia) ? NULL :$blood_dia;
				if($blood_dia) $this->health_checkup_model->get_result_status("bp_dia",$blood_dia,$sex);
				
				$blood_glucose_unit=$this->input->post('blood_glucose_unit');
				$blood_glucose_status=$this->input->post('blood_glucose_status');
				$blood_sugar=$this->input->post('blood_sugar');							
				$blood_sugar  = empty($blood_sugar) ? NULL : $blood_sugar;
				if($blood_sugar)
				{
					if($blood_glucose_unit=='mmol/L') $blood_sugar=round($blood_sugar*18,2);
					$this->health_checkup_model->get_result_status("blood_gluckose",$blood_sugar,$blood_glucose_status);
				}								
				
				
				$blood_hemoglobin=$this->input->post('blood_hemoglobin');	$blood_hemoglobin  = empty($blood_hemoglobin) ? NULL : $blood_hemoglobin;
				if($blood_hemoglobin) $this->health_checkup_model->get_result_status("blood_hemoglobin",$blood_hemoglobin,$sex);
				
				$pulse_ratio=$this->input->post('pulse_ratio');		$pulse_ratio  = empty($pulse_ratio) ? NULL : $pulse_ratio;
				if($pulse_ratio) $this->health_checkup_model->get_result_status("pulse_ratio",$pulse_ratio,$sex);
				
				$rhythm=$this->input->post('rhythm');		$rhythm  = empty($rhythm) ? NULL : $rhythm;
				if($rhythm) $this->health_checkup_model->get_result_status("rhythm",$rhythm,$sex);
				
				$cholesterol=$this->input->post('cholesterol');		$cholesterol  = empty($cholesterol) ? NULL : $cholesterol;
				if($cholesterol) $this->health_checkup_model->get_result_status("cholesterol",$cholesterol,$sex);
				
				$uric_acid=$this->input->post('uric_acid');		$uric_acid  = empty($uric_acid) ? NULL : $uric_acid;
				if($uric_acid) $this->health_checkup_model->get_result_status("uric_acid",$uric_acid,$sex);
				
				$hbsag=$this->input->post('hbsag');		$hbsag  = empty($hbsag) ? NULL : $hbsag;
				if($hbsag) $this->health_checkup_model->get_result_status("hbsag",$hbsag,$sex);
				
				
				$health_checkup_data=array(
						'reg_for_service_id'=>$reg_services_id,
						'registration_no'=>$this->input->post('registration_no'),
						'checkup_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'age'=>$this->input->post('age'),
						'height'=>$height,
						'weight'=>$weight,
						'bmi'=>$bmi,
						'waist'=>$waist_circumference,
						'hip'=>$hip,
						'waist_hip_ratio'=>$waist_hip_ratio,
						'temperature'=>$temperature,
						'oxygen_of_blood'=>$oxigen_blood_hemoglobin,
						'bp_sys'=>$blood_sys,
						'bp_dia'=>$blood_dia,
						'blood_glucose'=>$blood_sugar,
						'blood_glucose_type'=>$blood_glucose_status,
						'blood_hemoglobin'=>$blood_hemoglobin,
						'urinary_glucose'=>$unine_sugar,
						'urinary_protein'=>$urine_protein,
						'urinary_urobilinogen'=>$urinary_urobilinogen,
						'urinary_ph'=>$urinary_ph,
						'pulse_rate'=>$pulse_ratio,
						'arrhythmia'=>$rhythm,
						'cholesterol'=>$cholesterol,
						'uric_acid'=>$uric_acid,
						'hbsag'=>$hbsag,
						'color_status'=>$GLOBALS["overall_health_status"],
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'upload_status'=>0
						);
					
				$success_or_fail=$this->health_checkup_model->save_health_checkup($health_checkup_data);								
				
				//need to update date of birth
				if($data['registration_info']->user_id)
					{
						$a3m_user_id = $data['registration_info']->user_id;
					}
				else
					{
						$a3m_user_id = $a3m_account_id;
					}
				
				$a3m_user_details=$this->general_model->get_all_table_info_by_id('a3m_account_details', 'account_id', $a3m_user_id);
				if(!$a3m_user_details->dateofbirth)
				{
					
				$age=$this->input->post('age');
				$time = strtotime("-$age year", time());
  				$dob = date("Y-m-d", $time);
				
				$table_data=array(
						'dateofbirth'=>$dob												
						);
				
				$table_data2=array(
						'dob'=>$dob												
						);
				
				$this->general_model->update_table('a3m_account_details', $table_data,'account_id', $a3m_user_id);
				$this->general_model->update_table('gramcar_registration', $table_data2,'user_id', $a3m_user_id);
				}
				
				
				if($success_or_fail)
				$data['success_msg']="Save Successfull for ".$this->input->post('registration_no');
				else
				$data['success_msg']="Save Unsuccessfull! Please try again";
				
				
				$update_services_status=array(
						'services_status'=>2,						
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);
				/******************** Update the status of the services. Mark it as Taken *******************************/	
				$this->health_checkup_model->update_health_checkup_services_status($update_services_status,$reg_services_id); 
				
				/******************************* PHC API *************************************************/
				/*$fullname=$data['registration_info']->first_name." ".$data['registration_info']->middle_name." ".$data['registration_info']->last_name;
				$sex= $data['registration_info']->gender=="M" ? "Male" : "Female";
				$age=$this->input->post('age');
				
				
				$address="SSW SITE";
				$mobile=$data['registration_info']->phone;
				
				$mydata = array("EHEALTH02301120140512",$this->input->post('registration_no'), mdate('%Y-%m-%d %H:%i:%s', now()), $height, $weight, $bmi, $waist_circumference, $hip, $waist_hip_ratio, $temperature, $oxigen_blood_hemoglobin, $blood_sys, $blood_dia, $blood_sugar, $blood_glucose_status, $blood_hemoglobin, $unine_sugar, $urine_protein, $urinary_urobilinogen, $urinary_ph, $pulse_ratio, $rhythm, $cholesterol, $uric_acid, $hbsag, $GLOBALS["overall_health_status"],$fullname,$age,$sex,$address,$mobile);
				

				$ehealthdata = base64_encode(serialize($mydata));  // Encoding
				//$apisaid =file_get_contents('http://www.gramweb.net/phcssw/api/live_api/ssw_to_phcssw_checkup_entry.php?ehealthdata='.$ehealthdata);
				$apisaid =file_get_contents('http://localhost/phcssw/api/live_api/ssw_to_phcssw_checkup_entry.php?ehealthdata='.$ehealthdata);
				//echo "Return Value=".$apisaid;   //Success or Fail
				
				if(substr_compare($apisaid, "Success", 0, 6) == 0)
				{
				$upload_status=1;
				$health_checkup_status=array(						
						'upload_status'=>$upload_status
						);
				$success_or_fail=$this->health_checkup_model->update_health_checkup($health_checkup_status,$reg_services_id);												 				//echo $success_or_fail;				
				}
				
				if(substr_compare($apisaid, "Fail", 0, 3) == 0)
				{
				$upload_status=2;
				$health_checkup_data=array(						
						'upload_status'=>$upload_status
						);
				$this->health_checkup_model->update_health_checkup($health_checkup_data,$reg_services_id);
				}*/
				/******************************* PHC API END*************************************************/
				
				/******************************* PHC NEW API *************************************************/
				$user_reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $data['registration_info']->registration_no);
				$user_login_info=$this->general_model->get_all_table_info_by_id('a3m_account', 'id', $user_reg_info->user_id);
				
				$user_details_info=$this->general_model->get_all_table_info_by_id('a3m_account_details', 'account_id', $user_reg_info->user_id);
				
				if($user_reg_info->family_id)
				{
				$user_family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $user_reg_info->family_id);
				$household_name=$user_family_info->household_name;
				}
				else
				{
				$household_name='';
				}
				
				$phc_site_id_array=$this->general_model->get_all_table_info_by_id('gramcar_phc_site_map', 'gramcar_site', $user_reg_info->site_id);
				$phc_site_id=$phc_site_id_array->phc_site;	
				
				
				$username=$user_login_info->username;
				$email=$user_login_info->email;
				$password=$user_login_info->password;								
				
				// username, email, password, fullname, firstname, lastname, dateofbirth, gender, barcode, family_id, guardian_name, phone, note
				$mydata2 = array("EHEALTH02301120150301",$username, $email, $password, $user_details_info->fullname, $user_details_info->firstname, $user_details_info->lastname, $user_details_info->dateofbirth, $user_details_info->gender, $data['registration_info']->registration_no, $household_name, $user_reg_info->guardian_name, $user_reg_info->phone, $user_reg_info->note, $phc_site_id, $height, $weight, $bmi, $waist_circumference, $hip, $waist_hip_ratio, $temperature, $oxigen_blood_hemoglobin, $blood_sys, $blood_dia, $blood_sugar, $blood_glucose_status, $blood_hemoglobin, $unine_sugar, $urine_protein, $urinary_urobilinogen, $urinary_ph, $pulse_ratio, $rhythm, $cholesterol, $uric_acid, $hbsag, $GLOBALS["overall_health_status"]);
				
				//$mydata2 = array("EHEALTH02301120150301",$username, $email, $password, $user_details_info->fullname, $user_details_info->firstname, $user_details_info->lastname, $user_details_info->dateofbirth, $user_details_info->gender, $data['registration_info']->registration_no, $household_name, $user_reg_info->guardian_name, $user_reg_info->phone, $user_reg_info->note, $phc_site_id, $height, $weight, $bmi, $waist_circumference, $hip, $waist_hip_ratio, $temperature, $oxigen_blood_hemoglobin, $blood_sys, $blood_dia, $blood_sugar, $blood_glucose_status, $blood_hemoglobin, $unine_sugar, $urine_protein, $urinary_urobilinogen, $urinary_ph, $pulse_ratio, $rhythm, $cholesterol, $uric_acid, $hbsag, $GLOBALS["overall_health_status"]);
				
				
				$use_and_checkup_data = base64_encode(serialize($mydata2));  // Encoding
				//$apisaid2 =file_get_contents($this->config->item("phc_api_base_url").'api_user_and_checkup_insert/'.$use_and_checkup_data);
				
				$curl_handle=curl_init();
				curl_setopt($curl_handle, CURLOPT_URL,$this->config->item("phc_api_base_url").'api_user_and_checkup_insert/'.$use_and_checkup_data);
				curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_handle, CURLOPT_USERAGENT, 'browser');
				$query = curl_exec($curl_handle);
				curl_close($curl_handle);
				
				
				$data['success_msg']="Saved successfully, ".$query;
				
				if(substr_compare($query, "Success", 0, 6) == 0)
				{
				$upload_status=1;
				$health_checkup_status=array(						
						'upload_status'=>$upload_status
						);
				$success_or_fail=$this->health_checkup_model->update_health_checkup($health_checkup_status,$reg_services_id);												 				//echo $success_or_fail;				
				}
				else				
				{
				//if(substr_compare($apisaid2, "Fail", 0, 3) == 0)	
				$upload_status=2;
				$health_checkup_data=array(						
						'upload_status'=>$upload_status
						);
				$this->health_checkup_model->update_health_checkup($health_checkup_data,$reg_services_id);
				}
				/******************************* PHC NEW API END*************************************************/
				
				$data['title'] = 'GramCar Registration';
				$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);
				$this->load->view('health_checkup/view_add_health_checkup', isset($data) ? $data : NULL);
				
				//echo "Global=".$GLOBALS["overall_health_status"];					
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
	
	
	//Ajax function 
	public function resend_health_checkup_api_data()
	{
	$reg_services_id=$this->input->post('reg_services_id');	
	$registration_info = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);
	$health_checkup_info = $this->health_checkup_model->get_health_checkup_info_by_reg_services_id($reg_services_id);
	
	$user_reg_info=$this->general_model->get_all_table_info_by_id('gramcar_registration', 'registration_no', $registration_info->registration_no);
				
	$user_login_info=$this->general_model->get_all_table_info_by_id('a3m_account', 'id', $user_reg_info->user_id);
	
	$user_details_info=$this->general_model->get_all_table_info_by_id('a3m_account_details', 'account_id', $user_reg_info->user_id);
	
	if($user_reg_info->family_id)
	{
	$user_family_info=$this->general_model->get_all_table_info_by_id('gramcar_family', 'family_id', $user_reg_info->family_id);
	$household_name=$user_family_info->household_name;
	}
	else
	{
	$household_name='';
	}
	
	$phc_site_id_array=$this->general_model->get_all_table_info_by_id('gramcar_phc_site_map', 'gramcar_site', $user_reg_info->site_id);
	$phc_site_id=$phc_site_id_array->phc_site;	
	
	
	$username=$user_login_info->username;
	$email=$user_login_info->email;
	$password=$user_login_info->password;								
	
	// username, email, password, fullname, firstname, lastname, dateofbirth, gender, barcode, family_id, guardian_name, phone, note
	$mydata2 = array("EHEALTH02301120150301",$username, $email, $password, $user_details_info->fullname, $user_details_info->firstname, $user_details_info->lastname, $user_details_info->dateofbirth, $user_details_info->gender, $registration_info->registration_no, $household_name, $user_reg_info->guardian_name, $user_reg_info->phone, $user_reg_info->note, $phc_site_id, $health_checkup_info->height, $health_checkup_info->weight, $health_checkup_info->bmi, $health_checkup_info->waist, $health_checkup_info->hip, $health_checkup_info->waist_hip_ratio, $health_checkup_info->temperature, $health_checkup_info->oxygen_of_blood, $health_checkup_info->bp_sys, $health_checkup_info->bp_dia, $health_checkup_info->blood_glucose, $health_checkup_info->blood_glucose_type, $health_checkup_info->blood_hemoglobin, $health_checkup_info->urinary_glucose, $health_checkup_info->urinary_protein, $health_checkup_info->urinary_urobilinogen, $health_checkup_info->urinary_ph, $health_checkup_info->pulse_rate, $health_checkup_info->arrhythmia, $health_checkup_info->cholesterol, $health_checkup_info->uric_acid, $health_checkup_info->hbsag, $health_checkup_info->color_status);								
	
	
	$use_and_checkup_data = base64_encode(serialize($mydata2));  // Encoding
	//$apisaid2 =file_get_contents($this->config->item("phc_api_base_url").'api_user_and_checkup_insert/'.$use_and_checkup_data);
	
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$this->config->item("phc_api_base_url").'api_user_and_checkup_insert/'.$use_and_checkup_data);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, 'browser');
	$query = curl_exec($curl_handle);
	curl_close($curl_handle);
	
	
	$data['success_msg']="Saved successfully, ".$query;
	
		if(substr_compare($query, "Success", 0, 6) == 0)
		{
		$upload_status=1;
		$health_checkup_status=array(						
				'upload_status'=>$upload_status
				);
		$success_or_fail=$this->health_checkup_model->update_health_checkup($health_checkup_status,$reg_services_id);												 				//echo $success_or_fail;
		echo $query;
		}
		else
		echo $query;
	}
	
	
	public function view_single_health_checkup($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('view_health_checkup'))
			{				
						
				$data['title'] = 'GramCar Registration';
				$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['health_checkup_info'] = $this->health_checkup_model->get_health_checkup_info_by_reg_services_id($reg_services_id);
				$this->load->view('health_checkup/view_single_health_checkup', isset($data) ? $data : NULL);
				
			
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
	
	public function edit_single_health_checkup($reg_services_id)
	{			
		
		if ($this->authentication->is_signed_in())
		{
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			if($this->authorization->is_permitted('edit_health_checkup'))
			{				
						
								
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('age', 'Age', 'required');
			
				if ($this->form_validation->run() == FALSE)
				{
				$data['title'] = 'GramCar General Health Checkup';
				$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['health_checkup_info'] = $this->health_checkup_model->get_health_checkup_info_by_reg_services_id($reg_services_id);
				$this->load->view('health_checkup/view_edit_single_health_checkup', isset($data) ? $data : NULL);
				}
				else
				{
				//$GLOBALS["overall_health_status"]=0;	
				$sex=$this->input->post('sex');
				$height=$this->input->post('height');			$height  = empty($height) ? NULL : $height;
				$weight=$this->input->post('weight');			$weight  = empty($weight) ? NULL : $weight;
				
				$bmi=$this->input->post('bmi');					$bmi  = empty($bmi) ? NULL : $bmi; 
				if($bmi) $this->health_checkup_model->get_result_status("BMI",$bmi,$sex);
				
				$waist_circumference=$this->input->post('waist_circumference');	$waist_circumference  = empty($waist_circumference) ? NULL : $waist_circumference;
				if($waist_circumference) $this->health_checkup_model->get_result_status("waist",$waist_circumference,$sex);
				
				$hip=$this->input->post('hip');					$hip  = empty($hip) ? NULL : $hip;
				$waist_hip_ratio=$this->input->post('waist_hip_ratio');			$waist_hip_ratio  = empty($waist_hip_ratio) ? NULL : $waist_hip_ratio;
				if($waist_hip_ratio) $this->health_checkup_model->get_result_status("waist_hip_ratio",$waist_hip_ratio,$sex);
				
				$temperature=$this->input->post('temperature');					$temperature  = empty($temperature) ? NULL : $temperature;
				
				if($temperature) 
				{				
				$temperature=($temperature*1.8)+32; //	temperature in Fahrenheit
				$temperature=round($temperature, 2);    								
				$this->health_checkup_model->get_result_status("temperature",$temperature,$sex);					
				}
				
				$unine_sugar=$this->input->post('unine_sugar');	$unine_sugar  = empty($unine_sugar) ? NULL : $unine_sugar;
				if($unine_sugar) $this->health_checkup_model->get_result_status("urine_sugar",$unine_sugar,$sex);
				
				$urine_protein=$this->input->post('urine_protein');	$urine_protein  = empty($urine_protein) ? NULL : $urine_protein;
				if($urine_protein) $this->health_checkup_model->get_result_status("urine_protein",$urine_protein,$sex);
				
				$urinary_urobilinogen=$this->input->post('urinary_urobilinogen');$urinary_urobilinogen  = empty($urinary_urobilinogen) ? NULL : $urinary_urobilinogen;
				if($urinary_urobilinogen) $this->health_checkup_model->get_result_status("urinary_urobilinogen",$urinary_urobilinogen,$sex);
				
				$urinary_ph=$this->input->post('urinary_ph');		$urinary_ph  = empty($urinary_ph) ? NULL : $urinary_ph;
				if($urinary_ph) $this->health_checkup_model->get_result_status("urinary_ph",$urinary_ph,$sex);
				
				$oxigen_blood_hemoglobin=$this->input->post('oxigen_blood_hemoglobin');	$oxigen_blood_hemoglobin  = empty($oxigen_blood_hemoglobin) ? NULL : $oxigen_blood_hemoglobin;
				if($oxigen_blood_hemoglobin) $this->health_checkup_model->get_result_status("oxygen_of_blood_hemoglobin",$oxigen_blood_hemoglobin,$sex);
				
				$blood_sys=$this->input->post('blood_sys');		$blood_sys  = empty($blood_sys) ? NULL : $blood_sys;
				if($blood_sys) $this->health_checkup_model->get_result_status("bp_sys",$blood_sys,$sex);
				$blood_dia=$this->input->post('blood_dia');		$blood_dia  = empty($blood_dia) ? NULL :$blood_dia;
				if($blood_dia) $this->health_checkup_model->get_result_status("bp_dia",$blood_dia,$sex);
				
				$blood_glucose_unit=$this->input->post('blood_glucose_unit');
				$blood_glucose_status=$this->input->post('blood_glucose_status');
				$blood_sugar=$this->input->post('blood_sugar');							
				$blood_sugar  = empty($blood_sugar) ? NULL : $blood_sugar;
				if($blood_sugar)
				{
					if($blood_glucose_unit=='mmol/L') $blood_sugar=round($blood_sugar*18,2);
					$this->health_checkup_model->get_result_status("blood_gluckose",$blood_sugar,$blood_glucose_status);
				}								
				
				
				$blood_hemoglobin=$this->input->post('blood_hemoglobin');	$blood_hemoglobin  = empty($blood_hemoglobin) ? NULL : $blood_hemoglobin;
				if($blood_hemoglobin) $this->health_checkup_model->get_result_status("blood_hemoglobin",$blood_hemoglobin,$sex);
				
				$pulse_ratio=$this->input->post('pulse_ratio');		$pulse_ratio  = empty($pulse_ratio) ? NULL : $pulse_ratio;
				if($pulse_ratio) $this->health_checkup_model->get_result_status("pulse_ratio",$pulse_ratio,$sex);
				
				$rhythm=$this->input->post('rhythm');		$rhythm  = empty($rhythm) ? NULL : $rhythm;
				if($rhythm) $this->health_checkup_model->get_result_status("rhythm",$rhythm,$sex);
				
				$cholesterol=$this->input->post('cholesterol');		$cholesterol  = empty($cholesterol) ? NULL : $cholesterol;
				if($cholesterol) $this->health_checkup_model->get_result_status("cholesterol",$cholesterol,$sex);
				
				$uric_acid=$this->input->post('uric_acid');		$uric_acid  = empty($uric_acid) ? NULL : $uric_acid;
				if($uric_acid) $this->health_checkup_model->get_result_status("uric_acid",$uric_acid,$sex);
				
				$hbsag=$this->input->post('hbsag');		$hbsag  = empty($hbsag) ? NULL : $hbsag;
				if($hbsag) $this->health_checkup_model->get_result_status("hbsag",$hbsag,$sex);
				
				$health_checkup_data=array(
						'registration_no'=>$this->input->post('registration_no'),						
						'age'=>$this->input->post('age'),
						'height'=>$height,
						'weight'=>$weight,
						'bmi'=>$bmi,
						'waist'=>$waist_circumference,
						'hip'=>$hip,
						'waist_hip_ratio'=>$waist_hip_ratio,
						'temperature'=>$temperature,
						'oxygen_of_blood'=>$oxigen_blood_hemoglobin,
						'bp_sys'=>$blood_sys,
						'bp_dia'=>$blood_dia,
						'blood_glucose'=>$blood_sugar,
						'blood_glucose_type'=>$blood_glucose_status,
						'blood_hemoglobin'=>$blood_hemoglobin,
						'urinary_glucose'=>$unine_sugar,
						'urinary_protein'=>$urine_protein,
						'urinary_urobilinogen'=>$urinary_urobilinogen,
						'urinary_ph'=>$urinary_ph,
						'pulse_rate'=>$pulse_ratio,
						'arrhythmia'=>$rhythm,
						'cholesterol'=>$cholesterol,
						'uric_acid'=>$uric_acid,
						'hbsag'=>$hbsag,
						'color_status'=>$GLOBALS["overall_health_status"],
						'last_edit_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'edit_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id')),
						'upload_status'=>0
						);
					
				$success_or_fail=$this->health_checkup_model->update_health_checkup($health_checkup_data,$reg_services_id);								
				
				if($success_or_fail)
				$data['success_msg']="Update Successfull for ".$this->input->post('registration_no');
				else
				$data['success_msg']="Update Unsuccessfull! Please try again";
				
				/*$update_services_status=array(
						'services_status'=>2,						
						'update_date'=>mdate('%Y-%m-%d %H:%i:%s', now()),
						'update_user_id'=>$this->account_model->get_username_by_id($this->session->userdata('account_id'))
						);*/
				/******************** Update the status of the services. Mark it as Taken *******************************/	
				//$this->health_checkup_model->update_health_checkup_services_status($update_services_status,$reg_services_id);
				
				$data['title'] = 'GramCar General Health Checkup';
				$data['registration_info'] = $this->health_checkup_model->get_registration_info_by_reg_services_id($reg_services_id);
				$data['health_checkup_info'] = $this->health_checkup_model->get_health_checkup_info_by_reg_services_id($reg_services_id);
				$this->load->view('health_checkup/view_edit_single_health_checkup', isset($data) ? $data : NULL);
				
				//echo "Global=".$GLOBALS["overall_health_status"];					
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
	
	
}// END Class


/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
?>