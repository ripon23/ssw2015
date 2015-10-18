<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
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

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/social_delivery_48.png" width="48" height="41"> <?=lang('services_social-goods')?> <a href="social_goods/social_goods/show_cart" id="cart-button" class="btn btn-medium btn-success pull-right" ><i class="icon-shopping-cart icon-white"></i> Cart (<?=$this->cart->total_items()?>)</a></div>
    <div class="panel-body">
       
   
    <?php echo form_open('social_goods/social_goods/search_social_goods_list') ?>
        
       <table class="table table-bordered">
        <tr align="center" class="warning">
          <td><?=lang('registration_no')?></td>
          <td><?=lang('services_point')?></td>
          <td><?=lang('package')?></td>
          <td><?=lang('status')?></td>
          
          
        </tr>
        <tr align="center" class="success">
            <td><input type="text" name="sregistration_no" id="sregistration_no" value="<?php echo isset($sregistration_no)?$sregistration_no:'';?>" class="input-medium" placeholder="XXXXXXXXXXXXX"/></td>
            <td>
            <select name="sservices_point" class="input-medium" id="sservices_point">            	
            	<option value=""><?php echo lang('settings_select'); ?></option>
                <?php foreach ($all_services_point as $services_point) : ?>
                <option value="<?php echo $services_point->site_id; ?>" <?php if(isset($sservices_point)){ if($services_point->site_id==$sservices_point) echo ' selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='english'? $services_point->site_name:$services_point->site_name_bn; ?></option>
                <?php endforeach; ?> 
            </select> 
            </td>            
            <td>
            <select name="spackage" class="input-medium" id="spackage" disabled>            	
            	<option value=""><?php echo lang('settings_select'); ?></option>
                <?php foreach ($all_package as $package) : ?>
                <option value="<?php echo $package->package_id; ?>" <?php if(isset($spackage)){ if($package->package_id==$spackage) echo ' selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='english'? $package->package_name:$package->package_name_bn; ?></option>
                <?php endforeach; ?> 
            </select> 
            </td>
            <td>
            <select name="sservices_status" id="sservices_status" class="selectpicker span1.5" data-style="btn">
            	<option value=""><?php echo lang('settings_select'); ?></option>
                <option value="zero" data-content="<span class='label label-warning'>Pending</span>" <?php if(isset($sservices_status)){ if($sservices_status=="zero") echo ' selected="selected"'; }?>>Pending</option>
                <option value="1" data-content="<span class='label label-info'>Process</span>" <?php if(isset($sservices_status)){ if($sservices_status=="1") echo ' selected="selected"'; }?>>Process</option>
                <option value="2" data-content="<span class='label label-success'>Taken</span>" <?php if(isset($sservices_status)){ if($sservices_status=="2") echo ' selected="selected"'; }?>>Taken</option>
                <option value="3" data-content="<span class='label label-important'>Cancel</span>" <?php if(isset($sservices_status)){ if($sservices_status=="3") echo ' selected="selected"'; }?>>Cancel</option>
            </select>
            </td>
          
          </tr>
          <tr align="center" class="warning">
            <td colspan="2"><?=lang('date_between')?></td>
          	<td><?=lang('today')?></td>       
            <td></td>
          </tr>
          <tr align="center" class="success">
            <td colspan="2"><input type="text" name="sdate1" id="sdate1" value="<?php echo isset($sdate1)?$sdate1:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/> <?=lang('website_and')?> <input type="text" name="sdate2" id="sdate2" value="<?php echo isset($sdate2)?$sdate2:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/></td>
            <td><input type="checkbox" name="Today" id="Today" onClick="cuttentdate()" /></td>
            <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('mainmenu_view_registration')?>" class="btn-small btn-primary" /></td>
          </tr>
        </table>
        
        </form>
	
    
<table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th><abbr title="Services ID">S.ID</abbr></th>
                <th><?=lang('registration_no')?></th>
                <th><?=lang('settings_fullname')?></th>
<th><?=lang('site')?></th>
                <th><?=lang('services_point')?></th>
                <th><?=lang('package')?></th>
                <th><?=lang('services')?> <?=lang('date_time')?></th>
                <th><?=lang('status')?></th>               
                <?php if ($this->authorization->is_permitted('add_information_services')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>                				
</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_social_goods) ) {
			foreach ($all_social_goods as $social_goods) : ?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $social_goods->reg_for_service_id; ?></td>
                <td><?php echo $social_goods->registration_no; ?></td>
                <td><?php 
				$reg_info= $this->registration_model->get_all_registration_info_by_id($social_goods->registration_no); 
				echo "<a href=".base_url().'registration/registration/view_single_registration/'.$social_goods->registration_no.">".$reg_info->first_name." ".$reg_info->middle_name." ".$reg_info->last_name."</a>";
				
				?>
</td>
                <td><?php if($social_goods->services_point_id) echo $this->ref_site_model->get_site_name_by_sp_id($social_goods->services_point_id); ?></td>
                <td><?php if($social_goods->services_point_id) echo $this->ref_site_model->get_site_name_by_id($social_goods->services_point_id); ?></td>
                <td><?php if($social_goods->services_package_id) echo $this->ref_services_model->get_package_name_by_id($social_goods->services_package_id); ?></td>
                <td><?php echo $social_goods->services_date; ?></td>
                <td>
				<?php 
				if($social_goods->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($social_goods->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($social_goods->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($social_goods->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}
			else if	($social_goods->services_status==4)
				{
				echo '<span class="label label-important">Deleted</span>';
				}	
				?>                
              </td>                
                
                <?php //if ($this->authorization->is_permitted('add_social_goods')) : ?> 
                <td>
                <?php 				
				if (($this->authorization->is_permitted('entry_order')) && ($social_goods->services_status<2)) : 	
				?>
                <a href="<?php echo base_url().'social_goods/social_goods/check_out/'.$social_goods->reg_for_service_id;?>" class="btn btn-small btn-info"><?=lang('website_checkout')?></a>
                <?php
				endif; 				
				?>
                </td>                
                                
</tr>
            <?php 
			$i=$i+1;
			endforeach; 
			}//end if
			?> 
             
    	</table>                
		<div style="text-align:left"><?php echo $links; ?></div>
    
     
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>