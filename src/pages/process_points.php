<?php
// process_points.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Если сессия не содержит studentID, значит пользователь не вошел в систему.
// Для отладки можно временно убрать эту проверку, но лучше убедиться, что при авторизации устанавливается:
// $_SESSION['adminAccess'] = $user['adminAccess'];
if (!isset($_SESSION['studentID'])) {
    die("Not logged in.");
}

// Если переменная adminAccess не установлена, можно установить её для тестирования:
// $_SESSION['adminAccess'] = 1;

if (!isset($_SESSION['adminAccess']) || $_SESSION['adminAccess'] != 1) {
    // Если пользователь не админ, перенаправляем его не на регистрацию, а выводим сообщение
    die("Нет прав доступа.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['studentID'], $_POST['points'], $_POST['period'])) {
        die("Неверный запрос.");
    }

    $studentID = (int)$_POST['studentID'];
    $points = (int)$_POST['points'];
    $period = $_POST['period'];

    $conn = new mysqli("localhost", "root", "", "SCSite");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

    // Получаем shanyraqID ученика
    $stmt = $conn->prepare("SELECT shanyraqID FROM Students WHERE studentID = ?");
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $stmt->close();
        $conn->close();
        die("Ученик не найден.");
    }
    $row = $result->fetch_assoc();
    $shanyraqID = (int)$row['shanyraqID'];
    $stmt->close();

    // Выполняем INSERT ... ON DUPLICATE KEY UPDATE в зависимости от выбранного периода
    if ($period === 'first_half') {
        $stmt = $conn->prepare("
            INSERT INTO StudentsRating (shanyraqID, studentID, studentPointsFirstHalf, studentPointsSecondHalf)
            VALUES (?, ?, ?, 0)
            ON DUPLICATE KEY UPDATE studentPointsFirstHalf = studentPointsFirstHalf + ?
        ");
        $stmt->bind_param("iiii", $shanyraqID, $studentID, $points, $points);
    } elseif ($period === 'second_half') {
        $stmt = $conn->prepare("
            INSERT INTO StudentsRating (shanyraqID, studentID, studentPointsFirstHalf, studentPointsSecondHalf)
            VALUES (?, ?, 0, ?)
            ON DUPLICATE KEY UPDATE studentPointsSecondHalf = studentPointsSecondHalf + ?
        ");
        $stmt->bind_param("iiii", $shanyraqID, $studentID, $points, $points);
    } else {
        $conn->close();
        die("Неверно указан период.");
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Баллы ученику успешно обновлены!";
        // Остаёмся на странице профиля
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