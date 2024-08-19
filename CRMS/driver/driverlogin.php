<?php session_start(); ?>
<?php include('../dbcon.php'); ?>
<html>
<head>
	<title>Pickup Lorry Login</title>
	<link rel="stylesheet" type="text/css" href="userstyles.css">
	<link href="img/clothes-donation.png" rel="icon">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

	
</head>
<body>

<body>

	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="img/lorry.png" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="#" method="post">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="phone_num" class="form-control input_user" value="" placeholder="Phone number" required>
						</div>
						
						
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<button type="submit" name="userlogin" style="border-radius: 0%" class="btn login_btn">Driver Login</button>
				   </div>
					</form>
				</div>
				
				<?php
	if (isset($_POST['userlogin']))
		{
			$phone_num = mysqli_real_escape_string($con, $_POST['phone_num']);
			
			
			$query 		= mysqli_query($con, "SELECT * FROM driver WHERE  phone_num='$phone_num' ");
			$row		= mysqli_fetch_array($query);
			$num_row 	= mysqli_num_rows($query);
			
			if ($num_row > 0) 
				{			
					$_SESSION['driver_id']=$row['driver_id'];
					$_SESSION['name']=$row['name'];
					header('location:driverdashboard.php');
					
				}
			else
				{
					echo '
								<div class="alert alert-danger alert-dismissible">
                                        Invalid Phone Number.
                                    </div> ';
				}
		}
  ?>
				
				
		
				<div class="mt-4">
					<!--<div class="d-flex justify-content-center links">
						Don't have an account? <a href="#"  class="ml-2" style="text-decoration:none">Sign Up</a>
					</div> -->
					<div class="d-flex justify-content-center links">
						<a href="../" style="text-decoration:none; color: white">Back to Admin Panel</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>



</html>

