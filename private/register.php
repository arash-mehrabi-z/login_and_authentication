<?php
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  if(!empty($username) AND !empty($password)){
    try
    {
      $newId = Account::addAccount($username, $password);
    }
    catch (Exception $e)
    {
      echo $e->getMessage();
      die();
    }

    $msg = 'The new account ID is ' . $newId;
    $_SESSION['message'] = $msg ;
    $_SESSION['isLogged'] = TRUE ;
    header('location: profile.php');
  } 
  else {
    $msg = 'Please fill in all fields.';
  }
?>