<?php
require_once '../config/database.php';
$objCon = connectDB();

$data = $_POST;
$u_fullname = $data['u_fullname'];
$u_username = $data['u_username'];
$u_email = $data['u_email'];
$u_phone_number = $data['u_phone_number'];
$u_password = $data['u_password'];
$u_confirm_password = $data['u_confirm_password'];
$u_address = $_POST['u_address'];
$u_level = $data['u_level'];

// Check if the username already exists
$query = "SELECT * FROM users WHERE username = '$u_username'";
$result = mysqli_query($objCon, $query);
if ($u_password !== $u_confirm_password) {
    echo '<script>alert("Password and confirm password do not match"); window.location="register-v2.php";</script>';
    exit;
}
if (mysqli_num_rows($result) > 0) {
    // Username already exists
    echo '<script>alert("Username already taken");window.location="register-v2.php";</script>';
} else {
    // Username is available, insert into database
    $strSQL = "INSERT INTO Users (full_name, username, email, password, user_type, phone_number, address) 
                   VALUES ('$u_fullname', '$u_username', '$u_email', '$u_password', '$u_level', '$u_phone_number', '$u_address')";

    $objQuery = mysqli_query($objCon, $strSQL) or die(mysqli_error($objCon));
    if ($objQuery) {
        echo '<script>alert("Registration successful");window.location="../login.php";</script>';
    } else {
        echo '<script>alert("An error occurred");window.location="../register.php";</script>';
    }
}

mysqli_close($objCon);
?>
