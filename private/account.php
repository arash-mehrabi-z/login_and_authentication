<?php
class Account {
  public static function addAccount(string $username, string $password) {
    global $pdo ;

    $name = trim($username);
    $passwd = trim($password);

    // Check if password is valid, if not throws new Exception.
    if (!Account::isNameValid($name)) {
        throw new Exception('Invalid username');
    }

    if (!Account::isPasswdValid($passwd)) {
        throw new Exception('Invalid password');
    }

    if (!is_null(Account::getIdFromName($name))) {
        throw new Exception('Username not available. We have an account with the same username.');
    }

    // If it succeed, add the new account.

    // Insert query template

    $query = 'INSERT INTO reglog.accounts (username, password) VALUES (:name, :passwd)';

    // Password hash
    $hash = password_hash($passwd, PASSWORD_DEFAULT);

    // values array for PDO
    $values = array(':name' => $name, ':passwd' => $hash);

    // Execute the query
    try {
        $res = $pdo -> prepare($query);
        $res->execute($values);
    } 
    catch (PDOException $e) {
        throw new Exception('Database query error in adding new account.');
    }

    return $pdo->lastInsertId();
  } //end of addAccount method

  // A sanitization check on account's username 
  public static function isNameValid(string $name): bool {
    $valid = TRUE;

    $len = mb_strlen($name);

    if ($len < 8 OR $len > 16) {
        $valid = FALSE;
    }

    return $valid;
  }

  // A sanitization check on account's password
  public static function isPasswdValid(string $passwd): bool {
    $valid = TRUE;

    $len = mb_strlen($passwd);

    if ($len < 8 OR $len > 16) {
      $valid = FALSE;
    }

    return $valid;
  }

  public static function getIdFromName(string $name): ?int {
    global $pdo;

    // Since this method is public we check the name again here

    if (!Account::isNameValid($name)) {
        throw new Exception('Invalid name');
    }

    $id = NULL;

    // Search the id on the db.
    $query = 'SELECT account_id FROM authentication.accounts WHERE (account_name = :name)';
    $values = array(':name' => $name);

    try {
        $res = $pdo -> prepare($query);
        $res -> execute($values);
    } catch (PDOException $e) {
        throw new Exception('Database query error');
    }

    $row = $res -> fetch(PDO::FETCH_ASSOC);

    if(is_array($row)) {
        $id = intval($row['account_id'], 10);
    }

    return $id;
  }

  public static function login(string $username, string $password): bool {
    global $pdo;

    // Trim the name and passwd.
    $name = trim($username);
    $passwd = trim($password);

    if(!Account::isNameValid($name)){
        return FALSE;
    }

    if(!Account::isPasswdValid($passwd)){
        return FALSE;
    }

    $query = 'SELECT * FROM reglog.accounts WHERE (username = :name) AND (account_enabled = 1)' ;

    $values = array(':name' => $name);

    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch(PDOException $e){
        throw new Exception('Database query error in log in.');
    }

    $row = $res->fetch(PDO::FETCH_ASSOC);

    if(is_array($row)){
      if(password_verify($passwd, $row['password'])){
        // Authentication succeeded, set the class properties(id & name).
        // $this->id = intval($row['id'], 10);
        // $this->name = $name;
        // $this->authenticated = TRUE;
        Account::registerLoginSession($row['account_id']);

        return TRUE;
      }
    }
    return FALSE;
  }

  private static function registerLoginSession(int $Id){
    global $pdo;

    if(session_status() == PHP_SESSION_ACTIVE){
      /*
        Use a REPLACE statement to:
        - insert a new row with the session id, if it doesn't exist or ... 
        - update the row having the session id if it does exist.
      */

      $query = 'REPLACE INTO reglog.account_sessions (session_id, account_id, login_time) VALUES (:sid, :accountId, NOW()) ';
          
      $values = array(':sid' => session_id(), 'accountId' => $Id);

      try{
        $res = $pdo->prepare($query);
        $res->execute($values);
      }
      catch(PDOException $e) {
        throw new Exception('Database query error in register login session');
      }
    }
  }


} //end of class
?>