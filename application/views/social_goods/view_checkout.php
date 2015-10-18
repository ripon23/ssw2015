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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/social_delivery_48.png" width="48" height="41"> <?=lang('services_social-goods')?> (<?=lang('website_checkout')?>)<a href="social_goods/social_goods/show_cart" id="cart-button" class="btn btn-medium btn-success pull-right" ><i class="icon-shopping-cart icon-white"></i> Cart (<?=$this->cart->total_items()?>)</a></div>
    <div class="panel-body">
    
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
    
    <?php 
	if(isset($error_msg))
	{					 
	?>
    <div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?=$error_msg?>
    </div>
	<?php
	}
	?>
    
    
	<form class="form-horizontal" id="checkout-form" action="" method="post"> 
    
    <table class="table table-striped">
        <tr class="success">
          <td><?=lang('registration_no')?>: <?=$registration_info->registration_no?>
          <input type="hidden" name="registration_no" id="registration_no" value="<?=$registration_info->registration_no?>"/>
          </td>
          <td><?=lang('settings_fullname')?>: <?=$registration_info->first_name?> <?=$registration_info->middle_name?> <?=$registration_info->last_name?></td>          <td><?=lang('settings_gender')?>: <?php echo $registration_info->gender=="M" ? "Male" : "Female"; ?>
          </td>          
        </tr>
        <tr class="success">
        	<td><?=lang('settings_dateofbirth')?>: <?php if($registration_info->dob) { echo $registration_info->dob;}?></td>
            <td><?=lang('guardian_name')?>: <?=$registration_info->guardian_name?></td>
            <td><?=lang('phone')?>: 
            <?php 
			echo $registration_info->phone;			
			?>            
            </td>
        </tr>
	</table>   
   

    <table class="table table-bordered table-hover" >
    
    <tr>
      <th>QTY</th>
      <th align="left">Item Name</th>
      <th style="text-align:right">Item Price</th>
      <th style="text-align:center">Image</th>
      <th style="text-align:right">Sub-Total</th>
    </tr>
    
    <?php $i = 1; ?>
    
    <?php foreach ($this->cart->contents() as $items): ?>
    
        <?php //echo form_hidden($i.'[rowid]', $items['rowid']); ?>
    
        <tr>
          <td align="center"><?php echo $items['qty']; //echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '4','class'=>'span1')); ?></td>
          <td>
            <?php echo $items['name']; ?>
    
                <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
    
                    <p>
                        <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
    
                            <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
    
                        <?php endforeach; ?>
            </p>
    
                <?php endif; ?>
    
          </td>
          <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
          <td align="center"><img src="<?php echo base_url().RES_DIR; ?>/img/products/thumbnils/<?php echo $this->social_goods_model->get_product_thumbnil_by_id($items['id']); ?>" ></td>
          <td style="text-align:right"><?php echo $this->cart->format_number($items['subtotal']); ?> <?=lang('taka')?></td>
        </tr>
    
    <?php $i++; ?>
    
    <?php endforeach; ?>
    
    <tr>
      <td colspan="2"> </td>      
      <td>&nbsp; </td>
      <td class="right"><strong>Total</strong></td>
      <td class="right"><?php echo $this->cart->format_number($this->cart->total()); ?> <?=lang('taka')?></td>
    </tr>
    
    </table>
    
    <p>Order note: <textarea name="order_note" id="order_note" rows="5" class="input-xxlarge"><?=set_value('order_note')?></textarea></p>
    <p><?php 
	$attributes = array('class' => 'class="btn btn-small btn-warning"');
	echo form_submit('submit', 'Confirm Order',$attributes['class'] ); ?>
    <!--<a href="social_goods/social_goods/clear_cart" class="btn btn-small btn-danger" ><i class="icon-trash icon-white"></i> Clear cart</a>
    <a href="social_goods/social_goods/place_order" class="btn btn-small btn-success" ><i class="icon-ok icon-white"></i> Place Order</a>-->
    </p>
    
    </form>
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>