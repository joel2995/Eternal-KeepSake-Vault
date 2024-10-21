<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM capsules WHERE user_id = ? ORDER BY unlock_date ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('your-moving-background-image-url.jpg') no-repeat center center fixed;
            background-size: cover;
            animation: moveBackground 10s linear infinite;
            color: white;
        }

        @keyframes moveBackground {
            0% {
                background-position: 0% 0%;
            }
            100% {
                background-position: 100% 100%;
            }
        }

        .container {
            margin-top: 50px;
            padding: 30px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card-title {
            color: #ffc107; /* Gold color for titles */
        }
    </style>
    <title>Dashboard</title>
</head>
<body>
<div class="container">
    <h2>Your Capsules</h2>
    <?php while ($row = $result->fetch_assoc()): 
        $unlock_time = strtotime($row['unlock_date']);
        $current_time = time();
    ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text">Unlocks on: <?php echo date('Y-m-d H:i:s', $unlock_time); ?></p>
                
                <p class="card-text">
                    <?php if ($unlock_time <= $current_time): ?>
                        <?php if ($row['content_type'] == "text"): ?>
                            <?php echo htmlspecialchars($row['content']); ?>
                        <?php elseif ($row['content_type'] == "image" || $row['content_type'] == "video"): ?>
                            <a href='uploads/<?php echo htmlspecialchars($row['content']); ?>' target='_blank'>View File</a>
                        <?php endif; ?>
                    <?php else: ?>
                        This message is locked.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
