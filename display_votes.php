<?php
include 'db_connect.php';

// Fetch votes for Bangalore candidates
$bangalore_sql = "SELECT candidate_name, party, vote_count FROM CANDIDATES WHERE location = 'Bangalore'";
$bangalore_result = $conn->query($bangalore_sql);

// Fetch votes for Shimoga candidates
$shimoga_sql = "SELECT candidate_name, party, vote_count FROM CANDIDATES WHERE location = 'Shimoga'";
$shimoga_result = $conn->query($shimoga_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Vote Counts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #4CAF50;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tr:last-child td {
            border-bottom: none;
        }
        h2 {
            margin-top: 40px;
        }
        .no-candidates {
            text-align: center;
            font-size: 18px;
            color: #e74c3c;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Candidate Vote Counts</h1>

    <?php
    // Display Bangalore candidates
    if ($bangalore_result->num_rows > 0) {
        echo "<h2>Bangalore Candidates</h2>";
        echo "<table>
                <tr>
                    <th>Candidate Name</th>
                    <th>Party</th>
                    <th>Vote Count</th>
                </tr>";
        while ($row = $bangalore_result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['candidate_name']) . "</td>
                    <td>" . htmlspecialchars($row['party']) . "</td>
                    <td>" . htmlspecialchars($row['vote_count']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-candidates'>No candidates found for Bangalore.</p>";
    }

    // Display Shimoga candidates
    if ($shimoga_result->num_rows > 0) {
        echo "<h2>Shimoga Candidates</h2>";
        echo "<table>
                <tr>
                    <th>Candidate Name</th>
                    <th>Party</th>
                    <th>Vote Count</th>
                </tr>";
        while ($row = $shimoga_result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['candidate_name']) . "</td>
                    <td>" . htmlspecialchars($row['party']) . "</td>
                    <td>" . htmlspecialchars($row['vote_count']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-candidates'>No candidates found for Shimoga.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
