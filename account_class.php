<?php

class Account {
    // <-- Class properties -->

    // The ID of logged in account (or NULL if there is no logged in account)
    private $id;

    // The ID of logged in account (or NULL if there is no logged in account)
    private $name;

    // True if user is authenticated, False otherwise.
    private $authenticated;

    // <-- Public class methods -->

    // Add a new account to the system and returns its ID ( the account_id)
    // column of the accounts table )
    public function addAccount( string $name, string $passwd):int 
    {
        global $pdo ;

        $name = trim($name);
        $passwd = trim($passwd);

        // Check if password is valid, if not throws new Exception.
        if (!$this->isNameValid($name)) {
            throw new Exception('Invalid username');
        }

        if (!$this->isPasswdValid($passwd)) {
            throw new Exception('Invalid password');
        }

        if (!is_null($this->getIdFromName($name))) {
            throw new Exception('Username not available');
        }

        // If it succeed, add the new account.

        // Insert query template

        $query = 'INSERT INTO authentication.accounts (account_name, account_passwd) VALUES (:name, :passwd)';

        // Password hash
        $hash = password_hash($passwd, PASSWORD_DEFAULT);

        // values array for PDO
        $values = array(':name' => $name, ':passwd' => $hash);

        // Execute the query
        try {
            $res = $pdo -> prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        return $pdo->lastInsertId();
    }

    // A sanitization check on account's username 
    public function isNameValid(string $name): bool {
        $valid = TRUE;

        $len = mb_strlen($name);

        if ($len < 8 OR $len > 16) {
            $valid = FALSE;
        }

        return $valid;
    }

    // A sanitization check on accoun's password
    public function isPasswdValid(string $passwd): bool {
        $valid = TRUE;

        $len = mb_strlen($passwd);

        if ($len < 8 OR $len > 16) {
            $valid = FALSE;
        }

        return $valid;
    }

    public function getIdFromName(string $name): ?int {
        global $pdo;

        // Since this method is public we check the name again here

        if (!$this->isNameValid($name)) {
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

    // Edit an account (selected by its ID).
    public function editAccount(int $id, string $name, string $passwd, bool $enabled) {
        
        global $pdo ;

        $name = trim($name);
        $passwd = trim($passwd);

        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account id');
        }

        // Check if password is valid, if not throws new Exception.
        if (!$this->isNameValid($name)) {
            throw new Exception('Invalid username');
        }

        if (!$this->isPasswdValid($passwd)) {
            throw new Exception('Invalid password');
        }

        // Check if an account having the same name already exists.
        
        $idFromName = $this->getIdFromName($name);

        if(!is_null($idFromName) AND ($idFromName != $id)) {
            throw new Exception('Username already used');
        }

        // Finally edit the account
        $query = 'UPDATE authentication.accounts SET account_name = :name, account_passwd = :passwd, account_enabled = :enabled WHERE account_id = :id';

        $hash = password_hash($passwd, PASSWORD_DEFAULT);

        $intEnabled = $enabled ? 1 : 0 ;

        $values = array(':name' => $name, ':passwd' => $hash, ':enabled' => $intEnabled, ':id' => $id);

        // Execute the query
        try {
            $res = $pdo -> prepare($query);
            $res -> execute($values);
        } catch(PDOException $e) {
            throw new Exception('Database query error');
        }
    }

    public function isIdValid(int $id): bool {
    
        $valid = TRUE;

        if ($id < 1 OR $id > 1000000) {
            $valid = FALSE;
        }

        return $valid;

    }

    // Delete an account (Selected by its id)
    public function deleteAccount(int $id) {
        global $pdo;

        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }

        $query = 'DELETE FROM authentication.accounts WHERE account_id = :id';

        $values = array(':id' => $id) ;

        try {
            $res=$pdo->prepare($query);
            $res->execute($values);
        } catch(PDOException $e) {
            throw new Exception('Database query error.');
        }

        // Delete the sessions related to the account.
        $query = 'DELETE FROM authentication.accounts_sessions WHERE account_id = :id';

        $values = array(':id' => $id) ;

        try {
            $res=$pdo->prepare($query);
            $res->execute($values);
        } catch(PDOException $e) {
            throw new Exception('Database query error.');
        }

    }

    public function login(string $name, string $passwd): bool {
        global $pdo;

        // Trim the name and passwd.
        $name = trim($name);
        $passwd = trim($passwd);

        if(!$this->isNameValid($name)){
            return FALSE;
        }

        if(!$this->isPasswdValid($passwd)){
            return FALSE;
        }

        $query = 'SELECT * FROM authentication.accounts WHERE (account_name = :name) AND (account_enabled = 1)' ;

        $values = array(':name' => $name);

        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        }
        catch(PDOException $e){
            throw new Exception('Database query error.');
        }

        $row = $res->fetch(PDO::FETCH_ASSOC);

        if(is_array($row)){
            if(password_verify($passwd, $row['account_passwd'])){
                // Authentication succeeded, set the class properties(id & name).
                $this->id = intval($row['account_passwd'], 10);
                $this->name = $name;
                $this->authenticated = TRUE;

                $this->registerLoginSession();

                return TRUE;
            }
        }

        return FALSE;
    }

