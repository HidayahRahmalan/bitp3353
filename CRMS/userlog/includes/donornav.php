<style>
        .navbar-brand img.navbar-icon {
            height: 30px; /* Adjust the height to fit your design */
            width: auto;  /* Maintain aspect ratio */
            display: inline-block;
            vertical-align: middle;
        }

        .announcement-icon {
            vertical-align: middle; /* Align the icon vertically */
            cursor: pointer;
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            padding: 3px 6px;
            border-radius: 50%;
            background: grey;
            color: white;
        }

        .dropdown-menu-right {
            min-width: 300px; /* Increase the size of the message list box */
            max-height: 400px; /* Set a max height */
            overflow-y: auto;  /* Enable scrolling */
        }

        .list-group-item {
            word-wrap: break-word; /* Ensure long messages wrap properly */
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php
        include 'session.php';
        include '../dbcon.php';

        // Fetch unread announcements count for the logged-in user
        $qry = "SELECT COUNT(*) as unread_count FROM announcement WHERE user_id = $session_id AND is_read = 0";
        $result = mysqli_query($con, $qry);
        $row = mysqli_fetch_assoc($result);
        $unread_count = $row['unread_count'];
        ?>

        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="userdashboard.php">
                    <img src="../img/clothes-donation.png" alt="" class="navbar-icon">&nbsp;&nbsp;CRMS
                </a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: black;">
                        <?php
                        if (isset($_SESSION['name'])) {
                            echo $_SESSION['name'];
                        } else {
                            echo "Guest";
                        }
                        ?>
                        <i class="fa fa-user fa-fw" style="color: black;"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="profile.php"><i class="fa fa-user fa-fw"></i> Profile</a></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <i class="fa fa-envelope fa-2x announcement-icon" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                    <?php if ($unread_count > 0) { ?>
                        <span class="notification-badge"><?php echo $unread_count; ?></span>
                    <?php } ?>
                    <ul class="dropdown-menu dropdown-user dropdown-menu-right">
                        <li>
                            <ul class="list-group" style="margin: 10px;">
                                <?php
                                $qry = "SELECT message, created_at FROM announcement WHERE user_id = $session_id AND is_read = 0 ORDER BY created_at DESC";
                                $result = mysqli_query($con, $qry);

                                while($row = mysqli_fetch_assoc($result)){
                                    $formatted_date = date('d-m-Y', strtotime($row['created_at']));
                                    echo "<li class='list-group-item'>
                                            <div>{$row['message']}</div>
                                            <small>{$formatted_date}</small>
                                          </li>";
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li><a href="userdashboard.php"><i class="fa fa-dashboard fa-fw"></i> User's Dashboard</a></li>
                        <li><a href="userviewcampaigns.php"><i class="fa fa-flag"></i> View Campaigns</a></li>
                        <li>
                            <a href="#"><i class="fa fa-archive"></i> Donate Item <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="adddonate.php">New Donate</a></li>
                                <li><a href="viewdonate.php">View Donate Details</a></li>
                            </ul>
                        </li>
                        <li><a href="viewannounce.php"><i class="fa fa-bullhorn"></i> View Announcements</a></li>
                        <li><a href="addfeedback.php"><i class="fa fa-comments" aria-hidden="true"></i> Feedback</a></li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
    </div>

    
    <script>
        $(document).ready(function() {
            // Update announcements as read when the icon is clicked
            $('.announcement-icon').on('click', function() {
                $.ajax({
                    url: 'update_announcement.php',
                    type: 'POST',
                    data: { user_id: <?php echo $session_id; ?> },
                    success: function(response) {
                        // Remove the notification badge after updating
                        $('.notification-badge').remove();
                    }
                });
            });
        });
    </script>
</body>