

<?php 
include '../include/db-connection.php';
include '../include/session.php';

checkLogin();

// Check if user is admin
if (!isHOD()) {
    header('Location: ../login.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_leave_type']) && !empty($_POST['leave_type_name'])) {
      // Insert new leave type
      $leave_type_name = $conn->real_escape_string($_POST['leave_type_name']);
      $leave_type_status = $conn->real_escape_string($_POST['leave_type_status']);
      $sql = "INSERT INTO leave_types (type, status) VALUES ('$leave_type_name', '$leave_type_status')";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'New leave type created successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
    } elseif (isset($_POST['update_leave_type']) && !empty($_POST['leave_type_name']) && !empty($_POST['leave_type_id'])) {
        // Update existing leave type
        $leave_type_id = intval($_POST['leave_type_id']);
        $leave_type_name = $conn->real_escape_string($_POST['leave_type_name']);
        $leave_type_status = $conn->real_escape_string($_POST['leave_type_status']);
        $sql = "UPDATE leave_types SET type='$leave_type_name', status='$leave_type_status' WHERE id=$leave_type_id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = 'Leave type updated successfully';
        } else {
            $_SESSION['error'] = 'Error: ' . $conn->error;
        }
        // Redirect to avoid form resubmission
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['delete_leave_type']) && !empty($_POST['leave_type_id'])) {
      // Delete leave type
      $leave_type_id = intval($_POST['leave_type_id']);
      $sql = "DELETE FROM leave_types WHERE id=$leave_type_id";
      if ($conn->query($sql) === TRUE) {
          $_SESSION['message'] = 'Leave type deleted successfully';
      } else {
          $_SESSION['error'] = 'Error: ' . $conn->error;
      }
      // Redirect to avoid form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
  }
}

// Fetch leave_types
$sql = "SELECT * FROM leave_types";
$result = $conn->query($sql);
$leave_type_Array = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $leave_type_Array[] = $row;
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
        <h1>Leave Type</h1>
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
                            Add Leave Type
                        </button>
                        <!-- Table with stripped rows -->
                        <table id="leave_typesTable" class="table datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leave_type_Array as $leave_type): ?>
                                <tr>
                                    <td><?php echo $leave_type["id"]; ?></td>
                                    <td><?php echo $leave_type["type"]; ?></td>
                                    <td><?php echo $leave_type["status"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="setUpdateData(<?php echo htmlspecialchars(json_encode($leave_type)); ?>)">Update</button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteData(<?php echo $leave_type['id']; ?>)">Delete</button>
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

<!-- Add Leave Type Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add Leave Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addLeaveTypeName" class="form-label">Leave Type Name</label>
                    <input type="text" class="form-control" id="addLeaveTypeName" name="leave_type_name" required>
                </div>
                <div class="mb-3">
                    <label for="addleave_typestatus" class="form-label">Status</label>
                    <select class="form-select" id="addleave_typestatus" name="leave_type_status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_leave_type" class="btn btn-primary">Add</button>
            </div>
        </form>

        </div>
    </div>
</div>

<!-- Update Leave Type Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Update Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="updateLeaveTypeId" name="leave_type_id">
                    <div class="mb-3">
                        <label for="updateLeaveTypeName" class="form-label">Leave Type Name</label>
                        <input type="text" class="form-control" id="updateLeaveTypeName" name="leave_type_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="updateLeaveTypeStatus" class="form-label">Status</label>
                        <select class="form-select" id="updateLeaveTypeStatus" name="leave_type_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_leave_type" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Leave Type Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteLeaveTypeId" name="leave_type_id">
                    <p>Are you sure you want to delete this Leave Type?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="delete_leave_type" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#leave_typesTable').DataTable({
            "scrollX": false, // Enable horizontal scrolling if necessary
            "columns": [
                null, // ID column
                null, // Name column
                null, // Status column
                { "orderable": false } // Action column
            ]
        });
    });

    function setUpdateData(leave_type) {
        document.getElementById('updateLeaveTypeId').value = leave_type.id;
        document.getElementById('updateLeaveTypeName').value = leave_type.type;
        document.getElementById('updateLeaveTypeStatus').value = leave_type.status;
    }

    function setDeleteData(id) {
        document.getElementById('deleteLeaveTypeId').value = id;
    }

    
</script>

<?php include '../templates/footer.php'; ?>

