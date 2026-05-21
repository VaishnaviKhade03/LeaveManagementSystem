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
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_role']) && !empty($_POST['role_name'])) {
      // Insert new Role
      $role_name = $conn->real_escape_string($_POST['role_name']);
      $role_status = $conn->real_escape_string($_POST['role_status']);
      $sql = "INSERT INTO role (name, status) VALUES ('$role_name', '$role_status')";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'New role created successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } elseif (isset($_POST['update_role']) && !empty($_POST['role_name']) && !empty($_POST['role_id'])) {
      // Update existing role
      $role_id = intval($_POST['role_id']);
      $role_name = $conn->real_escape_string($_POST['role_name']);
      $role_status = $conn->real_escape_string($_POST['role_status']);
      $sql = "UPDATE role SET name='$role_name', status='$role_status' WHERE id=$role_id";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'Role updated successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } elseif (isset($_POST['delete_role']) && !empty($_POST['role_id'])) {
      // Delete role
      $role_id = intval($_POST['role_id']);
      $sql = "DELETE FROM role WHERE id=$role_id";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'Role deleted successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  }
}

// Fetch Roles 
$sql = "SELECT * FROM role";
$result = $conn->query($sql);
$roleArray = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $roleArray[] = $row;
  }
}

include '../templates/admin-header.php'; 
?>

<style>
  table.dataTable.no-footer {
    border-bottom: 1px #403d3d1c;
  }
</style>


<main id="main" class="main">
    <div class="pagetitle">
        <h1>Role Management</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Alert Messages -->
                        <?php if (isset($_SESSION['message']) || isset($_SESSION['error'])): ?>
                            <div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1050;">
                                <?php if (isset($_SESSION['message'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php echo $_SESSION['message']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php unset($_SESSION['message']); ?>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo $_SESSION['error']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php unset($_SESSION['error']); ?>
                                <?php endif; ?>
                            </div>
                            <script>
                                setTimeout(function() {
                                    let alertContainer = document.getElementById('alert-container');
                                    if (alertContainer) {
                                        alertContainer.style.display = 'none';
                                    }
                                }, 5000);
                            </script>
                        <?php endif; ?>

                        <button type="button" class="btn btn-primary mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                            Add Role
                        </button>
                        <!-- Table with stripped rows -->
                        <table id="roleTable" class="table datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roleArray as $role): ?>
                                <tr>
                                    <td class="text-center"><?php echo $role["id"]; ?></td>
                                    <td><?php echo $role["name"]; ?></td>
                                    <td><?php echo $role["status"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="setUpdateData(<?php echo $role['id']; ?>, '<?php echo $role['name']; ?>')">Update</button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteData(<?php echo $role['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- Add Role Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addRoleName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="addRoleName" name="role_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addRoleStatus" class="form-label">Status</label>
                        <select class="form-select" id="addRoleStatus" name="role_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_role" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Role Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Update Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="updateRoleId" name="role_id">
                    <div class="mb-3">
                        <label for="updateRoleName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="updateRoleName" name="role_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateRoleStatus" class="form-label">Status</label>
                        <select class="form-select" id="updateRoleStatus" name="role_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_role" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Role Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteRoleId" name="role_id">
                    <p>Are you sure you want to delete this Role?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="delete_role" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#roleTable').DataTable({
            "scrollX": false, // Enable horizontal scrolling
            "columns": [
                { "width": "15%" }, // Adjust width as needed for each column
                { "width": "25%" },
                { "width": "25%" },
                { "width": "35%", "orderable": false } // Disable sorting for action column
            ]
        });
    });

    function setUpdateData(id, name, status) {
        document.getElementById('updateRoleId').value = id;
        document.getElementById('updateRoleName').value = name;
        document.getElementById('updateRoleStatus').value = status;
    }

    function setDeleteData(id) {
        document.getElementById('deleteRoleId').value = id;
    }

    
</script>

<?php include '../templates/footer.php'; ?>