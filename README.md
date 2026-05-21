# 📊 Online Leave Management System (LMS)

A robust, secure, and modern web application developed in PHP and MySQL to streamline leave applications, approvals, and employee profile management. This system categorizes access control into distinct dashboards for **Administrators**, **Heads of Departments (HODs)**, and **Staff Members**.

---

## 🚀 Key Features

### 👤 Role-Based Portals & Dashboards

*   **Admin Panel:**
    *   Full-system configuration and dashboard analytics.
    *   Manage system roles, departments, and user statuses.
    *   Complete audit log of leave applications.
*   **HOD Dashboard:**
    *   Review and take action on leave applications from department staff (Approve/Reject).
    *   Track leave history, quotas, and pending requests.
    *   Manage department-specific settings and leave types.
*   **Staff Portal:**
    *   Apply for leaves with specific types, durations, and details.
    *   Real-time status updates on applied leaves.
    *   Detailed personal profile management with customizable profile pictures.

### ⚙️ Core Functionalities
*   **Secure Authentication:** Secure user registration, password hashing (`PASSWORD_DEFAULT`), and role-based session validation.
*   **Profile Image Uploads:** Safe handling of file uploads for custom user profiles.
*   **Modern Editor Integration:** Embedded **TinyMCE** rich-text editor for detailed descriptions.
*   **Clean Responsive UI:** Responsive design built with **Bootstrap 4** and customized modern UI styling.

---

## 🛠️ Tech Stack & Dependencies

*   **Backend:** PHP 7.4+ / PHP 8.x
*   **Database:** MySQL (MariaDB)
*   **Frontend Styles & Layouts:** Bootstrap 4, Vanilla CSS
*   **Client Scripting:** JavaScript, jQuery, Bootstrap Bundle JS
*   **Third-party Components:** TinyMCE Editor

---

## 📂 Project Structure

```text
LeaveManagementSystem/
├── admin/          # Admin portal pages and controllers
├── assets/         # CSS styles, JS assets, and vendor libraries (TinyMCE)
├── hod/            # HOD specific dashboard and leave approval flows
├── include/        # Common utilities (database connection, session validations)
├── staff/          # Staff portal pages and leave application flows
├── templates/      # Reusable UI fragments (headers, footers, sidebars)
├── uploads/        # Destination directory for uploaded profile images
├── index.php       # Landing / redirection handler
├── login.php       # User authentication page
├── logout.php      # Session termination script
├── profile.php     # Unified user profile viewer & updater
└── register.php    # Employee registration portal
```

---

## 💻 Installation & Setup

Follow these steps to set up the Leave Management System locally using an environment like XAMPP:

### 1. Prerequisites
Ensure you have the following installed on your machine:
*   [XAMPP](https://www.apachefriends.org/) (Apache, MySQL, PHP) or similar local web server environment.
*   [Git](https://git-scm.com/) installed on your local machine.

### 2. Clone the Repository
Clone the repository to your local web server's root directory (e.g., `htdocs` for XAMPP):
```bash
cd C:\xampp\htdocs
git clone https://github.com/VaishnaviKhade03/LeaveManagementSystem.git
```

### 3. Database Configuration
1. Start **Apache** and **MySQL** services in your XAMPP Control Panel.
2. Open your web browser and navigate to `http://localhost/phpmyadmin/`.
3. Create a new database named `leave_management_system`.
4. Import your SQL schema into the newly created database (if available).
5. Open [include/db-connection.php](file:///v:/Projects/LeaveManagementSystem/include/db-connection.php) and adjust connection credentials if necessary:
    ```php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "leave_management_system";
    ```

### 4. Running the Project
1. Open your web browser.
2. Navigate to: `http://localhost/LeaveManagementSystem/`
3. Register a new user, assign a role, and log in to explore the corresponding dashboard!

---

## 📄 License
This project is open-source and free to use. Refer to local licensing policies for distribution.
