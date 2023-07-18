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
    $insuranceID = $_GET['ID'];

    // Query to fetch the insurance record based on the provided ID
    $query = "SELECT * FROM installment_document_system.insurance WHERE company_id = $insuranceID";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful and if the record exists
    if ($result && mysqli_num_rows($result) > 0) {
        $insuranceData = mysqli_fetch_assoc($result);
    } else {
        // Redirect to the insurance list page if the record is not found
        header("location: insurance.php");
        exit;
    }
} else {
    // Redirect to the insurance list page if the ID parameter is not provided
    header("location: insurance.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $logo = $_POST['logo'];
    $company_name = $_POST['company_name'];
    $company_shortname = $_POST['company_shortname'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    // Update the insurance record in the database
    $updateQuery = "UPDATE installment_document_system.insurance SET 
                    logo = '$logo', company_name = '$company_name', company_shortname = '$company_shortname',
                    contact_number = '$contact_number',address = '$address',email = '$email',website = '$website',status = '$status' WHERE company_id = $insuranceID";

    // Execute the update query
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // Redirect to the insurance list page with a success message
        header("location: insurance.php?message=edit_success");
        exit;
    } else {
        // Redirect to the insurance list page with an error message
        header("location: insurance.php?message=edit_error");
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
        <h1>Edit Insurance</h1>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header card-navy card-outline">
            <div align="right">
                <a href="insurance.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <br>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ID=' . $insuranceID; ?>" method="POST">
                <div class="form-group">
                    <label for="company_name">ชื่อบริษัท</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" required value="<?php echo $insuranceData['company_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="company_shortname">ชื่อย่อ</label>
                    <input type="text" class="form-control" id="company_shortname" name="company_shortname" required value="<?php echo $insuranceData['company_shortname']; ?>">
                </div>
                <div class="form-group">
                    <label for="contact_number">เบอร์ติดต่อ</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required value="<?php echo $insuranceData['contact_number']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">อีเมลล์</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo $insuranceData['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="website">เว็ปไซต์</label>
                    <input type="text" class="form-control" id="website" name="website" required value="<?php echo $insuranceData['website']; ?>">
                </div>
                <div class="form-group">
                    <label for="address">ที่อยู่</label>
                    <input type="text" class="form-control" id="address" name="address" required value="<?php echo $insuranceData['address']; ?>">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Active" <?php if ($insuranceData['status'] == 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Inactive" <?php if ($insuranceData['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
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
