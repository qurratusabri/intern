<?php
session_start();
include("dbconn.php"); // Ensure your dbconn.php file correctly sets up the $connection variable

// Function to calculate the number of months between two dates
function monthsBetweenDates($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    if ($end < $start) {
        return 0; // Ensure end date is after start date
    }

    $interval = $start->diff($end);
    return ($interval->y * 12) + $interval->m;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Please log in first.');
            window.location.href='login.html';
          </script>";
    exit();
}

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proceed only if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture values from the HTML form and sanitize inputs
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $pic = mysqli_real_escape_string($connection, $_POST['pic']);
    $service = mysqli_real_escape_string($connection, $_POST['service']);
    $company = mysqli_real_escape_string($connection, $_POST['company']);
    $startDate = mysqli_real_escape_string($connection, $_POST['start']);
    $endDate = mysqli_real_escape_string($connection, $_POST['endDate']);
    $sqft = mysqli_real_escape_string($connection, $_POST['sqft']);
    $rent = mysqli_real_escape_string($connection, $_POST['rent']);
    $remarks = mysqli_real_escape_string($connection, $_POST['remarks']);
    $user_id = mysqli_real_escape_string($connection, $_SESSION['user_id']);

    // Initialize an array to hold uploaded filenames
    $filenames = array();

    // Check if files were uploaded without errors
    if (isset($_FILES["files"]) && count($_FILES['files']['name']) > 0) {
        $target_dir = "uploads/"; // Directory where the files will be uploaded

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf", "docx", "doc", "xls", "xlsx", "ppt", "pptx", "txt");

        // Loop through each uploaded file
        for ($i = 0; $i < count($_FILES["files"]["name"]); $i++) {
            $file_name = $_FILES["files"]["name"][$i];
            $file_tmp = $_FILES["files"]["tmp_name"][$i];
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Check if the file type is allowed
            if (!in_array($file_type, $allowed_types)) {
                echo "Sorry, the file " . htmlspecialchars($file_name) . " is not an allowed file type.<br>";
                continue; // Skip to the next file
            }

            // Generate a unique file name to prevent overwriting
            $new_file_name = uniqid() . '_' . basename($file_name);

            // Set the target file path
            $target_file = $target_dir . $new_file_name;

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Collect the filename
                $filenames[] = $new_file_name;
                echo "The file " . htmlspecialchars($new_file_name) . " has been uploaded.<br>";
            } else {
                echo "Sorry, there was an error uploading your file: " . htmlspecialchars($file_name) . "<br>";
            }
        }
    } else {
        echo "No files were uploaded.";
    }

    // Combine the filenames into a comma-separated string
    $filename = implode(',', $filenames);
    $filename = mysqli_real_escape_string($connection, $filename);

    // Calculate monthsLeft
    $monthsLeft = monthsBetweenDates($startDate, $endDate);

    if ($endDate < $startDate) {
        return 0;
    }

    // SQL query to insert data
    $sql = "INSERT INTO form (category, pic, service, company, start, endDate, sqft, rent, filename, remarks, monthsLeft, user_id) 
            VALUES ('$category', '$pic', '$service', '$company', '$startDate', '$endDate', '$sqft', '$rent', '$filename', '$remarks', '$monthsLeft', '$user_id')";

    // Execute query and handle errors
    if (mysqli_query($connection, $sql)) {
        echo "<script type='text/javascript'>
                alert('Add new record successful.');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "Error inserting record: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
