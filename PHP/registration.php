<?php
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if ($username && $email && $password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");// '?' are placeholders for the values

        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        $stmt->bind_param("sss", $username, $email, $hashed_password); //binding associates placeholders with actual values to prevent
                                                                       //SQL injection, the 'sss' stands for three incoming strings

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!";
            // Redirect to login page or another page
            header("Location: ../profile.php");
            exit();
        } else {
            // Registration failed
            echo "Error: " . $stmt->error;
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
