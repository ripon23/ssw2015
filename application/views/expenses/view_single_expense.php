<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>
<script type="text/javascript" src="<?php echo base_url().RES_DIR; ?>/js/textbox_color_change.js"></script>
   
<script>

jQuery(document).ready(function(){
								
});

</script>
</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/expenses.png" width="48" height="41"> <?=lang('expenses')?> </div>
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
    
       
 	<div class="span8">
    <table class="table table-bordered">
    	<tr>
           <td>Expense id</td>
           <td><?php echo $single_expense->expense_id; ?></td>
        </tr>
        <tr>
           <td>Expense site</td>
           <td><?php echo $this->ref_site_model->get_site_name_by_id($single_expense->expense_site_id);?></td>
        </tr>
        <tr>
           <td>Expense purpose</td>
           <td><?php echo $single_expense->expense_purpose; ?></td>
        </tr> 
        <tr>
           <td>Expense amount</td>
           <td><?=$single_expense->expense_amount?></td>
        </tr>
        <tr>
           <td>Payee</td>
           <td><?=$single_expense->expense_payee?></td>
        </tr>
        <tr>
           <td>Voucher no</td>
           <td><?=$single_expense->expense_voucher_no?></td>
        </tr>
        <tr>
           <td>Expense date</td>
           <td><?=$single_expense->expense_date?></td>
        </tr> 
        <tr>
           <td>Expense in income statement</td>
           <td><?php if($single_expense->expense_in_income_statement==1) echo "Yes"; else echo "No"; ?></td>
        </tr> 
        <tr>
           <td>Expense Note</td>
           <td><?=$single_expense->expense_note?></td>
        </tr> 
    </table>           
    </div>            
    
    <div class="span3">
    <table class="table table-striped">    	              
        <tr>
        	<td>Last Update: </td>
            <td><?=$single_expense->last_edit_date?> </td>
        </tr>
        <tr>
        	<td>Entry/Update user: </td>
            <td><?=$single_expense->edit_user_id?> </td>
        </tr>
        </table> 
        
        <?php
        if($this->authorization->is_permitted('add_expenses'))
        {
        ?>
        <a href="<?php echo base_url().'expenses/expenses/edit_single_expense/'.$single_expense->expense_id ;?>" class="btn btn-block btn-large btn-warning"><?=lang('website_edit')?></a>
        <?php
        }
        ?>
    </div><!-- /end span3 -->
     
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>