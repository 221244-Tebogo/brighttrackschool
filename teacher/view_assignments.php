<?php
include('../config.php');
include('../components/header.php');
include('../components/sidebar.php');

// Fetch all assignments
$query = "SELECT * FROM Assignment ORDER BY DueDate DESC";
$result = $conn->query($query);
?>

<div class="main-content">
    <h1>View Assignments</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Assignment Name</th>
                    <th>Due Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($assignment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($assignment['Name']) ?></td>
                        <td><?= htmlspecialchars($assignment['DueDate']) ?></td>
                        <td><?= htmlspecialchars($assignment['Description']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assignments found.</p>
    <?php endif; ?>
</div>

<?php
include('../components/footer.php');
?>
