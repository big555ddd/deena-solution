<?php
session_start();
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}

include 'config/database.php';
$connection = connectDB();

// Check if the ID parameter is provided in the URL
if (isset($_GET['ID'])) {
    $userID = $_GET['ID'];

    // Delete the user record from the database
    $deleteQuery = "DELETE FROM users WHERE user_id = $userID";

    // Execute the delete query
    $deleteResult = mysqli_query($connection, $deleteQuery);

    if ($deleteResult) {
        // Redirect to the user list page with a success message
        header("location: index.php?message=delete_success");
        exit;
    } else {
        // Redirect to the user list page with an error message
        header("location: index.php?message=delete_error");
        exit;
    }
} else {
    // Redirect to the user list page if the ID parameter is not provided
    header("location: index.php");
    exit;
}

// Close the database connection
mysqli_close($connection);
?>
