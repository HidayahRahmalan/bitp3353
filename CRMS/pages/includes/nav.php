
<link href="images/clothes-donation.png" rel="icon">
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
                <a class="navbar-brand" href="index.php"><img src="../img/clothes-donation.png" alt="" class="navbar-icon">&nbsp;&nbsp;CRMS ADMIN</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
               
             
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> Admin <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!-- <li class="divider"></li> -->
                        <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-recycle"></i> Recycle Event Details <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="addevent.php">Add Event</a>
                                </li>
                                <li>
                                    <a href="viewevent.php">View Event</a>
                                </li>
                                
                                <li>
                                    <a href="deleteevent.php">Remove Event</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="add_pickup.php"><i class="fa fa-table fa-calendar-plus-o"></i> Add Pickup</a>
                        </li>
                        <li>
                            <a href="viewpickup.php"><i class="fa fa-edit fa-eye"></i> View Pickup Details</a>
                        </li>
                        
                        <li>
                            <a href="deletepickup.php"><i class="fa fa-calendar-times-o"></i> Delete Pickup Details</a>
                        </li>
                       

                        <li>
                            <a href=""><i class="fa fa-truck"></i> Lorry <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add_driver.php">New Lorry Driver</a>
                                </li>
                                <li>
                                    <a href="add_lorry.php">New Lorry </a>
                                </li>
                                <li>
                                        <a href="viewlorry.php">View Lorry Available</a>
                                    </li>
                                    
                                    <li>
                                        <a href="deletelorry.php">Delete Lorry Details</a>
                                    </li>
                            </ul>
                        </li>

                        <li>
                            <a href=""><i class="fa fa-list"></i> Item Category <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="additem.php">New Item Category</a>
                                </li>
                                <li>
                                        <a href="viewitemcat.php">View Item Category</a>
                                    </li>
                                    
                                    <li>
                                        <a href="deleteitem.php">Delete Item Category</a>
                                    </li>
                            </ul>
                        </li>
            
                        <li>
                            <a href=""><i class="fa fa-file"></i> Report <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="reportdonationsummary.php">Donation Summary</a>
                                </li>
                                <li>
                                    <a href="reportinventory.php">Inventory Status</a>
                                </li>
                                <li>
                                    <a href="reportlorryhistory.php">Lorry History</a>
                                </li>
                                    
                            </ul>
                        </li>

                        <li>
                                        <a href="viewfeedback.php"> <i class="fa fa-comments" aria-hidden="true"></i> User Feedback</a>
                                    </li>
                                    <li>
                                        <a href="viewredeem.php"> <i class="fa fa-tags" aria-hidden="true"></i> User Points Redemption</a>
                                    </li>
                       
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>