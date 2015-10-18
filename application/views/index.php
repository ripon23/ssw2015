<!DOCTYPE html>
<html>
<head>
	<?php echo $this->load->view('head'); ?>

</head>
<body>

<?php echo $this->load->view('header'); ?>

        <div class="offset span4">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/school_bus_48.png" width="48" height="41"> <?=lang('car_urban_schedule_of_week')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>health_checkup/health_checkup"><?=lang('menu_health_checkup_list')?></a></p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>
        
        <div class="offset span8">                 
			<div class="panel panel-warning">
              <div class="panel-heading">
                <h2 class="panel-title"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/school_bus_48.png" width="48" height="41"> <?=lang('car_urban_car_schedule_of_today')?></h2>                
              </div>
              <div class="panel-body">
              	<p><a href="<?=base_url();?>blood_grouping/blood_grouping"><?=lang('menu_blood_grouping_list')?></a></p>
                <p>&nbsp;</p>
              </div>
            </div>
        </div>
    </div>
  </div>
        
        
        
        
<?php echo $this->load->view('footer'); ?>

</body>
</html>