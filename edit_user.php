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

    // Query to fetch the user record based on the provided ID
    $query = "SELECT * FROM users WHERE user_id = $userID";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful and if the record exists
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        // Redirect to the user list page if the record is not found
        header("location: index.php");
        exit;
    }
} else {
    // Redirect to the user list page if the ID parameter is not provided
    header("location: index.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    // Update the user record in the database
    $updateQuery = "UPDATE users SET 
                    full_name = '$fullName', email = '$email', phone_number = '$phoneNumber',
                    username = '$username', password = '$password', user_type = '$userType'
                    WHERE user_id = $userID";

    // Execute the update query
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // Redirect to the user list page with a success message
        header("location: index.php?message=edit_success");
        exit;
    } else {
        // Redirect to the user list page with an error message
        header("location: index.php?message=edit_error");
        exit;
    }
}

// Close the database connection
mysqli_close($connection);
?>
<?php include("header.php"); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <h1>Edit User</h1>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header card-navy card-outline">
            <div align="right">
                <a href="index.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <br>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ID=' . $userID; ?>" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required value="<?php echo $userData['full_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo $userData['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required value="<?php echo $userData['phone_number']; ?>">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?php echo $userData['username']; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required value="<?php echo $userData['password']; ?>">
                </div>
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select class="form-control" id="user_type" name="user_type" required>
                        <option value="user" <?php if ($userData['user_type'] == 'user') echo 'selected'; ?>>User</option>
                        <option value="manager" <?php if ($userData['user_type'] == 'manager') echo 'selected'; ?>>Manager</option>
                        <option value="admin" <?php if ($userData['user_type'] == 'admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- /.content -->
<?php include('footer.php'); ?>
