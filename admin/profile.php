<?php
// Include necessary files
include '../include/db-connection.php';
include '../include/session.php';

// Check if user is logged in
checkLogin();

// Check if user is admin
if (!isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Initialize variables to store user details
$userID = $_SESSION['user_id'];
$fullName = '';
$username = '';
$email = '';
$phone = '';
$address = '';
$dob = '';
$gender = '';
$profileImage = '';
$roleID = '';
$departmentID = '';
$status = '';

// Fetch user details to populate the form
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $fullName = $user['full_name'];
    $username = $user['username'];
    $email = $user['email'];
    $phone = $user['phone'];
    $address = $user['address'];
    $dob = $user['dob'];
    $gender = $user['gender'];
    $profileImage = $user['profile_image'];
    $roleID = $user['role_id'];
    $departmentID = $user['department_id'];
    $status = $user['status'];
}

// Fetch roles from database
$roles = [];
$stmt = $conn->prepare("SELECT * FROM `role`");
$stmt->execute();
$roleResult = $stmt->get_result();
while ($row = $roleResult->fetch_assoc()) {
    $roles[] = $row;
}

// Fetch departments from database
$departments = [];
$stmt = $conn->prepare("SELECT * FROM departments");
$stmt->execute();
$departmentResult = $stmt->get_result();
while ($row = $departmentResult->fetch_assoc()) {
    $departments[] = $row;
}

// Check if form is submitted for profile update
if (isset($_POST['updateProfile'])) {
    // Get form data
    $userID = $_POST['userID'];
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $roleID = $_POST['roleID'];
    $departmentID = $_POST['departmentID'];
    $status = $_POST['status'];

    // Handle profile image upload
    $profileImage = '';
    if (isset($_FILES["profileImage"]["name"]) && !empty($_FILES["profileImage"]["name"])) {
        $target_dir = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION));
        $profileImage = generateFileName($_SESSION['user_id'], $_FILES["profileImage"]["name"]);

        $target_file = $target_dir . $profileImage;
        $uploadOk = 1;

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profileImage"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["profileImage"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update user profile in the database
    $sql = "UPDATE users SET 
                full_name = '$fullName', 
                username = '$username', 
                email = '$email', 
                phone = '$phone', 
                address = '$address', 
                dob = '$dob', 
                gender = '$gender', 
                role_id = '$roleID', 
                department_id = '$departmentID', 
                status = '$status'";

    // Add profile image update to SQL if profileImage is set
    if (!empty($profileImage)) {
        $sql .= ", profile_image = '$profileImage'";
    }

    $sql .= " WHERE id = '$userID'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = 'Profile updated successfully';
        // Redirect to avoid form resubmission
        header("Location: admin-edit-profile.php");
        exit();
    } else {
        $_SESSION['error'] = 'Error updating profile: ' . $conn->error;
    }
}

// Function to generate unique filename
function generateFileName($userID, $fileName)
{
    $randomNumber = mt_rand(1000, 9999);
    $fileNameParts = pathinfo($fileName);
    $extension = $fileNameParts['extension'];
    return "profile_" . $userID . "_" . $randomNumber . "." . $extension;
}

include '../templates/admin-header.php';
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Profile</h1>
    </div><!-- End Page Title -->

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="userID" value="<?php echo $userID; ?>">

                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $fullName; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo $address; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $dob; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php echo ($gender == 'male') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php echo ($gender == 'female') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="other" value="other" <?php echo ($gender == 'other') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="other">Other</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="roleID" class="form-label">Role</label>
                                <select class="form-control" id="roleID" name="roleID">
                                    <?php foreach ($roles as $role) : ?>
                                        <option value="<?php echo $role['id']; ?>" <?php echo ($role['id'] == $roleID) ? 'selected' : ''; ?>><?php echo $role['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="departmentID" class="form-label">Department</label>
                                <select class="form-control" id="departmentID" name="departmentID">
                                    <?php foreach ($departments as $department) : ?>
                                        <option value="<?php echo $department['id']; ?>" <?php echo ($department['id'] == $departmentID) ? 'selected' : ''; ?>><?php echo $department['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="profileImage" class="form-label">Profile Image</label><br>
                                <img src="<?php echo $profileImage; ?>" alt="Current Profile Image" style="max-width: 100px;"><br><br>
                                <input type="file" class="form-control" id="profileImage" name="profileImage">
                            </div>

                            <button type="submit" class="btn btn-primary" name="updateProfile">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../templates/footer.php'; ?>
