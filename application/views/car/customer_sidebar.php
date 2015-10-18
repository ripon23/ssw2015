<!--<div class="panel panel-warning"> 		
    <div class="panel-heading"> <i class="icon-road"></i> <?=lang('car_my_menu')?></div>
    <div class="panel-body">-->
    	<ul class="nav nav-list">
            <?php if ($this->authorization->is_permitted('car_booking')) {?>
                <li <?php echo (isset($active) && $active=='booking')?' class="active"':'' ?>"><?php echo anchor('car/booking', 'Book a seat'); ?>
            </li>
                <?php 
            }?>
        	<?php if($this->authorization->is_permitted('car_my_booking')):?>
        	<li <?php echo (isset($active) && $active=='my_booking')?' class="active"':'' ?>"><?php echo anchor('car/my_booking', lang('car_my_ondemand_booking')); ?>
            </li>
            <?php endif;?>  
            
            <?php if($this->authorization->is_permitted('car_my_booking')):?>
        	<li <?php echo (isset($active) && $active=='my_schedule_booking')?' class="active"':'' ?>"><?php echo anchor('car/my_booking/my_schedule_booking', lang('car_my_schedule_booking')); ?>
            </li>
            <?php endif;?>  
            
            <li <?php echo (isset($active) && $active=='sign_out')?' class="active"':'' ?>"> 
            <?php echo anchor('account/sign_out', lang('website_sign_out')); ?>
            </li>
            
           
            </ul>