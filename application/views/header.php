<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container" style="border:none; background-color:#1b1b1b; padding:0">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
			<?php 
			echo anchor('', lang('website_title'), 'class="brand"'); 			
			?>
            <!--<a href="#"> <img src="<?php echo RES_DIR?>/img/gym_logo.png" alt="GYM" /></a>-->
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="divider-vertical"></li>
                    <!--<li><?php echo anchor('', 'Nav Link 1'); ?></li>
                    <li><?php echo anchor('', 'Nav Link 2'); ?></li>
                    <li><a href="#"><?=$this->session->userdata('site_lang')?></a></li>
                    <li><a href='<?php echo base_url(); ?>langswitch/switchLanguage/english'>English</a></li>
					<li><a href='<?php echo base_url(); ?>langswitch/switchLanguage/bangla'>Bangla</a></li>
					-->
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        	<i class="icon-font icon-white"></i> <?php 
							if($this->session->userdata('site_lang'))
							echo ucfirst($this->session->userdata('site_lang'));
							else
							echo "English";							
							?> <b class="caret"></b></a>

                        <ul class="dropdown-menu">
                        <li><?php echo anchor('langswitch/switchLanguage/english', 'English'); ?></li>
						<li><?php echo anchor('langswitch/switchLanguage/bangla', 'Bangla'); ?></li>
                        </ul>
                </ul>

                <ul class="nav pull-right">
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php if ($this->authentication->is_signed_in()) : ?>
                        	<i class="icon-user icon-white"></i> <?php echo $account->username; ?> <b class="caret"></b></a>
						<?php else : ?>
                        	<i class="icon-user icon-white"></i> <b class="caret"></b></a>
						<?php endif; ?>

                        <ul class="dropdown-menu">
							<?php if ($this->authentication->is_signed_in()) : ?>
                                <li class="nav-header">Account Info</li>
								<li><?php echo anchor('account/account_profile', lang('website_profile')); ?></li>
								<li><?php echo anchor('account/account_settings', lang('website_account')); ?></li>
								<?php if ($account->password) : ?>
									<li><?php echo anchor('account/account_password', lang('website_password')); ?></li>
								<?php endif; ?>
								<!--<li><?php //echo anchor('account/account_linked', lang('website_linked')); ?></li>-->    
                                <?php if ($this->authorization->is_permitted( array('retrieve_users', 'retrieve_roles', 'retrieve_permissions') )) : ?>
                                    <li class="divider"></li>
                                    <li class="nav-header">Admin Panel</li>
                                    <?php if ($this->authorization->is_permitted('retrieve_users')) : ?>
                                        <li><?php echo anchor('account/manage_users', lang('website_manage_users')); ?></li>
                                    <?php endif; ?>

                                    <?php if ($this->authorization->is_permitted('retrieve_roles')) : ?>
                                        <li><?php echo anchor('account/manage_roles', lang('website_manage_roles')); ?></li>
                                    <?php endif; ?>

                                    <?php if ($this->authorization->is_permitted('retrieve_permissions')) : ?>
                                        <li><?php echo anchor('account/manage_permissions', lang('website_manage_permissions')); ?></li>
                                    <?php endif; ?>
                                <?php endif; ?>

								<li class="divider"></li>
								<li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
							<?php else : ?>
								<li><?php echo anchor('account/sign_in', lang('website_sign_in')); ?></li>
							<?php endif; ?>

                        </ul>
                    </li>
                </ul>

            </div>
            <!--.nav-collapse -->
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="span12">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit" style="position: relative;">
                <div class="ribbon-wrapper-green">
                    <div class="ribbon-green">beta</div>
                </div>                
                <?php echo anchor('', '<img src='.RES_DIR.'/img/gramcar_logo.png alt=GramCar />'); ?>
                <!--<p><?php //echo lang('website_owner'); ?></p>

                <p><a class="btn btn-primary btn-large pull-right" href="http://gramweb.net/"><i class="icon-wrench icon-white"></i> Fork it &raquo;
                </a></p>-->
            </div>

        </div>

		<?php echo $this->load->view('main_nav'); ?>
		
        <div class="span12">
			<?php if ($this->session->flashdata('parmission')):?>
            <div class="alert alert-danger" role="alert">
            <i class="icon-info-sign"></i>
            <strong>Worning!</strong>
            <?php
            echo  $this->session->flashdata('parmission');
            ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php endif;?>
        </div>
        <div class="span12">
			<?php if ($this->session->flashdata('message_success')):?>
            <div class="alert alert-success" role="alert">
            	<i class="icon-ok"></i>
                <strong>Success!</strong>
                <?php
                echo  $this->session->flashdata('message_success');
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php endif;?>
        </div>
        <div class="span12">
			<?php if ($this->session->flashdata('message_error')):?>
            <div class="alert" role="alert">
            	<i class="icon-info-sign"></i>
                <strong>Warning!</strong>
                <?php
                echo  $this->session->flashdata('message_error');
                ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php endif;?>
        </div>