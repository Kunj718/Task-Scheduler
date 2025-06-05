<?php
require_once 'functions.php';

$email = isset($_GET['email']) ? $_GET['email'] : '';

$success = false;
$message = '';

if (!empty($email)) {
    $success = unsubscribeEmail($email);
    $message = $success ? 
        'You have been successfully unsubscribed from task reminders.' :
        'Error processing your unsubscribe request.';
} else {
    $message = 'Missing email address.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe from Task Reminders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .message {
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #337ab7;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Unsubscribe from Task Reminders</h1>
    <div class="message <?php echo $success ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <a href="index.php" class="back-link">Back to Task Planner</a>
</body>
</html> 