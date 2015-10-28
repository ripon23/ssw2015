<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>

</head>
<body>

<?php echo $this->load->view('header', array('active'=>'dashboard')); ?>
		


		<?php if ($this->authentication->is_signed_in()) : ?>
        
             
        <!---- Start --->

                    <?php if ($this->authorization->is_permitted(array('car_booking_management','car_booking','car_my_booking'))) : ?>
                    <div class="offset span3">
                    <div class="panel panel-warning">
                      <div class="panel-heading">
                        <i class="icon-plane"></i>
                        <?=lang('car_ondemand_booking')?>        
                      </div>
                      <div class="panel-body">                        
                        
                        <?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/booking"> <img src="<?php echo base_url().RES_DIR; ?>/img/on-demand-services-220.png" width="220" height="139">   </a></p>
                        <?php endif;?>                                                
                        
                      </div>
                    </div>
                    </div>
                    <?php
					endif;
					?>
                    
                    
                    <?php if ($this->authorization->is_permitted(array('car_booking_management','car_booking','car_my_booking'))) : ?>
                    
                    <div class="offset span3">
                    <div class="panel panel-warning">
                      <div class="panel-heading">
                        <i class="icon-plane"></i>
                        <?=lang('car_schedule_booking')?>        
                      </div>
                      <div class="panel-body">                        

                        <?php if ($this->authorization->is_permitted('car_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/booking/schedule_booking"><img src="<?php echo base_url().RES_DIR; ?>/img/schedule-services-220.png" width="220" height="139"></a></p>
                        <?php endif;?>                        
                        
                      </div>
                    </div>
                    </div>
                    <?php
					endif;
					?>
                    
                    
                    <?php if ($this->authorization->is_permitted(array('car_booking_management','car_booking','car_my_booking'))) : ?>
                    
                   <div class="offset span3">
                    <div class="panel panel-warning">
                      <div class="panel-heading">
                        <i class="icon-plane"></i>
                        <?=lang('car_my_booking')?>        
                      </div>
                      <div class="panel-body">                        
                        
						<?php if ($this->authorization->is_permitted('car_my_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/my_booking"><?=lang('car_my_ondemand_booking')?></a></p>
                        <?php endif;?> 
                        
                        <?php if ($this->authorization->is_permitted('car_my_booking')) : ?> 
                        <p><a href="<?=base_url();?>car/my_booking/my_schedule_booking"><?=lang('car_my_schedule_booking')?></a></p>
                        <?php endif;?>
                        
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        
                      </div>
                    </div>
                    </div>
                    <?php
					endif;
					?>                    
                    
                    <?php if ($this->authorization->is_permitted('create_health_booking')) : ?>
                    <div class="offset span3">
                    <div class="panel panel-warning">
                      <div class="panel-heading">
                        <i class="icon-briefcase"></i>
                         <?=lang('create_urban_health_booking')?>   
                      </div>
                      <div class="panel-body">               	
                            <p><a href="<?=base_url();?>booking/urban_health_booking/create_urban_health_booking"><?=lang('create_urban_health_booking')?></a></p>
                            <p><a href="<?=base_url();?>booking/urban_health_booking/my_urban_health_booking_list"><?=lang('my')?> <?=lang('urban_health_booking_list')?></a></p>
                            <p>&nbsp;</p>
                        	<p>&nbsp;</p>
                        	<p>&nbsp;</p>
                         </div>
                    </div>
                    </div>
                    <?php
					endif;
					?>

        <!---- END  ---->
        
		
        <?php 
		
		endif;
		?>       
       
        
 	</div>
 </div>       
<?php echo $this->load->view('footer'); ?>
</body>
</html>