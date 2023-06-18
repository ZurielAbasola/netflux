<?php
class Account {
    // Create a private variable to store the connection.
    private $con;
    private $errorArray = array();

    // Create a constructor to receive the connection.
    public function __construct($con) {
        $this->con = $con;
    }

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
        // Call the validation methods.
        $this->validateFirstName($fn);
        $this->validateLastName($ln); 
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
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
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em", $em);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }
 
    public function getError($error) {
        // Check if the error exists in the error array.
        if(in_array($error, $this->errorArray)) {
            return $error;
        }
    }
}
?>