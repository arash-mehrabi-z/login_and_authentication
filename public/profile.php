<?php
  // Restrict access
  session_start();
  if(!$_SESSION['isLogged']) {
    $_SESSION['message'] = 'Please log in';
    header("location: index.php"); 
    die(); 
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logged in users</title>
</head>
<body>
  <h2>Logged In Users</h2>
  <p><?php echo $_SESSION['message']; ?></p>
  <a href="./index.php">Back to Home</a>

</body>
</html>