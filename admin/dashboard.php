<?php
include '../include/db-connection.php';
include '../include/session.php';

// Check if user is logged in
checkLogin();

// Check if user is admin
if (!isAdmin()) {
    header('Location: ../login.php');
    exit();
}
require_once '../templates/admin-header.php';
require_once '../templates/sidebar.php';

// Dashboard data
$total_leaves = 0;
$leave_types = array();
$employees_by_department = array();
$employees_by_role = array();

// Total Leave Requests
$sql = "SELECT COUNT(*) AS total_leaves FROM leaves";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_leaves = $row["total_leaves"];
}

// Leave Types by Frequency
$sql = "SELECT lt.type, COUNT(*) AS count FROM leaves l JOIN leave_types lt ON l.leave_type_id = lt.id GROUP BY lt.type ORDER BY count DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leave_types[] = array("type" => $row["type"], "count" => $row["count"]);
    }
}

// Employees by Department
$sql = "SELECT d.name, COUNT(u.id) AS count FROM users u JOIN departments d ON u.department_id = d.id GROUP BY d.name ORDER BY count DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees_by_department[] = array("department" => $row["name"], "count" => $row["count"]);
    }
}

//Employees by Role
$sql = "SELECT r.name, COUNT(u.id) AS count FROM users u JOIN role r ON u.role_id = r.id GROUP BY r.name ORDER BY count DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees_by_role[] = array("role" => $row["name"], "count" => $row["count"]);
    }
}
?>

<main id="main" class="main">
    <section class="section">
        <div class="card card-outline">
            <div class="card-header">
                <div class="pagetitle">
                    <h1 style="color:#071952">Leave Management System</h1>
                </div>
            </div>
            <div class="card-body">
            <div class="row">
            <div class="col-md-3">
                <div class="card card2">
                    <div class="card-header">
                        <h4>Total leave Request</h4>
                    </div>
                    <div class="card-body">
                    <p>Leave types :<?= $total_leaves ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card2">
                    <div class="card-header">
                        <h4>Leave Types By Frequency</h4>
                    </div>
                    <div class="card-body">
                    <ul>
                    <?php foreach ($leave_types as $leave_type) { ?>
                        <li><?= $leave_type["type"] ?> (<?= $leave_type["count"] ?>)</li>
                    <?php } ?>
                </ul>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card2">
                    <div class="card-header">
                        <h4>Employees By Department</h4>
                    </div>
                    <div class="card-body">
                    <ul>
                    <?php foreach ($employees_by_department as $department) { ?>
                        <li><?= $department["department"] ?> (<?= $department["count"] ?>)</li>
                    <?php } ?>
                </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card2">
                    <div class="card-header">
                        <h4>Employees By Role</h4>
                    </div>
                    <div class="card-body">
                    <ul>
                    <?php foreach ($employees_by_role as $role) { ?>
                        <li><?= $role["role"] ?> (<?= $role["count"] ?>)</li>
                    <?php } ?>
                </ul>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</main> 

<?php include '../templates/footer.php'; ?>

<style>
    .card{
        color : #071952;
        padding: 0px 20px;
        margin-top: 20px;
        border-radius: 30px;
    }
    .card-body{
        padding: 15px 0px;
        color: #37B7C3;
    }
    .card-header{
        color:#071952;
    }
    .card-outline{
        border-radius: 10px;
    }
</style>