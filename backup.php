<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM capsules WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$capsules = [];

while ($row = $result->fetch_assoc()) {
    $capsules[] = $row;
}

$backup_file = 'backups/capsules_backup_' . time() . '.json';
file_put_contents($backup_file, json_encode($capsules));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Backup</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="container mt-5">
    <h2>Backup</h2>
    <p>Your capsules have been backed up successfully!</p>
    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
</div>
</body>
</html>
