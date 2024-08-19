<!DOCTYPE html>
<html>
<head>
    <title>CRMS - Homepage</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="img/clothes-donation.png" rel="icon">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body, html {
            height: 100%;
            font-family: "Inconsolata", sans-serif;
        }

        .bgimg {
            background-position: center;
            background-size: cover;
            background-image: url("im1.png");
            min-height: 75%;
        }

        .menu {
            display: none;
        }

        .img-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .img-row img {
            width: 32%; /* Adjust the width as needed to fit within the container */
            margin-top: 16px; /* Add margin for spacing between images */
        }
    </style>
</head>
<body>

<!-- Links (sit on top) -->
<div class="w3-top">
    <div class="w3-row w3-padding w3-black">
        <div class="w3-col s3">
            <a href="#" class="w3-button w3-block w3-black">HOME</a>
        </div>
        <div class="w3-col s3">
            <a href="#about" class="w3-button w3-block w3-black">ABOUT</a>
        </div>
        <div class="w3-col s3">
            <a href="#where" class="w3-button w3-block w3-black">HOW</a>
        </div>
        <div class="w3-col s3">
            <a href="userlog/userlogin.php" class="w3-button w3-block w3-black">LOGIN</a>
        </div>
    </div>
</div>

<!-- Header with image -->
<header class="bgimg w3-display-container w3-grayscale-min" id="home">
    <div class="w3-display-bottomleft w3-center w3-padding-large w3-hide-small">
        <span class="w3-tag"></span>
    </div>
    <div class="w3-display-middle w3-center">
        <span class="w3-text-white" style="font-size:90px"><br></span>
    </div>
    <div class="w3-display-bottomright w3-center w3-padding-large">
        <span class="w3-text-white"></span>
    </div>
</header>

<!-- Add a background color and large text to the whole page -->
<div class="w3-sand w3-grayscale w3-large">

<!-- About Container -->
<div class="w3-container" id="about">
    <div class="w3-content" style="max-width:700px">
        <h5 class="w3-center w3-padding-64"><span class="w3-tag w3-wide">ABOUT US</span></h5>
        <p>At Clothes Recycle Management System (CRMS), we are dedicated to fostering a more caring and sustainable community by making it effortless for individuals to donate clothes to those in need. 
            Through our innovative platform, we promote local recycling events in Pulau Pinang and streamline the entire donation process, enabling donors to easily schedule pickups or drop-offs and helping organizations manage their clothing drives efficiently.</p>
        <p>Join us in spreading kindness!</p>
        <div class="w3-panel w3-leftbar w3-light-grey">
            <p><i>“No one has ever become poor from giving.”</i></p>
            <p>Anne Frank</p>
        </div>
        <div class="img-row">
            <img src="img/bg1.jpeg" class="w3-margin-top">
            <img src="img/bg2.jpeg" class="w3-margin-top">
            <img src="img/bg3.jpg" class="w3-margin-top">
        </div>
    </div>
</div>

<!-- Menu Container -->
<div class="w3-container" id="menu">
    <div class="w3-content" style="max-width:700px">
        <h5 class="w3-center w3-padding-48"><span class="w3-tag w3-wide">DROPOFF LOCATION</span></h5>
        <center><p>To all Penangites and Melakarians, you can drop your item at our selected pickup point.</p></center>
        <div class="w3-row w3-center w3-card w3-padding">
            <a href="javascript:void(0)" onclick="openMenu(event, 'place1');" id="myLink">
                <div class="w3-col s6 tablink">Penang</div>
            </a>
            <a href="javascript:void(0)" onclick="openMenu(event, 'place2');">
                <div class="w3-col s6 tablink">Melaka</div>
            </a>
        </div>

        <div id="place1" class="w3-container menu w3-padding-48 w3-card">
            <center>
                <h5 style="font-weight: bold;">Usnasa Bundle Kepala Batas</h5>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.334984284675!2d100.44650327349763!3d5.517204134041692!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ad1f1a7f2a017%3A0xde72b3824c7c9117!2sUsnasa%20Bundle%20Kepala%20Batas!5e0!3m2!1sen!2smy!4v1717944745575!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <p class="w3-text-grey"> NO 56, Jalan Dagangan 2, Pusat Bandar Bertam Perdana, 13200 Kepala Batas, Pulau Pinang</p><br>
                <h5 style="font-weight: bold;">The Vintage Attire</h5>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.3277929407695!2d100.42569907349684!3d5.3668728355256405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac7f871599ee3%3A0xd00938f2bd7f5031!2sThe%20Vintage%20Attire!5e0!3m2!1sen!2smy!4v1717944778877!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <p class="w3-text-grey">17, Lorong Perda Utama 4, Bandar Baru Perda, 14000 Bukit Mertajam, Pulau Pinang</p><br>
            </center>
        </div>

        <div id="place2" class="w3-container menu w3-padding-48 w3-card">
            <center>
                <h5 style="font-weight: bold;">Pusat Amal QC (HQ MELAKA)</h5> 
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.615946800922!2d102.2117744734887!3d2.2957580578086723!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1fb5c7b060641%3A0x7d49e172cc0aa0f5!2sPusat%20Amal%20QC%20(HQ%20MELAKA)!5e0!3m2!1sen!2smy!4v1718703154174!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <p class="w3-text-grey">14, Jalan Orkid 1, Taman Paya Rumput Perdana, 76450 Melaka </p><br>
            </center>
        </div>
    </div>
</div>

<!-- Contact/Area Container -->
<div class="w3-container" id="where" style="padding-bottom:32px;">
    <div class="w3-content" style="max-width:700px">
        <h5 class="w3-center w3-padding-48"><span class="w3-tag w3-wide">HOW TO DONATE?</span></h5>
        <center><p>Donate now by following these simple steps:</p></center>
        <div class="img-row">
            <img src="img/1.png" class="w3-margin-top">
            <img src="img/2.png" class="w3-margin-top">
            <img src="img/3.png" class="w3-margin-top">
        </div>
        <p><span class="w3-tag">1</span> <strong>Pack</strong> your clothes into boxes or bag.</p>
        <p><span class="w3-tag">2</span> <strong>Login</strong> to your account and submit your donation.</p>
        <p><span class="w3-tag">3</span> <strong>Drop</strong> your items at the pickup location. </p>
    </div>
</div>

<!-- End page content -->
</div>

<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-48 w3-large">
    <p>&copy; <?php echo date("Y"); ?> Clothes Recycle Management System</p>
</footer>

<script>
// Tabbed Menu
function openMenu(evt, menuName) {
    var i, x, tablinks;
    x = document.getElementsByClassName("menu");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" w3-dark-grey", "");
    }
    document.getElementById(menuName).style.display = "block";
    evt.currentTarget.firstElementChild.className += " w3-dark-grey";
}
document.getElementById("myLink").click();
</script>

</body>
</html>
