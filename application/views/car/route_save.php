<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
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
    <div class="panel-heading"><i class="icon-road"></i> <?=lang('car_urban_add_route')?> </div>
    <div class="panel-body">
    <?php echo form_open(uri_string(),'class="form-horizontal"') ?>
        
            <table class="table table-bordered">
                
                <tr>
                	<div class="control-group">
                	<td align="right" width="180"><label for="route_name_en"><strong><?php echo  lang('route_name_en')?></strong></label></td>
                	<td>                    	
                    	<input type="text" value="<?php echo set_value('route_name_en') ? set_value('route_name_en') : (isset($update_details->route_name_en) ? $update_details->route_name_en : '')?>"  name="route_name_en" placeholder="<?php echo lang('route_name_en')?>">
                    	<span class="help-inline" style="color:#EF030A">
                    	   <?php echo form_error('route_name_en')?>
                        </span>
                    </td>
                    </div>
                    
                </tr>
                
                <tr>
                	<td align="right"><label for="route_name_bn"><strong><?php echo  lang('route_name_bn')?></strong></label></td>
                	<td>
                    	<input type="text" value="<?php echo set_value('route_name_bn') ? set_value('route_name_bn') : (isset($update_details->route_name_bn) ? $update_details->route_name_bn : '')?>"  name="route_name_bn" placeholder="<?php echo lang('route_name_bn')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('route_name_bn')?>
                        </span>
                    </td>
                </tr>
                
                <tr>
                	<td align="right"><label for="status"><strong><?php echo  lang('status')?></strong></label></td>                
                    <td>
                    <select name="status" id="status" class="selectpicker span2" data-style="btn">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <option <?php echo $this->input->post('enable')==1 ? 'selected' : (isset($update_details->enable) && $update_details->enable == 1? 'selected' : '')?> value="1" data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
						><?php echo lang('active')?></option>
                        <option <?php isset($update_details->enable) && $update_details->enable == "0"? 'selected' : ''?> value="0" data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                    </select>
                    <span class="help-inline" style="color:#EF030A">
						<?php echo form_error('status')?>
                    </span>
                    </td>  
                </tr>
                <tr>
                	<td align="right"><label for="status"><strong><?php echo  lang('select_car')?></strong></label></td>
                    <td>
                    	<select name="car">
                        	<option value=""><?php echo  lang('settings_select')?></option>
                            <?php foreach ($all_car as $car) :?>
                            <option <?php echo $this->input->post('car')==$car->car_id ? 'selected' : (isset($update_details->car_id) && $update_details->car_id == $car->car_id? 'selected' : '')?> value="<?php echo $car->car_id; ?>"><?php echo $car->licence_no; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    
                </tr>
                <tr>
                    <!-- Appended Input-->
                    <div class="control-group">
                        <th><label class="control-label" for="fcost"><strong>Fixed Cost</strong></label></th>
                        <td>
                            <div class="input-prepend">
                              <span class="add-on">৳</span>
                              <input value="<?php echo set_value('fcost') ? set_value('fcost') : (isset($update_details->fixed_cost) ? $update_details->fixed_cost : '')?>" id="fcost" name="fcost" class="input-large" placeholder="Fixed Cost" type="text">
                            </div>
                            <p class="help-block">
                                    <?php echo form_error('fcost')?>
                            </p>
                        </td>
                    </div>
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