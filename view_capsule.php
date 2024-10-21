<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$capsule_id = $_GET['id']; // Assuming you pass the capsule ID via the URL
$stmt = $conn->prepare("SELECT * FROM capsules WHERE id = ?");
$stmt->bind_param("i", $capsule_id);
$stmt->execute();
$result = $stmt->get_result();
$capsule = $result->fetch_assoc();

if (!$capsule) {
    echo "Capsule not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Capsule</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="container mt-5">
    <h2><?php echo htmlspecialchars($capsule['title']); ?></h2>
    <p><strong>Content Type:</strong> <?php echo htmlspecialchars($capsule['content_type']); ?></p>
    <p><strong>Content:</strong></p>
    <?php
    if ($capsule['content_type'] == 'text') {
        echo "<p>" . nl2br(htmlspecialchars($capsule['content'])) . "</p>";
    } else {
        // Display images or videos
        if ($capsule['content_type'] == 'image') {
            echo "<img src='uploads/" . htmlspecialchars($capsule['content']) . "' alt='Image' style='max-width:100%; height:auto;'/>";
        } elseif ($capsule['content_type'] == 'video') {
            echo "<video controls style='max-width:100%;'><source src='uploads/" . htmlspecialchars($capsule['content']) . "' type='video/mp4'>Your browser does not support the video tag.</video>";
        }
    }
    ?>
    <p><strong>Unlock Date:</strong> <?php echo htmlspecialchars($capsule['unlock_date']); ?></p>
    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
</div>
</body>
</html>
