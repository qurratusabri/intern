<?php
include('dbconn.php');

if (isset($_POST['submit'])) {
    $pic = $_GET['pic'];
    $service = $_GET['service'];
    $company = $_GET['company'];
    $start = $_GET['start'];
    $endDate = $_GET['endDate'];
    $sqft = $_GET['sqft'];
    $rent = $_GET['rent']; 
    $remarks = $_GET['remarks'];
    $monthsLeft = $_GET['monthsLeft'];
    $filename = $_GET['filename']; 

    // Correct SQL with WHERE clause
    $sql = "UPDATE form SET filename='$filename'";

    $stmt = $connection->prepare("SELECT * FROM form WHERE filename = ?");
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Proceed with the form
    } else {
        echo "No file found with the given filename.";
        exit;
    }

    
    if (mysqli_query($connection, $sql)) {
        // Redirect after successful update
        header("Location: downloadForm.php?filename=$filename&update=success");
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}
?>
