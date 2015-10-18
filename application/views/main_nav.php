<?php
$activeurl1= $this->uri->segment(1);
$activeurl2= $this->uri->segment(2);
$activeurl3= $this->uri->segment(3);
$activeurl=$activeurl1."/".$activeurl2."/".$activeurl3;
//echo $activeurl;
?>
<div class="span12">
     <div class="navbar">
        <div class="navbar-inner">
            <a class="navbar-brand" href="<?php echo base_url();?>" ><span class="glyphicon glyphicon-home" aria-hidden="true"></span> </a>
            <ul class="nav">
            <li <?php echo (isset($active) && $active=='home')?' class="active"':'' ?>><a href="./"><?=lang('mainmenu_home')?></a></li>
            
            <?php if ($this->authentication->is_signed_in()) : ?>
            <?php if ($this->authorization->is_permitted('view_registration')|| $this->authorization->is_permitted('create_registration')||$this->authorization->is_permitted('add_services_point_schedule')||$this->authorization->is_permitted('edit_booking')) : ?>
            <li class="dropdown <?php echo $activeurl=='registration/registration/'?' active':''; echo $activeurl=='registration/registration/view_registration'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('menu_registration')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">
                 <?php if ($this->authorization->is_permitted('create_registration')) : ?>  
                    <li <?php echo $activeurl=='registration/registration/new_registration/'?' class="active"':'' ?>><a tabindex="-1" href="./registration/registration/new_registration/"><?=lang('menu_new_registration')?></a></li>
                 <?php endif; ?> 
                  <?php if ($this->authorization->is_permitted('view_registration')) : ?>    
                    <li <?php echo $activeurl=='registration/registration/view_registration'?' class="active"':'' ?>><a tabindex="-1" href="<?=base_url();?>registration/registration/view_registration"><?=lang('menu_view_edit_delete_registration')?></a></li>
					<?php endif; ?>
                
				<?php if ($this->authorization->is_permitted('create_family')) : ?> 
                <li><a href="<?=base_url();?>registration/registration/new_family_registration"><?=lang('menu_family_registration')?></a></li>
                <?php endif; ?>
                 <?php if ($this->authorization->is_permitted('add_services_point_schedule')) : ?> 
                    <li <?php echo $activeurl=='services_point_schedule/services_point_schedule/add_services_point_schedule'?' class="active"':'' ?>><a tabindex="-1" href="<?=base_url();?>services_point_schedule/services_point_schedule/add_services_point_schedule"><?=lang('menu_add_services_point_schedule')?></a></li>
                 <?php endif; ?>  
                 
                 <?php if ($this->authorization->is_permitted('edit_booking')) : ?> 
                    <li <?php echo $activeurl=='booking/booking/booking_list'?' class="active"':'' ?>><a tabindex="-1" href="<?=base_url();?>booking/booking/booking_list"><?=lang('menu_booking_list')?></a></li>
                 <?php endif; ?>  
                 
                 <?php if ($this->authorization->is_permitted('create_urban_health_schedule')) : ?> 
                    <li <?php echo $activeurl=='urban_schedule/urban_schedule/create_urban_health_schedule'?' class="active"':'' ?>><a tabindex="-1" href="<?=base_url();?>urban_schedule/urban_health_schedule/create_urban_schedule"><?=lang('menu_create_urban_health_schedule')?></a></li>
                 <?php endif; ?>  
                 
                 <?php if ($this->authorization->is_permitted('create_urban_health_schedule')) : ?> 
                    <li <?php echo $activeurl=='booking/urban_health_booking/urban_health_booking_list'?' class="active"':'' ?>><a tabindex="-1" href="<?=base_url();?>booking/urban_health_booking/urban_health_booking_list"><?=lang('urban_health_booking_list')?></a></li>
                 <?php endif; ?>
                                      
	            </ul>
            </li>
             <?php endif; ?> 
             <?php if ($this->authorization->is_permitted('view_health_checkup')) : ?>
            <li class="dropdown <?php echo $activeurl=='health_checkup/health_checkup/'?' active':''; echo $activeurl=='health_checkup/search_health_checkup_list'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('menu_health_checkup')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">
                   
                    <li <?php echo $activeurl=='health_checkup/health_checkup'?' class="active"':'' ?>><a tabindex="-1" href="./health_checkup/health_checkup"><?=lang('menu_health_checkup_list')?></a></li>
                                                        
	            </ul>
            </li>
             <?php endif; ?>   
           	 <?php if ($this->authorization->is_permitted('view_blood_grouping')) : ?> 
             <li class="dropdown <?php echo $activeurl=='blood_grouping/blood_grouping/'?' active':''; echo $activeurl=='blood_grouping/search_blood_grouping_list'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('menu_blood_grouping')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">
                 
                    <li <?php echo $activeurl=='blood_grouping/blood_grouping'?' class="active"':'' ?>><a tabindex="-1" href="./blood_grouping/blood_grouping"><?=lang('menu_blood_grouping_list')?></a></li>
                                                            
	            </ul>
            </li>
            <?php endif; ?>
            <?php if ($this->authorization->is_permitted('view_information_services')) : ?>
            <li class="dropdown <?php echo $activeurl=='learning/learning/'?' active':''; echo $activeurl=='learning/search_learning_list'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('menu_learning')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">                   
                    <li <?php echo $activeurl=='learning/learning'?' class="active"':'' ?>><a tabindex="-1" href="./learning/learning"><?=lang('menu_learning_list')?></a></li>
                                                         
	            </ul>
            </li>            
            <?php endif; ?>  
  <?php if ($this->authorization->is_permitted('edit_product') || $this->authorization->is_permitted('enter_product')) : ?>
            <li class="dropdown <?php echo $activeurl=='social_goods/social_goods/'?' active':''; echo $activeurl=='social_goods/social_goods'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('services_social-goods')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">                 
                    <li <?php echo $activeurl=='social_goods/social_goods/view_product_grid'?' class="active"':'' ?>><a tabindex="-1" href="./social_goods/social_goods/view_product_grid"><?=lang('menu_view_product')?> (<?=lang('menu_grid')?>)</a></li>  
                    <li <?php echo $activeurl=='social_goods/social_goods/view_product_list'?' class="active"':'' ?>><a tabindex="-1" href="./social_goods/social_goods/view_product_list"><?=lang('menu_view_product')?> (<?=lang('menu_list')?>)</a></li>               
                 	
                  	<?php if ($this->authorization->is_permitted('edit_product')) : ?>  
                    <li <?php echo $activeurl=='social_goods/social_goods/product_management'?' class="active"':'' ?>><a tabindex="-1" href="./social_goods/social_goods/product_management"><?=lang('menu_product_management')?></a></li>                   
                 	<?php endif; ?> 
                    
                  	<?php if ($this->authorization->is_permitted('enter_product')) : ?>  
                    <li <?php echo $activeurl=='social_goods/social_goods/add_product'?' class="active"':'' ?>><a tabindex="-1" href="./social_goods/social_goods/add_product"><?=lang('menu_add_product')?></a></li>
                    <li <?php echo $activeurl=='social_goods/social_goods/order_list'?' class="active"':'' ?>><a tabindex="-1" href="./social_goods/social_goods/order_list"><?=lang('menu_order_list')?></a> </li>
                 	<?php endif; ?>
                                                           
	            </ul>
            </li>
            <?php endif; ?>
            <?php if ($this->authorization->is_permitted('view_emergency_services') || $this->authorization->is_permitted('view_emergency_services')) : ?>
            <li class="dropdown <?php echo $activeurl=='emergency/emergency/'?' active':''; echo $activeurl=='emergency/emergency'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('services_emergency')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">                 
                    <li <?php echo $activeurl=='emergency/emergency/charge_calculator'?' class="active"':'' ?>><a tabindex="-1" href="./emergency/emergency/charge_calculator"><?=lang('menu_charge_calculator')?></a></li>   
                                                                      	
                  	<?php if ($this->authorization->is_permitted('view_emergency_services')) : ?>  
                    <li <?php echo $activeurl=='emergency/emergency/emergency_services_list'?' class="active"':'' ?>><a tabindex="-1" href="./emergency/emergency/emergency_services_list"><?=lang('services_emergency')?> <?=lang('menu_list')?></a></li>                   
                 	<?php endif; ?> 
                    
                    <?php if ($this->authorization->is_permitted('view_emergency_services')) : ?>  
                    <li <?php echo $activeurl=='emergency/emergency/charge_calculator_setting'?' class="active"':'' ?>><a tabindex="-1" href="./emergency/emergency/charge_calculator_setting"><?=lang('menu_charge_calculator_setting')?></a></li>
                 	<?php endif; ?>                                      	
                                                           
	            </ul>
            </li>
            <?php endif; ?>  
            <?php if ($this->authorization->is_permitted('view_college_bus_services')) : ?> 
            <li class="dropdown <?php echo $activeurl=='college_bus/college_bus/'?' active':''; echo $activeurl=='college_bus/college_bus'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('services_college_bus')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">                 
                     
                    <li <?php echo $activeurl=='college_bus/college_bus/college_bus_services_list'?' class="active"':'' ?>><a tabindex="-1" href="./college_bus/college_bus"><?=lang('services_college_bus')?> <?=lang('menu_list')?></a></li>   
                                                           
	            </ul>
            </li>
            <?php endif; ?>  
             <?php if ($this->authorization->is_permitted('received_payment') || $this->authorization->is_permitted('approved_payment')) : ?> 
            <li class="dropdown <?php echo $activeurl=='payment/payment/'?' active':''; echo $activeurl=='payment/payment/payment_approval'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('menu_payment')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">
                <li <?php echo $activeurl=='payment/payment/registration_payment'?' class="active"':'' ?>><a tabindex="-1" href="./payment/payment/registration_payment"><?=lang('menu_registration_payment_received')?></a></li>
                 <?php if ($this->authorization->is_permitted('received_payment')) : ?>  
                    <li <?php echo $activeurl=='payment/payment'?' class="active"':'' ?>><a tabindex="-1" href="./payment/payment"><?=lang('menu_payment_received')?></a></li>
                 <?php endif; ?>  
                 
                  <?php if ($this->authorization->is_permitted('approved_payment')) : ?>  
                    <li <?php echo $activeurl=='payment/payment/payment_approval'?' class="active"':'' ?>><a tabindex="-1" href="./payment/payment/payment_approval"><?=lang('menu_payment_approve')?></a></li>
                 <?php endif; ?>                                          
	            </ul>
            </li> 
            <?php endif; ?> 
            <?php if($this->authorization->is_permitted('add_expenses')):?>
            <li class="dropdown <?php echo $activeurl=='report/report/'?' active':''; echo $activeurl=='report/report/report_home'?' active':'' ?>" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" ><?=lang('menu_report')?><b class="caret"></b></a>
	            <ul class="dropdown-menu">
                    <li <?php echo $activeurl=='report/report/report_home'?' class="active"':'' ?>><a tabindex="-1" href="./report/report/report_home"><?=lang('menu_report')?> </a></li>  
                    <li <?php echo $activeurl=='report/report/report_daily_services'?' class="active"':'' ?>><a tabindex="-1" href="./report/report/report_daily_services"><?=lang('menu_daily_services')?> </a></li>  
                    <li <?php echo $activeurl=='report/report/report_daily_revenue'?' class="active"':'' ?>><a tabindex="-1" href="./report/report/report_daily_revenue"><?=lang('menu_daily_revenue')?> </a></li>                
                    <li <?php echo $activeurl=='report/report/report_expense'?' class="active"':'' ?>><a tabindex="-1" href="./report/report/report_expense"><?=lang('expenses')?> </a></li>                
	            </ul>
            </li> 
            <?php endif;
			endif;
			?>
            <?php if (!$this->authentication->is_signed_in()) : ?>
            <li <?php echo (isset($active) && $active=='sign_in')?' class="active"':'' ?>><?php echo anchor('account/sign_in', lang('website_sign_in')); ?></li>
            <li <?php echo (isset($active) && $active=='sign_up')?' class="active"':'' ?>><?php echo anchor('account/sign_up', lang('website_sign_up')); ?></li>
            <?php endif;?>
            <?php if ($this->authentication->is_signed_in()) : ?>
            <li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
            <?php endif;?>            
            <?php if (!$this->authentication->is_signed_in()) : ?>
            <li <?php echo $activeurl=='booking/booking/'?' class="active"':'' ?>><a tabindex="-1" href="./booking/booking"><?=lang('mainmenu_booking')?></a></li>
            <?php endif;?>
            <?php if ($this->authentication->is_signed_in()) : ?>
            <li <?php echo (isset($active) && $active=='dashboard')?' class="active"':'' ?>><a href="dashboard"><?=lang('mainmenu_dashboard')?></a></li>
            <?php endif;?>
            </ul>
            </div>
            </div>
</div>