<?php
include "dbconn.php";


// Check if the filename is provided via GET
if (isset($_GET['filename'])) {
    // Retrieve the filename from the GET request
    $filename = $_GET['filename'];

    // Enclose the filename in single quotes in the SQL query
    $sql = "DELETE FROM form WHERE filename = '$filename'";
    $result = mysqli_query($connection, $sql);

    $stmt = $connection->prepare("DELETE FROM files WHERE filename = ?");
    $stmt->bind_param("s", $filename);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script type='text/javascript'>
            alert('Delete successful.');
            window.location.href = 'dashboard.php';
          </script>";
    } else {
        echo "No file found or delete failed";
    }
    $stmt->close();

}

// Close connection
mysqli_close($connection);
?>
