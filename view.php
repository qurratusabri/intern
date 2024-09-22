<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'dbconn.php';

// Get the filename from the URL parameter
$filename = isset($_GET['filename']) ? $_GET['filename'] : '';

// If the filename is invalid or empty, redirect back
if (empty($filename)) {
    header("Location: dashboard.php");
    exit();
}

// Prepare the SQL statement to fetch the record based on the filename and user ID
$stmt = $connection->prepare("SELECT * FROM form WHERE filename = ? AND user_id = ?");
$stmt->bind_param("si", $filename, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


// Check if the row exists
if ($result->num_rows === 0) {
    echo "No data found for this entry.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Record</title>
</head>
<script>
        $(document).ready( function () {
            $('#example').DataTable();
        });

        function confirmDelete(filename) {
            if (confirm("Are you sure you want to delete this data?")) {
                window.location.href = 'deleteForm.php?filename=' + filename;
            }
        }
    </script>
<body>
    <h1>View Record Details</h1>

    <table>
        
        <tr>
            <th>Category:</th>
            <td><?php echo htmlspecialchars($row['category']); ?></td>
        </tr>
        <tr>
            <th>PIC:</th>
            <td><?php echo htmlspecialchars($row['pic']); ?></td>
        </tr>
        <tr>
            <th>Services:</th>
            <td><?php echo htmlspecialchars($row['service']); ?></td>
        </tr>
        <tr>
            <th>Company/Act:</th>
            <td><?php echo htmlspecialchars($row['company']); ?></td>
        </tr>
        <tr>
            <th>Start Date:</th>
            <td><?php echo htmlspecialchars($row['start']); ?></td>
        </tr>
        <tr>
            <th>End Date:</th>
            <td><?php echo htmlspecialchars($row['endDate']); ?></td>
        </tr>
        <tr>
            <th>SQFT</th>
            <td><?php echo htmlspecialchars($row['sqft']); ?></td>
        </tr>
        <tr>
            <th>Rental</th>
            <td><?php echo htmlspecialchars($row['rent']); ?></td>
        </tr>
        <tr>
            <th>Remarks:</th>
            <td><?php echo htmlspecialchars($row['remarks']); ?></td>
        </tr>
        <tr>
            <th>Months Left Before Ends:</th>
            <td><?php echo htmlspecialchars($row['monthsLeft']); ?></td>
        </tr>
        <tr>
            <th>Document:</th>
            <td><?php echo htmlspecialchars($row['filename']); ?></td>
        </tr>
        <tr>
            <td><a href="<?php echo $file_path; ?>" class="btn btn-primary" download>Download</a>
                <a href="editForm.php?filename=<?php echo $row['filename']; ?>" class="btn btn-warning">Update</a>
                <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $row['filename']; ?>')" class="btn btn-danger">Delete</a></td>
        </tr>
        
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php
$stmt->close();
$connection->close();
?>
