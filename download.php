<?php

include 'dbconn.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Download files</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container mt-5">
        <h2>Download files</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>File Size</th>
                    <th>File Type</th>
                    <th>Actions</th> <!-- Added Actions column header -->
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM files";
                $result = $connection->query($sql);
                // display the uploaded files and download links
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $file_path = "uploads/" . $row['filename'];
                        ?>
                        <tr>
                            <td><?php echo $row['filename']; ?></td>
                            <td><?php echo $row['filesize']; ?> bytes</td>
                            <td><?php echo $row['filetype']; ?></td>
                            <td><a href="<?php echo $file_path; ?>" class="btn btn-primary" download>Download</a>
                            <a href="editForm.php?filename=<?php echo $row['filename']; ?>" class="btn btn-warning">Update</a>
                                <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $row['filename']; ?>')" class="btn btn-danger">Delete</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5">No files uploaded yet.</td> <!-- Adjusted colspan to match the number of columns -->
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$connection->close();
?>
