<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head', array('title' => lang('sign_up_page_name'))); ?>
	<style type="text/css">
		
		#passstrength {
		    /*color:red;*/
		    font-family:verdana;
		    font-size:10px;
		    font-weight:bold;
		}
		#pass_match{
			font-family:verdana;
		    font-size:10px;
		    font-weight:bold;
		}
		#email-result{
			font-family:verdana;
		    font-size:10px;
		    font-weight:bold;
		}
		

	</style>
	<script type="text/javascript">
		$(document).ready(function() {
		    var x_timer;   
		    var base_url = $("#base_url").val(); 
		    $("#username").keyup(function (e){	
		    	clearTimeout(x_timer);
		        var user_name = $(this).val();
		        x_timer = setTimeout(function(){
		            check_username_ajax(user_name);
		        }, 20);
		    }); 

			function check_username_ajax(username){
			    $("#user-result").html('<img src="resource/img/ajax-loader-1.gif" />');
			    $.post(base_url+'account/sign_up/ajax_check_username', {'username':username}, function(data) {
			      $("#user-result").html(data);
			    });
			}

			$('#sign_up_password').keyup(function(e) {
				var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
				var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
				var enoughRegex = new RegExp("(?=.{6,}).*", "g");
				if (false == enoughRegex.test($(this).val())) {
				     $('#passstrength').html("<span class='text-error'>More Characters</span>");
				} else if (strongRegex.test($(this).val())) {
				     $('#passstrength').html("<span class='text-success'>Strong!</span>");
				} else if (mediumRegex.test($(this).val())) {
				     $('#passstrength').html("<span class='text-info'>Medium!</span>");
				} else {
				     $('#passstrength').html("<span class='text-warning'>Weak!</span>");
				}
				return true;
			});

			$('#passconf').keyup(function(e) {
				var passconf = $(this).val();
				var sign_up_password = $('#sign_up_password').val();
				if (passconf == sign_up_password) {
					$('#pass_match').html("<span class='text-success'>Matched</span>");
				}else{
					$('#pass_match').html("<span class='text-error'>Not matched</span>");
				}				
			});

			$("#sign_up_email").keyup(function (e){	
				
		    	clearTimeout(x_timer);
		        var email = $(this).val();
		        var regEx = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    			
    			if (false == regEx.test($(this).val())) {
				     $('#email-result').html("<span class='text-error'>Invalid email</span>");
				     return false;
				}
		        x_timer = setTimeout(function(){
		            check_email_ajax(email);
		        }, 100);
		    }); 

			function check_email_ajax(email){
			    $("#email-result").html('<img src="'+base_url+'resource/img/ajax-loader-1.gif" />');
			    $.post(base_url+'account/sign_up/ajax_check_email', {'email':email}, function(data) {
			      $("#email-result").html(data);
			    });
			}
		});
	</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

		<?php if (! ($this->config->item("sign_up_enabled"))): ?>
			<div class="span12">
				<h3><?php echo lang('sign_up_heading'); ?></h3>

				<div class="alert">
					<strong><?php echo lang('sign_up_notice'); ?> </strong> <?php echo lang('sign_up_registration_disabled'); ?>
				</div>
			</div>
		<?php endif;?>

		<?php if ($this->config->item("sign_up_enabled")): ?>
			<div class="span6 offset3">

				<?php echo form_open(uri_string(), 'class="form-horizontal"'); ?>
				<?php echo form_fieldset(); ?>
				<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
				<div class="well">
					<legend><?php echo lang('sign_up_heading'); ?></legend>
					<div class="control-group <?php echo (form_error('sign_up_username') || isset($sign_up_username_error)) ? 'error' : ''; ?>">
						<label class="control-label" for="username"><?php echo lang('sign_up_username'); ?> <span class="text-error">*</span></label>

						<div class="controls">
							<?php echo form_input(array('name' => 'sign_up_username', 'autofocus'=>'autofocus', 'id' => 'username', 'value' => set_value('sign_up_username'), 'maxlength' => '24')); ?>
							<span id="user-result"></span>
							<?php if (form_error('sign_up_username') || isset($sign_up_username_error)) : ?>
								<span class="help-inline">
								<?php echo form_error('sign_up_username'); ?>
								<?php if (isset($sign_up_username_error)) : ?>
									<span class="field_error"><?php echo $sign_up_username_error; ?></span>
								<?php endif; ?>
								</span>
							<?php endif; ?>
						</div>
					</div>

					<div class="control-group <?php echo (form_error('sign_up_password')) ? 'error' : ''; ?>">
						<label class="control-label" for="sign_up_password"><?php echo lang('sign_up_password'); ?> <span class="text-error">*</span></label>

						<div class="controls">
							<?php echo form_password(array('name' => 'sign_up_password', 'id' => 'sign_up_password', 'value' => set_value('sign_up_password'))); ?>
							<span id="passstrength"></span>
							<?php if (form_error('sign_up_password')) : ?>
								<span class="help-inline">
								<?php echo form_error('sign_up_password'); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>

					<div class="control-group <?php echo (form_error('passconf')) ? 'error' : ''; ?>">
						<label class="control-label" for="passconf">Confirm Password <span class="text-error">*</span></label>

						<div class="controls">
							<?php echo form_password(array('name' => 'passconf', 'id' => 'passconf', 'value' => set_value('passconf'))); ?>
							<span id="pass_match"></span>
							<?php if (form_error('passconf')) : ?>
								<span class="help-inline">
								<?php echo form_error('passconf'); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>

					<div class="control-group <?php echo (form_error('sign_up_email') || isset($sign_up_email_error)) ? 'error' : ''; ?>">
						<label class="control-label" for="sign_up_email"><?php echo lang('sign_up_email'); ?> <span class="text-error">*</span></label>

						<div class="controls">
							<?php echo form_input(array('name' => 'sign_up_email', 'id' => 'sign_up_email', 'value' => set_value('sign_up_email'), 'maxlength' => '160')); ?>
							<span id="email-result"></span>
							<?php if (form_error('sign_up_email') || isset($sign_up_email_error)) : ?>
								<span class="help-inline">
								<?php echo form_error('sign_up_email'); ?>
								<?php if (isset($sign_up_email_error)) : ?>
									<span class="field_error"><?php echo $sign_up_email_error; ?></span>
								<?php endif; ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
                    
                    <div class="control-group <?php echo (form_error('sign_up_phone')) ? 'error' : ''; ?>">
						<label class="control-label" for="sign_up_password"><?php echo lang('sign_up_phone'); ?> <span class="text-error">*</span></label>

						<div class="controls">
							<?php echo form_input(array('name' => 'sign_up_phone', 'id' => 'sign_up_phone', 'value' => set_value('sign_up_phone'),'placeholder'=>'01XXXXXXXXX')); ?>
							<?php if (form_error('sign_up_phone')) : ?>
								<span class="help-inline">
								<?php echo form_error('sign_up_phone'); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
                    

					<?php if (isset($recaptcha)) :
						echo $recaptcha;
						if (isset($sign_up_recaptcha_error)) : ?>
							<span class="field_error"><?php echo $sign_up_recaptcha_error; ?></span>
						<?php endif; ?>
					<?php endif; ?>

					<div class="control-group">
						<label class="control-label"></label>

						<div class="controls">
							<?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-info', 'content' => '<i class="icon-pencil"></i> '.lang('sign_up_create_my_account'))); ?>
						</div>
					</div>

					<p style="text-align:center;"><?php echo lang('sign_up_already_have_account'); ?> <?php echo anchor('account/sign_in', lang('sign_up_sign_in_now')); ?></p>
				</div>

				<?php echo form_fieldset_close(); ?>
				<?php echo form_close(); ?>

			</div>

			<div class="span6">
				<?php if ($this->config->item('third_party_auth_providers')) : ?>
					<h3><?php echo sprintf(lang('sign_up_third_party_heading')); ?></h3>
					<ul>
						<?php foreach ($this->config->item('third_party_auth_providers') as $provider) : ?>
						<li class="third_party <?php echo $provider; ?>"><?php echo anchor('account/connect_'.$provider, ' ', array('title' => sprintf(lang('sign_up_with'), lang('connect_'.$provider)))); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div><!-- /span6 -->
		<?php endif;?>
    </div>
</div>

<?php echo $this->load->view('footer'); ?>

</body>
</html>

