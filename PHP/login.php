<?php
session_start(); //starting a session
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if ($username && $password) {
        // Prepare and bind
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if username exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_username, $db_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $db_password)) {
                // Password is correct, start a session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $db_username;
                echo "Login successful!";
                // Redirect to profile page or another page
                header("Location: ../profile.php");
                exit();
            } else {
                echo "Invalid password.";
                // Redirect back to login page
                header("Location: ../login_page.php");
            }
        } else {
            echo "No user found with that username.";
            header("Location: ../login_page.php");
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Please provide valid inputs.";
    }
}

// Close the connection
$conn->close();
?>
