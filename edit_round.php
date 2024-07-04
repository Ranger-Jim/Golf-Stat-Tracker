<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

include 'PHP/db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch the round data
$round_id = $_GET['round_id'];

$sql = "SELECT Rounds.*, HoleStats.hole_number, HoleStats.score AS hole_score, HoleStats.fairways_hit AS hole_fairways_hit, 
        HoleStats.gir AS hole_gir, HoleStats.putts AS hole_putts, 
        Courses.hole1_yardage, Courses.hole2_yardage, Courses.hole3_yardage, Courses.hole4_yardage, Courses.hole5_yardage, 
        Courses.hole6_yardage, Courses.hole7_yardage, Courses.hole8_yardage, Courses.hole9_yardage, 
        Courses.hole10_yardage, Courses.hole11_yardage, Courses.hole12_yardage, Courses.hole13_yardage, 
        Courses.hole14_yardage, Courses.hole15_yardage, Courses.hole16_yardage, Courses.hole17_yardage, 
        Courses.hole18_yardage, 
        Courses.hole1_par, Courses.hole2_par, Courses.hole3_par, Courses.hole4_par, Courses.hole5_par, 
        Courses.hole6_par, Courses.hole7_par, Courses.hole8_par, Courses.hole9_par, 
        Courses.hole10_par, Courses.hole11_par, Courses.hole12_par, Courses.hole13_par, 
        Courses.hole14_par, Courses.hole15_par, Courses.hole16_par, Courses.hole17_par, 
        Courses.hole18_par 
        FROM Rounds 
        INNER JOIN HoleStats ON Rounds.round_id = HoleStats.round_id 
        INNER JOIN Courses ON Rounds.course_id = Courses.course_id 
        WHERE Rounds.round_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param('i', $round_id);
$stmt->execute();
$result = $stmt->get_result();

$round_data = [];
while ($row = $result->fetch_assoc()) {
    $round_data[] = $row;
}

// Fetch the list of courses from the database
$courses = [];
$sql = "SELECT course_id, course_name, tee_color FROM Courses ORDER BY course_name ASC";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/styles.css">
    <title>Edit Round</title>
