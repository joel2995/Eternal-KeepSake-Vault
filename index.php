<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Eternal Keepsake Vault</title>
    <style>
        body {
            background: url('your-background-image-url.jpg') no-repeat center center fixed; /* Add your background image */
            background-size: cover;
        }
        .container {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            border-radius: 15px;
            padding: 30px;
            margin-top: 50px;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Welcome to Eternal Keepsake Vault</h1>
    <p>Your memories, preserved forever.</p>
    <a href="login.php" class="btn btn-primary">Login</a>
    <a href="register.php" class="btn btn-secondary">Register</a>
</div>
</body>
</html>
