<div class="panel panel-warning">  		
    <div class="panel-heading"> <i class="icon-road"></i> <?=lang('services_car')?> <?=lang('menu_list')?></div>
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
        <p><a href="<?=base_url();?>car/schedules"><?=lang('car_urban_schedule_management')?></a></p>
        <?php endif;?>
    </div>
</div>