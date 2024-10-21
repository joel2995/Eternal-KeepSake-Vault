<?php
session_start();
include 'db_connect.php';

// Start Output Buffering
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Notification</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="container mt-5">
    <h2>Notifications</h2>
    <?php
    if (isset($_SESSION['user_email'])) {
        $user_email = $_SESSION['user_email'];
        echo "<div class='alert alert-info'>Notification: This is a test notification for $user_email.</div>";
    } else {
        echo "<div class='alert alert-warning'>No email found in session.</div>";
    }
    ?>
    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
</div>
</body>
</html>

<?php
// End Output Buffering and flush output
ob_end_flush();
?>
