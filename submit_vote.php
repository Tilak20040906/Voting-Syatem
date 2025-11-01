<?php
include 'db_connect.php';

// Get candidate_id and voter_id from POST
$candidate_id = $_POST['candidate_id'];
$voter_id = $_POST['voter_id'];

// Debug: Print candidate_id and voter_id
//echo "Candidate ID = " . htmlspecialchars($candidate_id) . "<br>";
//echo "Voter ID = " . htmlspecialchars($voter_id) . "<br>";

// Check if candidate_id is valid
if (empty($candidate_id)) {
    die("Error: No candidate selected for voting.");
}

// Check if voter_id is valid
if (empty($voter_id)) {
    die("Error: Invalid voter ID.");
}

// Fetch home_booth_id for the voter
$voter_check_sql = "SELECT has_voted, home_booth_id FROM VOTERS WHERE voter_id = ?";
$voter_check_stmt = $conn->prepare($voter_check_sql);
$voter_check_stmt->bind_param("i", $voter_id);
$voter_check_stmt->execute();
$voter_check_stmt->bind_result($has_voted, $home_booth_id);
$voter_check_stmt->fetch();
$voter_check_stmt->close();

// Debug: Print home_booth_id
//echo "Debug: home_booth_id = " . htmlspecialchars($home_booth_id) . "<br>";

// Check if the voter has already voted
if ($has_voted == 1) {
    die("Error: You have already voted. One voter can cast their vote only once.");
}

// Update the vote count for the selected candidate
$update_sql = "UPDATE CANDIDATES SET vote_count = vote_count + 1 WHERE candidate_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $candidate_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f9;
        }
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        main {
            flex: 1;
            padding: 20px;
            text-align: center;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
        }
        .message {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .message h2 {
            color: #4CAF50;
        }
        .error {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Voting Confirmation</h1>
    </header>
    <main>
        <div class="message">
            <?php
            if ($update_stmt->execute()) {
                // Mark the voter as having voted
                $update_voter_sql = "UPDATE VOTERS SET has_voted = 1 WHERE voter_id = ?";
                $update_voter_stmt = $conn->prepare($update_voter_sql);
                $update_voter_stmt->bind_param("i", $voter_id);
                $update_voter_stmt->execute();
                $update_voter_stmt->close();

                echo "<p>Vote successfully recorded.</p>";

                // Determine location based on home_booth_id
                if ($home_booth_id == 1) {
                    echo "<h2>Thank you for voting in Bangalore.</h2>";
                } elseif ($home_booth_id == 2) {
                    echo "<h2>Thank you for voting in Shimoga.</h2>";
                } else {
                    echo "<p class='error'>Error: Invalid home_booth_id. Unable to determine location.</p>";
                }
            } else {
                echo "<p class='error'>Error: Could not record the vote. Please try again.</p>";
            }

            $update_stmt->close();
            $conn->close();
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Voting System. All Rights Reserved.</p>
    </footer>
</body>
</html>
