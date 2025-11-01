<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aadhar_number = $_POST['aadhar_number'];

    // Query to check if voter exists and fetch their details
    $voterQuery = "SELECT voter_id, voter_name, home_booth_id, has_voted FROM VOTERS WHERE aadhar_number = ?";
    $voterStmt = $conn->prepare($voterQuery);
    $voterStmt->bind_param("s", $aadhar_number);
    $voterStmt->execute();
    $voterResult = $voterStmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 15px 0;
        }
        main {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #4CAF50;
        }
        .error {
            color: #e74c3c;
            font-size: 18px;
        }
        p {
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Voter Verification</h1>
    </header>
    <main>
        <?php
        if ($voterResult->num_rows > 0) {
            $voter = $voterResult->fetch_assoc();
            $voter_id = $voter['voter_id'];
            $voter_name = $voter['voter_name'];
            $home_booth_id = $voter['home_booth_id'];
            $has_voted = $voter['has_voted'];

            // Check if the voter has already voted
            if ($has_voted) {
                echo "<h1>Voter Verified</h1>";
                echo "<p>Voter Name: " . htmlspecialchars($voter_name) . "</p>";
                echo "<p>Status: You have already cast your vote. Thank you for participating!</p>";
                echo "<p><a href='admin_dashboard.php'>Go back</a></p>";
            } else {
                // Voter is eligible to vote
                echo "<h1>Voter Verified</h1>";
                echo "<p>Voter Name: " . htmlspecialchars($voter_name) . "</p>";
                echo "<p>Registered Location: " . htmlspecialchars($home_booth_id) . "</p>";

                echo "<form action='vote_panel.php' method='POST'>
                        <input type='hidden' name='voter_id' value='" . htmlspecialchars($voter_id) . "'>
                        <input type='hidden' name='location' value='" . htmlspecialchars($home_booth_id) . "'>
                        <input type='submit' value='Open Voting Panel'>
                      </form>";
            }
        } else {
            // Voter not found
            echo "<p class='error'>Voter not found.</p>";
            echo "<p><a href='admin_dashboard.php'>Go back</a></p>";
        }

        $voterStmt->close();
        ?>
    </main>
</body>
</html>
