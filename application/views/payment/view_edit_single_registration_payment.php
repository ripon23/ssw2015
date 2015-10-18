<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>

jQuery(document).ready(function(){
								
});

function clean_received_payment()
{
	if (document.getElementById('payment_status_free').checked == true)
	{
		document.getElementById('received_amount').value='';
		document.getElementById('received_amount').setAttribute('disabled','disabled');
		
	}
	else
	{
		document.getElementById('received_amount').value='5';
		document.getElementById('received_amount').removeAttribute('disabled');
	}
}

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/payment_icon.png" width="48" height="41"> <?=lang('menu_payment')?></div>
    <div class="panel-body">
       
    <!--<div class="alert alert-info">Fields with <strong></strong><span class="required">*</span></strong> are required.</div>-->
    
	<?php 
	if(validation_errors())
	{					 
	?>
    <div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=validation_errors()?>
    </div>
	<?php
	}
	?>
    
    <?php 
	if(isset($success_msg))
	{					 
	?>
    <div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$success_msg?>
    </div>
	<?php
	}
	?>
	    
    
    
    <form class="form-horizontal" id="health-checkup-form" action="" method="post">    
    
    <table class="table table-striped">
        <tr class="success">
          <td><?=lang('registration_no')?>: <?=$registration_info->registration_no?>
          <input type="hidden" name="registration_no" id="registration_no" value="<?=$registration_info->registration_no?>"/>
          </td>
          <td><?=lang('settings_fullname')?>: <?=$registration_info->first_name?> <?=$registration_info->middle_name?> <?=$registration_info->last_name?></td>          <td><?=lang('settings_gender')?>: 
          <?php $sex  = $registration_info->gender=="M" ? "Male" : "Female"; ?>		  
          <?=$sex?>
          </td>          
        </tr>
        <tr class="success">
        	<td><?=lang('settings_dateofbirth')?>:<?php  echo $registration_info->dob; ?></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: 
            <?php 
			echo $registration_info->phone;			
			?>            
            </td>
        </tr>
        
	</table>     
    
    <div class="span8">    
    
 	<table class="table table-bordered">
    	<tr>
        	<td>Payment Status</td>
            <td>
			<label class="radio inline">
        <input type="radio" name="payment_status" id="payment_status_free" value="Free" <?php if($registration_payment_info->free_or_paid=='Free') echo 'checked';?>  onclick="javascript:clean_received_payment();">Free</label>
        <label class="radio inline">
        <input type="radio" name="payment_status" id="payment_status_paid" value="Paid" <?php if($registration_payment_info->free_or_paid=='Paid') echo 'checked';?>  onclick="javascript:clean_received_payment();">Paid</label>
            </td>                       
        </tr>
        <tr>
	        <td>Received amount</td>
            <td><input class="span1"  name="received_amount" id="received_amount" value="<?=$registration_payment_info->received_amount?>" type="text" <?php if($registration_payment_info->free_or_paid=='Free') echo 'disabled';?>  />     <?=lang('taka')?> </td>
        </tr>
        
        <tr>
	        <td colspan="2"><input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_save'); ?>" /></td>            
        </tr>
    </table>
    </div>
                                
                      
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>