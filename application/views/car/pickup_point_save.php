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
    <div class="panel-heading"><i class="icon-road"></i> <?=lang('car_urban_add_node')?> </div>
    <div class="panel-body">
    <?php echo form_open(uri_string(),'class="form-horizontal"') ?>
        
            <table class="table table-bordered">                
                <tr>
                	<div class="control-group">
                	<td align="right" width="180"><label for="route_name_en"><strong><?php echo  lang('car_pickuppoint_name')?></strong></label></td>
                	<td>
                    	
                    	<input type="text" id="pickup_point_en" value="<?php echo set_value('pickup_point_en') ? set_value('pickup_point_en') : (isset($update_details->pickup_point_en) ? $update_details->pickup_point_en : '')?>"  name="pickup_point_en" placeholder="<?php echo lang('node_name_en')?>">
                        	<span class="help-inline" style="color:#EF030A">
                        	<?php echo form_error('pickup_point_en')?>
                            </span>
                    </td>
                    </div>
                    
                </tr>
                
                <tr>
                	<td align="right"><label for="pickup_point_bn"><strong><?php echo  lang('car_pickuppoint_name')?></strong></label></td>
                	<td>
                    	<input type="text" id="pickup_point_bn" value="<?php echo set_value('pickup_point_bn') ? set_value('pickup_point_bn') : (isset($update_details->pickup_point_bn) ? $update_details->pickup_point_bn : '')?>"  name="pickup_point_bn" placeholder="<?php echo lang('node_name_bn')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('pickup_point_bn')?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th align="right"><label for="pickup_point_bn"><strong>Select Node</strong></label></th>
                    <?php
                      $selected_node = isset( $_POST['node_id'] ) ? $_POST['node_id'] : '' ;
                    ?>                    
                    <td>
                    <select name="node_id" id="sservices_status" class="selectpicker span2" data-style="btn">
                        <option value="">Select Node</option>
                        <?php foreach ($all_car_node as $car_node) {
                            ?>
                            <option value="<?php echo $car_node->node_id ?>" <?php echo (isset($update_details->node_id) && $update_details->node_id == $car_node->node_id? 'selected' : '')?>><?php echo $car_node->node_name_en?></option>

                            <?php 
                        }?>
                    </select>
                    <span class="help-inline" style="color:#EF030A">
                        <?php echo form_error('node_id')?>
                    </span>
                    </td>
                </tr>
                <tr>
                    <div class="control-group">
                    <td align="right" width="180"><label for="distance_to_node"><strong>Distance to Node</strong></label></td>
                    <td>
                        <div class="input-prepend">
                          <span class="add-on">K.M</span>
                          <input type="text" id="distance_to_node" value="<?php echo set_value('distance_to_node') ? set_value('distance_to_node') : (isset($update_details->distance_to_node) ? $update_details->distance_to_node : '')?>"  name="distance_to_node" placeholder="Distance to node">
                        </div>
                        
                        <span class="help-inline" style="color:#EF030A">
                        <?php echo form_error('distance_to_node')?>
                        </span>
                    </td>
                    </div>
                    
                </tr>
                <tr>
                	<td align="right"><label for="latitude"><strong><?php echo  lang('latitude')?></strong></label></td>
                	<td>
                    	<input type="text" id="latitude" value="<?php echo set_value('latitude') ? set_value('latitude') : (isset($update_details->latitude) ? $update_details->latitude : '')?>"  name="latitude" placeholder="<?php echo lang('latitude')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('latitude')?>
                        </span>
                    </td>
                </tr>
                
                 <tr>
                	<td align="right"><label for="longitude"><strong><?php echo  lang('longitude')?></strong></label></td>
                	<td>
                    	<input type="text" id="longitude" value="<?php echo set_value('longitude') ? set_value('longitude') : (isset($update_details->longitude) ? $update_details->longitude : '')?>"  name="longitude" placeholder="<?php echo lang('longitude')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('longitude')?>
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
                        <option <?php echo (isset($update_details->enable) && $update_details->enable == 0? 'selected' : '')?> value="0" data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                    </select>
                    <span class="help-inline" style="color:#EF030A">
						<?php echo form_error('status')?>
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