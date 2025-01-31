<?php
session_start();
$host = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "SCSite";

$logged_in = isset($_SESSION['studentID']);

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $grade = trim($_POST['grade']) . trim($_POST['liter']);
    $shanyraqID = trim($_POST['shanyraqID']);

    // Validate required fields
    if (empty($name) || empty($surname) || empty($phoneNumber) || empty($email) || empty($password) || empty($grade) || empty($shanyraqID)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: signUp.php");
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO Students (name, surname, email, password, grade, shanyraqID, phoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $surname, $email, $hashed_password, $grade, $shanyraqID, $phoneNumber);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: signUp.php");
        exit();
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="author" content="Akylbek Miras">
  <link rel="stylesheet" href="css/login.min.css">
  <link rel="stylesheet" href="frameworks/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
  <div class="sign-up">
    <form class="form my-5" action="signUp.php" method="POST" onsubmit="return validateForm()">
      <h2 class="text-center">Welcome to NIS Student Council!</h2>
      <p class="text-center mb-4 text-muted">Please, fill in all fields.</p>

      <div class="mb-2">
        <div class="row justify-content-around">
          <div class="input-container my-4 col-md-6">
            <input type="text" id="name-animated-input" name="name" placeholder="" required autocomplete="off">
            <label for="name-animated-input">Name</label>
            <div class="input-bg"></div>
          </div>
          <div class="input-container my-4 col-md-6">
            <input type="text" id="surname-animated-input" name="surname" class="" placeholder="" required autocomplete="off">
            <label for="surname-animated-input">Surname</label>
            <div class="input-bg"></div>
          </div>
        </div>

        <div class="row justify-content-around">
          <div class="input-container my-4 col-md-6">
            <input type="tel" id="phone-animated-input" name="phoneNumber" placeholder="8xxxxxxxxxx" required autocomplete="off">
            <label for="phone-animated-input">Phone number</label>
            <div class="input-bg"></div>
          </div>
          <div class="input-container my-4 col-md-6">
            <input type="email" id="email-animated-input" name="email" placeholder="" required autocomplete="off">
            <label for="email-animated-input">Email</label>
            <div class="input-bg"></div>
          </div>
        </div>

        <div class="row justify-content-around">
          <div class="mt-4 col-md-6">
            <div class="input-container password" style="margin: auto; position: relative;">
              <input type="password" id="password-animated-input" placeholder="" required autocomplete="off" onkeyup="validatePassword()">
              <label for="password-animated-input">Password</label>
              <div class="input-bg"></div>
              <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-animated-input', 'eye-icon-1')"
                      style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                <i id="eye-icon-1" class="fa-solid fa-eye-slash"></i>
              </button>
            </div>
            <p id="password-warning" class="px-3" style="color: red; font-size: 14px; display: none;">Password must be at least 8 characters long and contain: A-Z, a-z, 0-9 and special character (!@#$%^&*).</p>
          </div>

          <div class="mt-4 col-md-6">
            <div class="input-container password" style="margin: auto; position: relative;">
              <input type="password" id="verify-password-animated-input" name="password" placeholder="" required autocomplete="off" onkeyup="checkPasswordMatch()">
              <label for="verify-password-animated-input">Confirm Password</label>
              <div class="input-bg"></div>
              <button type="button" class="toggle-password" onclick="togglePasswordVisibility('verify-password-animated-input', 'eye-icon-2')"
                      style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                <i id="eye-icon-2" class="fa-solid fa-eye-slash"></i>
              </button>
            </div>
            <p id="match-warning" class="px-3" style="color: red; font-size: 14px; display: none;">Passwords do not match.</p>
          </div>
        </div>
      </div>

      <div class="row justify-content-around lr mb-4">
        <div class="col-md-6">
          <div class="select-grade">
            <div class="input-container my-5 s">
              <select class="form-select shadow" id="grade" name="grade" required>
                <option value="" selected disabled class=""></option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
              <label for="grade">Grade</label>
              <div class="input-bg"></div>
            </div>
          </div>

          <div class="select-liter">
            <div class="input-container my-5 s">
              <select class="form-select shadow" id="liter" name="liter" required>
                <option value="" selected disabled class=""></option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
                <option value="K">K</option>
              </select>
              <label for="liter">Liter</label>
              <div class="input-bg"></div>
            </div>
          </div>

          <div class="select-liter">
            <div class="input-container my-5 s">
              <select class="form-select shadow" id="shanyraq" name="shanyraqID" required>
                <option value="" selected disabled></option>
                <option value="1">Aqjaiyq</option>
                <option value="2">Alatau</option>
                <option value="3">Alash</option>
                <option value="4">Arys</option>
                <option value="5">Atameken</option>
                <option value="6">Baiqonyr</option>
                <option value="7">Jetisu</option>
                <option value="8">Kaspi</option>
                <option value="9">Oqjetpes</option>
                <option value="10">Orda</option>
                <option value="11">Samuryq</option>
                <option value="12">Saryarqa</option>
                <option value="13">Tulpar</option>
                <option value="14">Han-taniri</option>
                <option value="15">Shalqar</option>
              </select>
              <label for="shanyraq">Shanyraq</label>
              <div class="input-bg"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="signUpImg"></div>
        </div>
      </div>

      <div class="text-center">
        <button type="submit" name="submit" class="btn btn-primary sign-up-btn mb-2">Sign Up</button>
        <p class="text-center text-muted">Already have an account? <a href="signIn.php" class="">Sign in</a></p>
      </div>
    </form>
  </div>
</main>

<footer class="footer">
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
        <!--            <div class="col-lg-2"></div>-->
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
<script src="frameworks/bootstrap.bundle.min.js"></script>
<script src="js/pagesJs/password.js"></script>
<!-- <script src="frameworks/bootstrap/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</body>
</html>