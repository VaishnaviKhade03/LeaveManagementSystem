<?php 
   // include '../include/db-connection.php';
    $base = "http://localhost/Leave_Management_System/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Leave Management System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
   <!-- <link rel="icon" href="/assets/images/favicon.png" type="image/png"> -->
  <link href="<?php echo $base; ?>/assets/images/profile.png" rel="icon">
  <link href="<?php echo $base; ?>/assets/images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CNunito:300,300i,400,400i,600,600i,700,700i%7CPoppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $base; ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $base; ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $base; ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $base; ?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?php echo $base; ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?php echo $base; ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo $base; ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
  <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  <!-- Template Main CSS File -->
  <link href="<?php echo $base; ?>assets/css/style.css" rel="stylesheet">
</head>

<body>