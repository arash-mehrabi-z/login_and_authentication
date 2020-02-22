<?php
  session_start();
  $_SESSION['isLogged'] = FALSE;
  $_SESSION['message'] = 'You have successfully logged out';
  header('location: ../');