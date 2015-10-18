<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>
function approvedclick_id(reg_services_id)
{
	
	//var reg_no= document.getElementById('registration_id_'+numeric).value;	

    $.ajax({
           type: "POST",
           url: "payment/payment/approved_payment/"+reg_services_id,
		   data: "reg_services_id="+reg_services_id,
           success: function(msg)
           {               				   	
			alert(msg); // show response from the php script.			      	
			location.reload(true); 
           }
         });

    return false; // avoid to execute the actual submit of the form.
	
			
}// END approvedclick_id

function unapprovedclick_id(reg_services_id)
{
	
	//var reg_no= document.getElementById('registration_id_'+numeric).value;	

    $.ajax({
           type: "POST",
           url: "payment/payment/unapproved_payment/"+reg_services_id,
		   data: "reg_services_id="+reg_services_id,
           success: function(msg)
           {               				   	
			alert(msg); // show response from the php script.			      	
			location.reload(true); 
           }
         });

    return false; // avoid to execute the actual submit of the form.
	
			
}// END approvedclick_id

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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/payment_icon.png" width="48" height="41"> <?=lang('menu_payment')?></div>
    <div class="panel-body">
       
   
    <?php echo form_open('payment/payment/payment_approval') ?>
        
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
            	<option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $site1->site_name:$site1->site_name_bn; ?></option>
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
            	<option value="<?php echo $services1->services_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $services1->services_name:$services1->services_name_bn; ?></option>
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
                <th><?=lang('taka')?> <?=lang('website_receive')?> <?=lang('date_time')?></th>
                <th><?=lang('taka')?> <?=lang('quantity')?></th>
                <th><?=lang('status')?></th>               
                <?php if ($this->authorization->is_permitted('approved_payment')) : ?> 
                <th><?=lang('menu_payment_approve')?></th> 
                <?php endif; ?>                				
</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php foreach ($all_payment as $payment) : ?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $payment->reg_for_service_id; ?></td>
                <td><?php echo $payment->registration_no; ?></td>
                <td><?php 
				$reg_info= $this->registration_model->get_all_registration_info_by_id($payment->registration_no); 
				echo "<a href=".base_url().'registration/registration/view_single_registration/'.$payment->registration_no.">".$reg_info->first_name." ".$reg_info->middle_name." ".$reg_info->last_name."</a>";
				
				?>
</td>
                <td><?php if($payment->services_point_id) echo $this->ref_site_model->get_site_name_by_sp_id($payment->services_point_id); ?></td>
                <td><?php if($payment->services_point_id) echo $this->ref_site_model->get_site_name_by_id($payment->services_point_id); ?></td>
                <td><?php if($payment->services_package_id) echo $this->ref_services_model->get_package_name_by_id($payment->services_package_id); ?></td>
                <td><?php 
				$payment_status=$this->payment_model->get_payment_received_info_by_reg_services_id($payment->reg_for_service_id);
				echo $payment_status->payment_received_date; 
				?></td>
                <td><?php echo "<a href=".base_url().'payment/payment/view_single_payment/'.$payment_status->reg_for_service_id.">".$payment_status->received_amount." ".lang('taka')."</a>";?></td>
              <td>
				<?php 
				if($payment->services_status==0)
				{
				echo '<span class="label label-warning">Pending</span>';				
				}
			else if	($payment->services_status==1)
				{
				echo '<span class="label label-info">Process</span>';
				}
			else if	($payment->services_status==2)
				{
				echo '<span class="label label-success">Taken</span>';
				}
			else if	($payment->services_status==3)
				{
				echo '<span class="label label-important">Cancel</span>';
				}
			else if	($payment->services_status==4)
				{
				echo '<span class="label label-important">Deleted</span>';
				}	
				?>                
              </td>                
                
                <?php //if ($this->authorization->is_permitted('add_payment')) : ?> 
                <td nowrap>
                <?php 
				
				if($payment_status)
				{
					if($payment_status->approved_status==0)	
					{
					echo '<span class="label label-important">Paid</span>&nbsp;';	
					if ($this->authorization->is_permitted('approved_payment')) :
					//echo '<a href="'.base_url().'payment/payment/view_single_payment/'.$payment->reg_for_service_id.'" class="btn btn-small btn-success">'.lang('website_approved').'</a>&nbsp;';
					echo '<input type="button" name="approved_'.$i.'" id="approved_'.$i.'" value="'.lang('website_approved').'" onClick="approvedclick_id('.$payment_status->reg_for_service_id.')" class="btn-small btn-success" />&nbsp;';
					endif;
					if ($this->authorization->is_permitted('approved_payment')) :
					//echo '<a href="'.base_url().'payment/payment/edit_single_payment/'.$payment->reg_for_service_id.'" class="btn btn-small btn-warning">'.lang('website_unapproved').'</a>&nbsp;';
					echo '<input type="button" name="unapproved_'.$i.'" id="unapproved_'.$i.'" value="'.lang('website_unapproved').'" onClick="unapprovedclick_id('.$payment_status->reg_for_service_id.')" class="btn-small btn-warning" />&nbsp;';
					endif;
					}
					else if($payment_status->approved_status==1)
					{
					echo '<span class="label label-success">Paid</span>&nbsp;';	
					}
					else if($payment_status->approved_status==2)
					{
					echo '<span class="label label-warning">Paid</span>&nbsp;';	
					if ($this->authorization->is_permitted('approved_payment')) :					
					echo '<input type="button" name="approved_'.$i.'" id="approved_'.$i.'" value="'.lang('website_approved').'" onClick="approvedclick_id('.$payment_status->reg_for_service_id.')" class="btn-small btn-success" />&nbsp;';
					endif;
					}
				}												
				else
				{
				if ($this->authorization->is_permitted('received_payment')) : 	
				?>
                <a href="<?php echo base_url().'payment/payment/add_payment/'.$payment->reg_for_service_id;?>" class="btn btn-small btn-info"><?=lang('website_receive')?></a>
                <?php
				endif; 
				}
				?>
                </td>                
                                
</tr>
            <?php 
			$i=$i+1;
			endforeach; 
			//}
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