<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <?php include('../components/sidebar.php'); ?>
  <div class="main-content" style="margin-left: 250px; padding: 20px;">
    <h1>Teacher Dashboard</h1>
    <p>Welcome to your Dashboard. Here you can manage assignments and view submissions.</p>
    <a href="create_assignment.php">Create New Assignment</a>
    <a href="view_assignments.php">View Assignments</a>
  </div>
  <?php include('../components/footer.php'); ?>
</body>
</html>
