<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <?php if (isAdmin()) : ?>
  <li class="nav-item">
    <a class="nav-link " href="../admin/dashboard.php">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link " href="../admin/manage_departments.php">
    <i class="bi bi-building"></i>
      <span>Department</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link " href="../admin/manage_leave_types.php">
    <i class="bi bi-list-check"></i>
      <span>Leave Type</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link " href="../admin/role-manage.php">
      <i class="bi bi-person-fill"></i>
      <span>Role Management</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link " href="../admin/manage_staff.php">
      <i class="bi bi-people"></i>
      <span>Staff Management</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link " href="../admin/list_leaves.php">
      <i class="bi bi-list-task"></i>
      <span>Leave List</span>
    </a>
  </li><!-- End Dashboard Nav -->
  
  <?php elseif (isStaff()) : ?>
    <li class="nav-item">
      <a class="nav-link " href="../staff/dashboard.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link " href="../staff/leaves.php">
        <i class="bi bi-grid"></i>
        <span>Leaves</span>
      </a>
    </li> 
   
  </li>
  <?php elseif (isHOD()) : ?> 
    <li class="nav-item">
      <a class="nav-link " href="../hod/dashboard.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link " href="../hod/leaves.php">
        <i class="bi bi-grid"></i>
        <span>Leaves</span>
      </a>
    </li> 
    <li class="nav-item">
    <a class="nav-link " href="../hod/manage_leave_types.php">
      <i class="bi bi-list-task"></i>
      <span>Leave List</span>
    </a>
  <?php endif ?>
</ul>

</aside><!-- End Sidebar-->