<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
    <div class="container">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h2>Manage <b>Assignments</b></h2>
                        </div>
                        <div class="col-xs-6">
                            <a href="create_assignment.php" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Add New Assignment</span></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Assignment Name</th>
                            <th>Due Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Math Homework</td>
                            <td>2024-09-10</td>
                            <td>Chapter 5 Exercises</td>
                            <td>
                                <a href="#editAssignmentModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                <a href="#deleteAssignmentModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Science Project</td>
                            <td>2024-09-15</td>
                            <td>Volcano Experiment</td>
                            <td>
                                <a href="#editAssignmentModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                <a href="#deleteAssignmentModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </td>
                        </tr>
                        <!-- Additional rows here -->
                    </tbody>
                </table>
            </div>
        </div>        
    </div>

    <!-- Edit Modal HTML -->
    <div id="editAssignmentModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">						
                        <h4 class="modal-title">Edit Assignment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">					
                        <div class="form-group">
                            <label>Assignment Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" required></textarea>
                        </div>					
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-info" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal HTML -->
    <div id="deleteAssignmentModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">						
                        <h4 class="modal-title">Delete Assignment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">					
                        <p>Are you sure you want to delete this assignment?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
