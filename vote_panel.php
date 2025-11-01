<?php
include 'db_connect.php';

// Start HTML with header
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Voting Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #4caf50;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .container {
            margin: 50px auto;
            padding: 20px;
            max-width: 700px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4caf50;
        }
        form {
            margin-top: 20px;
        }
        input[type='radio'] {
            margin-right: 10px;
        }
        input[type='submit'] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type='submit']:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    Voting Panel
</header>
<div class='container'>
";

// Assume we have the voter's ID from POST
$voter_id = $_POST['voter_id']; // Ensure this is set and valid

// Debug: Print voter ID
echo "<p>Voter ID received is: " . htmlspecialchars($voter_id) . "</p>";

// Get home_booth_id for the voter
$sql = "SELECT home_booth_id FROM VOTERS WHERE voter_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $voter_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($home_booth_id);
$stmt->fetch();

if ($stmt->num_rows > 0) {
    // Debug: Print home_booth_id
    echo "<p>home_booth_id = " . htmlspecialchars($home_booth_id) . "</p>";

    // Determine the location based on home_booth_id
    if ($home_booth_id == 1) {
        $voter_location = "Bangalore";
        echo "<p>Opening Bangalore Candidates Panel</p>";
    } elseif ($home_booth_id == 2) {
        $voter_location = "Shimoga";
        echo "<p>Opening Shimoga Candidates Panel</p>";
    } else {
        die("<p class='error'>Error: Unknown home booth ID.</p>");
    }

    // Query to get candidates for the voter's registered location
    $candidate_sql = "SELECT candidate_id, candidate_name, party FROM CANDIDATES WHERE location = ?";
    $candidate_stmt = $conn->prepare($candidate_sql);
    $candidate_stmt->bind_param("s", $voter_location);
    $candidate_stmt->execute();
    $candidate_stmt->store_result();
    $candidate_stmt->bind_result($candidate_id, $candidate_name, $party_name);

    // Display the candidates
    if ($candidate_stmt->num_rows > 0) {
        echo "<h2>Candidates for location: " . htmlspecialchars($voter_location) . "</h2>";
        echo "<form action='submit_vote.php' method='POST'>";
        while ($candidate_stmt->fetch()) {
            echo "<input type='radio' name='candidate_id' value='" . htmlspecialchars($candidate_id) . "' required> " 
                 . htmlspecialchars($candidate_name) . " (" . htmlspecialchars($party_name) . ")<br>";
        }
        echo "<input type='hidden' name='voter_id' value='" . htmlspecialchars($voter_id) . "'>";
        echo "<input type='submit' value='Submit Vote'>";
        echo "</form>";
    } else {
        echo "<p>No candidates found for the registered location.</p>";
    }

    $candidate_stmt->close();
} else {
    echo "<p class='error'>Error: Could not determine the voter's registered location. Please check that the voter ID and booth data are correctly set.</p>";
}

$stmt->close();
$conn->close();

// Close HTML with footer
echo "
</div>
<footer>
    © 2024 Voting System. All rights reserved.
</footer>
</body>
</html>
";
?>
