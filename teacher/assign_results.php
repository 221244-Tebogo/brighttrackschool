<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Results</title>
</head>
<body>
    <h1>Assign Results to Student</h1>
    <form action="assign_results_process.php" method="post">
        <input type="number" name="submissionID" placeholder="Submission ID" required><br>
        <input type="number" name="marks" placeholder="Marks" required><br>
        <textarea name="comment" placeholder="Comment"></textarea><br>
        <button type="submit">Assign Result</button>
    </form>
</body>
</html>
