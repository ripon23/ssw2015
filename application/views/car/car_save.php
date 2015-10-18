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
    <div class="panel-heading"><i class="icon-road"></i> <?=lang('car_add')?> </div>
    <div class="panel-body">
    <?php echo form_open(uri_string(),'class="form-horizontal"') ?>
        
            <table class="table table-bordered">                
                <tr>
                	<div class="control-group">
                	<td align="right" width="180"><label for="model"><strong><?php echo  lang('car_model')?> *</strong></label></td>
                	<td>
                    	
                    	<input type="text" id="model" value="<?php echo set_value('model') ? set_value('model') : (isset($update_details->model) ? $update_details->model : '')?>"  name="model" placeholder="<?php echo lang('car_model')?>">
                        	<span class="help-inline" style="color:#EF030A">
                        	<?php echo form_error('model')?>
                            </span>
                    </td>
                    </div>
                    
                </tr>
                
                <tr>
                	<div class="control-group">
                	<td align="right" width="180"><label for="brand"><strong><?php echo  lang('car_brand')?></strong></label></td>
                	<td>
                    	
                    	<input type="text" id="brand" value="<?php echo set_value('brand') ? set_value('brand') : (isset($update_details->brand) ? $update_details->brand : '')?>"  name="brand" placeholder="<?php echo lang('car_brand')?>">
                        	<span class="help-inline" style="color:#EF030A">
                        	<?php echo form_error('brand')?>
                            </span>
                    </td>
                    </div>
                    
                </tr>
                
                <tr>
                	<td align="right"><label for="car_licence"><strong><?php echo  lang('car_licence')?> *</strong></label></td>
                	<td>
                    	<input type="text" id="car_licence" value="<?php echo set_value('car_licence') ? set_value('car_licence') : (isset($update_details->licence_no) ? $update_details->licence_no : '')?>"  name="car_licence" placeholder="<?php echo lang('car_licence')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('car_licence')?>
                        </span>
                    </td>
                </tr>
                
                <tr>
                	<td align="right"><label for="driver_id"><strong><?php echo  lang('car_driver')?></strong></label></td>
                	<td>
                        <select id="car_driver" name="driver_id" class="input-large">
                            <option value="">Select Driver</option>
                            <?php foreach ($drivers as  $driver) {
                                ?>
                                <option <?php echo ($this->input->post('driver_id')==$driver->id) ? 'selected' : (isset($update_details->driver_id) && $update_details->driver_id == $driver->id) ? 'selected' : '';?> value="<?php echo $driver->id;?>"><?php echo $driver->fullname;?></option>
                                <?php
                            }
                            ?>                            
                        </select>
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('driver_id')?>
                        </span>
                    </td>
                </tr>
                               
                <tr>
                	<td align="right"><label for="no_of_set"><strong><?php echo  lang('no_of_set')?> *</strong></label></td>
                	<td>
                    	<input type="text" id="no_of_set" value="<?php echo set_value('no_of_set') ? set_value('dms_lat') : (isset($update_details->no_of_set) ? $update_details->no_of_set : '')?>"  name="no_of_set" placeholder="<?php echo lang('no_of_set')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('no_of_set')?>
                        </span>
                    </td>
                </tr>
                
                 <tr>
                	<td align="right"><label for="hot_line"><strong><?php echo  lang('hot_line')?> *</strong></label></td>
                	<td>
                    	<input type="text" id="hot_line" value="<?php echo set_value('hot_line') ? set_value('hot_line') : (isset($update_details->hot_line) ? $update_details->hot_line : '')?>"  name="hot_line" placeholder="<?php echo lang('hot_line')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('hot_line')?>
                        </span>
                    </td>
                </tr>
                
                <tr>
                	<td align="right"><label for="status"><strong><?php echo  lang('status')?> *</strong></label></td>                
                    <td>
                    <select name="status" id="status" class="selectpicker span2" data-style="btn">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <option <?php echo $this->input->post('enable')==1 ? 'selected' : (isset($update_details->enable) && $update_details->enable == 1? 'selected' : '')?> value="1" data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
						><?php echo lang('active')?></option>
                        <option <?php echo (isset($update_details->enable) && $update_details->enable == 0? 'selected' : '')?> value="0" data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                    </select>
                    <span class="help-inline" style="color:#EF030A">
						<?php echo form_error('status')?>
                    </span>
                    </td>  
                </tr>
                <tr>
                	<td align="right"><label for="parking_address"><strong><?php echo  lang('parking_address')?></strong></label></td>
                	<td>                                           
                    	<textarea class="span5" id="parking_address"  name="parking_address"><?php echo set_value('parking_address') ? set_value('parking_address') : (isset($update_details->parking_address) ? $update_details->parking_address : '')?></textarea>
                    	<span class="help-inline" style="color:#EF030A">
							<?php echo form_error('parking_address')?>
                        </span>	
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