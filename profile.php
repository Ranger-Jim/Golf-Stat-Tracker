<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

include 'PHP/db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch the user's rounds
$sql = "SELECT Rounds.*, Courses.course_name FROM Rounds INNER JOIN Courses ON Rounds.course_id = Courses.course_id WHERE Rounds.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rounds = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rounds[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/styles.css">
    <title>Profile</title>
</head>
<body>
    <?php include 'include.php'; ?>
    
    <div class="banner-container">
        <div class="banner-overlay"></div>
    </div>

    <div class="profile-container">
        <h2>Your Rounds</h2>
        <?php if (empty($rounds)): ?>
            <p>No rounds found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Course</th>
                        <th>Total Score</th>
                        <th>Total Fairways Hit</th>
                        <th>Total GIR</th>
                        <th>Total Putts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rounds as $round): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($round['date']); ?></td>
                            <td><?php echo htmlspecialchars($round['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($round['total_score']); ?></td>
                            <td><?php echo htmlspecialchars($round['total_fairways_hit']); ?></td>
                            <td><?php echo htmlspecialchars($round['total_gir']); ?></td>
                            <td><?php echo htmlspecialchars($round['total_putts']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
