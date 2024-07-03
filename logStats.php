<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

include 'PHP/db_connection.php';

$user_id = $_SESSION['user_id'];

// Fetch the list of courses from the database
$courses = [];
$sql = "SELECT course_id, course_name, tee_color FROM Courses ORDER BY course_name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/styles.css">
    <title>Log Stats</title>
</head>
<body>
    <?php include 'include.php'; ?>
    
    <div class="banner-container">
        <div class="banner-overlay"></div>
    </div>

    <div class="log-stats-form">
        <form action="PHP/stats.php" method="post">
            <label for="course-select">Select Existing Course (optional):</label>
            <select id="course-select" name="course-id">
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <?php echo htmlspecialchars($course['course_name'] . " (" . $course['tee_color'] . ")"); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <div id="new-course-section">
                <h3>New Course Details</h3>
                <label for="new-course-name">Course Name:</label>
                <input type="text" id="new-course-name" name="new-course-name"><br>
                <label for="new-course-tee-color">Tee Color:</label>
                <input type="text" id="new-course-tee-color" name="new-course-tee-color"><br>
            </div>

            <label for="date-played">Date Played:</label>
            <input type="date" id="date-played" name="date-played" required><br>

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
                        <td><input type="number" id="par<?php echo $i; ?>" name="par<?php echo $i; ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="par-out" name="par-out" readonly></td>
                    <td><input type="number" id="par-in" name="par-in" readonly></td>
                    <td><input type="number" id="par-total" name="par-total" readonly></td>
                </tr>
                <tr>
                    <td>Yardage</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="yard<?php echo $i; ?>" name="yard<?php echo $i; ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="yard-out" name="yard-out" readonly></td>
                    <td><input type="number" id="yard-in" name="yard-in" readonly></td>
                    <td><input type="number" id="yard-total" name="yard-total" readonly></td>
                </tr>
                <tr>
                    <td>Score</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="score<?php echo $i; ?>" name="score<?php echo $i; ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="score-out" name="score-out" readonly></td>
                    <td><input type="number" id="score-in" name="score-in" readonly></td>
                    <td><input type="number" id="score-total" name="score-total" readonly></td>
                </tr>
                <tr>
                    <td>Fairways Hit</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="checkbox" id="fw<?php echo $i; ?>" name="fw<?php echo $i; ?>" onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="fw-out" name="fw-out" readonly></td>
                    <td><input type="number" id="fw-in" name="fw-in" readonly></td>
                    <td><input type="number" id="fw-total" name="fw-total" readonly></td>
                </tr>
                <tr>
                    <td>GIR</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="checkbox" id="gir<?php echo $i; ?>" name="gir<?php echo $i; ?>" onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="gir-out" name="gir-out" readonly></td>
                    <td><input type="number" id="gir-in" name="gir-in" readonly></td>
                    <td><input type="number" id="gir-total" name="gir-total" readonly></td>
                </tr>
                <tr>
                    <td>Putts</td>
                    <?php for ($i = 1; $i <= 18; $i++): ?>
                        <td><input type="number" id="putt<?php echo $i; ?>" name="putt<?php echo $i; ?>" required onchange="calculateTotals()"></td>
                    <?php endfor; ?>
                    <td><input type="number" id="putt-out" name="putt-out" readonly></td>
                    <td><input type="number" id="putt-in" name="putt-in" readonly></td>
                    <td><input type="number" id="putt-total" name="putt-total" readonly></td>
                </tr>
            </table>
            <button type="submit">Submit</button>
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

        for (let i = 1; i <= 9; i++) {
            parOut += parseInt(document.getElementById('par' + i).value) || 0;
            yardOut += parseInt(document.getElementById('yard' + i).value) || 0;
            scoreOut += parseInt(document.getElementById('score' + i).value) || 0;
            fairwaysOut += document.getElementById('fw' + i).checked ? 1 : 0;
            girOut += document.getElementById('gir' + i).checked ? 1 : 0;
            puttsOut += parseInt(document.getElementById('putt' + i).value) || 0;
        }

        for (let i = 10; i <= 18; i++) {
            parIn += parseInt(document.getElementById('par' + i).value) || 0;
            yardIn += parseInt(document.getElementById('yard' + i).value) || 0;
            scoreIn += parseInt(document.getElementById('score' + i).value) || 0;
            fairwaysIn += document.getElementById('fw' + i).checked ? 1 : 0;
            girIn += document.getElementById('gir' + i).checked ? 1 : 0;
            puttsIn += parseInt(document.getElementById('putt' + i).value) || 0;
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
    }

    // Attach the calculateTotals function to the form fields for dynamic updates
    document.querySelectorAll('input[type="number"], input[type="checkbox"]').forEach(function(input) {
        input.addEventListener('change', calculateTotals);
    });
    </script>
</body>
</html>
