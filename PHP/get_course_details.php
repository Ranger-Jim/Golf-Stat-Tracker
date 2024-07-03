<?php
include 'db_connection.php';

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    $sql = "SELECT * FROM Courses WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    echo json_encode($course);

    $stmt->close();
}
$conn->close();
?>
