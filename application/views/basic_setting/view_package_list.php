
<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/jquery.simple-dtpicker.js"></script>
<link type="text/css" href="<?php echo base_url().RES_DIR; ?>/css/jquery.simple-dtpicker.css" rel="stylesheet" />
<script type="text/javascript">
        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });


function toggle() {
		var ele = document.getElementById("toggleText");
		var text = document.getElementById("displayText");
		if(ele.style.display == "block") {
				ele.style.display = "none";
			text.innerHTML = "<i class='icon-plus icon-white'></i>Add new package";
		}
		else {
			ele.style.display = "block";
			text.innerHTML = "<i class='icon-minus icon-white'></i>Hide add new package";
		}
	} 





function updateclick_id(button_id)
{
var numeric = button_id.replace('update_package_','');

	var package_id=document.getElementById('package_id_'+numeric).value;         // From Hidden field    
	var package_services = $.trim($("#package_services_"+numeric+" option:selected").val());	
	var package_name_english=document.getElementById('package_name_english_'+numeric).value;
	var package_name_bangla = document.getElementById('package_name_bangla_'+numeric).value;
	var package_description = document.getElementById('package_description_'+numeric).value;
	var package_price = document.getElementById('package_price_'+numeric).value;
	var statusvalue = $.trim($("#services_status_"+numeric+" option:selected").val());	
	
	var dataString='package_id='+package_id+'&package_services='+ package_services +'&package_name_english='+ package_name_english + '&package_name_bangla=' + package_name_bangla + '&package_description=' + package_description + '&package_price='+package_price+'&statusvalue='+statusvalue;	
	$.ajax
		({
			type: "POST",
			url: "basic_setting/basic_setting/update_gramcar_package",
			data: dataString,
			cache: false,
			success: function(msg)
			{
			alert("Update "+msg+" for: "+package_id);
			location.reload(); 			
			}
		});	
	
}


jQuery(document).ready(function(){
	

	
	<!-- Start -->
	$("#add_package").click(function() 
	{
	var package_services = $.trim($("#package_services option:selected").val());	
	var package_name_english=document.getElementById('package_name_english').value;
	var package_name_bangla = document.getElementById('package_name_bangla').value;
	var package_description = document.getElementById('package_description').value;
	var package_price = document.getElementById('package_price').value;
	var statusvalue = $.trim($("#package_status2 option:selected").val());	
	
	if(package_name_english!='' && package_services!='' && package_name_bangla!='' && package_price!='')
		{
		var dataString='package_services='+ package_services +'&package_name_english='+ package_name_english + '&package_name_bangla=' + package_name_bangla + '&package_description=' + package_description + '&package_price='+package_price+'&statusvalue='+statusvalue;	
		$.ajax
			({
				type: "POST",
				url: "basic_setting/basic_setting/add_new_gramcar_package",
				data: dataString,
				cache: false,
				success: function(msg)
				{			
				alert("Add new GramCar package "+msg);			
				location.reload(true); 					
				}
			});		
		}<!-- End if-->	
		else
		{
			alert("Please fill all field");
		}
	});
	<!-- End -->
		
		
});

