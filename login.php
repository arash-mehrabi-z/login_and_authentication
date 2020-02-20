<?php

  //Check For Submit
	if (filter_has_var(INPUT_POST, 'submit')) {
    /* Start the PHP Session */
    session_start();
  
    /* Include the database connection file (remember to change the connection parameters) */
    require './db_inc.php';
  
    /* Include the Account class file */
    require './account_class.php';
  
    /* Create a new Account object */
    $account = new Account();
  
    try
    {
      $newId = $account->addAccount($_POST['username'], $_POST['password']);
    }
    catch (Exception $e)
    {
      echo $e->getMessage();
      die();
    }
  
    echo 'The new account ID is ' . $newId;
    
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method="POST">

    <input type = "text" name = "username" placeholder = "username" 
      required autofocus></br>
    <input type = "password" name = "password" placeholder = "password" required>
    <br>
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
  </form>
</body>
</html>