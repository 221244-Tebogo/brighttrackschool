<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes and Subjects</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .overview {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .overview-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .overview-card:last-child {
            margin-right: 0;
        }

        .icon-container {
            background-color: #007bff;
            padding: 15px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .material-icons {
            font-size: 48px;
            color: white;
        }

        .overview-info h4 {
            margin: 0;
            font-size: 22px;
        }

        .overview-info p {
            margin: 5px 0 0;
            font-size: 16px;
        }

        .table-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .table-item {
            flex: 0 0 48%;
            margin-bottom: 20px;
        }

        .table-item h1 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        table thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <?php include '../components/admin_sidebar.php'; ?>
    </aside>
<div class="container">
<div class="overview">
    <div class="overview-card">
        <div class="icon-container">
            <span class="material-icons">class</span>
        </div>
        <div class="overview-info">
            <h4>Classes</h4>
            <p>Manage your classes</p>
        </div>
    </div>
    
   
    <div class="overview-card">
        <div class="icon-container">
            <span class="material-icons">book</span>
        </div>
        <div class="overview-info">
            <h4>Subjects</h4>
            <p>Manage your subjects</p>
        </div>
    </div>
</div>

<div class="table-wrapper">
  
    <div class="table-item">
        <div class="btn-container">
            <a href="add_class.php" class="btn btn-primary mb-3">Add New Class</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Grade</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../config.php';
                $sql = "SELECT * FROM Class";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['ClassName']}</td>
                                <td>{$row['Grade']}</td>
                                <td>
<a href='edit_class.php?id={$row['ClassID']}' class='btn btn-success btn-sm'>Edit</a>
                                    <a href='delete_class.php?id={$row['ClassID']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No classes found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="table-item">
        <div class="btn-container">
            <a href="add_subject.php" class="btn btn-primary mb-3">Add New Subject</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM Subject";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['Name']}</td>
                                <td>
                                    <a href='edit_subject.php?id={$row['SubjectID']}' class='btn btn-success btn-sm'>Edit</a>
                                    <a href='delete_subject.php?id={$row['SubjectID']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No subjects found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>

</body>
</html>
