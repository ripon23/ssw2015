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
	<div class="span12">
    	<div class="panel panel-warning">  		
            <div class="panel-heading"><i class="icon-road"></i> <?=lang('car_urban_schedule_list')?> </div>
            <div class="panel-body">
            	<?php if(!empty($all_routes) ) : 
				$language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
				foreach ($all_routes as $route) :
				$schedules = $this->general->get_all_table_info_by_id_asc_desc_with_join('car_route_node_mapping', 'route_id', $route->route_id,'map_id','asc');
				//echo "<pre>"; print_r($schedules); echo "</pre>"; 				
				?>         
                <table class="table table-bordered">                	            
                    <tr>
                        <th colspan="<?php echo count($schedules)?>">
                        	<?=lang('route_name')?> : 
							<?php echo ($language=='bangla')? ($route->route_name_bn==NULL || $route->route_name_bn=='')?$route->route_name_en:$route->route_name_bn : $route->route_name_en?>
                        </th>                        
                    </tr>
                    <tr>
                    	
                    	<?php foreach ($schedules as $schedule) :?>
                        <td>
                    	<?php echo ($language=='bangla')? ($schedule->node_name_bn==NULL || $schedule->node_name_bn=='')? $schedule->node_name_en : $schedule->node_name_bn : $schedule->node_name_en?></div>
                       </td>
                        <?php 
						endforeach;
						?>                       
                    </tr>	
                </table>
                <?php 
				endforeach;
				endif
				?>
                
        
            </div><!-- /end panel-body -->
        </div><!-- /end panel -->
    </div>
	<div class="span9">
    
        
    <div class="panel panel-info">  		
    <div class="panel-heading"><i class="icon-road"></i> <?=lang('add_station_on_route')?> </div>
    <div class="panel-body">
    <?php echo form_open(uri_string(),'class="form-horizontal"') ?>
        
            <table class="table table-bordered">
                
                <tr>
                	<div class="control-group">
                	<td align="right" width="220"><label for="route_name_en"><strong><?php echo  lang('select_route')?> *</strong></label></td>
                	<td>
                    <?php 
					$language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
					?>
                    	<select name="route_id">
                        	<option value=""><?php echo  lang('select_route')?></option>
                            <?php foreach($all_routes as $route): ?>
                            <option value="<?php echo $route->route_id?>">
								<?php echo ($language=='bangla')? ($route->route_name_bn==NULL || $route->route_name_bn=='')?$route->route_name_en:$route->route_name_bn : $route->route_name_en?>
                             </option>
                            <?php endforeach;?>
                        </select>                    	
                    	
                        	<span class="help-inline" style="color:#EF030A">
                        	<?php echo form_error('route_id')?>
                            </span>
                    </td>
                    </div>
                    
                </tr>
                
                <tr>
                	<td align="right"><label for="node_id"><strong><?php echo  lang('select_node')?> *</strong></label></td>
                	<td>
                    	<select name="node_id">
                        	<option value=""><?php echo  lang('select_node')?></option>
                            <?php foreach($all_nodes as $node): ?>
                            <option value="<?php echo $node->node_id?>">
								<?php echo ($language=='bangla')? ($node->node_name_bn==NULL || $node->node_name_bn=='')?$node->node_name_en:$node->node_name_bn : $node->node_name_en?>
                             </option>
                            <?php endforeach;?>
                        </select>
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('node_id')?>
                        </span>
                    </td>
                </tr>
                
                <tr>
                	<td align="right"><label for="prev_node_id"><strong><?php echo  lang('select_prev_node')?> *</strong></label></td>
                	<td>
                    	<select name="prev_node_id">
                        	<option value=""><?php echo  lang('select_prev_node')?></option>
                            <option value="NULL"><?php echo  lang('null')?></option>
                            <?php foreach($all_nodes as $node): ?>
                            <option value="<?php echo $node->node_id?>">
								<?php echo ($language=='bangla')? ($node->node_name_bn==NULL || $node->node_name_bn=='')?$node->node_name_en:$node->node_name_bn : $node->node_name_en?>
                             </option>
                            <?php endforeach;?>
                        </select>
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('prev_node_id')?>
                        </span>
                    </td>
                </tr>
                
                <tr>
                	<td align="right"><label for="next_node_id"><strong><?php echo  lang('select_next_node')?> *</strong></label></td>
                	<td>
                    	<select name="next_node_id">
                        	<option value=""><?php echo  lang('select_next_node')?></option>
                            <option value="NULL"><?php echo  lang('null')?></option>
                            <?php foreach($all_nodes as $node): ?>
                            <option value="<?php echo $node->node_id?>">
								<?php echo ($language=='bangla')? ($node->node_name_bn==NULL || $node->node_name_bn=='')?$node->node_name_en:$node->node_name_bn : $node->node_name_en?>
                             </option>
                            <?php endforeach;?>
                        </select>
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('node_id')?>
                        </span>
                    </td>
                </tr>
                <tr>
                	<td align="right"><label for="duration_to_next"><strong><?php echo  lang('duration_to_next')?> </strong></label></td>
                	<td>
                    	<input type="time" class="input" name="duration_to_next" placeholder="<?php echo  lang('minute')?>"> 
                        <?php echo  lang('minute');
						//echo date('- d m Y h:i A',time());
						?>
                        
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('duration_to_next')?>
                        </span>
                    </td>
                </tr>
                <tr>
                	<td align="right"><label for="duration_to_next"><strong><?php echo  lang('duration_to_next')?> </strong></label></td>
                	<td>
                    	<input type="text" class="input-mini" name="duration_to_next" placeholder="<?php echo  lang('minute')?>"> 
                        <?php echo  lang('minute')?>
                        <span class="help-inline" style="color:#EF030A">
							<?php echo form_error('duration_to_next')?>
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