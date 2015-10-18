<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('services_emergency')?>: <?=lang('menu_charge_calculator_setting')?></div>
    <div class="panel-body">             		        
    
    <?php 
	if(validation_errors())
	{					 
	?>
    <div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=validation_errors()?>
    </div>
	<?php
	}
	?>
    
    <?php 
	if(isset($success_msg))
	{					 
	?>
    <div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$success_msg?>
    </div>
	<?php
	}
	?>
    
    <form class="form-horizontal" id="charge-calculator-form" action="" method="post">    
    
        <div class="control-group inline">
        <label class="control-label" for="per_km_price">Per Km price</label>
        <div class="controls">
        <input type="text" id="per_km_price" name="per_km_price" placeholder="In Km" class="input-small" value="<?=$charge_calculator_info->per_km_price?>">
        <span class="help-inline"> <?=lang('taka')?></span>
        </div>        
        </div>
        <div class="control-group">
        <label class="control-label" for="per_min_price">Per minute price</label>
        <div class="controls">
        <input type="text" id="per_min_price" name="per_min_price" placeholder="In minute" class="input-small" value="<?=$charge_calculator_info->per_min_price?>" >
        <span class="help-inline"><?=lang('taka')?></span>
        </div>
        </div>
        
        <div class="control-group">
        <label class="control-label" for="minimum_charge">Minimum Charge</label>
        <div class="controls">
        <input type="text" name="minimum_charge" id="minimum_charge" value="<?=$charge_calculator_info->minimum_price?>" class="input-small">
        <span class="help-inline"><?=lang('taka')?></span>
        </div>
        </div>        
    
     <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_update'); ?>" />
            </div>
        </div>
        
    </div><!-- /end span11 -->
    
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>