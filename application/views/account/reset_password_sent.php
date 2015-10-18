<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head', array('title' => lang('sign_in_page_name'))); ?>
</head>
<body>

<?php echo $this->load->view('header'); ?>
        <div class="span4 offset4">
			<?php echo sprintf(lang('reset_password_sent_instructions'), anchor('account/forgot_password', lang('reset_password_resend_the_instructions'))); ?>
        </div>
	</div>
</div>
	<?php echo $this->load->view('footer'); ?>
</body>
</html>