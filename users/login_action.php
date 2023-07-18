<?php
session_start(); // Start the session

if (isset($_SESSION['user_login'])) { // If already logged in
    header("location: ../index.php"); // Redirect to index.php
    exit;
}

require_once '../config/database.php';
$objCon = connectDB(); // Connect to the database

$username = mysqli_real_escape_string($objCon, $_POST['username']); // Get the username
$password = mysqli_real_escape_string($objCon, $_POST['password']); // Get the password

$strSQL = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$objQuery = mysqli_query($objCon, $strSQL);
$row = mysqli_num_rows($objQuery);

if ($row) {
    $res = mysqli_fetch_assoc($objQuery);
    $_SESSION['user_login'] = array(
        'id' => $res['user_id'],
        'username' => $res['username'],
        'level' => $res['user_type'],
        'full_name' => $res['full_name'],
        'email' => $res['email'],
        'picture' => $res['picture'],
        'phone_number' => $res['phone_number'],
        'address' => $res['address']
    );

    if ($res['user_type'] == 'admin') {
        header("Location: ../index.php");
    } elseif ($res['user_type'] == 'manager'){
        header("Location: ../insurance.php");
    }elseif ($res['user_type'] == 'user'){
        header("Location: ../doc.php");
    }
    exit;
} else {
    echo '<script>alert("Username or password is incorrect!");window.location="../login.php";</script>';
}
?>