</head>
<body>
    <?php include 'include.php'; ?>

    <div class="banner-container">
        <div class="banner-overlay"></div>
    </div>

    <div class="log-stats-form">
        <form id="editRoundForm" action="PHP/update_round.php" method="post">
            <input type="hidden" name="round_id" id="roundId" value="<?php echo htmlspecialchars($round_id); ?>">

            <label for="course-select">Select Existing Course (optional):</label>
            <select id="course-select" name="course-id">
                <option value="">New Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['course_id']); ?>" <?php echo ($round_data[0]['course_id'] == $course['course_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['course_name'] . " (" . $course['tee_color'] . ")"); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <div id="new-course-section" style="<?php echo empty($round_data[0]['course_id']) ? 'display:block;' : 'display:none;'; ?>">
                <h3>New Course Details</h3>
                <label for="new-course-name">Course Name:</label>
                <input type="text" id="new-course-name" name="new-course-name" value="<?php echo htmlspecialchars($round_data[0]['course_name'] ?? ''); ?>"><br>
                <label for="new-course-tee-color">Tee Color:</label>
                <input type="text" id="new-course-tee-color" name="new-course-tee-color" value="<?php echo htmlspecialchars($round_data[0]['tee_color'] ?? ''); ?>"><br>
            </div>

            <label for="date-played">Date Played:</label>
            <input type="date" id="date-played" name="date-played" value="<?php echo htmlspecialchars($round_data[0]['date']); ?>" required><br>

            <table>
                <tr>
                    <th>Hole</th>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <th><?php echo $i; ?></th>
                    <?php endfor; ?>
                    <th>Out</th>
                    <th>In</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>Par</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="par<?php echo $i; ?>" name="par<?php echo $i; ?>" value="<?php echo htmlspecialchars($round_data[0]['hole' . $i . '_par']); ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="par-out" name="par-out" readonly></td>
                    <td><input type="number" id="par-in" name="par-in" readonly></td>
                    <td><input type="number" id="par-total" name="par-total" readonly></td>
                </tr>
                <tr>
                    <td>Yardage</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="yard<?php echo $i; ?>" name="yard<?php echo $i; ?>" value="<?php echo htmlspecialchars($round_data[0]['hole' . $i . '_yardage']); ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="yard-out" name="yard-out" readonly></td>
                    <td><input type="number" id="yard-in" name="yard-in" readonly></td>
                    <td><input type="number" id="yard-total" name="yard-total" readonly></td>
                </tr>
                <tr>
                    <td>Score</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="score<?php echo $i; ?>" name="score<?php echo $i; ?>" value="<?php echo htmlspecialchars($round_data[$i-1]['hole_score']); ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="score-out" name="score-out" readonly></td>
                    <td><input type="number" id="score-in" name="score-in" readonly></td>
                    <td><input type="number" id="score-total" name="score-total" readonly></td>
                </tr>
                <tr>
                    <td>Fairways Hit</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="checkbox" id="fw<?php echo $i; ?>" name="fw<?php echo $i; ?>" <?php echo $round_data[$i-1]['hole_fairways_hit'] ? 'checked' : ''; ?> onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="fw-out" name="fw-out" readonly></td>
                    <td><input type="number" id="fw-in" name="fw-in" readonly></td>
                    <td><input type="number" id="fw-total" name="fw-total" readonly></td>
                </tr>
                <tr>
                    <td>GIR</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="checkbox" id="gir<?php echo $i; ?>" name="gir<?php echo $i; ?>" <?php echo $round_data[$i-1]['hole_gir'] ? 'checked' : ''; ?> onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="gir-out" name="gir-out" readonly></td>
                    <td><input type="number" id="gir-in" name="gir-in" readonly></td>
                    <td><input type="number" id="gir-total" name="gir-total" readonly></td>
                </tr>
                <tr>
                    <td>Putts</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="putt<?php echo $i; ?>" name="putt<?php echo $i; ?>" value="<?php echo htmlspecialchars($round_data[$i-1]['hole_putts']); ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="putt-out" name="putt-out" readonly></td>
                    <td><input type="number" id="putt-in" name="putt-in" readonly></td>
                    <td><input type="number" id="putt-total" name="putt-total" readonly></td>
                </tr>
            </table>

            <input type="hidden" id="par3-count" name="par3-count">

            <button type="submit">Update</button>
        </form>
    </div>

    <script>
        document.getElementById('course-select').addEventListener('change', function() {
            var courseId = this.value;
            if (courseId !== "") {
                // Make an AJAX request to fetch course details
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'PHP/get_course_details.php?course_id=' + courseId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var courseDetails = JSON.parse(xhr.responseText);
                        // Populate the form fields with the course details
                        document.getElementById('new-course-name').value = courseDetails.course_name;
                        document.getElementById('new-course-tee-color').value = courseDetails.tee_color;

                        for (var i = 1; i <= 18; i++) {
                            document.getElementById('par' + i).value = courseDetails['hole' + i + '_par'];
                            document.getElementById('yard' + i).value = courseDetails['hole' + i + '_yardage'];
                            
                            // Check if the hole is a par 3 and disable the fairway hit checkbox if it is
                            if (courseDetails['hole' + i + '_par'] == 3) {
                                document.getElementById('fw' + i).disabled = true;
                                document.getElementById('fw' + i).checked = false; // Ensure it's unchecked
                            } else {
                                document.getElementById('fw' + i).disabled = false;
                            }
                        }

                        calculateTotals();

                        // Hide the new course section
                        document.getElementById('new-course-section').style.display = 'none';
                    }
                };
                xhr.send();
            } else {
                // Show the new course section if no existing course is selected
                document.getElementById('new-course-section').style.display = 'block';
            }
        });

        function calculateTotals() {
            let parOut = 0;
            let parIn = 0;
            let yardOut = 0;
            let yardIn = 0;
            let scoreOut = 0;
            let scoreIn = 0;
            let fairwaysOut = 0;
            let fairwaysIn = 0;
            let girOut = 0;
            let girIn = 0;
            let puttsOut = 0;
            let puttsIn = 0;
            let par3Count = 0;

            for (let i = 1; i <= 9; i++) {
                let parValue = parseInt(document.getElementById('par' + i).value) || 0;
                parOut += parValue;
                yardOut += parseInt(document.getElementById('yard' + i).value) || 0;
                scoreOut += parseInt(document.getElementById('score' + i).value) || 0;
                fairwaysOut += document.getElementById('fw' + i).checked ? 1 : 0;
                girOut += document.getElementById('gir' + i).checked ? 1 : 0;
                puttsOut += parseInt(document.getElementById('putt' + i).value) || 0;

                if (parValue == 3) {
                    par3Count++;
                }
            }

            for (let i = 10; i <= 18; i++) {
                let parValue = parseInt(document.getElementById('par' + i).value) || 0;
                parIn += parValue;
                yardIn += parseInt(document.getElementById('yard' + i).value) || 0;
                scoreIn += parseInt(document.getElementById('score' + i).value) || 0;
                fairwaysIn += document.getElementById('fw' + i).checked ? 1 : 0;
                girIn += document.getElementById('gir' + i).checked ? 1 : 0;
                puttsIn += parseInt(document.getElementById('putt' + i).value) || 0;

                if (parValue == 3) {
                    par3Count++;
                }
            }

            document.getElementById('par-out').value = parOut;
            document.getElementById('par-in').value = parIn;
            document.getElementById('par-total').value = parOut + parIn;

            document.getElementById('yard-out').value = yardOut;
            document.getElementById('yard-in').value = yardIn;
            document.getElementById('yard-total').value = yardOut + yardIn;

            document.getElementById('score-out').value = scoreOut;
            document.getElementById('score-in').value = scoreIn;
            document.getElementById('score-total').value = scoreOut + scoreIn;

            document.getElementById('fw-out').value = fairwaysOut;
            document.getElementById('fw-in').value = fairwaysIn;
            document.getElementById('fw-total').value = fairwaysOut + fairwaysIn;

            document.getElementById('gir-out').value = girOut;
            document.getElementById('gir-in').value = girIn;
            document.getElementById('gir-total').value = girOut + girIn;

            document.getElementById('putt-out').value = puttsOut;
            document.getElementById('putt-in').value = puttsIn;
            document.getElementById('putt-total').value = puttsOut + puttsIn;

            document.getElementById('par3-count').value = par3Count;
        }

        // Attach the calculateTotals function to the form fields for dynamic updates
        document.querySelectorAll('input[type="number"], input[type="checkbox"]').forEach(function(input) {
            input.addEventListener('change', calculateTotals);
        });

        // Populate form with round data
        function populateForm() {
            <?php foreach ($round_data as $data): ?>
                document.getElementById('score<?php echo $data['hole_number']; ?>').value = "<?php echo $data['hole_score']; ?>";
                document.getElementById('fw<?php echo $data['hole_number']; ?>').checked = <?php echo $data['hole_fairways_hit'] ? 'true' : 'false'; ?>;
                document.getElementById('gir<?php echo $data['hole_number']; ?>').checked = <?php echo $data['hole_gir'] ? 'true' : 'false'; ?>;
                document.getElementById('putt<?php echo $data['hole_number']; ?>').value = "<?php echo $data['hole_putts']; ?>";
                document.getElementById('par<?php echo $data['hole_number']; ?>').value = "<?php echo $data['hole_par'] ?? ''; ?>";
                document.getElementById('yard<?php echo $data['hole_number']; ?>').value = "<?php echo $data['hole_yardage'] ?? ''; ?>";
            <?php endforeach; ?>
            calculateTotals();
        }

        document.addEventListener('DOMContentLoaded', populateForm);
    </script>
</body>
</html>
