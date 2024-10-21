<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT COUNT(*) as capsule_count FROM capsules WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Analytics</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="container mt-5">
    <h2>Your Analytics</h2>
    <p>Total Capsules Created: <?php echo $data['capsule_count']; ?></p>
    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
</div>
</body>
</html>
