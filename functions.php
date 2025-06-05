<?php

// File paths
define('TASKS_FILE', __DIR__ . '/tasks.txt');
define('SUBSCRIBERS_FILE', __DIR__ . '/subscribers.txt');
define('PENDING_SUBSCRIPTIONS_FILE', __DIR__ . '/pending_subscriptions.txt');

// Add these lines at the top after <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

// Helper function to read file contents as array
function readFileAsArray($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return $content ? explode("\n", trim($content)) : [];
}

// Helper function to write array to file
function writeArrayToFile($file, $array) {
    $content = implode("\n", array_filter($array));
    file_put_contents($file, $content);
}

// Helper function to generate unique task ID
function generateTaskId() {
    return uniqid('task_');
}

function addTask($task_name) {
    $tasks = readFileAsArray(TASKS_FILE);
    $task_name = trim($task_name);
    
    // Check for duplicates
    foreach ($tasks as $task) {
        $taskData = json_decode($task, true);
        if ($taskData && $taskData['name'] === $task_name) {
            return false;
        }
    }
    
    $task = [
        'id' => generateTaskId(),
        'name' => $task_name,
        'completed' => false,
        'created_at' => time()
    ];
    
    $tasks[] = json_encode($task);
    writeArrayToFile(TASKS_FILE, $tasks);
    return true;
}

function getAllTasks() {
    $tasks = readFileAsArray(TASKS_FILE);
    $result = [];
    
    foreach ($tasks as $task) {
        $taskData = json_decode($task, true);
        if ($taskData) {
            $result[] = $taskData;
        }
    }
    
    return $result;
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = readFileAsArray(TASKS_FILE);
    $updated = false;
    
    foreach ($tasks as &$task) {
        $taskData = json_decode($task, true);
        if ($taskData && $taskData['id'] === $task_id) {
            $taskData['completed'] = $is_completed;
            $task = json_encode($taskData);
            $updated = true;
            break;
        }
    }
    
    if ($updated) {
        writeArrayToFile(TASKS_FILE, $tasks);
    }
    return $updated;
}

function deleteTask($task_id) {
    $tasks = readFileAsArray(TASKS_FILE);
    $filtered = array_filter($tasks, function($task) use ($task_id) {
        $taskData = json_decode($task, true);
        return $taskData && $taskData['id'] !== $task_id;
    });
    
    writeArrayToFile(TASKS_FILE, $filtered);
    return true;
}

function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Add this helper function before subscribeEmail
function sendWithPHPMailer($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'familymemorie7@gmail.com'; // Your Gmail address
        $mail->Password = 'ypnhcowdjnrwkcfd';    // App password from Google
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('familymemorie7@gmail.com', 'Task Planner');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Optionally log $mail->ErrorInfo
        return false;
    }
}

function subscribeEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $pending = readFileAsArray(PENDING_SUBSCRIPTIONS_FILE);
    $subscribers = readFileAsArray(SUBSCRIBERS_FILE);
    // Check if already subscribed or pending
    if (in_array($email, $subscribers) || in_array($email, $pending)) {
        return false;
    }
    $code = generateVerificationCode();
    $pending[] = $email . ':' . $code;
    writeArrayToFile(PENDING_SUBSCRIPTIONS_FILE, $pending);
    // Send verification email
    $basePath = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $host = '192.168.29.198';
    $verification_link = "http://" . $host . $basePath . "/verify.php?email=" . urlencode($email) . "&code=" . $code;
    $subject = "Verify subscription to Task Planner";
    $message = "<p>Click the link below to verify your subscription to Task Planner:</p>";
    $message .= "<p><a id='verification-link' href='{$verification_link}'>Verify Subscription</a></p>";
    sendWithPHPMailer($email, $subject, $message);
    return true;
}

function verifySubscription($email, $code) {
    $pending = readFileAsArray(PENDING_SUBSCRIPTIONS_FILE);
    $subscribers = readFileAsArray(SUBSCRIBERS_FILE);
    
    foreach ($pending as $key => $entry) {
        list($pending_email, $pending_code) = explode(':', $entry);
        if ($pending_email === $email && $pending_code === $code) {
            // Remove from pending
            unset($pending[$key]);
            writeArrayToFile(PENDING_SUBSCRIPTIONS_FILE, $pending);
            
            // Add to subscribers
            $subscribers[] = $email;
            writeArrayToFile(SUBSCRIBERS_FILE, $subscribers);
            return true;
        }
    }
    return false;
}

function unsubscribeEmail($email) {
    $subscribers = readFileAsArray(SUBSCRIBERS_FILE);
    $filtered = array_filter($subscribers, function($sub) use ($email) {
        return $sub !== $email;
    });
    
    writeArrayToFile(SUBSCRIBERS_FILE, $filtered);
    return true;
}

function sendTaskEmail($email, $pending_tasks) {
    $subject = "Task Planner - Pending Tasks Reminder";
    $message = "<h2>Pending Tasks Reminder</h2>";
    $message .= "<p>Here are the current pending tasks:</p>";
    $message .= "<ul>";
    foreach ($pending_tasks as $task) {
        $message .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }
    $message .= "</ul>";
    $host = '192.168.29.198';
    $unsubscribe_link = "http://" . $host . "/rtCamp/src/unsubscribe.php?email=" . urlencode($email);
    $message .= "<p><a id='unsubscribe-link' href='{$unsubscribe_link}'>Unsubscribe from notifications</a></p>";
    return sendWithPHPMailer($email, $subject, $message);
}

function sendTaskReminders() {
    $tasks = getAllTasks();
    $subscribers = readFileAsArray(SUBSCRIBERS_FILE);
    
    // Filter pending tasks
    $pending_tasks = array_filter($tasks, function($task) {
        return !$task['completed'];
    });
    
    if (empty($pending_tasks)) {
        return;
    }
    
    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
} 