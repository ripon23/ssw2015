<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script>

jQuery(document).ready(function(){

});
</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/expenses.png" width="48" height="41"> Daily expense</div>
    <div class="panel-body">
     
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
   
    <?php echo form_open('report/report/report_expense_search') ?>
        
        <table class="table table-bordered">
        <tr align="center" class="warning">

          <td><?=lang('site')?></td>
          <td><?=lang('date_between')?></td>
          <td></td>
          
          
        </tr>
        <tr align="center" class="success">           
            <td>
            <select name="sreg_site" class="input-large" id="sreg_site">
            	<option value=""><?php echo lang('settings_select'); ?></option>            
                <?php foreach ($site as $site1) : ?>
            	<option value="<?php echo $site1->site_id; ?>" <?php if(isset($sreg_site)){ if($sreg_site==$site1->site_id) echo ' selected="selected"'; } ?>><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
				<?php endforeach; ?>
        	</select>
            </td>
            <td>
            <input type="text" name="sdate1" id="sdate1" value="<?php echo isset($sdate1)?$sdate1:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/> <?=lang('website_and')?> <input type="text" name="sdate2" id="sdate2" value="<?php echo isset($sdate2)?$sdate2:'';?>" placeholder="YYYY-MM-DD" class="input-medium"/>
            </td>
          <td> <input type="submit" name="search_submit" id="search_submit" value="<?=lang('website_search')?>" class="btn-small btn-primary" /></td>
          </tr>
          
        </table>
        
       </form>
       
       <?php if(isset($sdate1)&& isset($sdate2)&&isset($sreg_site)){ ?>
        <table class="table table-bordered">
        	<tr>            	
                <td align="right"><a href="./report/report/export_to_excel_expense/<?=$sreg_site?>/<?=$sdate1?>/<?=$sdate2?>">Export to excel</a></td>
            <tr/>
        </table>
		<?php } ?>
       
       
       <table class="table table-bordered">
       	<tr>
       		<th>Date</th>
            <th>Payee</th>
            <th>Voucher No</th>
            <th align="right">Expense (BDT)</th>
            <th>Expense Purpose</th>
        </tr>  
        <?php
		if(isset($all_expense))
		{
		foreach ($all_expense as $expense) :
		
		?>
        <tr>
       		<td><?=$expense->expense_date?></td>
            <td><?=$expense->expense_payee?></td>
            <td><?=$expense->expense_voucher_no?></td>
            <td align="right"><?=$expense->expense_amount?></td>
            <td><?=$expense->expense_purpose?></td>
        </tr>
        <?php
		endforeach;
		}
		?>
       </table>




                

     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>