    private function registerLoginSession(){
        global $pdo;

        if(session_status() == PHP_SESSION_ACTIVE){
            /*
                Use a REPLACE statement to:
                - insert a new row with the session id, if it doesn't exist or ... 
                - update the row having the session id if it does exist.
            */

            $query = 'REPLACE INTO authentication.account_sessions (session_id, account_id, login_time) VALUES (:sid, :accountId, NOW()) ';
            
            $values = array(':sid' => session_id(), 'accountId' => $this->id);

            try{
                $res = $pdo->prepare($query);
                $res->execute($values);
            }
            catch(PDOException $e) {
                throw new Exception('Database query error');
            }
        }
    }

    // Login using sessions
    public function sessionLogin(): bool{
        global $pdo;

        if(session_status() == PHP_SESSION_ACTIVE){
            $query=
            'SELECT * FROM authentication.account_sessions WHERE (account_sessions.id = :sid)' . 
            'AND (account_sessions.login_time >= (NOW() - INTERVAL 7 DAY)) AND (account_sessions.account_id = accounts.accounts_id)' . 
            'AND (accounts.account_enabled = 1)';

            $values = array(':sid' => session_id());

            try{
                $res = $pdo->prepare($query);
                $res->execute($values);
            }
            catch(PDOException $e) {
                throw new Exception('Database query error');
            }

            $row = $res->fetch(PDO::FETCH_ASSOC);

            if(is_array($row)){
                $this->id = intval($row['account_id'], 10);
                $this->name = $row['account_name'];
                $this->authenticated = TRUE;

                return TRUE;
            }
        }
        return FALSE;
    }

    public function logout(){
        global $pdo;

        // If there is no logged in user do nothing.
        if(is_null($this->id)){
            return;
        }

        // Reset the account related properties
        $this->id = NULL;
        $this->name = NULL;
        $this->authenticated = FALSE;

        // If there is an open session, remove it from the account_sessions table. 
        if(session_status() == PHP_SESSION_ACTIVE){

            $query = 'DELETE FROM authentication.account_sessions WHERE (session_id = :sid)';

            $values = array(':sid' => session_id());

            try{
                $res = $pdo->prepare($query);
                $res->execute($values);
            }
            catch(PDOException $e) {
                throw new Exception('Database query error');
            }
        }
    }
    // Getter function for $authenticated property.
    public function isAuthenticated(): bool {
        return $this->authenticated;
    }

    // Close all account's open sessions except for this one.
    // aka logout from all other devices

    public function closeOtherSessions(){
        global $pdo;

        // If there is no logged in user do nothing.
        if(is_null($this->id)){
            return;
        }

        // If there is an open session, remove it from the account_sessions table. 
        if(session_status() == PHP_SESSION_ACTIVE){

            $query = 'DELETE FROM authentication.account_sessions WHERE (session_id != :sid)' . 
            'AND (account_id = :account_id)';

            $values = array(':sid' => session_id(), ':account_id' => $this->id);

            try{
                $res = $pdo->prepare($query);
                $res->execute($values);
            }
            catch(PDOException $e) {
                throw new Exception('Database query error');
            }
        }
    }
}