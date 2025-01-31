<?php
session_start();
$host = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "SCSite";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: profile.php");
    exit();
}

// Check if user is logged in
$logged_in = isset($_SESSION['studentID']);
$user = null;
$update_success = null;
$error_message = null;

if ($logged_in) {
    $studentID = $_SESSION['studentID'];

    // Fetch user details
    $query = "
        SELECT s.name, s.surname, s.email, s.phoneNumber, s.grade, s.shanyraqID, sh.shanyraqName
        FROM Students s
        LEFT JOIN Shanyraqs sh ON s.shanyraqID = sh.shanyraqID
        WHERE s.studentID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        session_destroy();
        header("Location: profile.php");
        exit();
    }
}

// Handle profile update
if ($logged_in && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_surname = trim($_POST['surname']);
    $new_email = trim($_POST['email']);
    $new_phoneNumber = trim($_POST['phoneNumber']);
    $new_grade = trim($_POST['grade']);
    $new_shanyraq = trim($_POST['shanyraqID']);

    // Validate inputs
    if (empty($new_name) || empty($new_surname) || empty($new_email) || empty($new_phoneNumber) || empty($new_grade) || empty($new_shanyraq)) {
        $error_message = "All fields are required.";
    } else {
        // Update user details in the database
        $update_query = "
            UPDATE Students
            SET name = ?, surname = ?, email = ?, phoneNumber = ?, grade = ?, shanyraqID = ?
            WHERE studentID = ?
        ";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssi", $new_name, $new_surname, $new_email, $new_phoneNumber, $new_grade, $new_shanyraq, $studentID);

        if ($stmt->execute()) {
            $update_success = "Profile updated successfully!";
            // Refresh user data
            $user['name'] = $new_name;
            $user['surname'] = $new_surname;
            $user['email'] = $new_email;
            $user['phoneNumber'] = $new_phoneNumber;
            $user['grade'] = $new_grade;
            $user['shanyraqID'] = $new_shanyraq;
            header("Location: profile.php?updated=1");
            exit();
        } else {
            $error_message = "Failed to update profile.";
        }

        $stmt->close();
    }
}

// Close DB connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Akylbek Miras">
    <link rel="stylesheet" href="css/profile.min.css">
    <link rel="stylesheet" href="css/login.min.css">
    <link rel="stylesheet" href="frameworks/bootstrap.min.css">
    <title>NIS Council Oral</title>
    <!-- <script src="https://kit.fontawesome.com/d9162f00fa.js" crossorigin="anonymous"></script> -->
    <script>
        function toggleEditMode() {
            document.getElementById("edit-mode").style.display = "block";
            document.getElementById("view-mode").style.display = "none";
            document.getElementById("edit-btn").style.display = "none";
        }

        function cancelEdit() {
            document.getElementById("edit-mode").style.display = "none";
            document.getElementById("view-mode").style.display = "block";
            document.getElementById("edit-btn").style.display = "inline-block";
        }
    </script>
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
    <?php if ($logged_in && $user): ?>
        <div class="profile-container">
            <h2>Welcome!</h2>
            <div class="profile-img"></div>
            <!-- Success / Error Messages -->
            <?php if ($update_success): ?>
                <p style="color:green;"><?= $update_success ?></p>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <p style="color:red;"><?= $error_message ?></p>
            <?php endif; ?>

            <!-- View Mode -->
            <div id="view-mode" class="profile-info p-3">

                <p class="text-mid">Name: <?= htmlspecialchars($user['name']) ?></p>
                <p class="text-mid">Surname: <?= htmlspecialchars($user['surname']) ?></p>
                <p class="text-mid">Email: <?= htmlspecialchars($user['email']) ?></p>
                <p class="text-mid">Phone: <?= htmlspecialchars($user['phoneNumber']) ?></p>
                <p class="text-mid">Grade: <?= htmlspecialchars($user['grade']) ?></p>
                <p class="text-mid">Shanyraq: <?= htmlspecialchars($user['shanyraqName'] ?? 'Not Assigned') ?></p>

                <button id="edit-btn" class="btn btn-primary edit-btn" onclick="toggleEditMode()">Edit</button>
            </div>

            <!-- Edit Mode -->
            <form id="edit-mode" action="profile.php" method="POST" style="display: none;">
                <p><strong>Name:</strong>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></p>

                <p><strong>Surname:</strong>
                <input type="text" name="surname" value="<?= htmlspecialchars($user['surname']) ?>" required></p>

                <p><strong>Email:</strong>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></p>

                <p><strong>Phone:</strong>
                <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required></p>

                <p><strong>Grade:</strong>
                <input type="text" name="grade" value="<?= htmlspecialchars($user['grade']) ?>" required></p>

                <p><strong>Shanyraq:</strong>
                <select class="form-select shadow" id="shanyraq" name="shanyraqID" required>
                    <option value="" disabled>Choose Shanyraq</option>
                    <option value="1" <?= $user['shanyraqID'] == 1 ? 'selected' : '' ?>>Aqjaiyq</option>
                    <option value="2" <?= $user['shanyraqID'] == 2 ? 'selected' : '' ?>>Alatau</option>
                    <option value="3" <?= $user['shanyraqID'] == 3 ? 'selected' : '' ?>>Alash</option>
                    <option value="4" <?= $user['shanyraqID'] == 4 ? 'selected' : '' ?>>Arys</option>
                    <option value="5" <?= $user['shanyraqID'] == 5 ? 'selected' : '' ?>>Atameken</option>
                    <option value="6" <?= $user['shanyraqID'] == 6 ? 'selected' : '' ?>>Baiqonyr</option>
                    <option value="7" <?= $user['shanyraqID'] == 7 ? 'selected' : '' ?>>Jetisu</option>
                    <option value="8" <?= $user['shanyraqID'] == 8 ? 'selected' : '' ?>>Kaspi</option>
                    <option value="9" <?= $user['shanyraqID'] == 9 ? 'selected' : '' ?>>Oqjetpes</option>
                    <option value="10" <?= $user['shanyraqID'] == 10 ? 'selected' : '' ?>>Orda</option>
                    <option value="11" <?= $user['shanyraqID'] == 11 ? 'selected' : '' ?>>Samuryq</option>
                    <option value="12" <?= $user['shanyraqID'] == 12 ? 'selected' : '' ?>>Saryarqa</option>
                    <option value="13" <?= $user['shanyraqID'] == 13 ? 'selected' : '' ?>>Tulpar</option>
                    <option value="14" <?= $user['shanyraqID'] == 14 ? 'selected' : '' ?>>Han-taniri</option>
                    <option value="15" <?= $user['shanyraqID'] == 15 ? 'selected' : '' ?>>Shalqar</option>
                </select>

                <button type="submit" name="update_profile" class="btn btn-success">Update Data</button>
                <button type="button" class="btn btn-secondary cancel-btn" onclick="cancelEdit()">Cancel</button>
            </form>

            <!-- Logout Button -->
            <form action="profile.php" method="POST">
                <button type="submit" name="logout" class="btn btn-danger logout-btn">Log Out</button>
            </form>
        </div>
    <?php else: ?>
        <h1 style="margin-top: 20%; font-size: 60px!important" class="text-center text-lighter">You are not logged in.</h1>
        <p  class="text-muted text-center">Press here to <a href="signIn.php">Sign In</a></p>
    <?php endif; ?>
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
<!-- <script src="frameworks/bootstrap/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</body>
</html>