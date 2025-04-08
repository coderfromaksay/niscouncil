<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['token']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        die("❌ Missing required fields!");
    }

    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords match
    if ($newPassword !== $confirmPassword) {
        die("❌ Passwords do not match!");
    }

    // Hash new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Get studentID from token
    $stmt = $conn->prepare("SELECT studentID FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("❌ Invalid or expired token!");
    }

    $stmt->bind_result($studentID);
    $stmt->fetch();
    $stmt->close();

    // Update password in Students table
    $stmt = $conn->prepare("UPDATE Students SET password = ? WHERE studentID = ?");
    $stmt->bind_param("si", $hashedPassword, $studentID);
    $stmt->execute();
    $stmt->close();

    // Delete used token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();

    echo "✅ Password updated successfully! <a href='signIn.php'>Login now</a>";
}
$conn->close();
?>