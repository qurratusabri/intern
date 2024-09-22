<?php

include('dbconn.php');

// Check if 'filename' is set 
if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    
    // Fetch the data from the 'form' table where 'filename' matches
    $query = mysqli_query($connection, "SELECT * FROM form WHERE filename='$filename'");
    
    // Check if the query returned any result
    if ($row = mysqli_fetch_assoc($query)) {
        // Continue with the form
    } else {
        echo "No file found with the given filename.";
        exit;
    }
} else {
    echo "No filename specified.";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EDIT FORM</title>
  <link rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
  </style>
</head>
<body>
    <form method="POST" action="updateForm.php?filename=<?php echo $filename; ?>">
    <div class="container">
      <h1 style="color:white;">Edit Form</h1>
      <br>
      <br>
      <div class="input-box">
        <label>PIC:</label>
        <input type="text" value="<?php echo $row['pic']; ?>" name="pic" >
      </div>
      <div class="input-box">
        <label>Services:</label>
        <input type="text" value="<?php echo $row['service']; ?>" name="service" >
      </div>
      <div class="input-box">
        <label>company:</label>
        <input type="text" value="<?php echo $row['company']; ?>" name="company" >
      </div>
      <div class="input-box">
        <label>Start Date:</label>
        <input type="text" value="<?php echo $row['start']; ?>" name="start" >
      </div>
      <div class="input-box">
        <label>End Date:</label>
        <input type="text" value="<?php echo $row['endDate']; ?>" name="endDate" >
      </div>
      <div class="input-box">
        <label>SQFT:</label>
        <input type="text" value="<?php echo $row['sqft']; ?>" name="sqft" >
      </div>
      <div class="input-box">
        <label>Rental:</label>
        <input type="text" value="<?php echo $row['rent']; ?>" name="rent" >
      </div>
      <div class="input-box">
        <label>Remarks:</label>
        <input type="text" value="<?php echo $row['remarks']; ?>" name="remarks" >
      </div>
      <div class="input-box">
        <label>Months  Left Before Ends:</label>
        <input type="text" value="<?php echo $row['monthsLeft']; ?>" name="monthsLeft" >
      </div>
      <div class="input-box">
        <label>Document:</label>
        <input type="text" value="<?php echo $row['filename']; ?>" name="filename" >
      </div>

      <br>
      <div class="form-field">
        <button class="button" type="button" onclick="window.history.back()" style="color:black;">BACK</button>
        <button class="button" type="submit" name="submit" style="color:black;">CONFIRM</button>
      </div>
    </div>
</form>
</body>
</html>
