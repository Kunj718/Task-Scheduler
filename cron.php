<?php
require_once 'functions.php';

// Only allow this script to be run from CLI
if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

// Send task reminders
sendTaskReminders(); 