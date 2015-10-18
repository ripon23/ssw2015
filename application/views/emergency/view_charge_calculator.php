<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
function calculate_total_price(per_km_price,per_min_price,minimum_price)
{
var total_price=0;	
var travel_distance=document.getElementById("travel_distance").value;
var travel_time=document.getElementById("travel_time").value;
var minimum_charge=document.getElementById("minimum_charge").value;
total_price=parseInt((per_km_price*travel_distance)+(per_min_price*travel_time)+parseInt(minimum_charge));

if(document.getElementById("need_return").checked===true)
	{
	//total_price=parseInt(total_price*2);	
	total_price=parseInt((parseInt(travel_distance*per_km_price)*2)+(per_min_price*travel_time)+parseInt(minimum_charge));	
	}

document.getElementById("total_charge").value=total_price;
}

jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('services_emergency')?>: <?=lang('menu_charge_calculator')?></div>
    <div class="panel-body">             		        
    
    <form class="form-horizontal" id="charge-calculator-form" action="" method="post">    
    
        <div class="control-group inline">
        <label class="control-label" for="travel_distance">Travel Distance</label>
        <div class="controls">
        <input type="text" id="travel_distance" placeholder="In Km" class="input-small" onKeyUp="calculate_total_price(<?=$charge_calculator_info->per_km_price?>,<?=$charge_calculator_info->per_min_price?>,<?=$charge_calculator_info->minimum_price?>)">
        <span class="help-inline">(<?=$charge_calculator_info->per_km_price?> <?=lang('taka')?>/Km)</span>
        </div>        
        </div>
        <div class="control-group">
        <label class="control-label" for="travel_time">Travel Time</label>
        <div class="controls">
        <input type="text" id="travel_time" placeholder="In minute" class="input-small" onKeyUp="calculate_total_price(<?=$charge_calculator_info->per_km_price?>,<?=$charge_calculator_info->per_min_price?>,<?=$charge_calculator_info->minimum_price?>)">
        <span class="help-inline">(<?=$charge_calculator_info->per_min_price?> <?=lang('taka')?>/Minute)</span>
        </div>
        </div>
        
        <div class="control-group">
        <label class="control-label" for="minimum_charge">Minimum Charge</label>
        <div class="controls">
        <input type="text" name="minimum_charge" id="minimum_charge" value="<?=$charge_calculator_info->minimum_price?>" class="input-small" onKeyUp="calculate_total_price(<?=$charge_calculator_info->per_km_price?>,<?=$charge_calculator_info->per_min_price?>,<?=$charge_calculator_info->minimum_price?>)">
        <span class="help-inline"><?=lang('taka')?></span>
        </div>
        </div>
        
        <div class="control-group">
        <div class="controls">
        <label class="checkbox">
        <input type="checkbox" name="need_return" id="need_return" onClick="calculate_total_price(<?=$charge_calculator_info->per_km_price?>,<?=$charge_calculator_info->per_min_price?>,<?=$charge_calculator_info->minimum_price?>)"> <i class="icon-share-alt"></i> Return 
        </label>
        </div>
        </div>
        
        <div class="control-group">
        <label class="control-label" for="minimum_charge">Total Charge</label>
        <div class="controls">
        <input type="text" name="total_charge" id="total_charge" value="" class="input-small" disabled>
        <span class="help-inline"><?=lang('taka')?></span>
        </div>
        </div>
    
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>