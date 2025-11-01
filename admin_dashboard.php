<?php
include 'db_connect.php'; // Include the database connection

// Admin login credentials check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize user input
    $username = $conn->real_escape_string($_POST['admin_username']);
    $password = $conn->real_escape_string($_POST['admin_password']);

    // Use prepared statements to prevent SQL injection
    // Ensure the column names match your table structure
    $stmt = $conn->prepare("SELECT * FROM ADMIN WHERE admin_username = ? AND admin_password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    if ($adminResult->num_rows > 0) {
        
        // Login successful
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Admin Dashboard</title>
                  <link rel='stylesheet' href='styles.css'>
              </head>
              <body>
                  <h1>Welcome, Admin</h1>
                  
                  <!-- Voter Authentication Form -->
                  <form action='authenticate_voter.php' method='POST'>
                      <label for='aadhar_number'>Enter Voter Aadhar Number:</label>
                      <input type='text' id='aadhar_number' name='aadhar_number' required><br>
                      <input type='submit' value='Verify Voter'>
                  </form>

                  <!-- Display Votes Button -->
                  <form action='display_votes.php' method='POST' style='margin-top: 20px;'>
                      <input type='submit' value='Display Votes'>
                  </form>
              </body>
              </html>";
    } else {
        // Login failed
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Login Failed</title>
              </head>
              <body>
                  <p>Invalid credentials. <a href='login.html'>Try again</a>.</p>
              </body>
              </html>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
