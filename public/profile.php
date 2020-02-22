<?php
  // Restrict access
  session_start();
  if(!$_SESSION['isLogged']) {
    $_SESSION['message'] = 'Please log in';
    header("location: index.php"); 
    //die(); 
  }
  require '../private/db_conn.php';
  global $pdo;

  $query = 'SELECT account_id, username, reg_time FROM reglog.accounts' ;

  try {
    $res = $pdo->prepare($query);
    $res->execute();
  }
  catch(PDOException $e){
    throw new Exception('Database query error in showing accounts.');
  }
  $rows = $res->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logged in users</title>
</head>
<body>
  <h1>Logged In Users</h1>
  <h2><?php echo $_SESSION['message']; ?></h2>
  <a href="./index.php">Back to Home</a>
  <h3>List of registered users:</h3>
  <table>
    <thead>
      <tr>
        <th>Username</th>
        <th>ID</th>
        <th>Register Time</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $row) { ?>
        <tr>
          <td><?php echo $row['username']; ?></td>
          <td><?php echo $row['account_id']; ?></td>
          <td><?php echo $row['reg_time']; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <br>
  <a href="../private/logout.php">logout</a>
</body>
</html>