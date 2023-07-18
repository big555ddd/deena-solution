<?php
session_start(); // Start the session

require_once 'config/database.php';
require_once 'LineLogin.php';

$objCon = connectDB(); // Connect to the database

$lineLogin = new LineLogin();

// Check if the authorization code and state are set in the callback URL
if (isset($_GET['code']) && isset($_GET['state'])) {
    $code = $_GET['code'];
    $state = $_GET['state'];

    // Verify the state to prevent CSRF attacks
    if ($_SESSION['state'] === $state) {
        // Get the token using the authorization code
        $token = $lineLogin->token($code, $state);

        // Check if the token is valid
        if ($token && isset($token->access_token)) {
            // Get the user profile using the access token
            $profile = $lineLogin->profileFormIdToken($token);

            // Check if the user exists in the database
            $userExists = $lineLogin->checkUserExistsInDatabase($profile);

            if (!$userExists) {
                // Save the user to the database and get the saved name
                $name = $lineLogin->saveUserToDatabase($profile);
            } else {
                // User already exists, use the retrieved name
                $name = mysqli_real_escape_string($objCon, $profile->name);
            }

            // Retrieve user data from the database based on the provided name
            $query = "SELECT * FROM users WHERE full_name = '$name'";
            $result = mysqli_query($objCon, $query);

            // Proceed with the remaining logic and redirect based on user type

            // Example:
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Set the user session data
                $_SESSION['user_login'] = array(
                    'id' => $row['user_id'],
                    'username' => $row['username'],
                    'level' => $row['user_type'],
                    'full_name' => $row['full_name'],
                    'email' => $row['email'],
                    'picture' => $profile->picture, // Include the picture field from the profile
                    'phone_number' => $row['phone_number'],
                    'address' => $row['address']
                );
            
                // Redirect based on user type
                if ($row['user_type'] == 'admin') {
                    header("Location: index.php");
                } elseif ($row['user_type'] == 'manager') {
                    header("Location: insurance.php");
                } elseif ($row['user_type'] == 'user') {
                    header("Location: doc.php");
                }
            
                exit;
            } else {
                // User not found in the database, handle the error or redirect accordingly
                echo '<script>alert("User not found in the database!");window.location="login.php";</script>';
                exit;
            }
            
        } else {
            // Error in getting the token, handle the error or redirect accordingly
            echo '<script>alert("Error in getting the token!");window.location="login.php";</script>';
            exit;
        }
    } else {
        // Invalid state parameter, handle the error or redirect accordingly
        echo '<script>alert("Invalid state parameter!");window.location="login.php";</script>';
        exit;
    }
} else {
    // Authorization code and state not found, handle the error or redirect accordingly
    echo '<script>alert("Authorization code and state not found!");window.location="login.php";</script>';
    exit;
}
