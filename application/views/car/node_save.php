<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript">
    $(function(){
        $("#route_id").change(function()
        {
            var route_id=$(this).val();
            var field_name = 'route_id';
            var table_name = 'car_node';
            //var base_url = $('#base_url').val();

            // get_chile($table_name, $where_field, $where_field_value, $select)
            var url = "car/booking/get_chile/"+table_name+"/"+field_name+"/"+route_id;
            // var url = "car/booking/test";
            $.ajax
            ({
                type: "GET",
                url: url,
                beforeSend : function (){
                    //$(".dis_load").html('<img src="'+base_url+'images/ajax/ajax-1.gif">');
                    $("#node_list").html('<option value ="">Loading...</option>');
                },
                //data: dataString,
                cache: false,               
                success: function(response)
                {
                    $("#node_list").html(response);
                }
            });
        })
    })
</script>
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
                	<td align="right" width="180"><label for="route_name_en"><strong><?php echo  lang('node_name_en')?></strong></label></td>
                	<td>
                    	
                    	<input type="text" id="route_name_en" value="<?php echo set_value('node_name_en') ? set_value('node_name_en') : (isset($update_details->node_name_en) ? $update_details->node_name_en : '')?>"  name="node_name_en" placeholder="<?php echo lang('node_name_en')?>">
                    	<span class="help-inline" style="color:#EF030A">
                    	<?php echo form_error('node_name_en')?>
                        </span>
                    </td>
                    </div>
                    
                </tr>
                
                <tr>
                	<td align="right"><label for="node_name_bn"><strong><?php echo  lang('node_name_bn')?></strong></label></td>
                	<td>
                    	<input type="text" id="node_name_bn" value="<?php echo set_value('node_name_bn') ? set_value('node_name_bn') : (isset($update_details->node_name_bn) ? $update_details->node_name_bn : '')?>"  name="node_name_bn" placeholder="<?php echo lang('node_name_bn')?>">
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('node_name_bn')?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><label for="route_id"><strong>Select Route</strong></label></td>
                    <td>
                        <select name="route_id" id="route_id" class="selectpicker span2" data-style="btn">
                            <option value="">Select Route</option>
                            <?php foreach ($all_routes as $routes) {?>
                            <option <?php echo (isset($update_details->route_id) && $update_details->route_id == $routes->route_id) ? 'selected' :''; ?> value="<?php echo $routes->route_id?>"><?php echo $routes->route_name_en?></option>
                                
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><label for="node_list"><strong>Previous Node</strong></label></td>
                    <td>
                        <select name="previous_node" id="node_list" class="span2">
                            <option value="">Select Node</option>                            
                        </select>
                    </td>
                </tr>
                <tr>
                    <div class="control-group">
                    <td align="right" width="180"><label for="distance_previous"><strong>Distance From Previous Node</strong></label></td>
                    <td>
                        <div class="input-prepend">
                          <span class="add-on">K.M</span>
                          <input class="input-small" type="text" id="distance_previous" value="<?php echo set_value('distance_previous') ? set_value('distance_previous') : (isset($update_details->distance_previous) ? $update_details->distance_previous : '')?>"  name="distance_previous" placeholder="Distance Previous In Kilometer">
                        </div>
                        
                        <span class="help-inline" style="color:#EF030A">
                        <?php echo form_error('node_name_en')?>
                        </span>
                    </td>
                    </div>
                    
                </tr>

                <tr>
                    <div class="control-group">
                    <td align="right" width="180"><label for="time_previous"><strong>Time From Previous In Minute</strong></label></td>
                    <td>
                        <div class="input-append">                          
                          <input type="text" id="time_previous" class="input-small" value="<?php echo set_value('time_previous') ? set_value('time_previous') : (isset($update_details->time_previous) ? $update_details->time_previous : '')?>"  name="time_previous" placeholder="Minute">
                            <span class="add-on">Minute</span>
                        </div>
                        
                        <span class="help-inline" style="color:#EF030A">
                        <?php echo form_error('time_previous')?>
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