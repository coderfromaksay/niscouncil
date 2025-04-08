<?php
session_start();
require 'config.php'; // Include database & time zone

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['email'])) {
        echo "error: No email provided!";
        exit();
    }

    $email = trim($_POST['email']);

    if (empty($email)) {
        echo "error: Please enter your email!";
        exit();
    }


    // Check if email exists
    $stmt = $conn->prepare("SELECT studentID FROM Students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
                echo "error: This email is not registered!";
                exit();
            }

    $stmt->bind_result($studentID);
    $stmt->fetch();
    $stmt->close();

    // Generate reset token & expiration time (24 hours)
    $token = bin2hex(random_bytes(50)); // Secure token
    $expires_at = date("Y-m-d H:i:s", strtotime("+24 hours")); // 24-hour expiration

    // Save token in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (studentID, token, expires_at)
                            VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=?, expires_at=?");
    if (!$stmt) {
            echo "error: Database error: " . $conn->error;
            exit();
        }

    $stmt->bind_param("issss", $studentID, $token, $expires_at, $token, $expires_at);
        $stmt->execute();
        $stmt->close();

    // Create reset link
    $resetLink = "http://localhost/SCSite/dist/resetPassword.php?token=" . $token;

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'miras.akylbek@gmail.com'; // Change to your Gmail
        $mail->Password = 'khia gacu lwzn vqxj';   // Use your 16-character App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('miras.akylbek@gmail.com', 'NIS Oral Council');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "<p>Click the link below to reset your password:</p>
                       <p><a href='$resetLink'>$resetLink</a></p>
                       <p>This link will expire in 24 hours.</p>";

        $mail->send();
        echo "✅ Password reset link has been sent to your email!";
    } catch (Exception $e) {
        die("❌ Mailer Error: " . $mail->ErrorInfo);
    }
}
?>