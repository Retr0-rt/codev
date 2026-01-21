# CoCode - Coworking & Project Management Platform

**CoCode** is a lightweight, self-hosted project management and coworking platform designed to streamline collaboration between administrators, project managers, and developers. It features a role-based access control (RBAC) system, allowing distinct workflows for assigning tasks, managing users, and tracking project progress.

Built with simplicity in mind, CoCode runs entirely on PHP's built-in server and SQLite, removing the need for complex database setups or heavy web server software (like Apache/Nginx) for local development.

## Technologies Used
* **Backend:** PHP 8+ (Native, no frameworks)
* **Database:** SQLite3 (Serverless, zero-configuration)
* **Frontend:** HTML5, CSS3, JavaScript
* **Architecture:** LAMP-style (Linux/Windows, Apache/Built-in, MySQL/SQLite, PHP) logic adapted for portability.

## Main Functionalities
* **Role-Based Dashboards:** Distinct views and permissions for **Admins**, **Project Managers**, and **Developers**.
* **Project Management:** Create projects, define scopes, and manage timelines.
* **Task Assignment:** Dynamic allocation of tasks to developers with status tracking.
* **User Management:** Add, edit, and remove users with secure authentication.
* **Portable Deployment:** Runs instantly on any machine with PHP installed.

---

## Prerequisites & Setup

To run CoCode, you do **not** need XAMPP or WAMP. You only need **PHP** installed on your system.

### 1. Install PHP
If you don't have PHP installed, download it here:
* **Windows:** [Download PHP for Windows](https://windows.php.net/download/) (VS16 x64 Thread Safe is recommended).
* **Linux:** `sudo apt install php php-sqlite3`
* **macOS:** `brew install php`

### 2. Enable SQLite (Crucial Step!)
CoCode uses **SQLite** (`codev.db`), which comes bundled with PHP but is often disabled by default on Windows.

1.  Locate your `php.ini` file (run `php --ini` in your terminal to find the path).
2.  Open the file in a text editor (Notepad, VS Code, etc.).
3.  Search for the line: `;extension=sqlite3`
4.  **Remove the semicolon (`;`)** at the beginning to enable it.
    * *Before:* `;extension=sqlite3`
    * *After:* `extension=sqlite3`
5.  Save and close the file.

---

##  Installation

Clone the repository to your local machine:

```bash
git clone https://github.com/Retr0-rt/codev.git codev
cd codev
.\LAUNCH_WEBSITE.bat
```
---
## Login credentials:
* Username: admin
* Password: admin
* (every user you find in the users table has the username as password too)