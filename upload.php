<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if files were uploaded without errors
    if (isset($_FILES["files"]) && count($_FILES['files']['name']) > 0) {
        $target_dir = "uploads/"; // Directory where the files will be uploaded
        
        include 'dbconn.php';

        }

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf", "docx", "doc", "xls", "xlsx", "ppt", "pptx", "txt");

        // Loop through each uploaded file
        for ($i = 0; $i < count($_FILES["files"]["name"]); $i++) {
            $file_name = $_FILES["files"]["name"][$i];
            $file_tmp = $_FILES["files"]["tmp_name"][$i];
            $file_size = $_FILES["files"]["size"][$i];
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Check if the file type is allowed
            if (!in_array($file_type, $allowed_types)) {
                echo "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, and TXT files are allowed.";
                continue; // Skip to the next file
            }

            // Check if a new file name was provided by the user
            if (!empty($_POST["newFileName"])) {
                // Sanitize the new file name and append the file extension
                $new_file_name = basename($_POST["newFileName"]) . "_$i." . $file_type; // Add index to avoid duplicate names
            } else {
                // Use the original file name if no new name was provided
                $new_file_name = basename($file_name);
            }

            // Set the target file path
            $target_file = $target_dir . $new_file_name;

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Insert the file information into the database
                $sql = "INSERT INTO files (filename, filesize, filetype) VALUES ('$new_file_name', $file_size, '$file_type')";

                if ($connection->query($sql) === TRUE) {
                    echo "The file " . htmlspecialchars($new_file_name) . " has been uploaded and stored in the database.<br>";
                } else {
                    echo "Error storing file information in the database: " . $connection->error . "<br>";
                }
            } else {
                echo "Sorry, there was an error uploading your file: " . htmlspecialchars($new_file_name) . "<br>";
            }
        }

        $connection->close();
    } else {
        echo "No files were uploaded.";
    }
} else {
    echo "Invalid request method.";
}
?>
