<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=GramCar_services_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1"> 	
          <tr align="center">
            <td rowspan="2">Date</td>
            <td rowspan="2">Services Point</td>
            <td rowspan="2">Booking</td>
            <td rowspan="2">Reg.</td>
            <?php foreach ($services as $services1) : ?>
            <?php echo '<td colspan="'.($this->report_model->get_package_number_from_service_id($services1->services_id)+1).'"><abbr title="'.$services1->services_name.'">'.substr($services1->services_name,0,5).'</abbr></td>'; ?>
			<?php endforeach; ?>
            <td rowspan="2">Grand </td>
          </tr>
          <tr>
          	<?php foreach ($services as $services1) : ?>
            		<?php $all_package = $this->report_model->get_all_services_package_by_id($services1->services_id); 
					if($all_package)
					{
					foreach ($all_package as $package1) :
					echo '<td><abbr title="'.$package1->package_name.'">'.substr($package1->package_name,0,3).'</abbr></td>';
					endforeach;
					echo '<td> Total </td>';
					}
					else
					{
					echo '<td>&nbsp;</td>';
					}
					?>
			<?php endforeach; ?>
           
          </tr>
          <?php
		  $sum_booking_number=0;
		  $sum_total_registration=0;
		  $sum_total_package=0;
		  $sum_total_services=0;
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
            <td>
			<?php 
			$total_registration =$this->report_model->get_number_of_registration($sreg_site, $date_range);
			$sum_total_registration=$sum_total_registration+$total_registration;
			echo $total_registration;
			?>
            </td>
            <?php 
				$grand_total=0;
				foreach ($services as $services1) : ?>
            		<?php 					
					$all_package = $this->report_model->get_all_services_package_by_id($services1->services_id); 
					if($all_package)
					{
					$total_services=0;	
					foreach ($all_package as $package1) :
					$total_package=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,$services1->services_id,$package1->package_id);
					//$sum_total_package=$sum_total_package+$total_package;
					$total_services=$total_package+$total_services;
					//$sum_total_services=$sum_total_services+$total_services;
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
							$total_package1=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,4,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$total_package1.'</td>';	
						}
						if($services1->services_id==6)
						{
							$total_package1=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,6,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$total_package1.'</td>';	
						}
						$grand_total=$total_package1+$grand_total;
						

					}
					?>
			<?php endforeach; ?>
            
          <td style="font-weight:bold; font-size:12px"><?=$grand_total?><?php $sum_grand_total=$sum_grand_total+$grand_total;?></td>
          	<?php
			endforeach;
		  	}
			?>         
          </tr>
          
          <?php if(isset($sdate1)&& isset($sdate2)&&isset($sreg_site)){ ?>
          <tr>
            <td>TOTAL</td>
            <td>&nbsp;</td>
            <td><?=$sum_booking_number?> </td>
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
					
					
					echo '<td>'.$this->report_model->get_count_date_range_services_in_package($sreg_site,$sdate1,$sdate2,$services1->services_id,$package1->package_id).'</td>';
					endforeach;
					echo '<td style="font-weight:bold; font-size:12px">'.$this->report_model->get_count_date_range_services_in_package_sum($sreg_site,$sdate1,$sdate2,$services1->services_id,$package1->package_id).'</td>';
					
					}
					else
					{
						$total_package1=0;
						if($services1->services_id==4)
						{
							//$total_package1=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,4,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$this->report_model->get_count_date_range_services_in_package($sreg_site,$sdate1,$sdate2,4,1).'</td>';	
						}
						if($services1->services_id==6)
						{
							//$total_package1=$this->report_model->get_count_daily_services_in_package($sreg_site,$date_range,6,1);
							echo '<td style="font-weight:bold; font-size:12px">'.$this->report_model->get_count_date_range_services_in_package($sreg_site,$sdate1,$sdate2,6,1).'</td>';	
						}
						//$grand_total=$total_package1+$grand_total;

					}
					?>
			<?php endforeach; ?>
            <td style="font-weight:bold; font-size:12px"><?=$sum_grand_total?></td>
          </tr>
          <?php } ?>
          
        </table>