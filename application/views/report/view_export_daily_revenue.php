<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=GramCar_revenue_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1"> 	
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
					echo '<td style="font-weight:bold; font-size:12px"></td>';
					
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