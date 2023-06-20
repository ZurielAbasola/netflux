<?php
class Account {
    private $con;      // Connection variable.
    private $errorArray = array();      // Array to store the error messages.

    // Constructor to receive the connection.
    // The connection is passed as a parameter.
    // The connection is stored in the $con variable.
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
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);   // Insert the user details into the database.
        }

        return false;
    }

    // Login function to receive the values from the form.
    // The values are passed as parameters.
    // The function returns true if the login was successful.
    // The function returns false if the login was unsuccessful.
    public function login($un, $pw) {
        $pw = hash("sha512", $pw);    // Hash the password.
        
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");    // Prepare the query.
        $query->bindValue(":un", $un);      // Bind the username.
        $query->bindValue(":pw", $pw);      // Bind the password.

        $query->execute();      // Execute the query.

        // Check if the query returned 1 row.
        // If yes, the login was successful.
        // If no, the login was unsuccessful.
        if($query->rowCount() == 1) {
            return true;
        }

        // Push the error message into the error array.
        // Return false.
        array_push($this->errorArray, Constants::$loginFailed);
        return false;
    }


    // Login function to receive the values from the form.
    // The values are passed as parameters.
    // The function returns true if the login was successful.
    // The function returns false if the login was unsuccessful.
    public function insertUserDetails($fn, $ln, $un, $em, $pw) {
        $pw = hash("sha512", $pw);   // Hash the password.

        $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password) 
                                        VALUES (:fn, :ln, :un, :em, :pw)");   // Prepare the query.
        $query->bindValue(":fn", $fn);      // Bind the first name.
        $query->bindValue(":ln", $ln);      // Bind the last name.
        $query->bindValue(":un", $un);      // Bind the username.
        $query->bindValue(":em", $em);      // Bind the email.
        $query->bindValue(":pw", $pw);      // Bind the password.

        return $query->execute();       // Execute the query.
    }

    private function validateFirstName($fn) {
        // Check if the length of the first name is between 2 and 25 characters.
        if(strlen($fn) < 2 || strlen($fn) > 25) {
            array_push($this->errorArray, Constants::$firstNameCharacters);     // Push the error message into the error array.
        }
    }
    
    private function validateLastName($ln) {
        // Check if the length of the first name is between 2 and 25 characters.
        if(strlen($ln) < 2 || strlen($ln) > 25) {
            array_push($this->errorArray, Constants::$lastNameCharacters);  // Push the error message into the error array.
        }
    }
    
    private function validateUsername($un) {
        // Check if the length of the first name is between 2 and 25 characters.
        if(strlen($un) < 2 || strlen($un) > 25) {
            array_push($this->errorArray, Constants::$usernameCharacters);  // Push the error message into the error array.
            return;
        }

        // Create a query to check if the username already exists.
        // Use a prepared statement to prevent SQL injection.
        // The :un is a placeholder.
        // The bindValue() method binds the placeholder to the actual value.
        // The execute() method executes the query.
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un"); // Prepare the query.
        $query->bindValue(":un", $un);      // Bind the username.

        $query->execute();      // Execute the query.

        // Check if the query returned any rows.
        // If it did, then the username already exists.
        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);       // Push the error message into the error array.
        }
    }

    // Validate the emails.
    // Check if the emails match.
    // Check if the email is valid.
    // Check if the email is already in use.
    private function validateEmails($em, $em2) {    
        // Check if the emails match.
        if($em != $em2) {
            array_push($this->errorArray, Constants::$emailsDontMatch); // Push the error message into the error array.
            return;
        }

        // Check if the email is valid.
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);    // Push the error message into the error array.
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");  // Prepare the query.
        $query->bindValue(":em", $em);    // Bind the email.

        $query->execute();    // Execute the query.

        // Check if the query returned any rows.
        // If it did, then the email is already in use.
        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);  // Push the error message into the error array.
        }
    }

    // Validate the passwords.
    // Check if the passwords match.
    // Check if the passwords are between 2 and 25 characters.
    private function validatePasswords($pw, $pw2) {
        // Check if the passwords match.
        if($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDontMatch);  // Push the error message into the error array.
            return;
        }

        // Check if the passwords are between 2 and 25 characters.
        if(strlen($pw) < 2 || strlen($pw) > 25) {
            array_push($this->errorArray, Constants::$passwordsLength);     // Push the error message into the error array.
        }
    }

    // Get the error message.
    // The error message is passed as a parameter.
    // The function returns the error message.
    public function getError($error) {
        // Check if the error exists in the error array.
        // If it does, then return the error message.
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }
}
?>