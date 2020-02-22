<?php

  session_start();
  /* Include the database connection file (remember to change the connection parameters) */
  require_once '../private/db_conn.php';

  /* Include the Account class file */
  require_once '../private/account.php';
  // Messages
  $msg = '';

  //If user has clicked on register button.
  if(filter_has_var(INPUT_POST, 'register')){
    require_once '../private/register.php';
  }
  // User has pressed login button. 
  elseif(filter_has_var(INPUT_POST, 'login')){
    require_once '../private/login.php';
  }


  if(isset($_SESSION['message'])){
    $msg = $msg . $_SESSION['message'] . '<br>';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register or Login</title>
</head>
<body>
  <h2>Register or Login</h2>
  <!-- For security issues we use htmlspecialchars -->
  <?php if($msg != '') { ?>
    <h3><?php echo $msg; ?></h3>
  <?php } ?>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
    <label for="username">Username: </label>
    <input type="text" name="username" id="username" value="<?php echo isset($_POST['username']) ? $username : ''?>" required>

    <label for="password">Password: </label>
    <input type="password" name="password" id="password" value="<?php echo isset($_POST['password']) ? $password : ''?>" required>
    <br>
    <br>
    <button type="submit" name="register">Register</button>
    <button type="submit" name="login">Login</button>
  </form>
</body>
</html>