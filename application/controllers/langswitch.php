<?php
class LangSwitch extends CI_Controller
{
    function __construct() {
        parent::__construct();
        //$this->load->helper('url');
	

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization'));
		$this->load->model(array('account/account_model'));
	
    }
     
	
	 public function switchLanguage($language = "") {
            $language = ($language != "") ? $language : "english";
            $this->session->set_userdata('site_lang', $language);
            //redirect(base_url());
			 $ref = $this->input->server('HTTP_REFERER', TRUE);
			 redirect($ref, 'location');  
    }
	
}
?>