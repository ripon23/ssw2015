<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo isset($title) ? $title.' - '.lang('website_title') : lang('website_title'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="ICT based Farming and Monitoring Support System (IFMS)">
<meta name="author" content="Programmer: Zahidul Hossein Ripon Email:riponmailbox@gmail.com">

<base href="<?php echo base_url(); ?>"/>

<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico"/>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap.min.css"/>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/css/style.css"/>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/css/jquery-ui.min.css" />
<link href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap-select.css">

<?php 
$language = ($this->session->userdata('site_lang')=='bangla')? 'bn':'en';
if ($language=='bn'):
?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/css/fonts_bn.css"/>
<?php 
endif;
?>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>-->
<script src="<?php echo base_url().RES_DIR; ?>/js/jquery.min.js"></script>
<script src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-select.js"></script>

    

<!--<script src="<?php echo base_url().RES_DIR; ?>/js/libraries/RGraph.common.core.js" ></script>
    <script src="<?php echo base_url().RES_DIR; ?>/js/libraries/RGraph.common.dynamic.js" ></script>
    <script src="<?php echo base_url().RES_DIR; ?>/js/libraries/RGraph.common.tooltips.js" ></script>
    <script src="<?php echo base_url().RES_DIR; ?>/js/libraries/RGraph.common.effects.js" ></script>
    <script src="<?php echo base_url().RES_DIR; ?>/js/libraries/RGraph.pie.js" ></script>-->

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->