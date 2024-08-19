<?php
include "session.php";

if(isset($_POST['submit']))
  {
    $user_id=$_SESSION['user_id'];
    $name=$_POST['name'];
  $phone_num=$_POST['phone_num'];
  $address=$_POST['address'];
  
     $query=mysqli_query($con, "update user set name ='$name',phone_num='$phone_num',address='$address' where user_id='$user_id'");
    if ($query) {
    
    echo '<script>alert("Profile has been updated")</script>';
  }
  else
    {
     
      echo '<script>alert("Something Went Wrong. Please try again.")</script>';
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>User - CRMS</title>
    <link href="img/clothes-donation.png" rel="icon">
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="../icofont/icofont.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


</head>

<body>

    <div id="wrapper">

        <?php include 'includes/donornav.php'?>

        <div id="page-wrapper">
            <div class="row">
            <div class="col-lg-12 breadcrumb-container">
                    
                    <h1 class="page-header">Profile</h1>
                            <ol class="breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Profile</li>
                            </ol>
                        </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Update your profile:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">

                                <?php
									include '../dbcon.php';
									$user_id=$_SESSION['user_id'];
									$qry= "select * from user where user_id='$user_id'";
									$result=mysqli_query($con,$qry);
									while($row=mysqli_fetch_array($result)){
									?> 

                                    <form role="form" action="" method="post">

                                    <div class="form-group">
                                            <label>User Name</label>
                                            <input class="form-control" type="text"  name="name" value='<?php echo $row['name']; ?>' required>
                                        </div>
                                    <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" type="text"  name="phone_num" value='<?php echo $row['phone_num']; ?>' required>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input class="form-control" type="text"  name="address" value='<?php echo $row['address']; ?>' required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input class="form-control" type="text"  name="username" value='<?php echo $row['username']; ?>' readonly>
                                        </div>
                                        
                                        
                                      
                                     
                                       

                                        
                                       
             <!-- id hidden grna input type ma "hidden" -->
             <input type="hidden" name="user_id" value="<?php echo $row['user_id'];?>">
                                
             <button type="submit"  class="btn btn-success">Make Changes</button>
 
                                    </form>
                                </div>

						<?php
						}
						?>
                                
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

<footer>
        <p>&copy; <?php echo date("Y"); ?>: Developed By Salihah</p>
    </footer>
	
	<style>
	footer{
   background-color: #424558;
    bottom: 0;
    left: 0;
    right: 0;
    height: 35px;
    text-align: center;
    color: #CCC;
}

footer p {
    padding: 10.5px;
    margin: 0px;
    line-height: 100%;
}
	</style>

</html>
                             
