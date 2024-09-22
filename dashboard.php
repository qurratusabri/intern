<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Stop further script execution
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

include 'dbconn.php'; // Include database connection

// Define categories
$categories = ["licensing", "tenant", "services", "outsource", "biomedical-facilities", "marcomm", "clinical", "support"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <style>
         /* CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-size: cover;
            background-position: center;
            color: white;
        }

        /* Logo */
        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 70px;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 80px;
            height: 100%;
            background-color: transparent;
            backdrop-filter: blur(50px);
            border-right: 2px solid rgba(225, 225, 255, 0.2);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 6px 14px;
            transition: width 0.5s ease;
            z-index: 10;
        }

        .sidebar.active {
            width: 260px;
        }

        .sidebar .logo-menu {
            display: flex;
            align-items: center;
            width: 100%;
            height: 70px;
        }

        .sidebar .logo-menu .menu {
            font-size: 25px;
            color: black;
            pointer-events: none;
            opacity: 0;
            transition: 0.3s;
        }

        .sidebar.active .logo-menu .menu {
            opacity: 1;
            transition-delay: 0.2s;
        }

        .sidebar .logo-menu .toggle-btn {
            position: absolute;
            top: 20px; /* Adjusted top for better alignment */
            left: 25%; /* Adjusts correctly when sidebar is active */
            width: 40px;
            height: 40px;
            font-size: 22px;
            color: black;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
            transition: 0.5s;
            z-index: 100; /* Ensure it stays on top */
        }

        .sidebar.active .logo-menu .toggle-btn {
            left: 80%; /* Adjusted for the active sidebar */
        }

        .sidebar .list {
            margin-top: 10px;
        }

        .sidebar .list .list-item {
            list-style: none;
            width: 100%;
            height: 50px;
            margin: 5px 0;
            line-height: 50px;
        }

        .sidebar .list .list-item a {
            display: flex;
            align-items: center;
            font-size: 18px;
            color: black;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.5s;
        }

        .sidebar .list .list-item.active a,
        .sidebar .list .list-item a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar .list .list-item a i {
            min-width: 50px;
            height: 50px;
            text-align: center;
            line-height: 50px;
        }

        .sidebar .link-name {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .sidebar.active .link-name {
            opacity: 1;
            pointer-events: auto;
            transition-delay: calc(0.1s * var(--i));
        }

        /* Table Styles */
        .center-table {
            display: flex;
            justify-content: center;
            background-color: rgba(240, 240, 240, 0.5);
            color: black;
            margin-top: 30px;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        .container {
            padding-top: 40px;
            width: 85%;
            margin: 0 auto;
            flex: 1;
        }

        #example_wrapper {
            width: 100%;
            overflow-x: auto;
        }

        #example {
            width: 100%;
            padding-top: 10px;
            color: black;
            table-layout: fixed;
        }

        #example th,
        #example td {
            text-align: center;
            color: black;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dataTables_filter input[type="search"] {
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            box-shadow: none;
            max-width: 150px;
        }

        /* Button Styles */
        .button {
            background-color: blue;
            color: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn {
            background: grey;
            color: white;
            font-size: 1.2em;
            padding: 5px 20px;
            text-decoration: none;
            border-radius: 20px;
        }

        .btn:hover {
            background: #fff;
            color: grey;
        }

        /* Misc Styles */
        h1 {
            color: black;
            text-shadow: 0 0 5px #999;
            font-size: 50px;
            text-align: center;
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .dataTables_filter {
            display: flex;
            align-items: center;
        }

        #categoryFilter {
            margin-left: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
            color: black;
        }
    </style>
    <script>
        $(document).ready(function () {
            var table = $('#example').DataTable();

            // Filter table based on category selection
            $('#categoryFilter').on('change', function () {
                var selectedCategory = $(this).val();
                table.column(0).search(selectedCategory).draw();
            });

            // Toggle sidebar
            $('.toggle-btn').click(function () {
                $('.sidebar').toggleClass('active');
            });
        });
    </script>
</head>
<body>
    <div class="logo-container">
        <img src="logo.png" alt="Logo" class="logo">
    </div>

    <div class="sidebar">
        <div class="logo-menu">
            <h2 class="menu">Menu</h2>
            <i class='bx bx-menu toggle-btn'></i>
        </div>
        <ul class="list">
            <li class="list-item active">
                <a href="">
                    <i class='bx bx-home'></i>
                    <span class="link-name" style="--i:1;">Dashboard</span>
                </a>
            </li>
            <li class="list-item">
                <a href="logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="link-name" style="--i:3;">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="container">
        <h1>Agreement</h1>
        <div class="filter-container">
            <label for="categoryFilter">Category:</label>
            <select id="categoryFilter">
                <option value="">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <a href="form.html" class="btn">+ Add New Record</a>

        <div class="center-table">
            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>PIC</th>
                        <th>Service</th>
                        <th>Company/Act</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Prepare the SQL statement
                    $stmt = $connection->prepare("SELECT category, pic, service, company, filename FROM form WHERE user_id = ?");
                    if (!$stmt) {
                        die("Prepare failed: " . $connection->error);
                    }

                    // Bind parameters
                    if (!$stmt->bind_param("i", $user_id)) {
                        die("Bind failed: " . $stmt->error);
                    }

                    // Execute the statement
                    if (!$stmt->execute()) {
                        die("Execution failed: " . $stmt->error);
                    }

                    // Get the result
                    $result = $stmt->get_result();

                    // Define the path to the documents folder
                    $documentPath = "uploads/";

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["category"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["pic"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["service"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["company"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["filename"]) . "</td>";

                            // Check if 'filename' exists before accessing it
                            if (!empty($row['filename'])) {
                                echo "<td><a href='view.php?filename=" . htmlspecialchars($row['filename']) . "'>View</a></td>";
                            } else {
                                echo "<td>Not available</td>";
                            }


                            echo "</tr>";
                        }
                    }

                    // Close statements and connection
                    $stmt->close();
                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
