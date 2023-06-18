<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");

$account = new Account($con);

    if(isset($_POST["submitButton"])) {
        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

        $success = $account->login($username, $password);

        // Check if the registration was successful.
        // If it was, redirect to index.php.
        if($success) {
            // Store session.
            $_SESSION["userLoggedIn"] = $username;
            
            // Redirect to index.php.
            header("Location: index.php");
        }
    }
?>
<!DOCTYPE html>
<html> 
    <head>
        <title>Welcome to Netflux</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="signInContainer">

            <div class="column">

                <div class="header">
                    <img src="assets/images/logo.png" title="Logo" alt="Site logo" />
                    <h3>Sign In</h3> 
                    <span>to continue to Netflux</span>
                </div>

                <form method="POST">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <input type="text" name="username" placeholder="Username" required>

                    <input type="password" name="password" placeholder="Password" required> 

                    <input type="submit" name="submitButton" value="SUBMIT">

                </form>

                <a href="register.php" class="signInMessage" style=
                    "font-size: 15px;
                    font-weight: 400;
                    text-decoration: none;
                ">Need an account? Sign up here!</a>

            </div>

        </div>

    </body>
</html>
 