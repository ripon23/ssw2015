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
	var agree=confirm("Are you sure you want to delete this expense entey?");
	if(agree)
	{
	var expense_id= document.getElementById('expenses_id_'+numeric).value;	

    $.ajax({
           type: "POST",
           url: "expenses/expenses/delete_expense",
		   data: "expense_id="+expense_id,
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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/expenses.png" width="48" height="41"> <?=lang('expenses')?> </div>
    <div class="panel-body">
       
   
    <?php echo form_open('expenses/expenses/search_view_expenses') ?>
        
       <table class="table table-bordered">
        <tr class="warning">
          <td>Expense Id</td>
          <td>Expense Purpose</td>
          <td>Expense Amount</td>
          <td>Expense Site</td>
          <td>Expense in income statement?</td>
         
          
        </tr>
        <tr class="success">
            <td><input type="text" name="sexpense_id" id="sexpense_id" value="<?php echo isset($sexpense_id)?$sexpense_id:'';?>" class="input-medium"/></td>
            <td><input type="text" name="sexpense_purpose" id="sexpense_purpose" value="<?php echo isset($sexpense_purpose)?$sexpense_purpose:'';?>" class="input-medium"/></td>            
            <td><input type="text" name="sexpense_amount" id="sexpense_amount" value="<?php echo isset($sexpense_amount)?$sexpense_amount:'';?>" class="input-medium"/></td>
            <td>
            <select name="sexpense_site" class="input-large" id="sexpense_site">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if(isset($sreg_site)){ if($sreg_site==$site1->site_id) echo ' selected="selected"'; } ?>><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
            </td>
            <td>
            <label class="radio inline">
       		<input type="radio" name="expense_in_income_statement" id="expense_in_income_statement" value="1" >Yes</label>
        	<label class="radio inline">
        	<input type="radio" name="expense_in_income_statement" id="expense_in_income_statement" value="zero">No</label>
          	</td>
            
            
          </tr>
          <tr class="warning">
            <td colspan="2" align="center">Expense Date Between</td>
          	<td align="center">Today</td>
          	<td align="center">Voucher no</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="success">
            <td colspan="2"><input type="text" name="sdate1" id="sdate1" value="<?php echo isset($sdate1)?$sdate1:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/> <?=lang('website_and')?> <input type="text" name="sdate2" id="sdate2" value="<?php echo isset($sdate2)?$sdate2:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/></td>
            <td align="center"><input type="checkbox" name="Today" id="Today" onClick="cuttentdate()" /></td>
            <td align="center"><input type="text" name="sexpense_voucher_no" id="sexpense_voucher_no" value="<?php echo isset($sexpense_voucher_no)?$sexpense_voucher_no:'';?>" class="input-medium"/></td>
            <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('mainmenu_view_registration')?>" class="btn-small btn-primary" /></td>
          </tr>
        </table>
        
        </form>
	
    
<table class="table table-bordered table-striped">
			<tr>
                <th>#</th>
                <th>Expenses Purpose</th>
                <th>Expenses amount</th>
                <th>Expenses date</th>
                <th>Expenses site</th>
                
                <?php if ($this->authorization->is_permitted('add_expenses')) : ?> 
                <th><?=lang('website_view')?></th> 
                <?php endif; ?>
                
				<?php if ($this->authorization->is_permitted('add_expenses')) : ?> 
                <th><?=lang('website_edit')?></th> 
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('add_expenses')) : ?> 
                <th><?=lang('website_delete')?></th>  
                <?php endif; ?>
            </tr>
            <?php 
			$i=$page+1;
			?>
            <?php 
			if( !empty($all_expenses) ) {
			foreach ($all_expenses as $expenses) : 
			?>
            <tr id="row_<?=$i?>">
				<td><?=$i?></td>
                <td><?php echo $expenses->expense_purpose;?></td>
                <td><?php echo $expenses->expense_amount ; ?></td>
                <td><?php echo $expenses->expense_date;?></td>
                <td><?php echo $this->ref_site_model->get_site_name_by_id($expenses->expense_site_id);?></td>
                
                 <?php if ($this->authorization->is_permitted('add_expenses')) : ?> 
                <td><a href="<?php echo base_url().'expenses/expenses/view_single_expense/'.$expenses->expense_id ;?>" class="btn btn-small btn-info"><?=lang('website_view')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('add_expenses')) : ?> 
                <td><a href="<?php echo base_url().'expenses/expenses/edit_single_expense/'.$expenses->expense_id ;?>" class="btn btn-small btn-warning"><?=lang('website_edit')?></a></td>
                <?php endif; ?>
                
                <?php if ($this->authorization->is_permitted('add_expenses')) : ?> 
                <td>
                <input type="hidden" name="expenses_id_<?=$i?>" id="expenses_id_<?=$i?>" value="<?=$expenses->expense_id?>"/>
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