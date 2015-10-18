<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>

</head>
<body>

<?php echo $this->load->view('header', array('active'=>'dashboard')); ?>

		<?php if ($this->authentication->is_signed_in()) : ?>
        
        <?php if ($this->authorization->is_permitted('view_registration')|| $this->authorization->is_permitted('create_registration')||$this->authorization->is_permitted('add_services_point_schedule')||$this->authorization->is_permitted('edit_booking')) : ?>
		<div class="offset span12">
           	<div class="alert alert-info">
    		<button type="button" class="close" data-dismiss="alert">&times;</button>
    		
                <div class="form-search">              
                    <form class="form-search" method="post" action="<?php echo base_url().'dashboard/barcode_search/'?>">
                    <img src="<?php echo base_url().RES_DIR; ?>/img/barcode_scanner.png" width="24" height="24" >
                        <div class="input-append">                    
                        <input type="text" class="span2 search-query" placeholder="Registration No" name="registration_no" id="registration_no" value="<?php echo set_value('registration_no');?>">
                        <button type="submit" name="search" class="btn btn-info" ><?=lang('website_search')?></button>                    
                        </div>
                        <div class="error" style="float:right">
                        <?php echo form_error('registration_no'); ?>
                        <?php
                        if(isset($permission_msg))
                        echo $permission_msg;
                        ?>
                        </div>
                    </form>
                 </div>
    		</div> 
    	</div>
        <?php 
		endif;
        ?>
        
        <!---- Start --->
        <div class="span12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/school_bus_48.png" width="48" height="48"> 
                <?=lang('car_urban_services')?></h2>                
              </div>
              <div class="panel-body">
              	<div class="row">
                <?php if ($this->authorization->is_permitted(array('car_manage_route','car_manage_node', 'car_manage_picuppoint','car_manage','car_schedule_manage'))) : ?>
                    <div class="span3 panel panel-warning">
                      <div class="panel-heading">
                        <i class="icon-road"></i>
                        <?=lang('car_urban_route_management')?>          
                      </div>
                      <div class="panel-body">
                        <?php if ($this->authorization->is_permitted('car_manage_route')) : ?> 
                        <p><a href="<?=base_url();?>car/routes"><?=lang('car_urban_manage_route')?></a></p>
                        <?php endif;?>
                        <?php if ($this->authorization->is_permitted('car_manage_node')) : ?> 
                        <p><a href="<?=base_url();?>car/nodes"><?=lang('car_urban_manage_node')?></a></p>
                        <?php endif;?>
                        <?php if ($this->authorization->is_permitted('car_manage_picuppoint')) : ?> 
                        <p><a href="<?=base_url();?>car/pickuppoint"><?=lang('car_urban_add_pickuppoint')?></a></p>
                        <?php endif;?>                        
                         <?php if ($this->authorization->is_permitted('car_manage')) : ?> 
                        <p><a href="<?=base_url();?>car/add_car"><?=lang('car_list')?></a></p>
                        <?php endif;?>                                                 
						
						<?php if ($this->authorization->is_permitted('car_schedule_manage')) : ?> 
                        <p><a href="<?=base_url();?>car/schedules">On demand <?=lang('car_urban_schedule_management')?></a></p>
                        <?php endif;?>
                        
                        <?php if ($this->authorization->is_permitted('car_schedule_manage')) : ?> 
                        <p><a href="<?=base_url();?>car/sdrt_schedules">sDRT <?=lang('car_urban_schedule_management')?></a></p>
                        <?php endif;?>
                        
                       
                        
                        
                      </div>
                    </div>
                    <?php
					endif;
					?>
                    
                    <?php if ($this->authorization->is_permitted(array('car_booking_management','car_booking','car_my_booking'))) : ?>
                    
                    <div class="span3 panel panel-warning">
                      <div class="panel-heading">
                        <i class="icon-road"></i>
                        <?=lang('car_booking_info')?>          
                      </div>
                      <div class="panel-body">
                        <?php if ($this->authorization->is_permitted('car_booking_management')) : ?> 
                        <p><a href="<?=base_url();?>car/latest_booking"><?=lang('car_ondemand_booking_list')?></a> 
                        	<!--<span class="badge badge-warning"> 11</span>--></p>
                        
                        <p><a href="<?=base_url();?>car/latest_booking/latest_schedule_booking"><?=lang('car_schedule_booking_list')?></a></p>
                        <?php endif;?>
                        
                        <?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/booking"><?=lang('car_ondemand_booking')?></a></p>
                        <?php endif;?>
                        
                        <?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/booking/schedule_booking"><?=lang('car_schedule_booking')?></a></p>
                        <?php endif;?>
                        
						<?php if ($this->authorization->is_permitted('car_my_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/my_booking"><?=lang('car_my_ondemand_booking')?></a></p>
                        <?php endif;?> 
                        
                        <?php if ($this->authorization->is_permitted('car_my_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/my_booking/my_schedule_booking"><?=lang('car_my_schedule_booking')?></a></p>
                        <?php endif;?>                         
                        
                      </div>
                    </div>
                    <?php
					endif;
					?>
                    
                 </div>
              </div>
            </div>
            
        </div>
        
        <!---- END  ---->
        
		
        <?php 
		
		endif;
		?>       
        
        <?php if ($this->authentication->is_signed_in()) : ?>
        
        <?php if ($this->authorization->is_permitted('view_registration')|| $this->authorization->is_permitted('create_registration')||$this->authorization->is_permitted('add_services_point_schedule')||$this->authorization->is_permitted('edit_booking')) : ?>
        <div class="offset span4">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="48"> 
                <?=lang('dashboard_label_registration')?></h2>                
              </div>
              <div class="panel-body">
              <?php if ($this->authorization->is_permitted('create_registration')) : ?> 
              	<p><a href="<?=base_url();?>registration/registration/new_registration/"><?=lang('menu_new_registration')?></a></p>
                <?php endif;?>
                <?php if ($this->authorization->is_permitted('view_registration')) : ?> 
                <p><a href="<?=base_url();?>registration/registration/view_registration"><?=lang('menu_view_edit_delete_registration')?></a></p>
                <?php endif;?>
                
				<?php if ($this->authorization->is_permitted('create_family')) : ?> 
                <p><a href="<?=base_url();?>registration/registration/new_family_registration"><?=lang('menu_family_registration')?></a>                
                
				<?php if ($this->authorization->is_permitted('view_family')) : ?> 
                &nbsp;&nbsp;<a href="<?=base_url();?>registration/registration/family_list"><?=lang('menu_family_list')?></a>
                <?php endif;?>                               
                </p>
                
                <?php else :?>
                <p>&nbsp;</p>
                <?php endif;?>
                
                
                
              </div>
            </div>
        </div>        
        <?php endif;?>
        
         <?php if ($this->authorization->is_permitted('view_information_services')) : ?>
        <div class="offset span4">                 
			<div class="panel panel-success">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/internet_services_48.png" width="48" height="41"> <?=lang('services_internet')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>learning/learning"><?=lang('menu_learning_list')?></a></p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>
        <?php endif;?>  
        
         <?php if ($this->authorization->is_permitted('view_blood_grouping')) : ?> 
        <div class="offset span4">                 
			<div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/blood_gropping_48.png" width="48" height="41"> <?=lang('services_blood_grouping')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>blood_grouping/blood_grouping"><?=lang('menu_blood_grouping_list')?></a></p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>
        <?php endif;?>        
        
		<?php if ($this->authorization->is_permitted('view_health_checkup')) : ?>
        <div class="offset span4">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/health_checkup_48.png" width="48" height="41"> <?=lang('services_health-checkpup')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>health_checkup/health_checkup"><?=lang('menu_health_checkup_list')?></a></p>
                <p><a href="<?=base_url();?>booking/urban_health_booking/urban_health_booking"><?=lang('menu_urban_health_booking')?></a></p>
                <p><a href="<?=base_url();?>booking/urban_health_booking/create_urban_health_booking"><?=lang('create_urban_health_booking')?></a></p>
              </div>
            </div>
        </div>
        <?php endif;?>  
           
         <?php if ($this->authorization->is_permitted('view_college_bus_services')) : ?> 
        <div class="offset span4">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/school_bus_48.png" width="48" height="41"> <?=lang('services_college_bus')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>college_bus/college_bus/"><?=lang('services_college_bus')?> <?=lang('menu_list')?></a></p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>
         <?php endif;?> 
         <?php if ($this->authorization->is_permitted('view_emergency_services') ) : ?>  
        <div class="offset span4">                 
			<div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('services_emergency')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>emergency/emergency/charge_calculator"><?=lang('menu_charge_calculator')?></a></p>
                <p><a href="<?=base_url();?>emergency/emergency/emergency_services_list"><?=lang('services_emergency')?> <?=lang('menu_list')?></a></p>
                <p><a href="<?=base_url();?>emergency/emergency/charge_calculator_setting"><?=lang('menu_charge_calculator_setting')?></a></p>
              </div>
            </div>
        </div>
        <?php endif;?>
        <?php if ($this->authorization->is_permitted('edit_product') || $this->authorization->is_permitted('enter_product')) : ?>
        <div class="offset span4">                 
			<div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/social_delivery_48.png" width="48" height="41"> <?=lang('services_social-goods')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>social_goods/social_goods/"><?=lang('menu_view_product')?></a></p>
                <p><a href="<?=base_url();?>social_goods/social_goods/add_product/"><?=lang('menu_add_product')?></a></p>
                <p><a href="<?=base_url();?>social_goods/social_goods/order_list/"><?=lang('menu_order_list')?></a><span class="badge badge-warning"> <?=$this->social_goods_model->social_goods_new_order_count()?></span></p>
              </div>
            </div>
        </div>
        <?php endif;?>
        <?php if($this->authorization->is_permitted('add_edit_delete_gramcar_services')): ?>
        <div class="offset span4">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/setting_48.png" width="48" height="41"> <?=lang('basic_setting')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>basic_setting/basic_setting"><?=lang('website_add')?> / <?=lang('website_edit')?> /  <?=lang('website_delete')?> <?=lang('label_gramcar')?> <?=lang('services')?></a></p>
                <p><a href="<?=base_url();?>basic_setting/basic_setting/package_list"><?=lang('website_add')?> / <?=lang('website_edit')?> /  <?=lang('website_delete')?> <?=lang('label_gramcar')?> <?=lang('package')?></a></p>
                <p><a href="<?=base_url();?>basic_setting/basic_setting/site_list"><?=lang('website_add')?> / <?=lang('website_edit')?> /  <?=lang('website_delete')?> <?=lang('site')?> /  <?=lang('services_point')?> </a></p>
              </div>
            </div>
        </div>
		<?php endif;?>
        <?php if ($this->authorization->is_permitted('received_payment') || $this->authorization->is_permitted('approved_payment')) : ?> 
    	<div class="offset span4">                 
			<div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/payment_icon.png" width="48" height="41"> <?=lang('menu_payment')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>payment/payment"><?=lang('menu_payment_received')?></a></p>
                <p><a href="<?=base_url();?>report/report/report_home"><?=lang('menu_report')?></a></p>
                <p><a href="<?=base_url();?>payment/payment/payment_approval"><?=lang('menu_payment_approve')?></a> <span class="badge badge-warning"><?=$this->payment_model->payment_wating_for_approval_count()?></span></p>
              </div>
            </div>
        </div>
        <?php endif;?>
        <?php if($this->authorization->is_permitted('add_expenses')):?>
        <div class="offset span4">                 
			<div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/expenses.png" width="48" height="41"> <?=lang('expenses')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>expenses/expenses/"><?=lang('menu_expenses_list')?></a></p>
                <p><a href="<?=base_url();?>expenses/expenses/add_expenses/"><?=lang('menu_add_expenses')?></a></p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>        
        <?php endif;?>
        <?php if($this->authorization->is_permitted('add_consumables')):?>
        <div class="offset span4">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/consumables.png" width="48" height="41"> <?=lang('consumables')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>consumables/consumables/"><?=lang('menu_consumables_list')?></a></p>
                <p><a href="<?=base_url();?>consumables/consumables/add_consumables/"><?=lang('menu_add_consumables')?></a></p>
              	<p>&nbsp;</p>
              </div>
            </div>
        </div>
        <?php endif;
		endif;
		?>
        
        <?php if ($this->authorization->is_permitted('create_health_booking')) : ?>
        <div class="offset span4">
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/health_checkup_48.png" width="48" height="41"> <?=lang('create_urban_health_booking')?></h2>                
              </div>
              <div class="panel-body">              	
                <p><a href="<?=base_url();?>booking/urban_health_booking/create_urban_health_booking"><?=lang('create_urban_health_booking')?></a></p>
                <p><a href="<?=base_url();?>booking/urban_health_booking/my_urban_health_booking_list"><?=lang('my')?> <?=lang('urban_health_booking_list')?></a></p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>
        <?php endif;?>  
        
 	</div>
 </div>       
<?php echo $this->load->view('footer'); ?>
</body>
</html>