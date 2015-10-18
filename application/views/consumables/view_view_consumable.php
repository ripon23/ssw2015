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

function deleteclick_id(button_id)
{
	var numeric = button_id.replace('delete_','');
	var agree=confirm("Are you sure you want to delete this consumable entey?");
	if(agree)
	{
	var consumable_id= document.getElementById('consumable_id_'+numeric).value;	

    $.ajax({
           type: "POST",
           url: "consumables/consumables/delete_consumable",
		   data: "consumable_id="+consumable_id,
           success: function(msg)
           {               	
			   	//removeTableRow(button_id);
			   	$('#row_' + numeric).addClass('error');			  
				//document.getElementById('row_' + numeric).style.backgroundColor = 'red';
				$('#row_' + numeric).fadeOut(4000, function(){   				
				//$("#row_"+ numeric).remove();
				$('#row_' + numeric).removeClass('error');
				});
			alert(msg); // show response from the php script.			      	
           }
         });

    return false; // avoid to execute the actual submit of the form.
	}// END IF
	else
	{
		return false; // avoid to execute the actual submit of the form.
	}
			
}// END deleteclick_id
</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/consumables.png" width="48" height="41"> <?=lang('consumables')?> </div>
    <div class="panel-body">
       
   
    <?php echo form_open('consumables/consumables/search_view_consumables') ?>
        
       <table class="table table-bordered">
        <tr class="warning">
          <td>Consumable Id</td>
          <td>Consumable Name</td>
          <td>Consumables Stock</td>
          <td>Consumables Category</td>
          <td>Consumables Site</td>
          <td>&nbsp;</td>
         
          
        </tr>
        <tr class="success">
            <td><input type="text" name="sconsumable_id" id="sconsumable_id" value="<?php echo isset($sconsumable_id)?$sconsumable_id:'';?>" class="input-medium"/></td>
            <td><input type="text" name="sconsumable_name" id="sconsumable_name" value="<?php echo isset($sconsumable_name)?$sconsumable_name:'';?>" class="input-medium"/></td>            
            <td><input type="text" name="sconsumable_stock" id="sconsumable_stock" value="<?php echo isset($sconsumable_stock)?$sconsumable_stock:'';?>" class="input-medium"/></td>
            <td>
             <select name="sconsumable_category" class="input-large" id="sconsumable_category">
                        <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($consumable_categorys as $categorys) : ?>
                        <option value="<?php echo $categorys->consumable_category_id; ?>"><?php echo $this->session->userdata('site_lang')=='bangla'? $categorys->site_name_bn:$categorys->consumable_category_name; ?></option>
                        <?php endforeach; ?>
            </select>
            <td>
            <select name="sconsumable_site" class="input-large" id="sconsumable_site">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if(isset($sreg_site)){ if($sreg_site==$site1->site_id) echo ' selected="selected"'; } ?>><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
            </td>
            </td>
            <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('website_search')?>" class="btn-small btn-primary" /></td>
            
            
          </tr>
          
        </table>
        
        </form>
	
    
<table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th>Consumables Id</th>
                <th>Consumables Name</th>
                <th>Consumables Category</th>
                <th>Consumables Site</th>
                <th>Consumables Per Price</th>
                <th>Consumables Stock</th>
                <th>Consumables Note</th>
                
                <?php if ($this->authorization->is_permitted('add_consumables')) : ?> 
                <th><?=lang('website_view')?></th> 
                <?php endif; ?>
                
				<?php if ($this->authorization->is_permitted('add_consumables')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('add_consumables')) : ?> 
                <th><?=lang('website_delete')?></th>  
                <?php endif; ?>
            </tr>
            <?php 
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_consumables) ) {
			foreach ($all_consumables as $consumables) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $consumables->consumable_id;?></td>
                <td><?php echo $consumables->consumable_name;?></td>
                <td><?php echo $this->consumables_model->get_consumable_category_name_by_id($consumables->consumable_category_id);  ?></td>
                <td><?php echo $this->ref_site_model->get_site_name_by_id($consumables->consumable_site_id);?></td>
                <td><?php echo $consumables->consumable_per_price;?></td>
                <td><?php echo $consumables->consumable_stock;?></td>
                <td><?php echo $consumables->consumable_note;?></td>
                
                 <?php if ($this->authorization->is_permitted('add_consumables')) : ?> 
                <td><a href="<?php echo base_url().'consumables/consumables/view_single_consumable/'.$consumables->consumable_id ;?>" class="btn btn-small btn-info"><?=lang('website_view')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('add_consumables')) : ?> 
                <td><a href="<?php echo base_url().'consumables/consumables/edit_single_consumable/'.$consumables->consumable_id ;?>" class="btn btn-small btn-warning"><?=lang('website_edit')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('add_consumables')) : ?> 
                <td>
                <input type="hidden" name="consumable_id_<?=$i?>" id="consumable_id_<?=$i?>" value="<?=$consumables->consumable_id?>"/>
                <input type="button" name="delete_<?=$i?>" id="delete_<?=$i?>" value="<?=lang('website_delete')?>" onClick="deleteclick_id(this.id)" class="btn-small btn-danger" />
                </td>
                <?php endif; ?>
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