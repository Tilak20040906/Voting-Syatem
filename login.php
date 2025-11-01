<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Login</h1>
    <form action="admin_dashboard.php" method="POST">
        <label for="admin_username">Username:</label>
        <input type="text" id="admin_username" name="admin_username" required><br>

        <label for="admin_password">Password:</label>
        <input type="password" id="admin_password" name="admin_password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
