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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/payment_icon.png" width="48" height="41"> Registration payment</div>
    <div class="panel-body">
       
   
    <?php echo form_open('payment/payment/search_registration_payment_list') ?>
        
       <table class="table table-bordered">
        <tr align="center" class="warning">
          <td><?=lang('registration_no')?></td>
          <td>Payment Status</td>
          <td>&nbsp;</td>                    
          
        </tr>
        <tr align="center" class="success">
            <td><input type="text" name="sregistration_no" id="sregistration_no" value="<?php echo isset($sregistration_no)?$sregistration_no:'';?>" class="input-medium" placeholder="XXXXXXXXXXXXX"/></td>
            <td><select name="spayment_status" id="spayment_status" class="selectpicker span1.5" data-style="btn">
              <option value=""><?php echo lang('settings_select'); ?></option>
              <option value="Free" data-content="<span class='label label-warning'>Free</span>" <?php if(isset($spayment_status)){ if($spayment_status=="Free") echo ' selected="selected"'; }?>>Free</option>
              <option value="Paid" data-content="<span class='label label-success'>Paid</span>" <?php if(isset($spayment_status)){ if($spayment_status=="Paid") echo ' selected="selected"'; }?>>Paid</option>
              
            </select></td>            
            <td><input type="submit" name="search_submit" id="search_submit" value="<?=lang('mainmenu_view_registration')?>" class="btn-small btn-primary" /></td>
            
          
          </tr>
        </table>
        
        </form>
	
    
<table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th><?=lang('registration_no')?></th>
                <th><?=lang('settings_fullname')?></th>                                
                <th><?=lang('status')?></th>  
                <th>Amount</th>               
                <?php if ($this->authorization->is_permitted('received_payment')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>                				
</tr>
            <?php 
			//if($this->input->post("season"))
			//{
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_payment) ) {
			foreach ($all_payment as $payment) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>           
                <td><?php echo $payment->registration_no; ?></td>
                <td><?php 
				$reg_info= $this->registration_model->get_all_registration_info_by_id($payment->registration_no); 
				echo "<a href=".base_url().'registration/registration/view_single_registration/'.$payment->registration_no.">".$reg_info->first_name." ".$reg_info->middle_name." ".$reg_info->last_name."</a>";				
				?>
				</td>
                           
                <td>
				<?php 
				
				 $payment_info=$this->payment_model->get_registration_payment_received_info_by_reg_id($payment->registration_no);
				if($payment_info)
				{
					if($payment_info->free_or_paid=='Free')
					{
					echo '<span class="label label-warning">Free</span>';				
					}
					else if	($payment_info->free_or_paid=='Paid')
					{
					echo '<span class="label label-success">Paid</span>';
					}
				}
				?>                
              </td>                
               <td> 
                <?php
               
				if($payment_info)
				{
					echo $payment_info->received_amount;
				}
                ?>
                </td> 
               
                <td>
                <?php 
				if($payment_info)
				{
					if ($this->authorization->is_permitted('received_payment')) :
					echo '<a href="'.base_url().'payment/payment/edit_single_registration_payment/'.$payment->registration_no.'" class="btn btn-small btn-warning">'.lang('website_edit').'</a>&nbsp;';
					endif;
				}
				else
				{
					if ($this->authorization->is_permitted('received_payment')) : 	
					?>
					<a href="<?php echo base_url().'payment/payment/add_registration_payment/'.$payment->registration_no;?>" class="btn btn-small btn-info"><?=lang('website_receive')?></a>
					<?php
					endif; 
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