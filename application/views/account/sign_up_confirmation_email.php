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
		Hi <?php echo $username;?>, <br><br> Thanks for creating an account on GramCar.<br>
        Please follow the username and password to login into GramCar applictation<br>
        <br>
        Username : <?php echo $username;?>
		<br>
        Password : <?php echo $password;?>
        

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