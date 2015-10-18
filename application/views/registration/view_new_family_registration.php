<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript">
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
			$("#reg_services_point").html(html);
			//$('#union').removeAttr('selected').find('option:first').attr('selected', 'selected');
			}
		});
	
	});
	<!-- End -->	
	
	<!-- Start -->
	$("#reg_services").change(function()
	{
	var id=$(this).val();
	var dataString;	
	//var dataString = 'id='+ id;	
	$.ajax
		({
			type: "POST",
			url: "registration/registration/load_services_pacakge/"+id,
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#reg_services_package").html(html);			
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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/registration_48.png" width="48" height="41"> <?=lang('registration_form')?></div>
    <div class="panel-body">
       
    <!--<div class="alert alert-info">Fields with <strong></strong><span class="required">*</span></strong> are required.</div>-->
    
	<?php                		                        
			if(isset($error))
            {					 
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <?=$error?>
            </div>
            <?php
            }
            ?>
			
			<?php 
			if(validation_errors())
			{					 
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<?=validation_errors()?>
			</div>
			<?php
			}
			?>
            
            
			<?php 
			if(isset($success_msg))
			{					 
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<?=$success_msg?>
			</div>
			<?php
			}
			?>
			
            <?php 
			if(isset($error_msg))
			{					 
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<?=$error_msg?>
			</div>
			<?php
			}
			?>
	    
    
    
    <form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="" method="post" enctype="multipart/form-data">   

            
            	
            <!-- Patient  input-->
            <table class="table table-bordered table-striped">            	
                <tr>
                	<td><?=lang('site')?> *</td>
                    <td>
                    <div class="span8">
                    
                    <?=lang('site')?> :
                    <select name="reg_site" class="input-large" id="reg_site">
                        <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($site as $site1) : ?>
                        <option value="<?php echo $site1->site_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $site1->site_name:$site1->site_name_bn; ?></option>
                        <?php endforeach; ?>
                    </select>
                    
					<?=lang('services_point')?> :
                    <select name="reg_services_point" class="input-large" id="reg_services_point">
                    <option value=""><?php echo lang('settings_select'); ?></option>            
                    </select>        
                    </div>
                    </td>
                </tr>
                
                <tr>
                	<td><?=lang('household_name')?> *</td>
                    <td>
                    <div class="span8">
                    <input id="household_name" name="household_name" type="text" value="<?=set_value('household_name')?>" placeholder="<?=lang('household_name')?>" class="form-control"/>  
                    </div>
                    </td>
                </tr>
                
                <tr>
                	<td><?=lang('apartment_name')?> </td>
                    <td>
                    <div class="span8">
                    <input id="apartment_name" name="apartment_name" type="text" value="<?=set_value('apartment_name')?>" placeholder="<?=lang('apartment_name')?>" class="form-control"/>  
                    </div>
                    </td>
                </tr>
                
                <tr>
                	<td><?=lang('apartment_number')?> </td>
                    <td>
                    <div class="span8">
                    <input id="apartment_number" name="apartment_number" type="text" value="<?=set_value('apartment_number')?>" placeholder="<?=lang('apartment_number')?>" class="form-control"/>  
                    </div>
                    </td>
                </tr>
                
                <tr>
                	<td><?=lang('primary_contact_person')?> (User Id) </td>
                    <td>
                    <div class="span8">
                    <input id="primary_contact_person" name="primary_contact_person" type="text" value="<?=set_value('primary_contact_person')?>" placeholder="User id" class="form-control"/>
                    </div>
                    </td>
                </tr>                    
            
            	<tr>   
                    <td><?=lang('note')?></td>
            		<td>
                    <div class="span8">
                        <textarea class="form-control" id="family_note" name="family_note" placeholder="<?=lang('note')?>" rows="5"><?=set_value('family_note')?></textarea>
                    </div>
                    </td>
                </tr>                             
        		
                <tr class="warning">   
                    <td><?=lang('services')?>/<?=lang('package')?></td>
            		<td>
                    <div class="span8">
						
						<?=lang('services')?>
                        
                        <select name="reg_services" class="input-large" id="reg_services">
                        <option value=""><?php echo lang('settings_select'); ?></option>   
                        <?php foreach ($services as $services1) : ?>
                            <option value="<?php echo $services1->services_id; ?>"><?php echo $this->session->userdata('site_lang')=='english'? $services1->services_name:$services1->services_name_bn; ?></option>
                            <?php endforeach; ?>         
                        </select>        
                       
                   		<?=lang('package')?></label>                                
                        <select name="reg_services_package" class="input-large" id="reg_services_package">
                        <option value=""><?php echo lang('settings_select'); ?></option>            
                        </select>                                                       
                       
                    </div>
                    </td>
                </tr>
                              
            </table>
                                                  
           
                <div class="control-group">
                    <div class="controls">
                    <input class="btn btn-primary pull-right" type="submit" name="save" value="<?=lang('website_save')?>" />
                    </div>
                </div>
          
          
          <table class="table table-bordered">
          <tr class="info">
            <td rowspan="2"><p align="center">Package No.</p></td>
            <td rowspan="2"><p align="center">Family Member<br />
              (No.    of Subscriber)</p></td>
            <td colspan="3" ><p align="center">Number of Checkup per Month</p></td>
          </tr>
          <tr class="info">
            <td valign="top"><p align="center">Gold:    1 Time</p></td>
            <td valign="top"><p align="center">Diamond:    2 Time</p></td>
            <td valign="top"><p align="center">Platinum:    4 Time</p></td>
          </tr>
          <tr>
            <td valign="top"><p align="center">1</p></td>
            <td valign="top"><p align="center">1</p></td>
            <td valign="top"><p align="right">300    Taka</p></td>
            <td valign="top"><p align="right">500    Taka</p></td>
            <td valign="top"><p align="right">800    Taka</p></td>
          </tr>
          <tr>
            <td valign="top"><p align="center">2</p></td>
            <td valign="top"><p align="center">2</p></td>
            <td valign="top"><p align="right">500    Taka</p></td>
            <td valign="top"><p align="right">800    Taka</p></td>
            <td valign="top"><p align="right">1,500    Taka</p></td>
          </tr>
          <tr>
            <td valign="top"><p align="center">3</p></td>
            <td valign="top"><p align="center">3</p></td>
            <td valign="top"><p align="right">650    Taka</p></td>
            <td valign="top"><p align="right">1,300    Taka</p></td>
            <td valign="top"><p align="right">2,300    Taka</p></td>
          </tr>
          <tr>
            <td valign="top"><p align="center">4</p></td>
            <td valign="top"><p align="center">Full    Family</p></td>
            <td valign="top"><p align="right">800    Taka</p></td>
            <td valign="top"><p align="right">1,800    Taka</p></td>
            <td valign="top"><p align="right">3,000    Taka</p></td>
          </tr>
          <tr>
            <td colspan="5" valign="top"><p>Note: </p>
              <ol>
                <li>Online prescription, health    profile and record keeping free</li>
                <li>Additional Doctors consultancy per time will costs additional 150 Taka</li>
                <li>Family Registration Fee 500    Taka (for lifetime)</li>
                <li>This packages are for Basic Health    checkup services</li>
                <li>Registered members will get    10% discount on other checkup services</li>
                <li>Off Day: Tuesday, Friday and    National Holidays</li>
              </ol></td>
          </tr>
        </table>      
        

</form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>