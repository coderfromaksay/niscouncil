<?php
// search_student.php

header('Content-Type: application/json');


$conn = new mysqli("localhost", "root", "", "SCSite");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

$conn->set_charset("utf8");

$q = $_GET['q'] ?? '';
$q = trim($q);

if ($q === '') {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT studentID, name, surname, grade
    FROM Students
    WHERE name LIKE CONCAT('%', ?, '%')
       OR surname LIKE CONCAT('%', ?, '%')
       OR grade LIKE CONCAT('%', ?, '%')
    LIMIT 30
");
$stmt->bind_param("sss", $q, $q, $q);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode($students);

$stmt->close();
$conn->close();
?>