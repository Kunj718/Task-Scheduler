# Task Management System with Email Notifications

A robust PHP-based task management system that enables users to create, track, and manage their tasks with integrated email notification capabilities. The system provides a simple yet effective way to stay organized and receive timely reminders for your tasks.

## 🌟 Features

- **Task Management**
  - Create and track tasks
  - Mark tasks as completed
  - View task history
  - Real-time task status updates

- **Email Integration**
  - Email subscription system
  - Email verification process
  - Easy unsubscribe functionality
  - Automated email notifications

- **Automation**
  - Cron job support for automated tasks
  - Cross-platform compatibility (Windows and Unix-based systems)
  - Automated email notifications

## 🛠️ Technical Stack

- PHP
- PHPMailer for email functionality
- File-based data storage
- Cron jobs for automation

## 📁 Project Structure

```
src/
├── index.php              # Main application interface
├── functions.php          # Core functionality
├── verify.php            # Email verification system
├── unsubscribe.php       # Email unsubscribe functionality
├── cron.php              # Automated task processing
├── tasks.txt             # Task data storage
├── subscribers.txt       # Email subscriber management
├── pending_subscriptions.txt  # Pending email verifications
├── setup_cron.bat        # Windows cron setup
├── setup_cron.sh         # Unix cron setup
└── PHPMailer/            # Email handling library
```

## ⚙️ Prerequisites

- PHP 7.4 or higher
- Web server (Apache/Nginx)
- SMTP server access for email functionality
- Cron job access for automated tasks
- File write permissions for data storage

## 🚀 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/task-management-system.git
   cd task-management-system
   ```

2. Configure your web server to point to the project directory

3. Set up SMTP configuration:
   - Open `functions.php`
   - Update the SMTP settings with your email server details

4. Set up cron jobs:
   - For Windows: Run `setup_cron.bat`
   - For Unix-based systems: Run `setup_cron.sh`

5. Set proper file permissions:
   ```bash
   chmod 755 src/
   chmod 644 src/*.txt
   ```

## 💻 Usage

### Creating Tasks
1. Access the main interface through `index.php`
2. Enter task details in the form
3. Submit to create a new task

### Managing Email Subscriptions
1. Subscribe to notifications through the subscription form
2. Verify your email address
3. Manage subscription preferences through the unsubscribe link

### Automated Notifications
- System automatically sends reminders for pending tasks
- Notifications are sent based on configured cron schedule

## 🔧 Configuration

### Email Settings
Configure your SMTP settings in `functions.php`:
```php
define('SMTP_HOST', 'your-smtp-host');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@example.com');
define('SMTP_PASSWORD', 'your-password');
```

### Cron Job Settings
- Default schedule: Every hour
- Customize in `cron.php` as needed

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- Your Name - Initial work

## 🙏 Acknowledgments

- PHPMailer for email functionality
- Contributors and supporters of the project

## 📞 Support

For support, email your-email@example.com or open an issue in the repository.

---

⭐ Star this repository if you find it helpful! 