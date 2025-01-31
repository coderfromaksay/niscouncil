<?php

session_start();

$host = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "SCSite";

$logged_in = isset($_SESSION['studentID']);

// Connect to database using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// SQL query to fetch data from Shanyraqs table
$query = "SELECT shanyraqID, shanyraqName, shanyraqPointsFirstHalf, shanyraqPointsSecondHalf FROM Shanyraqs";
$result = $conn->query($query);

$shanyraqs = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $shanyraqs[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Akylbek Miras">
    <link rel="stylesheet" href="css/rating.min.css">
    <link rel="stylesheet" href="frameworks/bootstrap.min.css">
    <title>NIS Council Oral</title>
    <!-- <script src="https://kit.fontawesome.com/d9162f00fa.js" crossorigin="anonymous"></script> -->
</head>
<body class="">

<header class="header background-img">
   <div class="container">
      <nav class="d-flex justify-content-between align-items-center header-container">
         <a href="home.php"><img src="img/naizablacklogo.png" alt="logo" class="logo"></a>
         <ul class="header-ul d-lg-flex d-none">
            <li><a action="team.php" href="team.php" class="text text-center">Team</a></li>
            <li><a href="https://nml-oral.vercel.app" class="text text-center">NML</a></li>
            <li><a href="rating.php" class="text text-center">Rating</a></li>
            <li><a href="home.php" class="text text-center">Home</a></li>
         </ul>
         <?php if ($logged_in): ?>
             <!-- Show Profile Icon if Logged In -->
             <a class="btn px-3 d-lg-block d-none" href="profile.php">
                 <i class="fa-solid fa-user" style="font-size: 20px;"></i><label class="mx-1 text">Profile</label>
             </a>
         <?php else: ?>
             <!-- Show Sign In Button if NOT Logged In -->
             <a class="button-header btn px-4  d-lg-block d-none" href="signIn.php">Sign in
                         <svg  xmlns="http://www.w3.org/2000/svg" width="7" height="11" viewBox="0 0 7 11" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.29289 5.50001L0 2.20712L1.41421 0.792908L6.12132 5.50001L1.41421 10.2071L0 8.79291L3.29289 5.50001Z" fill="white"/>
                         </svg>
                      </a>
         <?php endif; ?>
         <!--         <button class="button-header btn px-4  d-lg-none d-block navbar-toggler collapsed px-4 py-3" data-bs-toggle="collapse" data-bs-target="#header-info" aria-expanded="false" aria-controls="company-info">Navigation</button>-->
         <button type="button" class="navbar-togler navbar-toggler collapsed py-2 px-2 d-lg-none d-block" data-bs-toggle="collapse" data-bs-target="#header-info" aria-expanded="false" aria-controls="company-info"><span class="navbar-togler-icon"></span></button>
      </nav>
      <div class="collapse d-lg-none" style="" id="header-info">
         <div class="container my-2">
            <div class="row">
               <div class="list-unstyled list-group text-decoration-none">
                  <a href="team.php" class="text-center list-group-item list-group-item-action p-2">Team</a>
                  <a href="https://nml-oral.vercel.app" class=" text-center list-group-item list-group-item-action p-2">NML</a>
                  <a href="rating.php" class=" text-center list-group-item list-group-item-action p-2">Rating</a>
                  <a href="home.php" class=" text-center list-group-item list-group-item-action p-2">Home</a>
                    <?php if ($logged_in): ?>
                      <a href="profile.php" class=" text-center list-group-item list-group-item-action p-2">Profile</a>
                    <?php else: ?>
                      <a href="signIn.php" class=" text-center list-group-item list-group-item-action p-2">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
         </div>
      </div>
   </div>
</header>

<main class="container">

    <nav class="rating-nav d-flex justify-content-around align-items-center">
        <a href="#" class="active text-mid" data-rating="shanyraqs">Shanyraqs' rating</a>
        <a href="#" class="text-mid" data-rating="students">Students' rating</a>
    </nav>

    <section id="shanyraqs" class="rating-section active" style="">
        <h1 class="text-mid text-center">Rating of Shanyraqs</h1>
        <table class="table shadow-lg table-stripeds" id="shanyraqTable">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center align-content-center up-left-corner">Shanyraq</th>
                    <th colspan="5" class="text-center up-right-corner">Points</th>
                </tr>
                <tr>
                    <th class="text-center d-sm-table-cell d-none">1st half</th>
                    <th class="text-center d-sm-table-cell d-none">2nd half</th>
                    <th class="text-center">Overall</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shanyraqs as $shanyraq): ?>
                    <tr>
                        <td class="text-center"> <?= htmlspecialchars($shanyraq['shanyraqName']) ?> </td>
                        <td class="text-center d-sm-table-cell d-none"><?= $shanyraq['shanyraqPointsFirstHalf'] ?></td>
                        <td class="text-center d-sm-table-cell d-none"><?= $shanyraq['shanyraqPointsSecondHalf'] ?></td>
                        <td class="text-center">-</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section id="students" class="rating-section" style="">
        <h1 class="text-mid text-center ">Rating of Students</h1>
        <table class="table shadow-lg table-striped">
            <thead>
            <tr>
                <th class="text-center align-content-center up-left-corner">Student</th>
                <th class="text-center d-sm-table-cell d-none">Shanyraq</th>
                <th class="text-center d-sm-table-cell d-none">Class</th>
                <th class="text-center up-right-corner">Points</th>
            </tr>
            </thead>
        </table>
    </section>
</main>

<footer class="footer mt-5">
    <div class="container py-4">
        <div class="align-items-center footer-container">
            <div class="row">
                <div class="col-sm-3 text-center text-md-start">
                    <div class="row">
                        <p class="text p-2">NIS Student Council</p>
                    </div>
                    <div class="row">
                        <p class="text p-2">Powered by MofD</p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-md-start">
                    <ul class="footer-ul">
                        <li class="first-li text px-2">Help</li>
                        <li><a href="" class="text-lighter">Terms</a></li>
                        <li><a href="" class="text-lighter">Creators</a></li>
                        <li><a href="" class="text-lighter">Report a problem</a></li>
                    </ul>
                </div>
                <div class="col-sm-4 text-center text-md-start">
                    <div class="row social-medias py-3 d-inline-block w-100" id="social-medias">
                        <img src="img/instagram.png" alt="instagram_logo" class="instagram-logo align-center">
                        <a href="https://www.instagram.com/nisura_council?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="text text-mid align-bottom text-center">@naiza_council</a>
                    </div>
                    <div class="row social-medias py-3 d-inline-block w-100">
                        <img src="img/github.png" alt=github_logo" class="github-logo align-center">
                        <a href="https://github.com/coderfromaksay/niscouncil" target="_blank" class="text text-mid align-bottom text-center">niscouncil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>





<!-- ////////////////// -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin=""></script>
<script src="js/pagesJs/rating.js"></script>
<script src="frameworks/bootstrap.bundle.min.js"></script>
<!-- <script src="frameworks/bootstrap/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</body>
</html>