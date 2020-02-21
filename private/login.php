<?php
  $username = $_POST['username'];
  $password = $_POST['password'];

  if(!empty($username) AND !empty($password)){
    try
    {
      $successfull = Account::login($username, $password);
    }
    catch (Exception $e)
    {
      echo $e->getMessage();
      die();
    }

    if($successfull){
      $_SESSION['isLogged'] = TRUE ;
      $_SESSION['message'] = 'You have successfully logged in' ;
      header('location: profile.php');
    }
    else {
      $_SESSION['message'] = 'Authentication failed.';
    }
  } 
  else {
    $msg = 'Please fill in all fields.';
  }
?>