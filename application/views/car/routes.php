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
			/*$.ajax({
				type: "POST",
				url: href,
				success: function(response){
					if(response ==1 || response !=0 ){
						$(btn).parents('tr').fadeOut("slow");					
					}
				}
			});*/
		
		}
		return false;
	});
});
$(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });


function cuttentdate()
{
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
//document.write(today);
document.getElementById('sdate1').value=today;
}


</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span9">
    
    <div class="panel panel-info">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/emergency_48.png" width="48" height="41"> <?=lang('car_urban_manage_route')?> </div>
    <div class="panel-body">
       
   
    <?php echo form_open('car/routes/search_route') ?>
       
            <table class="table table-bordered">
                
                <tr class="warning">
                	<td>
                    	<input type="text" value="<?php echo set_value('route_name')?>"  name="route_name" placeholder="Route Name">
                    </td>                    
                    <td>
                    <select name="status" id="sservices_status" class="selectpicker span1.5" data-style="btn">
                        <option value=""><?php echo lang('settings_select'); ?></option>
                        <option value="1" data-content="<span class='label label-success'><?php echo lang('active')?></span>" 
						><?php echo lang('active')?></option>
                        <option value="0" data-content="<span class='label label-warning'><?php echo lang('inactive')?></span>" ><?php echo lang('inactive')?></option>
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
                <th><?=lang('route_name')?></th>
                <!--<th><?=lang('start_node')?></th>-->
                <th>Fixed Cost</th>
                <th><?=lang('status')?></th>                               
                <th>
				<?php if ($this->authorization->is_permitted('car_add_route')) : ?> 
                <a href="car/routes/save" class="btn btn-primary"> <i class="icon-plus-sign icon-white"></i> <?=lang('car_urban_add_route')?></a> 
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
			if(!empty($all_routes) ) {
			$language = ($this->session->userdata('site_lang')=='bangla')? 'bangla':$this->config->item("default_language");
			foreach ($all_routes as $route) : 
			?>
            
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td>
				<?php echo ($language=='bangla')? ($route->route_name_bn==NULL || $route->route_name_bn=='')?$route->route_name_en:$route->route_name_bn : $route->route_name_en?>
                </td>
               <!-- <td>
                	<?php echo  $route->start_node;?>
                </td>-->
                <td align="center">
                	<?php echo  "৳ ".$route->fixed_cost;?>
                </td>
                <td align="center">
                	<span class="label <?php echo ($route->enable==0)? 'label-warning':'label-info'?>">
						<?php echo ($route->enable==NULL || $route->enable==0)? lang('inactive') :lang('active')?>
                    </span>    
                </td>
                <td width="150">
                	<?php if($this->authorization->is_permitted('car_delete_route')): ?>
                	<a href="car/routes/save/<?php echo $route->route_id?>" class="btn btn-small btn-warning"><i class="icon-edit icon-white"></i> <?php echo lang('website_edit'); ?></a>
                    <?php endif; ?>
                    <?php if($this->authorization->is_permitted('car_delete_route')): ?>
                    <a href="car/routes/delete/<?php echo $route->route_id?>" class="btn btn-small btn-danger delete"><i class="icon-trash icon-white"></i> <?php echo lang('website_delete'); ?></a>
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
            	<th colspan="7"><?php echo lang('not_found'); ?></th>
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