</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><?=lang('basic_setting')?></div>
    <div class="panel-body">
       
    
    <div class="span11" style="margin-bottom:20px;">
    	<a id="displayText" href="javascript:toggle();" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i>Add new package</a>
	   			<div id="toggleText" style="display: none">                                              
                    <form class="form-horizontal" name="frm_add_services" method="post">
                        <table class="table table-striped table-bordered">
                        <thead>
                        <tr>                            
                            <th>GramCar Services</th>
                            <th>Package Name(English) </th>
                            <th>Package Name(Bangla)</th>
                            <th>Package Description</th> 
                            <th>Price</th> 
                            <th>Status</th>                           
                            <th>Action</th>  
                        </tr>
                        <thead>
                        <tbody>
                        	<tr>                            	
                                <td>
								<select name="package_services" class="input-medium" id="package_services">
                                <option value=""><?php echo lang('settings_select'); ?></option>   
                                <?php foreach ($gramcar_services as $services1) : ?>
                                    <option value="<?php echo $services1->services_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $services1->services_name_bn:$services1->services_name; ?></option>
                                    <?php endforeach; ?>         
                                </select>
                                </td>      
                                <td>
								<input type="text" name="package_name_english" id="package_name_english" class="input-medium"/>
                                </td>      
                                <td>
                                <input type="text" name="package_name_bangla" id="package_name_bangla" class="input-medium"/>
                                </td>
                                <td>
                                <input type="text" name="package_description" id="package_description" class="input-medium"/> 
                        		</td> 
                                <td>
                                <input type="text" name="package_price" id="package_price" class="input-mini"/> 
                        		</td>                              
                                <td>
                                <select name="package_status2" id="package_status2" class="selectpicker span1.5" data-style="btn">
                                	<option value="1" selected data-content="<span class='label label-success'>Active</span>">Active</option>                              		<option value="0" data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                                </select>
                                </td>
                                <td>										
								<input class="btn btn-primary pull-right" type="button" name="add_package" id="add_package" value="<?=lang('website_save')?>" />						                                </td>
                            </tr>
                        </tbody>
                        </table>             
                	</form> 
    	</div> <!-- toggleText END -->
    </div>
    
    
    <div class="span11" style="margin-bottom:40px;">
    
        <form class="form-horizontal" id="registration-services-edit" name="registration-services-edit" action="" method="post">
        <table class="table table-striped table-bordered">
        <thead>
    	<tr>
        	<th>#</th>
            <th>Package ID</th>
            <th>GramCar Services</th>
            <th>Package Name(English) </th>
            <th>Package Name(Bangla)</th>
            <th>Package Description</th> 
            <th>Price</th> 
            <th>Status</th>
            <?php if ($this->authorization->is_permitted('edit_delete_package')) : ?> 
            <th>Action</th>
            <?php endif; ?>                        
            
        </tr>
        <thead>
        <tbody>
        <?php
		$i=1;
		foreach ($packages_list as $packages) :
		?>
        <tr align="center">
        	<td><?=$i++?></td>
            <td>
            <?=$packages->package_id?>	
            <input type="hidden" name="package_id_<?=$i?>" id="package_id_<?=$i?>" value="<?=$packages->package_id?>"  />
            </td>
            <td>
			<select name="package_services_<?=$i?>" class="input-medium" id="package_services_<?=$i?>">
            <option value=""><?php echo lang('settings_select'); ?></option>   
            <?php foreach ($gramcar_services as $services1) : ?>
            	<option value="<?php echo $services1->services_id; ?>" <?php if($packages->services_id){if($services1->services_id==$packages->services_id) echo 'selected="selected"'; }?>><?php echo $this->session->userdata('site_lang')=='bangla'? $services1->services_name_bn:$services1->services_name; ?></option>
				<?php endforeach; ?>         
	        </select>
            </td>
            <td>
			<input type="text" name="package_name_english_<?=$i?>" id="package_name_english_<?=$i?>" value="<?=$packages->package_name?>" class="input-medium"/>
            </td>      
        	<td>
			<input type="text" name="package_name_bangla_<?=$i?>" id="package_name_bangla_<?=$i?>" value="<?=$packages->package_name_bn?>" class="input-medium"/>
            </td>
            <td>
            <input type="text" name="package_description_<?=$i?>" id="package_description_<?=$i?>" value="<?=$packages->package_description?>" class="input-medium"/>
            </td>  
            <td>
            <input type="text" name="package_price_<?=$i?>" id="package_price_<?=$i?>" value="<?=$packages->package_price?>" class="input-mini" />
            </td>     
            <td>
            <select name="services_status_<?=$i?>" id="services_status_<?=$i?>" class="selectpicker span1.5" data-style="btn">
                <option value="0" <?php if($packages->package_status==0) echo 'selected="selected"'; ?> data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                <option value="1" <?php if($packages->package_status==1) echo 'selected="selected"'; ?> data-content="<span class='label label-success'>Active</span>">Active</option>
            </select>
            </td>
             <?php if ($this->authorization->is_permitted('edit_delete_package')) : ?> 
            <td>          			
			<input class="btn btn-small btn-primary" type="button" name="update_package_<?=$i?>" onClick="updateclick_id(this.id)" id="update_package_<?=$i?>" value="<?=lang('website_update')?>" />
            </td>
            <?php endif; ?>
            
        </tr>
        <?php endforeach; ?>
        <tbody>
        </table>               
    </form>                     	 
    </div> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>