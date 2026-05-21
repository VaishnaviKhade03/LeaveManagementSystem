<?php 
include 'include/db-connection.php';

$message = "";

// Fetch roles (Active only)
$roles_result = $conn->query("SELECT id, name FROM role WHERE status = 'Active'");

// Fetch departments (Active only)
$departments_result = $conn->query("SELECT id, name FROM departments WHERE status = 'Active'");

// Registration logic and popup on success
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Account created successfully";
    $msgType = "success";
}

if (isset($_POST['register'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $role_id = intval($_POST['role_id']);
    $department_id = intval($_POST['department_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
        $msgType = "warning";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $profile_image = "";
        if (!empty($_FILES['profile_image']['name'])) {
            $targetDir = "assets/images/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $profile_image = uniqid() . "_" . basename($_FILES["profile_image"]["name"]);
            $targetFile = $targetDir . $profile_image;
            if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
                $message = "Image upload failed!";
                $msgType = "warning";
            }
        }
        if (empty($message)) {
            $sql = "INSERT INTO users (full_name, username, email, password, phone, address, dob, gender, profile_image, role_id, department_id, status, created_at, updated_at) 
                    VALUES ('$full_name', '$username', '$email', '$hashed_password', '$phone', '$address', '$dob', '$gender', '$profile_image', $role_id, $department_id, '$status', NOW(), NOW())";
            if ($conn->query($sql) === TRUE) {
                header("Location: register.php?success=1");
                exit;
            } else {
                $message = "Error: " . $conn->error;
                $msgType = "warning";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Leave Management System</title>
    <link href="<?php echo $base; ?>/assets/images/profile.png" rel="icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body class="bg-light">
    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?php echo $msgType == 'success' ? 'success' : 'warning' ?> alert-dismissible fade show position-fixed" role="alert" 
            style="top:20px; right:20px; max-width: 370px; z-index:1050; 
                box-shadow: 0 2px 7px rgba(0,0,0,0.1); border-radius: 6px; 
                background-color: <?php echo $msgType == 'success' ? '#dff0d8' : '#fcf8e3'; ?>; color: #3c763d;">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#3c763d">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if(alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 500);
                }
            }, 4000);
        </script>
    <?php endif; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 bg-white p-4 rounded shadow-sm">
                <h3 class="text-center mb-4">Create Your Account</h3>
                <form method="POST" action="register.php" enctype="multipart/form-data" novalidate>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="">Choose...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="role_id">Role</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <option value="">Select Role...</option>
                                <?php while($role_row = $roles_result->fetch_assoc()): ?>
                                    <option value="<?= $role_row['id'] ?>"><?= htmlspecialchars($role_row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="department_id">Department</label>
                            <select name="department_id" id="department_id" class="form-control" required>
                                <option value="">Select Department...</option>
                                <?php while($dept_row = $departments_result->fetch_assoc()): ?>
                                    <option value="<?= $dept_row['id'] ?>"><?= htmlspecialchars($dept_row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col-md-8">
                            <label for="profile_image">Profile Image (optional)</label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control-file" accept="image/*" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary btn-block">Create Account</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
