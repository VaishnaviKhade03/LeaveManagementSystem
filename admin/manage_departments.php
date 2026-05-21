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
  if (isset($_POST['add_department']) && !empty($_POST['department_name'])) {
      // Insert new department
      $department_name = $conn->real_escape_string($_POST['department_name']);
      $department_status = $conn->real_escape_string($_POST['department_status']);
      $sql = "INSERT INTO departments (name, status) VALUES ('$department_name', '$department_status')";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'New department created successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } elseif (isset($_POST['update_department']) && !empty($_POST['department_name']) && !empty($_POST['department_id'])) {
      // Update existing department
      $department_id = intval($_POST['department_id']);
      $department_name = $conn->real_escape_string($_POST['department_name']);
      $department_status = $conn->real_escape_string($_POST['department_status']);
      $sql = "UPDATE departments SET name='$department_name', status='$department_status' WHERE id=$department_id";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'Department updated successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  } elseif (isset($_POST['delete_department']) && !empty($_POST['department_id'])) {
      // Delete department
      $department_id = intval($_POST['department_id']);
      $sql = "DELETE FROM departments WHERE id=$department_id";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'Department deleted successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  }
}

// Fetch departments
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);
$departmentArray = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $departmentArray[] = $row;
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
        <h1>Department</h1>
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
                            Add Department
                        </button>
                        <!-- Table with stripped rows -->
                        <table id="departmentsTable" class="table datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($departmentArray as $department): ?>
                                <tr>
                                    <td class="text-center"><?php echo $department["id"]; ?></td>
                                    <td><?php echo $department["name"]; ?></td>
                                    <td><?php echo $department["status"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="setUpdateData(<?php echo $department['id']; ?>, '<?php echo $department['name']; ?>')">Update</button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteData(<?php echo $department['id']; ?>)">Delete</button>
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

<!-- Add Department Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addDepartmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="addDepartmentName" name="department_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addDepartmentStatus" class="form-label">Status</label>
                        <select class="form-select" id="addDepartmentStatus" name="department_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_department" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Department Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Update Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="updateDepartmentId" name="department_id">
                    <div class="mb-3">
                        <label for="updateDepartmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="updateDepartmentName" name="department_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addDepartmentStatus" class="form-label">Status</label>
                        <select class="form-select" id="addDepartmentStatus" name="department_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_department" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Department Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteDepartmentId" name="department_id">
                    <p>Are you sure you want to delete this department?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="delete_department" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#departmentsTable').DataTable({
            "scrollX": false, // Enable horizontal scrolling
            "columns": [
                { "width": "15%" }, // Adjust width as needed for each column
                { "width": "30%" },
                { "width": "25%" },
                { "width": "30%", "orderable": false } // Disable sorting for action column
            ]
        });
    });

    function setUpdateData(id, name) {
        document.getElementById('updateDepartmentId').value = id;
        document.getElementById('updateDepartmentName').value = name;
    }

    function setDeleteData(id) {
        document.getElementById('deleteDepartmentId').value = id;
    }

    
</script>

<?php include '../templates/footer.php'; ?>

