<?php 
include 'include/db-connection.php'; 
include 'include/session.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging output for the username and password
    // var_dump($username);
    // var_dump($password);

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT u.id, u.full_name, u.username, u.password, u.role_id, r.id as role_id, r.name as role_name
    FROM users as u
    INNER JOIN role as r ON u.role_id = r.id
    WHERE u.username = ?");
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check for SQL errors
    if ($conn->error) {
        die('Execute failed: ' . $conn->error);
    }

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Debugging output for fetched user data
        var_dump($user);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session and redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role_name'];

            // Redirect based on role
            if (isAdmin()) {
                header('Location: admin/dashboard.php');
            } elseif (isHOD()) {
                header('Location: hod/dashboard.php');
            } else {
                header('Location: staff/dashboard.php');
            }
            exit();
        } else {
            // Incorrect password
            $_SESSION['error_message'] = "Invalid password";
            header('Location: login.php');
            exit();
        }
    } else {
        // No user found
        $_SESSION['error_message'] = "No user found with that username";
        header('Location: login.php');
        exit();
    }
}

include 'templates/header.php';
?>

<style>
  .notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: #f44336;
  color: white;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  display: none; /* Initially hidden */
}

/* Display the notification when it's inserted */
.notification {
  display: block;
}
</style>
<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                            <!-- <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="<?php echo $base; ?>assets/images/logo.png" alt="">
                            </a> -->
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                    <p class="text-center small">Enter your username & password to login</p>
                                </div>

                                <form action="" method="post" class="row g-3 needs-validation">
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                            <input type="text" name="username" class="form-control" id="yourUsername" required>
                                            <div class="invalid-feedback">Please enter your username.</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" name="login" type="submit">Login</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Don't have account? <a href="register.php">Create an account</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
