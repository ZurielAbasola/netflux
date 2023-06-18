<?php
class Account {
    private $con;
    private $errorArray = array();

    // Constructor to receive the connection.
    public function __construct($con) {
        $this->con = $con;
    }

    // Register function to receive the values from the form.
    // The values are passed as parameters.
    // The function returns true if the registration was successful.
    // The function returns false if the registration was unsuccessful.
    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
        // Call the validation methods.
        $this->validateFirstName($fn);
        $this->validateLastName($ln); 
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        // Check if the error array is empty.
        if(empty($this->errorArray)) {
            // Insert into database.
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }

        return false;
    }

    // Login function to receive the values from the form.
    // The values are passed as parameters.
    // The function returns true if the login was successful.
    // The function returns false if the login was unsuccessful.
    public function insertUserDetails($fn, $ln, $un, $em, $pw) {
        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password)
                                        VALUES (:fn, :ln, :un, :em, :pw)");
        $query->bindValue(":fn", $fn); 
        $query->bindValue(":ln", $ln);
        $query->bindValue(":un", $un);
        $query->bindValue(":em", $em);
        $query->bindValue(":pw", $pw); 

        return $query->execute();
    }

    private function validateFirstName($fn) {
        // Check if the length of the first name is between 2 and 25 characters.
        if(strlen($fn) < 2 || strlen($fn) > 25) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }
    
    private function validateLastName($ln) {
        // Check if the length of the first name is between 2 and 25 characters.
        if(strlen($ln) < 2 || strlen($ln) > 25) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }
    
    private function validateUsername($un) {
        // Check if the length of the first name is between 2 and 25 characters.
        if(strlen($un) < 2 || strlen($un) > 25) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        // Create a query to check if the username already exists.
        // Use a prepared statement to prevent SQL injection.
        // The :un is a placeholder.
        // The bindValue() method binds the placeholder to the actual value.
        // The execute() method executes the query.
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindValue(":un", $un);

        $query->execute();

        // Check if the query returned any rows.
        // If it did, then the username already exists.
        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }

    private function validateEmails($em, $em2) {
        if($em != $em2) {
            array_push($this->errorArray, Constants::$emailsDontMatch);
            return;
        }

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em", $em);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    private function validatePasswords($pw, $pw2) {
        if($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDontMatch);
            return;
        }

        if(strlen($pw) < 2 || strlen($pw) > 25) {
            array_push($this->errorArray, Constants::$passwordsLength);
        }
    }
 
    public function getError($error) {
        // Check if the error exists in the error array.
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }
}
?>