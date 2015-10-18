<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
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

jQuery(document).ready(function(){
<!-- Start -->
	$("#reg_site").change(function()
	{
	var id=$(this).val();
	var dataString;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_servicespoint/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#sservices_point").html(html);			
			}
		});
	
	});
	<!-- End -->
	
	<!-- Start -->
	$("#reg_services").change(function()
	{
	var id=$(this).val();
	var dataString;		
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_services_pacakge/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#spackage").html(html);			
			}
		});
	
	});
	<!-- End -->
});
</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/internet_services_48.png" width="48" height="41"> <?=lang('menu_report')?></div>
    <div class="panel-body">
       
   
    <?php echo form_open('report/report/report_home_search') ?>
        
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
            <select name="reg_site" class="input-large" id="reg_site">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if(isset($sreg_site)){ if($sreg_site==$site1->site_id) echo ' selected="selected"'; } ?>><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
            
            <select name="sservices_point" class="input-medium" id="sservices_point">            	
            	<option value=""><?php echo lang('settings_select'); ?></option>                
            </select> 
            </td>            
            <td>
            <select name="reg_services" class="input-large" id="reg_services">
            <option value=""><?php echo lang('settings_select'); ?></option>   
            <?php foreach ($services as $services1) : ?>
            	<option value="<?php echo $services1->services_id; ?>" <?php if(isset($sreg_services)){ if($sreg_services==$services1->services_id) echo ' selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='bangla'? $services1->services_name_bn:$services1->services_name; ?></option>
				<?php endforeach; ?>         
	        </select>
            
            <select name="spackage" class="input-medium" id="spackage">            	
            	<option value=""><?php echo lang('settings_select'); ?></option>                
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
            <td>Services <?=lang('date_between')?></td>
            <td>Payment 
            <?=lang('date_between')?></td>
          	<td><?=lang('today')?></td>       
            <td></td>
          </tr>
          <tr align="center" class="success">
            <td><input type="text" name="sdate1" id="sdate1" value="<?php echo isset($sdate1)?$sdate1:'';?>" placeholder="YYYY-MM-DD" class="input-small"/> <?=lang('website_and')?> <input type="text" name="sdate2" id="sdate2" value="<?php echo isset($sdate2)?$sdate2:'';?>" placeholder="YYYY-MM-DD" class="input-small"/></td>
            <td><input type="text" name="pdate1" id="pdate1" value="<?php echo isset($pdate1)?$pdate1:'';?>" placeholder="YYYY-MM-DD" class="input-small"/> <?=lang('website_and')?> <input type="text" name="pdate2" id="pdate2" value="<?php echo isset($pdate2)?$pdate2:'';?>" placeholder="YYYY-MM-DD" class="input-small"/></td>
            <td><input type="checkbox" name="Today" id="Today" onClick="cuttentdate()" /></td>
            <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('mainmenu_view_registration')?>" class="btn-small btn-primary" /></td>
          </tr>
        </table>
        
        </form>
		
        <table class="table table-bordered">
        	<tr>
            	<td>Total Services: <?php if($number_of_services) echo $number_of_services;?></td>
                <td>Total Amount Received: <?php if($total_amount_received) echo $total_amount_received;?> <?=lang('taka')?></td>
                <td><a href="./report/report/export_to_excel">Export to excel</a></td>
            <tr/>
      </table>
    
<table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th><abbr title="Services ID">S.ID</abbr></th>
                <th><?=lang('registration_no')?></th>
                <th><?=lang('settings_fullname')?></th>
<th><?=lang('site')?></th>
                <th><?=lang('services_point')?></th>
                <th><?=lang('services')?> </th>
                <th><?=lang('package')?></th>
                <th><?=lang('services')?> <?=lang('date_time')?></th>
                <th>Payment received <?=lang('date_time')?></th>
                <th><?=lang('status')?></th> 
                <th>Received</th>  
                <th>Approved</th>                                            				
</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_reg_services) ) {
			foreach ($all_reg_services as $report) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td>
				<?php 
				if(($report->services_id==2) && ($report->services_status==2))
				echo '<a href="health_checkup/health_checkup/view_single_health_checkup/'.$report->reg_for_service_id.'">'.$report->reg_for_service_id.'</a>';				
				if(($report->services_id==2) && ($report->services_status!=2))
				echo $report->reg_for_service_id;
                
				if(($report->services_id==3) && ($report->services_status==2))
				echo '<a href="blood_grouping/blood_grouping/view_single_blood_grouping/'.$report->reg_for_service_id.'">'.$report->reg_for_service_id.'</a>';
				if(($report->services_id==3) && ($report->services_status!=2))
				echo $report->reg_for_service_id;
				
				if(($report->services_id==4) && ($report->services_status==2))
				{
				$order_id=$this->social_goods_model->get_order_id_from_reg_service_id($report->reg_for_service_id);	
				echo '<a href="social_goods/social_goods/social_goods_order_details/'.$order_id.'">'.$report->reg_for_service_id.'</a>';
				//echo $report->reg_for_service_id;
				}
				
				if(($report->services_id==4) && ($report->services_status!=2))
				echo $report->reg_for_service_id;
				
				if(($report->services_id==5) && ($report->services_status==2))
				echo '<a href="learning/learning/view_single_learning/'.$report->reg_for_service_id.'">'.$report->reg_for_service_id.'</a>';
				if(($report->services_id==5) && ($report->services_status!=2))
				echo $report->reg_for_service_id;
				
				if(($report->services_id==6) && ($report->services_status==2))
				echo '<a href="emergency/emergency/view_single_emergency/'.$report->reg_for_service_id.'">'.$report->reg_for_service_id.'</a>';
				if(($report->services_id==6) && ($report->services_status!=2))
				echo $report->reg_for_service_id;
				?>                                
                </td>
                <td><?php echo $report->registration_no; ?></td>
                <td><?php 
				$reg_info= $this->registration_model->get_all_registration_info_by_id($report->registration_no); 
				echo "<a href=".base_url().'registration/registration/view_single_registration/'.$report->registration_no.">".$reg_info->first_name." ".$reg_info->middle_name." ".$reg_info->last_name."</a>";				
				?>
</td>
                <td><?php if($report->services_point_id) echo $this->ref_site_model->get_site_name_by_sp_id($report->services_point_id); ?></td>
                <td><?php if($report->services_point_id) echo $this->ref_site_model->get_site_name_by_id($report->services_point_id); ?></td>
                <td><?php if($report->services_id) echo $this->ref_services_model->get_services_name_by_id($report->services_id);?></td>
                <td><?php if($report->services_package_id) echo $this->ref_services_model->get_package_name_by_id($report->services_package_id); ?></td>
                <td><?php echo $report->services_date; ?></td>
                <td><?php if($report->payment_received_date) echo $report->payment_received_date; ?></td>
                <td>
				<?php 
				if($report->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($report->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($report->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($report->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}
			else if	($report->services_status==4)
				{
				echo '<span class="label label-important">Deleted</span>';
				}	
				?>                
              </td>                
              <td><?php if(($report->received_amount)||($report->received_amount=='0')) echo "<a href=".base_url().'payment/payment/view_single_payment/'.$report->reg_for_service_id.">".$report->received_amount." ".lang('taka')."</a>"; ?> </td>  
              <td><?php  //echo $report->approved_status;
			  if($report->approved_status=='0')
				{
				echo '<span class="label label-warning">Unapproved</span>';				
				}
				else if($report->approved_status=='1')
				{
				echo '<span class="label label-success">Approved</span>';
				}
				else
				{
				echo '<span class="label">Not Paid</span>';
				}
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