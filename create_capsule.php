<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content_type = $_POST['content_type'];
    $unlock_date = $_POST['unlock_date'] . ' ' . $_POST['unlock_time'];

    $content = '';

    if ($content_type == "text") {
        $content = $_POST['content'];
    } elseif ($content_type == "image" || $content_type == "video") {
        $target_dir = "uploads/";
        $file_name = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = array("jpg", "jpeg", "png", "gif", "mp4", "avi", "mkv");
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if ($_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) {
            if (in_array($file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $content = $file_name;
                } else {
                    echo "Error uploading file.";
                    exit();
                }
            } else {
                echo "Invalid file type.";
                exit();
            }
        } else {
            echo "File upload error: " . $_FILES["fileToUpload"]["error"];
            exit();
        }
    } else {
        echo "Invalid content type.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO capsules (user_id, title, content_type, content, unlock_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $content_type, $content, $unlock_date);
    if ($stmt->execute()) {
        $last_inserted_id = $stmt->insert_id;

        // Handle tags if provided
        if (isset($_POST['tags'])) {
            $tags = explode(',', $_POST['tags']);
            foreach ($tags as $tag) {
                $tag = trim($tag);
                $stmt_tag = $conn->prepare("INSERT INTO tags (capsule_id, tag_name) VALUES (?, ?)");
                $stmt_tag->bind_param("is", $last_inserted_id, $tag);
                $stmt_tag->execute();
            }
        }

        echo "Capsule created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Capsule</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <style>
        body {
            background: url('your-moving-background-image-url.jpg') no-repeat center center fixed; /* Add your background image */
            background-size: cover;
        }
        .container {
            background: rgba(0, 0, 0, 0.8); /* Darker semi-transparent background */
            border-radius: 15px;
            padding: 30px;
            margin-top: 50px;
            color: white; /* Text color */
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Create Capsule</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="form-group">
            <label for="content_type">Content Type:</label>
            <select class="form-control" name="content_type" required id="content_type">
                <option value="text" selected>Text</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
            </select>
        </div>
        <div class="form-group" id="text_area_container">
            <label for="content">Content:</label>
            <textarea class="form-control" name="content" id="content" rows="4"></textarea>
        </div>
        <div class="form-group" id="file_input_container" style="display: none;">
            <label for="fileToUpload">Upload File:</label>
            <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
        </div>
        <div class="form-group">
            <label for="tags">Tags (comma-separated):</label>
            <input type="text" class="form-control" name="tags" placeholder="e.g., travel, family">
        </div>
        <div class="form-group">
            <label for="unlock_date">Unlock Date:</label>
            <input type="date" class="form-control" name="unlock_date" required>
        </div>
        <div class="form-group">
            <label for="unlock_time">Unlock Time:</label>
            <input type="time" class="form-control" name="unlock_time" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Capsule</button>
    </form>
</div>

<script>
    document.getElementById('content_type').addEventListener('change', function() {
        var contentType = this.value;
        var textAreaContainer = document.getElementById('text_area_container');
        var fileInputContainer = document.getElementById('file_input_container');

        if (contentType === 'text') {
            textAreaContainer.style.display = 'block';
            fileInputContainer.style.display = 'none';
        } else {
            textAreaContainer.style.display = 'none';
            fileInputContainer.style.display = 'block';
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
