
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
			text.innerHTML = "<i class='icon-plus icon-white'></i>Add new services";
		}
		else {
			ele.style.display = "block";
			text.innerHTML = "<i class='icon-minus icon-white'></i>Hide add new services";
		}
	} 





function updateclick_id(button_id)
{
var numeric = button_id.replace('update_services_','');

	var services_id=document.getElementById('services_id_'+numeric).value;         // From Hidden field     
	var services_name_english=document.getElementById('services_name_english_'+numeric).value;
	var services_name_bangla = document.getElementById('services_name_bangla_'+numeric).value;
	var services_description = document.getElementById('services_description_'+numeric).value;		
	var statusvalue = $.trim($("#services_status_"+numeric+" option:selected").val());	
	
	var dataString='services_id='+services_id+'&services_name_english='+ services_name_english + '&services_name_bangla=' + services_name_bangla + '&services_description=' + services_description + '&statusvalue='+statusvalue;	
	$.ajax
		({
			type: "POST",
			url: "basic_setting/basic_setting/update_gramcar_services",
			data: dataString,
			cache: false,
			success: function(msg)
			{
			alert("Update "+msg+" for: "+services_id);
			location.reload(); 			
			}
		});	
	
}


jQuery(document).ready(function(){
	

	
	<!-- Start -->
	$("#add_services").click(function() 
	{
	var services_name_english=document.getElementById('services_name_english').value;
	var services_name_bangla = document.getElementById('services_name_bangla').value;
	var services_description = document.getElementById('services_description').value;		
	var statusvalue = $.trim($("#services_status option:selected").val());	
	
	if(services_name_english!='')
		{
		var dataString='services_name_english='+ services_name_english + '&services_name_bangla=' + services_name_bangla + '&services_description=' + services_description + '&statusvalue='+statusvalue;	
		$.ajax
			({
				type: "POST",
				url: "basic_setting/basic_setting/add_new_gramcar_services",
				data: dataString,
				cache: false,
				success: function(msg)
				{			
				alert("Add new GramCar services "+msg);			
				location.reload(true); 					
				}
			});		
		}<!-- End if-->	
		else
		{
			alert("Services name should not blank");
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
    	<a id="displayText" href="javascript:toggle();" class="btn btn-small btn-info"><i class="icon-plus icon-white"></i>Add new services</a>
	   			<div id="toggleText" style="display: none">                                              
                    <form class="form-horizontal" name="frm_add_services" method="post">
                        <table class="table table-striped table-bordered">
                        <thead>
                        <tr>                            
                            <th>Services Name(English) </th>
                            <th>Services Name(Bangla)</th>
                            <th>Services Description</th> 
                            <th>Status</th>                           
                            <th>Action</th>
                        </tr>
                        <thead>
                        <tbody>
                        	<tr>                            	
                                <td>
								<input type="text" name="services_name_english" id="services_name_english" />
                                </td>      
                                <td>
                                <input type="text" name="services_name_bangla" id="services_name_bangla" />
                                </td>
                                <td>
                                <input type="text" name="services_description" id="services_description" /> 
                        		</td>                              
                                <td>
                                <select name="services_status2" id="services_status" class="selectpicker span1.5" data-style="btn">
                                	<option value="1" selected data-content="<span class='label label-success'>Active</span>">Active</option>                              		<option value="0" data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                                </select>
                                </td>
                                <td>										
								<input class="btn btn-primary pull-right" type="button" name="add_services" id="add_services" value="<?=lang('website_save')?>" />						                                </td>
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
            <th>Services ID</th>
            <th>Services Name(English) </th>
            <th>Services Name(Bangla)</th>
            <th>Services Description</th> 
            <th>Status</th>                           
            <th>Action</th>
        </tr>
        <thead>
        <tbody>
        <?php
		$i=1;
		foreach ($services_list as $services) :
		?>
        <tr align="center">
        	<td><?=$i++?></td>
            <td>
            <?=$services->services_id?>	
            <input type="hidden" name="services_id_<?=$i?>" id="services_id_<?=$i?>" value="<?=$services->services_id?>" />
            </td>
            <td>
			<input type="text" name="services_name_english_<?=$i?>" id="services_name_english_<?=$i?>" value="<?=$services->services_name?>" />
            </td>      
        	<td>
			<input type="text" name="services_name_bangla_<?=$i?>" id="services_name_bangla_<?=$i?>" value="<?=$services->services_name_bn?>" />
            </td>
            <td>
            <input type="text" name="services_description_<?=$i?>" id="services_description_<?=$i?>" value="<?=$services->services_description?>" />
            </td>       
            <td>
            <select name="services_status_<?=$i?>" id="services_status_<?=$i?>" class="selectpicker span1.5" data-style="btn">
                <option value="0" <?php if($services->services_status==0) echo 'selected="selected"'; ?> data-content="<span class='label label-important'>Inactive</span>">Inactive</option>
                <option value="1" <?php if($services->services_status==1) echo 'selected="selected"'; ?> data-content="<span class='label label-success'>Active</span>">Active</option>
            </select>
            </td>
            <td>          			
			<input class="btn btn-small btn-primary" type="button" name="update_services_<?=$i?>" onClick="updateclick_id(this.id)" id="update_services_<?=$i?>" value="<?=lang('website_update')?>" />
            </td>
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