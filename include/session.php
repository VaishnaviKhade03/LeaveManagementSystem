<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';
}

function isHOD() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'HOD';
}

function isStaff() {
    return isset($_SESSION['role']) && ($_SESSION['role'] != 'HOD' && $_SESSION['role'] != 'Admin');
}
?>
