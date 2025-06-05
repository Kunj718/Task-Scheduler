#!/bin/bash

# Get the absolute path of the cron.php file
SCRIPT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/cron.php"

# Create the CRON job command
CRON_CMD="0 * * * * php $SCRIPT_PATH"

# Add the CRON job
(crontab -l 2>/dev/null | grep -v "$SCRIPT_PATH"; echo "$CRON_CMD") | crontab -

echo "CRON job has been set up to run every hour." 