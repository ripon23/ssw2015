<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
$(document).ready(function(){
	$(".delete").click(function(e){
		e.preventDefault(); 
		var href = $(this).attr("href");
		var btn=this;
			
		if(confirm("Sure you want to delete it? There is NO undo!"))
		{
			window.location.assign(href);
		
		}
		return false;
	});
});

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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('car_urban_node_management')?> </div>
    <div class="panel-body">
       
   
    <?php echo form_open('car/add_car/search') ?>
       
            <table class="table table-bordered">
                <tr class="warning">
                	<td>
                    	<input type="text" value="<?php echo set_value('car_licence')?>"  name="car_licence" placeholder="<?php echo 'Car Licence No.'?>">
                    </td>
                    <?php
					  $selected = isset( $_POST['status'] ) ? $_POST['status'] : '' ;
					?>                    
                    <td align="center">
                    <select name="status" id="sservices_status" class="selectpicker span1.5" data-style="btn">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <option value="1" <?php echo $selected == 1 ? 'selected' : '' ?> data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
						><?php echo lang('active')?></option>
                        <option value="0" <?php echo $selected == '0' ? 'selected' : '' ?> data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
                    </select>
                    </td>                    
                    <td>
                    <button class="btn-small btn-primary" type="submit" name="search_submit"><i class="icon-search icon-white"></i> <?=lang('website_search')?></button>
                    </td>
                </tr>
            </table>
            
        <?php echo form_close();?>
	
    
        <table class="table table-bordered table-striped">
			<tr>
                <th width="40"><?=lang('sl')?></th>
                <th><?=lang('car_model')?></th>
                <th><?=lang('car_licence')?></th>
                <th><?=lang('car_driver')?></th>
                <th><?=lang('hot_line')?></th>
                <th><?=lang('status')?></th>                               
                <th>
				<?php if ($this->authorization->is_permitted('car_add')) : ?> 
                <a href="car/add_car/save" class="btn btn-mini btn-primary"> <i class="icon-plus-sign icon-white"></i> <?=lang('car_add')?></a> 
                <?php endif; ?>
                </th>                				
			</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$page = (isset($page))? $page:0;
			$i=$page+1;
			?>
            <?php 
			if(!empty($all_car) ) {
			$language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
			foreach ($all_car as $car) :
			?>
            
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $car->model?></td>
                <td><?php echo $car->licence_no; ?></td>
                <td>                    
                <?php 
                $driver_results = $this->general->get_all_table_info_by_id_custom("a3m_account_details", "fullname", 'account_id', $car->driver_id);
                echo $driver_results->fullname;?>
                </td>
                
                <td><?php echo $car->hot_line; ?></td>
                
                <td align="center">
                	<span class="label <?php echo ($car->enable==0)? 'label-warning':'label-info'?>">
						<?php echo ($car->enable==NULL || $car->enable==0)? lang('inactive') :lang('active')?>
                    </span>    
                </td>
                <td width="150">
                	<?php if($this->authorization->is_permitted('car_edit')): ?>
                	<a href="car/add_car/save/<?php echo $car->car_id?>" class="btn btn-small btn-warning"><i class="icon-edit icon-white"></i> <?php echo lang('website_edit'); ?></a>
                    <?php endif; ?>
                    <?php if($this->authorization->is_permitted('car_delete')): ?>
                    <a href="car/add_car/delete/<?php echo $car->car_id?>" class="btn btn-small btn-danger delete"><i class="icon-trash icon-white"></i> <?php echo lang('website_delete'); ?></a>
                    <?php endif ?>
                </td>
            </tr>
            <?php 
			$i=$i+1;
			endforeach; 
			}
			else			
			{
			?> 
            <tr>
            	<th colspan="9" align="left"><?php echo lang('not_found'); ?></th>
            </tr>
            <?php 
			}
			?>
             
    	</table>                
		<div style="text-align:left"><?php if(isset($links)) echo $links; ?></div>
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