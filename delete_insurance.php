<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
    // Delete the insurance record from the database
    $deleteQuery = "DELETE FROM installment_document_system.insurance WHERE company_id = $insuranceID";

    // Execute the delete query
    $deleteResult = mysqli_query($connection, $deleteQuery);

    if ($deleteResult) {
        // Redirect to the insurance list page with a success message
        header("location: insurance.php?message=delete_success");
        exit;
    } else {
        // Redirect to the insurance list page with an error message
        header("location: insurance.php?message=delete_error");
        exit;
    }
}

// Close the database connection
mysqli_close($connection);
?>
<?php include("header.php"); ?>
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
                <strong>Warning!</strong> You are about to delete the insurance record. This action cannot be undone.
            </div>
            <!-- Add pop-up modal for delete confirmation -->
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this insurance record?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ID=' . $insuranceID; ?>" method="POST">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div align="center">
                <!-- Trigger the delete confirmation modal -->
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteConfirmationModal">Delete</button>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?php include('footer.php'); ?>
