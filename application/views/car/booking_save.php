<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script src= "http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>

<script>
$(window).on('load', function () {
	$('.selectpicker').selectpicker({
		'selectedText': 'cat'
	});	
});
	

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span9">    
    <div class="panel panel-info">  		
    <div class="panel-heading"><i class="icon-road"></i> <?php echo lang('booking_now'); ?></div>
    <div class="panel-body">
    <?php echo form_open(uri_string(),'class="form-horizontal"');
	$language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
	?>
        	<input type="hidden" value="<?php echo $schedule_details->schedule_id; ?>" name="id">
            <span style="color:red">
            	<?php echo form_error('id');?>
            </span>
            <table class="table table-bordered" data-ng-app="" data-ng-init="quantity=<?php echo 1;?>;price=<?php echo $schedule_details->price;?>">                
                <tr>
                	<div class="control-group">
                	<td align="right" width="180"><?php echo lang('route_name');?> </td>
                	<td>                    
                    	<?php echo ($language=='bangla')? ($route_details->route_name_bn==NULL || $route_details->route_name_bn=='')?$route_details->route_name_en:$route_details->route_name_bn : $route_details->route_name_en?>
                    	
                    </td>
                    </div>
                    
                </tr>
                
                 <tr>
                	<td align="right"><?php echo lang('schedule_time'); ?></td>
                	<td>
						<?php echo date("g:i A", strtotime($schedule_details->start_time));  ?>
                        
                    </td>
                </tr>
                <tr>
                	<td align="right">
                    	 <?php echo lang('booking_date'); ?>
                    </td>
                    <td>
                    	<input name="booking_date" type="date">
                    	<span style="color:red;">
                    		<?php echo form_error('booking_date');?>
                    	</span>
                    </td>
                    
                </tr>
                <tr>
                	<td align="right">
                    	 <?php echo lang('rise_station'); ?>
                    </td>
                    <td>
                        <select name="rise_station">
                        	<option value=""><?php echo lang('select'); ?></option>
                            <?php foreach($route_nodes as $node): ?>
                            <option value="<?php echo $node->node_id?>">
								<?php echo ($language=='bangla')? ($node->node_name_bn==NULL || $node->node_name_bn=='')?$node->node_name_en:$node->node_name_bn : $node->node_name_en?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <span style="color:red;">
                    		<?php echo form_error('rise_station');?>
                    	</span>
                    </td>
                </tr>
                <tr>
                	<td align="right">
                    	 <?php echo lang('off_station'); ?>
                    </td>
                    <td>
                    	<select name="off_station">
                        	<option value=""><?php echo lang('select'); ?></option>
                            <?php foreach($route_nodes as $node): ?>
                            <option value="<?php echo $node->node_id?>">
								<?php echo ($language=='bangla')? ($node->node_name_bn==NULL || $node->node_name_bn=='')?$node->node_name_en:$node->node_name_bn : $node->node_name_en?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <span style="color:red;">
                    		<?php echo form_error('off_station');?>
                    	</span>
                    </td>
                </tr>
                <tr>
                	<td align="right">
                    <?php echo lang('no_of_set'); ?>
                    </td>
                	<td>                    	   
                        <input class="input-small" name="no_of_set" type="number" ng-model="quantity">
                        <span style="color:red;">
							<?php echo form_error('no_of_set');
                            if (isset($no_of_seat_error)):
                            echo $no_of_seat_error;
                            endif;
                            ?>                            
                        </span>  
                    </td>    
                                  
                </tr>
                
                <tr>
                	<td align="right">
                    	 <?php echo lang('price'); ?>
                    </td>
                    <td>
                    <input id="appendedInput" readonly class="span1 uneditable-input" value="<?php echo $schedule_details->price;?>" name="price" type="number" ng-model="price">
                    <span style="color:red;">
                    	<?php echo form_error('price');?>
                    </span>
                    </td>
                </tr>
                <tr>
                	<td align="right">
                     <?php echo lang('total_cost'); ?>: </p>
                    </td>
                    <td>
                    <div class="input-prepend input-append">
                    		<span class="add-on" style="font-size:16px;">৳</span>
                          <input disabled class="span1 uneditable-input" value="{{quantity * price}}" id="appendedInput" type="text">
                          <span class="add-on">.00</span>
                        </div>
                    
                    </td>
                </tr>
                <tr>
                	<td align="right"></td>
                    <td><button type="submit" class="btn btn-primary" name="submit"><i class="icon-check icon-white"></i> Save</button></td>
                </tr>
                
            </table>
            
        		<?php echo form_close();?>
	
    	</div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span9 -->
    
    <div class="span3">
    	<?php echo $this->load->view('car/car_sidebar');?>
    </div><!-- /end row -->
</div>
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>