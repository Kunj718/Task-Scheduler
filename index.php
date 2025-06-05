<?php
require_once 'functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        $task_name = trim($_POST['task-name']);
        if (!empty($task_name)) {
            addTask($task_name);
        }
    }
    
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (!empty($email)) {
            subscribeEmail($email);
        }
    }
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'mark_completed':
            if (isset($_POST['task_id']) && isset($_POST['completed'])) {
                $result = markTaskAsCompleted($_POST['task_id'], $_POST['completed'] === 'true');
                echo json_encode(['success' => $result]);
            }
            break;
            
        case 'delete_task':
            if (isset($_POST['task_id'])) {
                $result = deleteTask($_POST['task_id']);
                echo json_encode(['success' => $result]);
            }
            break;
    }
    exit;
}

$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Planner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .task-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .task-item.completed {
            background-color: #f8f8f8;
            text-decoration: line-through;
            color: #888;
        }
        .task-status {
            margin-right: 10px;
        }
        .delete-task {
            margin-left: auto;
            color: red;
            cursor: pointer;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="text"], input[type="email"] {
            padding: 8px;
            width: 300px;
            margin-right: 10px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Task Planner</h1>
    
    <div class="section">
        <h2>Add New Task</h2>
        <form method="POST">
            <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
            <button type="submit" id="add-task">Add Task</button>
        </form>
    </div>
    
    <div class="section">
        <h2>Task List</h2>
        <ul class="tasks-list">
            <?php foreach ($tasks as $task): ?>
            <li class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>" data-task-id="<?php echo htmlspecialchars($task['id']); ?>">
                <input type="checkbox" class="task-status" <?php echo $task['completed'] ? 'checked' : ''; ?>>
                <span class="task-name"><?php echo htmlspecialchars($task['name']); ?></span>
                <button class="delete-task">Delete</button>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="section">
        <h2>Subscribe to Task Reminders</h2>
        <form method="POST">
            <input type="email" name="email" required placeholder="Enter your email">
            <button type="submit" id="submit-email">Subscribe</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle task completion
            document.querySelectorAll('.task-status').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const taskItem = this.closest('.task-item');
                    const taskId = taskItem.dataset.taskId;
                    const completed = this.checked;
                    
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=mark_completed&task_id=${taskId}&completed=${completed}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            taskItem.classList.toggle('completed', completed);
                        }
                    });
                });
            });
            
            // Handle task deletion
            document.querySelectorAll('.delete-task').forEach(button => {
                button.addEventListener('click', function() {
                    const taskItem = this.closest('.task-item');
                    const taskId = taskItem.dataset.taskId;
                    
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete_task&task_id=${taskId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            taskItem.remove();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html> 