<?php
$menu = "insurance";
session_start();
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}

include 'config/database.php';
$connection = connectDB();

// Check if the ID parameter is provided in the URL for deletion
if (isset($_GET['deleteID'])) {
    $insuranceID = $_GET['deleteID'];

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

    // Check if the delete confirmation form is submitted
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
}

// Fetch all insurance records from the database
$query = "SELECT company_id, company_name, logo, company_shortname, address, contact_number, email, website, status FROM installment_document_system.insurance";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query was successful
if ($result) {
    $insuranceList = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($connection);
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
        <h1>Insurance</h1>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header card-navy card-outline">
            <div align="right">
            <form action="insurance/add_insurance.php" method="POST" enctype="multipart/form-data">
                    <div class="modal fade" id="addInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="addInsuranceModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addInsuranceModal">Add Insurance</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="short">Short Name</label>
                                        <textarea class="form-control" id="short" name="short" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <input type="file" name="logo" class="form-control streched-link" accept="image/gif, image/jpeg, image/png" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" id="website" name="website" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addInsuranceModal"><i class="fa fa-user-plus"></i> Add Company</button>
                </form>
            </div>
        </div>
        <br>
        <div class="card-body p-1">
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($_GET['message']) && $_GET['message'] == 'delete_success') { ?>
                        <div class="alert alert-success">
                            Insurance record has been deleted successfully.
                        </div>
                    <?php } elseif (isset($_GET['message']) && $_GET['message'] == 'delete_error') { ?>
                        <div class="alert alert-danger">
                            Error occurred while deleting the insurance record.
                        </div>
                    <?php } ?>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>logo</th>
                                <th>ชื่อบริษัท</th>
                                <th>ชื่อย่อ</th>
                                <th>สถานะ</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($insuranceList as $insurance) { ?>
                                <tr>
                                    <td><?php echo $insurance['company_id']; ?></td>
                                    <td><img src="insurance/<?php echo $insurance['logo']; ?>" alt="Logo" style="width: 30px; height: 50;"></td>
                                    <td><?php echo $insurance['company_name']; ?></td>
                                    <td><?php echo $insurance['company_shortname']; ?></td>
                                    <td><?php echo $insurance['status']; ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <a href="edit_insurance.php?ID=<?php echo $insurance['company_id']; ?>&ACTION=EDIT" class="btn btn-success">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteConfirmationModal<?php echo $insurance['company_id']; ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteConfirmationModal<?php echo $insurance['company_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Insurance Record</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this insurance record?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?deleteID=' . $insurance['company_id']; ?>" method="POST">
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?php include('footer.php'); ?>
<script>
    $(function() {
        $(".datatable").DataTable();
    });
</script>
</body>

</html>