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
  		
    <div class="panel-heading"><img src="<?php echo base_url().RES_DIR; ?>/img/services-icons/internet_services_48.png" width="48" height="41"> Daily Revenue</div>
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
   
    <?php echo form_open('report/report/report_daily_revenue_search') ?>
        
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
                <td align="right"><a href="./report/report/export_to_excel_daily_revenue/<?=$sreg_site?>/<?=$sdate1?>/<?=$sdate2?>">Export to excel</a></td>
            <tr/>
        </table>
		<?php } ?>
        
		<table class="table table-bordered" style="font-size:11px">
          <tr align="center">
            <th rowspan="2">Date</th>
            <th rowspan="2">Services Point</th>
            <th rowspan="2">Booking</th>
            <th rowspan="2">Reg.</th>
             <?php foreach ($services as $services1) : ?>
            <?php echo '<th colspan="'.($this->report_model->get_package_number_from_service_id($services1->services_id)+1).'"><abbr title="'.$services1->services_name.'">'.substr($services1->services_name,0,5).'</abbr></th>'; ?>
			<?php endforeach; ?>    
            <!--<td colspan="4">PHC</td>
            <td colspan="3">Blood Grouping</td>
            <td colspan="3">College Bus</td>
            <td rowspan="2">Emergency</td>
            <td colspan="3">Computer &amp; Internet </td>
            <td colspan="4">Social Goods</td>-->
            <th rowspan="2">Grand </td>
          </tr>
          <tr>
          	<?php foreach ($services as $services1) : ?>
            		<?php $all_package = $this->report_model->get_all_services_package_by_id($services1->services_id); 
					if($all_package)
					{
					foreach ($all_package as $package1) :
					echo '<th><abbr title="'.$package1->package_name.'">'.substr($package1->package_name,0,3).'</abbr></th>';
					endforeach;
					echo '<th> Total </th>';
					}
					else
					{
					echo '<th>&nbsp;</th>';
					}
					?>
			<?php endforeach; ?>

          </tr>
          <?php
		  $sum_booking_number=0;
		  $sum_total_registration=0;
		  $sum_grand_total=0;
		  if(isset($all_possible_date))
		  {
		  foreach ($all_possible_date as $date_range) :
		  ?>
          <tr>
            <td><?=$date_range?></td>
            <td>
			<?php 
			if($this->report_model->is_exists_services_point_in_given_date($sreg_site, $date_range))
			{
				echo $this->report_model->get_services_point_name($sreg_site, $date_range);
			}
			?>
            </td>
            <td>
			<?php 
            $total_booking_number=$this->report_model->get_booking_number($sreg_site, $date_range);
			$sum_booking_number=$sum_booking_number+$total_booking_number;
			echo $total_booking_number;
			?>
            </td>
            <td><?php 						
			$grand_total=0;
			$total_registration_payment= $this->report_model->get_registration_payment($sreg_site, $date_range);
			$sum_total_registration=$sum_total_registration+$total_registration_payment;
			echo $total_registration_payment;
			$grand_total=$grand_total+$total_registration_payment;
			?></td>
            <?php 
				
				foreach ($services as $services1) : ?>
            		<?php 					
					$all_package = $this->report_model->get_all_services_package_by_id($services1->services_id); 
					if($all_package)
					{
					$total_services=0;	
					foreach ($all_package as $package1) :
					$total_package=$this->report_model->get_revenue_daily_services_in_package($sreg_site,$date_range,$services1->services_id,$package1->package_id);
					$total_services=$total_package+$total_services;
					
					echo '<td>'.$total_package.'</td>';
					endforeach;
					echo '<td style="font-weight:bold; font-size:12px">'.$total_services.'</td>';
					$grand_total=$total_services+$grand_total;
					}
					else
					{
						$total_package1=0;
						if($services1->services_id==4)
						{
							$total_package1=$this->report_model->get_revenue_daily_services_in_package($sreg_site,$date_range,4,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$total_package1.'</td>';	
						}
						if($services1->services_id==6)
						{
							$total_package1=$this->report_model->get_revenue_daily_services_in_package($sreg_site,$date_range,6,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$total_package1.'</td>';	
						}
						$grand_total=$total_package1+$grand_total;

					}
					?>
			<?php endforeach; ?>
            
          <td style="font-weight:bold; font-size:12px"><?=$grand_total?> <?=lang('taka')?><?php $sum_grand_total=$sum_grand_total+$grand_total;?></td>
          <?php
			endforeach;
		  	}
			?>
          </tr>
        
        
         <?php if(isset($sdate1)&& isset($sdate2)&&isset($sreg_site)){ ?>
          <tr>
            <td>TOTAL</td>
            <td>&nbsp;</td>
            <td><?=$sum_booking_number?></td>
            <td><?=$sum_total_registration?></td>            
             <?php 
				//$grand_total=0;
				foreach ($services as $services1) : ?>
            		<?php 					
					$all_package = $this->report_model->get_all_services_package_by_id($services1->services_id); 
					if($all_package)
					{
					$total_services=0;	
					foreach ($all_package as $package1) :
					
					
					echo '<td>'.$this->report_model->get_revenue_date_range_services_in_package($sreg_site,$sdate1,$sdate2,$services1->services_id,$package1->package_id).'</td>';
					endforeach;
					echo '<td style="font-weight:bold; font-size:12px">'.$this->report_model->get_revenue_date_range_services($sreg_site,$sdate1,$sdate2,$services1->services_id).'</td>';
					
					}
					else
					{
						$total_package1=0;
						if($services1->services_id==4)
						{
							//$total_package1=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,4,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$this->report_model->get_revenue_date_range_services_in_package($sreg_site,$sdate1,$sdate2,4,1).'</td>';	
						}
						if($services1->services_id==6)
						{
							//$total_package1=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,6,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$this->report_model->get_revenue_date_range_services_in_package($sreg_site,$sdate1,$sdate2,6,1).'</td>';	
						}
						//$grand_total=$total_package1+$grand_total;

					}
					?>
			<?php endforeach; ?>
            <td style="font-weight:bold; font-size:12px"><?=$sum_grand_total?> <?=lang('taka')?></td>
          </tr>
          <?php } ?>
          
        </table>




                

     
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>