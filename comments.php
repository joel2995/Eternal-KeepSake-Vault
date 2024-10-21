<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['capsule_id']) || !isset($_SESSION['user_id'])) {
        die("Invalid request.");
    }
    
    $capsule_id = $_POST['capsule_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    // Check if the capsule_id exists
    $check_stmt = $conn->prepare("SELECT * FROM capsules WHERE id = ?");
    $check_stmt->bind_param("i", $capsule_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Capsule exists, insert the comment
        $stmt = $conn->prepare("INSERT INTO comments (capsule_id, user_id, comment) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }
        
        $stmt->bind_param("iis", $capsule_id, $user_id, $comment);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Comment added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error: Capsule ID does not exist.</div>";
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Comment</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="container mt-5">
    <h2>Add Comment</h2>
    <form method="post">
        <input type="hidden" name="capsule_id" value="<?php echo isset($_GET['capsule_id']) ? htmlspecialchars($_GET['capsule_id']) : ''; ?>">
        <div class="form-group">
            <textarea class="form-control" name="comment" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>
    <a href="dashboard.php" class="btn btn-secondary mt-2">Cancel</a>
</div>
</body>
</html>
