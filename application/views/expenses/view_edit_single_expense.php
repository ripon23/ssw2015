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
	    
    
    
    <form class="form-horizontal" id="social-goods-form" action="" method="post" enctype="multipart/form-data">        
              	
                <div class="control-group">
                    <label class="control-label" for="expense_purpose">Expense purpose *:</label>        
                    <div class="controls">
                    <input class="input-large"  name="expense_purpose" id="expense_purpose"  value="<?php echo $single_expense->expense_purpose;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="expense_amount">Expense amount *: </label>        
                    <div class="controls">
                    <input class="input-large"  name="expense_amount" id="expense_amount"  value="<?php echo $single_expense->expense_amount;?>" type="text" />
                    </div>
                </div>
                
                 <div class="control-group">
                    <label class="control-label" for="expense_payee">Payee : </label>        
                    <div class="controls">
                    <input class="input-large"  name="expense_payee" id="expense_payee"  value="<?php echo $single_expense->expense_payee;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="expense_voucher_no">Voucher no : </label>        
                    <div class="controls">
                    <input class="input-large"  name="expense_voucher_no" id="expense_voucher_no"  value="<?php echo $single_expense->expense_voucher_no;?>" type="text" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="product_description">Expense date *: </label>        
                    <div class="controls">
                    <input type="text" name="expense_date"  placeholder="Expense date" value="<?php echo $single_expense->expense_date;?>" class="input-large" id="expenses_date"/>
                    </div>
                </div>
                <script type="text/javascript">
				$(function(){
					$('*[name=expense_date]').appendDtpicker();
				});
				</script>
                
                <div class="control-group">
                    <label class="control-label" for="expense_site"><?=lang('site')?> *:</label>        
                    <div class="controls">
                    <select name="expense_site" class="input-large" id="expense_site">
                        <option value=""><?php echo lang('settings_select'); ?></option>            
                        <?php foreach ($site as $site1) : ?>
                        <option value="<?php echo $site1->site_id; ?>" <?php if($single_expense->expense_site_id==$site1->site_id) echo 'selected';?> ><?php echo $this->session->userdata('site_lang')=='bangla'? $site1->site_name_bn:$site1->site_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="expense_in_income_statement">Expense in income statement : </label>        
                    <div class="controls">                   
                    <input type="checkbox" name="expense_in_income_statement" id="expense_in_income_statement" value="1" <?php if($single_expense->expense_in_income_statement==1) echo 'checked'; ?>/>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="expense_note"><?=lang('note')?></label>        
                    <div class="controls">
                    <textarea rows="3" placeholder="Expense note" name="expense_note" id="expense_note"><?php echo $single_expense->expense_note;?></textarea>        
                    </div>
                </div>
                
                
    
    <div class="span11">
     
        <div class="control-group">
            <div class="controls">
            <input class="btn btn-primary pull-right" type="submit" name="save" value="<?php echo lang('website_update'); ?>" />
            </div>
        </div>
        
    </div><!-- /end span11 -->
    </form> 
     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>