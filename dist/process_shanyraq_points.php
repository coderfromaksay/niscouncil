<?php
// process_shanyraq_points.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['studentID'])) {
    die("Not logged in.");
}

if (!isset($_SESSION['adminAccess']) || $_SESSION['adminAccess'] != 1) {
    die("Нет прав доступа.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['shanyraqID'], $_POST['points'], $_POST['period'])) {
        die("Неверный запрос.");
    }

    $shanyraqID = (int)$_POST['shanyraqID'];
    $points = (int)$_POST['points'];
    $period = $_POST['period'];

    $conn = new mysqli("localhost", "root", "", "SCSite");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

    if ($period === 'first_half') {
        $stmt = $conn->prepare("UPDATE Shanyraqs
                                SET shanyraqPointsFirstHalf = shanyraqPointsFirstHalf + ?
                                WHERE shanyraqID = ?");
        $stmt->bind_param("ii", $points, $shanyraqID);
    } elseif ($period === 'second_half') {
        $stmt = $conn->prepare("UPDATE Shanyraqs
                                SET shanyraqPointsSecondHalf = shanyraqPointsSecondHalf + ?
                                WHERE shanyraqID = ?");
        $stmt->bind_param("ii", $points, $shanyraqID);
    } else {
        $conn->close();
        die("Неверно указан период.");
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Баллы шаныраку успешно обновлены!";
        header("Location: profile.php");
        exit();
    } else {
        echo "Ошибка при обновлении баллов: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("Неверный запрос (method not POST).");
}
?>