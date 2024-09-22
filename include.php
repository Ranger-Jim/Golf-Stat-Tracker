<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <title>Golf Stats Guru</title>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-logo">Golf Stats Guru</div>
        <ul class="navbar-links">
            <li><a href="index.php">Home</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logStats.php">Log Stats</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="PHP/logout.php">Logout</a></li>
                <li><span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span></li>
            <?php else: ?>
                <li><a href="login_page.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>