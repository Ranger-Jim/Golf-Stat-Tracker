<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="Styles/styles.css">
        <title>View Stats</title>
    </head>
    <body>
        <nav class="navbar">
            <div class="navbar-logo">Golf Stats Tracker</div>
            <ul class="navbar-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="logStats.php">Log Stats</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    
        <div class="banner-container">
            <div class="banner-overlay">
            </div>
        </div>

        <div class="form-container">
            <h2>Login</h2>
            <form action="PHP/login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                <button type="submit">Register</button>
            </form>
        </div>
    </body>
</html>