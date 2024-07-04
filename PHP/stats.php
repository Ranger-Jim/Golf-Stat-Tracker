<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course-id'];
    $user_id = $_SESSION['user_id'];
    $date_played = $_POST['date-played'];

    if (empty($course_id)) {
        // Insert new course
        $course_name = $_POST['new-course-name'];
        $tee_color = $_POST['new-course-tee-color'];
        $total_yardage = $_POST['yard-total'];
        $total_par = $_POST['par-total'];

        $sql = "INSERT INTO Courses (course_name, tee_color, hole1_yardage, hole2_yardage, hole3_yardage, hole4_yardage, hole5_yardage, hole6_yardage, hole7_yardage, hole8_yardage, hole9_yardage, hole10_yardage, hole11_yardage, hole12_yardage, hole13_yardage, hole14_yardage, hole15_yardage, hole16_yardage, hole17_yardage, hole18_yardage, total_yardage, hole1_par, hole2_par, hole3_par, hole4_par, hole5_par, hole6_par, hole7_par, hole8_par, hole9_par, front9_par, hole10_par, hole11_par, hole12_par, hole13_par, hole14_par, hole15_par, hole16_par, hole17_par, hole18_par, back9_par, total_par) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing the statement for new course: " . $conn->error);
        }

        $stmt->bind_param(
            "ssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii",
            $course_name,
            $tee_color,
            $_POST['yard1'],
            $_POST['yard2'],
            $_POST['yard3'],
            $_POST['yard4'],
            $_POST['yard5'],
            $_POST['yard6'],
            $_POST['yard7'],
            $_POST['yard8'],
            $_POST['yard9'],
            $_POST['yard10'],
            $_POST['yard11'],
            $_POST['yard12'],
            $_POST['yard13'],
            $_POST['yard14'],
            $_POST['yard15'],
            $_POST['yard16'],
            $_POST['yard17'],
            $_POST['yard18'],
            $total_yardage,
            $_POST['par1'],
            $_POST['par2'],
            $_POST['par3'],
            $_POST['par4'],
            $_POST['par5'],
            $_POST['par6'],
            $_POST['par7'],
            $_POST['par8'],
            $_POST['par9'],
            $_POST['par-out'],
            $_POST['par10'],
            $_POST['par11'],
            $_POST['par12'],
            $_POST['par13'],
            $_POST['par14'],
            $_POST['par15'],
            $_POST['par16'],
            $_POST['par17'],
            $_POST['par18'],
            $_POST['par-in'],
            $total_par
        );

        if ($stmt->execute()) {
            $course_id = $stmt->insert_id;
        } else {
            die("Error executing the statement for new course: " . $stmt->error);
        }

        $stmt->close();
    }

    // Calculate total stats
    $total_score = array_sum(array_map(function($i) { return $_POST["score$i"]; }, range(1, 18)));
    $total_fairways_hit = array_sum(array_map(function($i) { return $_POST["par$i"] == 3 ? 0 : (isset($_POST["fw$i"]) ? 1 : 0); }, range(1, 18)));
    $total_gir = array_sum(array_map(function($i) { return isset($_POST["gir$i"]) ? 1 : 0; }, range(1, 18)));
    $total_putts = array_sum(array_map(function($i) { return $_POST["putt$i"]; }, range(1, 18)));

    // Insert round data
    $sql = "INSERT INTO Rounds (user_id, course_id, date, total_score, total_fairways_hit, total_gir, total_putts) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing the statement for round data: " . $conn->error);
    }

    $stmt->bind_param("iisiiii", $user_id, $course_id, $date_played, $total_score, $total_fairways_hit, $total_gir, $total_putts);

    if ($stmt->execute()) {
        $round_id = $stmt->insert_id;
    } else {
        die("Error executing the statement for round data: " . $stmt->error);
    }

    $stmt->close();

    // Insert hole stats
    $sql = "INSERT INTO HoleStats (round_id, hole_number, score, fairways_hit, gir, putts) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing the statement for hole stats: " . $conn->error);
    }

    for ($i = 1; $i <= 18; $i++) {
        $score = $_POST["score$i"];
        $fairways_hit = $_POST["par$i"] == 3 ? NULL : (isset($_POST["fw$i"]) ? 1 : 0);
        $gir = isset($_POST["gir$i"]) ? 1 : 0;
        $putts = $_POST["putt$i"];
        $stmt->bind_param("iiiiii", $round_id, $i, $score, $fairways_hit, $gir, $putts);

        if (!$stmt->execute()) {
            die("Error executing the statement for hole $i: " . $stmt->error);
        }
    }

    $stmt->close();
    header("Location:../profile.php");
    exit();
}

$conn->close();
?>
