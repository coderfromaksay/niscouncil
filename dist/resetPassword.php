<?php
session_start();
require 'config.php';

if (!isset($_GET['token'])) {
    $_SESSION['error'] = "Invalid or expired token!";
    header("Location: forgotPassword.php");
    exit();
}

$token = $_GET['token'];

// Check if the token is valid & not expired
$stmt = $conn->prepare("SELECT studentID FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $_SESSION['error'] = "Invalid or expired token!";
    header("Location: forgotPassword.php");
    exit();
}

$stmt->bind_result($studentID);
$stmt->fetch();
$stmt->close();
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
  <title>Reset Password</title>
  <!-- <script src="https://kit.fontawesome.com/d9162f00fa.js" crossorigin="anonymous"></script> -->
</head>
<body class="">

<main class="container">
  <div class="sign-up">
    <form class="form my-5" action="updatePassword.php" method="POST" onsubmit="return validateForm()">
      <h2 class="text-center">Reset Your Password</h2>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
      <?php endif; ?>

      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

      <div class="mb-4">
          <div class="my-5 cl-1" style="margin: auto;">
            <div class="input-container password" style="margin: auto; position: relative;">
              <input type="password" id="password-animated-input" name="password" placeholder="" required autocomplete="off" onkeyup="validatePassword()">
              <label for="password-animated-input">New Password</label>
              <div class="input-bg"></div>
              <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-animated-input', 'eye-icon-1')"
                      style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                <i id="eye-icon-1" class="fa-solid fa-eye-slash"></i>
              </button>
            </div>
            <p id="password-warning" class="px-3" style="color: red; font-size: 14px; display: none;">
                Password must be at least 8 characters long and contain A-Z, a-z, 0-9, and special character (!@#$%^&*).
            </p>
          </div>

          <div class="my-5 cl-1" style="margin: auto;">
            <div class="input-container password" style="margin: auto; position: relative;">
              <input type="password" id="verify-password-animated-input" name="confirm_password" placeholder="" required autocomplete="off" onkeyup="checkPasswordMatch()">
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

      <div class="text-center">
        <button type="submit" name="submit" class="btn btn-primary sign-up-btn mb-2">Update Password</button>
      </div>
    </form>
  </div>
</main>







<!-- ////////////////// -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin=""></script>
<script src="frameworks/bootstrap.bundle.min.js"></script>
<script src="js/pagesJs/password.js"></script>
<!-- <script src="frameworks/bootstrap/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</body>
</html>