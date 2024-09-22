<?php
session_start();
include("dbconn.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $user_pass = $_POST['user_pass'];

    // Use prepared statements to prevent SQL injection
    $stmt = $connection->prepare("SELECT * FROM user WHERE user_id = ? AND user_pass = ?");
    $stmt->bind_param("ss", $user_id, $user_pass);
    $stmt->execute();
    $result = $stmt->get_result();

    $bacarekod = $result->num_rows;

    if ($bacarekod == 0) {
        echo "<script type='text/javascript'>
                alert('You are not registered.');
                window.location.href = 'login.html';
              </script>";
    } else {
        $sid = $result->fetch_assoc();
        $_SESSION['user_id'] = $sid['user_id'];

        header("Location: dashboard.php");
        exit();
    }

    $stmt->close();
    $connection->close();
} else {
    echo "<script type='text/javascript'>
            alert('You have been logged out.');
            window.location.href = 'login.html';
          </script>";
}
?>


