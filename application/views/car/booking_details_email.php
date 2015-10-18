<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	.row{
		padding: 20px 30px;
		background: #ddd;		
	}
	.container{
		background: #FFF;
		padding: 20px;
		border-radius: 10px;
		border: 1px solid #ddd;
	}
	table{
		margin-bottom: 20px;
		margin-top: 20px;
		border:solid 1px #ddd;
		border-spacing: 0px;
		border-collapse: collapse;
	}
	table tr td, th{
		padding: 5px;
	}

	</style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
</head>
<body>
	<div class="row">
	<div class="container">
		<header>
			<div>
				<a href="http://gramweb.net/projects/ssw/gramcar/" target="_blank" title="SSW GramCar"><img src="http://gramweb.net/projects/ssw/gramcar/resource/img/gramcar_logo.png"></a>
			</div>
		</header>
		<hr>
		<br>
		Hi <?php echo $username;?>, <br> Here is your booking details
		<table width="100%" border="1">
			<thead>
				<tr>
					<th colspan="2">Pickup Point Details</th>
					<th colspan="2">Drop Point Details</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Date for booking : </th>
					<td><?php echo $booking_date;?></td>
					<th>Total cost (No. of seat <?php echo $no_of_set;?>) : </th>
					<td> ৳ <?php echo $amount_of_cost;?> BDT</td>
				</tr>
				<tr>
					<th>Pickup point : </th>
					<td><?php echo $pickup_point;?></td>
					<th>Drop point : </th>
					<td><?php echo $drop_point;?></td>
				</tr>
				<tr>
					<th>Pickup time : </th>
					<td><?php echo $pickup_time;?></td>
					<th>Arrival time : </th>
					<td><?php echo $arrival_time;?></td>
				</tr>

			</tbody>
		</table>
		<br>
		<br>
		Regards,<br>
		GramCar Team<br>
		E-mail: gramcar@gramweb.net<br>
		Tel: 01974726227<br>
	</div>
    </div>
</body>
</html>
