<?php
if (isset($_SESSION['error'])) {
    echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo "<p class='text-success text-center'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="frameworks/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            .text-muted.text-danger {
                color: red !important;
            }
        </style>
</head>
<body>
    <div class="container mt-5">
            <h2 class="text-center">Forgot Password</h2>
            <form id="forgotPasswordForm" class="col-md-6 mx-auto form">
                <p for="email" class="fs-5">Enter your email</p>
                <div class="input-container mb-3 w-100">
                    <input type="email" id="email" name="email" placeholder="Enter your email" required autocomplete="off">
                    <div class="input-bg"></div>
                </div>

                <!-- Error Message Below Input -->
                <p id="error-message" class="text-muted text-danger" style="display: none;"></p>

                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
        </div>

        <script>
            $(document).ready(function () {
                $("#forgotPasswordForm").submit(function (event) {
                    event.preventDefault(); // Prevent form from reloading

                    let email = $("#email").val();

                    $.ajax({
                        url: "sendResetLink.php",
                        type: "POST",
                        data: { email: email },
                        success: function (response) {
                            if (response.startsWith("error:")) {
                                $("#error-message").text(response.replace("error:", "")).show(); // Show error
                            } else {
                                alert(response); // Show success message
                                $("#error-message").hide();
                            }
                        },
                        error: function () {
                            $("#error-message").text("❌ An unexpected error occurred.").show();
                        }
                    });
                });
            });
        </script>
</body>
</html>