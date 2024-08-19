


<style>
.navbar-brand img.navbar-icon {
    height: 30px; /* Adjust the height to fit your design */
    width: auto;  /* Maintain aspect ratio */
    display: inline-block;
    vertical-align: middle;
}
</style>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="driverdashboard.php"><img src="../img/clothes-donation.png" alt="" class="navbar-icon">&nbsp;&nbsp;CRMS DRIVER</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
               
             
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <?php
                include "session.php";
                if (isset($_SESSION['name'])) {
                    echo $_SESSION['name'];
                } else {
                    echo "Guest";
                }
                ?>
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!-- <li class="divider"></li> -->
                        <li><a href="profile.php"><i class="fa fa-user fa-fw"></i> Profile</a></li>
                        
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
               
                <!-- /.dropdown -->
            </ul>
            
            <!-- /.navbar-top-links -->
              
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="driverdashboard.php"><i class="fa fa-dashboard fa-fw"></i> Driver's Dashboard</a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-archive"></i> Duty Time<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="addduty.php">Add Duty Time</a>
                                </li>
                               
                                <li>
                                        <a href="viewduty.php">View Duty Time</a>
                                    </li>
                                    
                                    
                            </ul>
                        </li>
            
                        
                        

                        <li>
                            <a href="userviewcampaigns.php"><i class="fa fa-flag"></i> View Campaigns </a>
                           
                        </li>
            
                        
                       
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>