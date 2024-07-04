<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

include 'PHP/db_connection.php';

$user_id = $_SESSION['user_id'];

// Initialize variables
$average_score = 0;
$fairways_hit_percentage = 0;
$gir_percentage = 0;
$average_putts_per_hole = 0;
$total_par3_holes = 0;

// Fetch the user's rounds
$sql = "SELECT Rounds.*, Courses.course_name, Courses.par3_count FROM Rounds INNER JOIN Courses ON Rounds.course_id = Courses.course_id WHERE Rounds.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rounds = [];
$total_score = 0;
$total_fairways_hit = 0;
$total_gir = 0;
$total_putts = 0;
$total_holes_played = 0;
$round_count = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rounds[] = $row;
        $total_score += $row['total_score'];
        $total_fairways_hit += $row['total_fairways_hit'];
        $total_gir += $row['total_gir'];
        $total_putts += $row['total_putts'];
        $total_holes_played += 18; // Assuming each round has 18 holes
        $round_count++;
        $total_par3_holes += $row['par3_count']; // Add the number of par 3 holes for each round
    }
}

// Calculate averages
if ($round_count > 0) {
    $average_score = round($total_score / $round_count, 2);
    $fairways_hit_percentage = round(($total_fairways_hit / ($total_holes_played - $total_par3_holes)) * 100, 2);
    $gir_percentage = round(($total_gir / $total_holes_played) * 100, 2);
    $average_putts_per_hole = round($total_putts / $total_holes_played, 2);
}

// Fetch the last 10 rounds played
$recent_rounds = array_slice($rounds, -10);

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
        <!-- Statistics Overview -->
        <div class="stats-overview">
            <h2>Statistics Overview</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>Average Score</h3>
                    <p><?php echo htmlspecialchars($average_score); ?></p>
                </div>
                <div class="stat-item">
                    <h3>Fairways Hit (%)</h3>
                    <p><?php echo htmlspecialchars($fairways_hit_percentage); ?></p>
                </div>
                <div class="stat-item">
                    <h3>GIR (%)</h3>
                    <p><?php echo htmlspecialchars($gir_percentage); ?></p>
                </div>
                <div class="stat-item">
                    <h3>Average Putts Per Hole</h3>
                    <p><?php echo htmlspecialchars($average_putts_per_hole); ?></p>
                </div>
                <!-- Add more stat items as needed -->
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="recent-activities">
            <h2>Recent Activities</h2>
            <?php if (empty($recent_rounds)): ?>
                <p>No rounds found.</p>
            <?php else: ?>
                <table class="profile-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Course</th>
                            <th>Total Score</th>
                            <th>Total Fairways Hit</th>
                            <th>Total GIR</th>
                            <th>Total Putts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_rounds as $round): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($round['date']); ?></td>
                                <td><?php echo htmlspecialchars($round['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($round['total_score']); ?></td>
                                <td><?php echo htmlspecialchars($round['total_fairways_hit']); ?></td>
                                <td><?php echo htmlspecialchars($round['total_gir']); ?></td>
                                <td><?php echo htmlspecialchars($round['total_putts']); ?></td>
                                <td><a href="edit_round.php?round_id=<?php echo $round['round_id']; ?>">Edit</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
