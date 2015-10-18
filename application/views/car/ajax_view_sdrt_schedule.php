<table class="table table-striped" style="font-size:10px">
  
  <tr>
    <th>Date</th>
    <th>Car</th>
    <th>Start Time</th>
    <th>Arrival Time</th>
    <th>Available Seat</th>
    <th>Per Seat Fare</th>
    <th style="background-color:#FFE1D2">From - To - Seat</th>
  </tr>
  <?php
  foreach($schedule_array as $schedule)
  {
  ?>  
  <tr align="center">
    <td><?=$schedule->schedule_date?></td>
    <td><?=$this->booking_model->get_car_name($schedule->car_id)?></td>
    <td><?=$schedule->start_time?></td>
    <td><?=$schedule->arrival_time?></td>
    <td>
	<?php 
	$available_seat=$this->booking_model->get_available_seat_in_a_car($schedule->schedule_id,$schedule->car_id);
	echo $available_seat;
	?></td>
    <td><?=$schedule->per_seat_fare ?> TK</td>
    <td style="background-color:#FFE1D2">
    <!--   Another table  -->
    	<div id="sdrt_booking_form_<?=$schedule->schedule_id?>">
        <table border="0" style="border:none;">
        	<tr>
          		<td style="background-color:#FFE1D2;border:none;">From</td>
          		<td style="background-color:#FFE1D2;border:none;">
                <select id="pickup_point_id_<?=$schedule->schedule_id?>" name="pickup_point_id_<?=$schedule->schedule_id?>" class="input-medium" style="font-size:10px">
                    <option value="">Select Pickup Point </option>
                    <?php foreach ($all_pickup_point as $key => $pickup_point) {?>
                    <option <?php echo set_select('pickup_point_id')==$pickup_point->pickup_point_id? "selected":""; ?> value="<?php echo $pickup_point->pickup_point_id; ?>"><?php echo $pickup_point->pickup_point_en; ?></option>
                    <?php
                    }?>                          
                </select>
                </td>
          		<td rowspan="3" style="background-color:#FFE1D2;border:none;">
                <button name="booked_<?=$schedule->schedule_id?>" id="booked_<?=$schedule->schedule_id?>" onClick="book_my_seat(this.id)" class="btn btn-mini btn-warning" type="button">Book my seat</button>
                </td>
        	</tr>
        	<tr>
          		<td style="background-color:#FFE1D2;border:none;">To</td>
          		<td style="background-color:#FFE1D2;border:none;">
                <select id="drop_point_id_<?=$schedule->schedule_id?>" name="drop_point_id_<?=$schedule->schedule_id?>" class="input-medium" style="font-size:10px">
                    <option value="">Select Drop Point </option>
                    <?php foreach ($all_pickup_point as $key => $pickup_point) {?>
                    <option <?php echo set_select('drop_point_id')==$pickup_point->pickup_point_id? "selected":""; ?> value="<?php echo $pickup_point->pickup_point_id; ?>"><?php echo $pickup_point->pickup_point_en; ?></option>
                    <?php
                    }?>                          
                </select>
                </td>
        	</tr>
        	<tr>
          		<td style="background-color:#FFE1D2;border:none;">Number of Seat</td>
          		<td style="background-color:#FFE1D2;border:none;">
                <select id="no_of_seat_<?=$schedule->schedule_id?>" name="no_of_seat_<?=$schedule->schedule_id?>" class="input-mini" style="font-size:10px">        
					<?php
                    for($i=1;$i<=$available_seat;$i++)
                    {
                    ?>
                    <option value="<?=$i?>"><?=$i?></option>
                    <?php
                    }
                    ?>
                </select> 
                
                </td>
        	</tr>
      	</table>
        </div> <!-- sdrt_booking_form END  -->
    <!--  End Another table  -->       
       
    </td>

  </tr>
  <?php
  }
  ?>
</table>
