<?php
session_start();
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}

include '../config/database.php';
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
    // Delete the insurance record from the database
    $deleteQuery = "DELETE FROM installment_document_system.insurance WHERE company_id = $insuranceID";

    // Execute the delete query
    $deleteResult = mysqli_query($connection, $deleteQuery);

    if ($deleteResult) {
        // Redirect to the insurance list page with a success message
        header("location: ../insurance.php?message=delete_success");
        exit;
    } else {
        // Redirect to the insurance list page with an error message
        header("location: ../insurance.php?message=delete_error");
        exit;
    }
}

// Close the database connection
mysqli_close($connection);
?>
<?php include("../header.php"); ?>
<!-- Include SweetAlert library -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <h1>Delete Insurance</h1>
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
            <div class="alert alert-danger">
                <strong>Warning!</strong> You are about to delete the insurance record. This action cannot be undone. Are you sure you want to proceed?
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ID=' . $insuranceID; ?>" method="POST">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $insuranceData['company_name']; ?>" readonly>
                </div>
                <!-- Add SweetAlert confirmation dialog on form submission -->
                <button type="submit" class="btn btn-danger" onclick="confirmDelete(event)"><i class="fa fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</section>
<!-- /.content -->
<?php include('../footer.php'); ?>
<!-- JavaScript code to display SweetAlert confirmation dialog -->
<script>
function confirmDelete(event) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            // If user confirms deletion, submit the form
            event.target.closest("form").submit();
        }
    });
}
</script>
