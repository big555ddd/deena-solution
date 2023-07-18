<?php
session_start();
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}

include 'config/database.php';
$connection = connectDB();

// Retrieve the user ID from the session
$userID = $_SESSION['user_login']['id'];

// Query to fetch the user record based on the user ID
$query = "SELECT * FROM users WHERE user_id = '$userID'";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query was successful and if the record exists
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Redirect to the login page if the user record is not found
    header("location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];

    // Check if a new picture file is uploaded
    if (!empty($_FILES['picture']['tmp_name'])) {
        // Retrieve the file information
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define the target directory to store the uploaded picture
        $targetDirectory = "upload/"; // Replace with your target directory

        // Generate a unique file name for the picture
        $newFileName = uniqid() . '.' . $fileExtension;

        // Set the target path for the uploaded picture
        $targetPath = $targetDirectory . $newFileName;

        // Check if the file is moved to the target directory successfully
        if (move_uploaded_file($fileTmpPath, $targetPath)) {
            // Update the user's picture in the database
            $updateQuery = "UPDATE users SET picture = '$targetPath' WHERE user_id = '$userID'";
            $updateResult = mysqli_query($connection, $updateQuery);

            if ($updateResult) {
                // Update the user's data in the session
                $_SESSION['user_login']['picture'] = $targetPath;
            } else {
                // Handle the error if the update query fails
                // You can redirect to an error page or show an error message
            }
        } else {
            // Handle the error if the file upload fails
            // You can redirect to an error page or show an error message
        }
    }

    // Update the user settings in the database
    $updateQuery = "UPDATE users SET 
                    full_name = '$fullName', email = '$email', phone_number = '$phoneNumber',
                    address = '$address'
                    WHERE user_id = '$userID'";

    // Execute the update query
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // Update the user data in the session
        $_SESSION['user_login']['full_name'] = $fullName;
        $_SESSION['user_login']['email'] = $email;
        $_SESSION['user_login']['phone_number'] = $phoneNumber;
        $_SESSION['user_login']['address'] = $address;

        // Redirect to the setting page with a success message
        header("location: setting.php?message=edit_success");
        exit;
    } else {
        // Redirect to the setting page with an error message
        header("location: setting.php?message=edit_error");
        exit;
    }
}

// Close the database connection
mysqli_close($connection);
?>

<?php include("header.php"); ?>
<!-- HTML code -->
<!DOCTYPE html>
<html>

<head>
    <title>Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section class="content">
        <div class="container">
            <h2 class='text-center'>Settings</h2>
            <?php
            // Check if a success message is present in the URL
            if (isset($_GET['message']) && $_GET['message'] == "edit_success") {
                echo '<div class="alert alert-success">Profile updated successfully.</div>';
            }
            // Check if an error message is present in the URL
            elseif (isset($_GET['message']) && $_GET['message'] == "edit_error") {
                echo '<div class="alert alert-danger">Error updating profile. Please try again.</div>';
            }
            ?>
            <form method="POST" action="setting.php" enctype="multipart/form-data">
                <div class="mb-3 text-center"> <!-- Added 'text-center' class for center alignment -->
                    <?php if (!empty($user['picture'])) { ?>
                        <div class="profile-picture" style="position: relative; display: inline-block;">
                            <img src="<?php echo $user['picture']; ?>" alt="Profile Picture" class="img-thumbnail" id="profileImage">
                            <button type="button" class="btn btn-link" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;" data-bs-toggle="modal" data-bs-target="#pictureModal"></button>
                        </div>
                    <?php } else { ?>
                        <div class="profile-picture">
                            <img src="assets/dist/img/avatar5.png" alt="Default Profile Picture" class="img-thumbnail" id="profileImage">
                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#pictureModal"></button>
                        </div>
                    <?php } ?>
                </div>
                <script>
                    // JavaScript code to handle click events for the image and button
                    document.addEventListener('DOMContentLoaded', function() {
                        var profileImage = document.getElementById('profileImage');
                        var pictureModalButton = document.querySelector('[data-bs-target="#pictureModal"]');

                        // Click event for the image
                        profileImage.addEventListener('click', function() {
                            pictureModalButton.click();
                        });

                        // Click event for the button
                        pictureModalButton.addEventListener('click', function(event) {
                            event.stopPropagation(); // Prevent the click event from propagating to the image
                        });
                    });
                </script>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $user['phone_number']; ?>">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address"><?php echo $user['address']; ?></textarea>
                </div>

                <!-- Picture Upload Modal -->
                <div class="modal fade" id="pictureModal" tabindex="-1" aria-labelledby="pictureModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pictureModalLabel">Upload Picture</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="picture" name="picture">
                                    <label class="input-group-text" for="picture">Choose file</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input JS -->
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>