<?php
include '../include/db-connection.php';
include '../include/session.php';

// Check if user is logged in
checkLogin();

// Check if user is admin
if (!isHOD()) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Always define as arrays FIRST to avoid warnings
$leaves = [];
$leaves_types = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_leave'])) {
        $leave_type_id = $_POST['leave_type'];
        $reason = $conn->real_escape_string($_POST['reason']);
        $startDate = $conn->real_escape_string($_POST['fromDate']);
        $endDate = $conn->real_escape_string($_POST['toDate']); 

        $sql = "INSERT INTO leaves (user_id, leave_type_id ,start_date ,end_date ,reason) VALUES ('$userId', '$leave_type_id' ,'$startDate' ,'$endDate','$reason')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = 'New leave created successfully';
        } else {
            $_SESSION['error'] = 'Error: ' . $conn->error;
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } 
}

// Fetch all leaves for this user
$sql = "SELECT l.*, lt.id , lt.type FROM leaves as l INNER JOIN leave_types as lt ON l.leave_type_id=lt.id WHERE l.user_id = $userId";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $leaves[] = $row;
    }
}

// Fetch leave types
$sql2 = "SELECT * FROM leave_types";
$result2 = $conn->query($sql2);
if ($result2 && $result2->num_rows > 0) {
    while($rows = $result2->fetch_assoc()) {
        $leaves_types[] = $rows;
    }
}

function dateDiffInDays($date1, $date2) { 
    $diff = strtotime($date2) - strtotime($date1); 
    return abs(round($diff / 86400)); 
} 

include '../templates/admin-header.php';
?>
<main id="main" class="main"> 
    <div class="pagetitle">
      <h1>Leave List</h1>
    </div>
    <button type="button" class="btn btn-primary mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#add">
        Add Leave
    </button>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Start Date</th> 
                    <th>End Date</th> 
                    <th>Days</th> 
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($leaves)): ?>
                    <?php foreach($leaves as $leave): 
                        $dateDiff = dateDiffInDays($leave["start_date"], $leave["end_date"]);
                    ?>
                        <tr>
                            <td><?= $leave["id"]; ?></td>
                            <td><?= $leave["type"]; ?></td>
                            <td><?= $leave["start_date"]; ?></td>   
                            <td><?= $leave["end_date"]; ?></td>   
                            <td><?= $dateDiff; ?></td>  
                            <td><?= $leave["status"]; ?></td>   
                        </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No records found.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
            </div>
          </div>
        </div>
      </div>
    </section>
</main>

<!-- add leave modal -->
<div class="modal fade" id="add" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Add Leave</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="post" class="row g-3 p-3">
            <div class="form-row d-flex add-staf-field">
              <div class="form-group col">
                <label for="fromDate" class="form-label">From Date</label> 
                <input type="date" name="fromDate" id="fromDate" required/>
              </div>
              <div class="form-group col">
                <label for="toDate" class="form-label">To Date</label>
                <input type="date" name="toDate" id="toDate" required/>
              </div>
            </div>
            <div class="form-row d-flex add-staf-field">
              <div class="form-group col">
                <label for="leave_type" class="form-label">Leave Type</label>
                <select class="form-select" id="leave_type" name="leave_type" required>
                  <?php foreach($leaves_types as $leave): ?>
                    <option value="<?= $leave['id']; ?>"><?= htmlspecialchars($leave['type']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group col">
                <label for="reason" class="form-label">Reason</label>
                <input type="text" name="reason" id="reason" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="add_leave">Add</button>
            </div>
          </form>
      </div>
  </div>
</div>
<?php
include '../templates/footer.php';
?>
