<?php

  $host = 'localhost';
  $user = 'admin';
  $passwd = 'R1224161212n';
  $dbname = 'reglog';
  $pdo = NULL;
  //data source name
  $dsn = 'mysql:host=' . $host . '; dbname=' . $dbname;

  try {
      $pdo = new PDO($dsn, $user, $passwd);
      /* Enable exceptions on errors */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      echo 'Database connection failed ' . $e->getMessage();
      die();
  }

