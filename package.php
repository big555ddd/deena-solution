<?php
session_start();
$menu = "package";
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}

include 'config/database.php';
$connection = connectDB();

// Check if the ID parameter is provided in the URL for deletion
if (isset($_GET['deleteID'])) {
    $productsID = $_GET['deleteID'];

    // Query to fetch the products record based on the provided ID
    $query = "SELECT * FROM installment_document_system.products WHERE product_id = $productsID";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful and if the record exists
    if ($result && mysqli_num_rows($result) > 0) {
        $productsData = mysqli_fetch_assoc($result);
    } else {
        // Redirect to the products list page if the record is not found
        header("location: products.php");
        exit;
    }

    // Check if the delete confirmation form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Delete the products record from the database
        $deleteQuery = "DELETE FROM installment_document_system.products WHERE product_id = $productsID";

        // Execute the delete query
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            // Redirect to the products list page with a success message
            header("location: package.php?message=delete_success");
            exit;
        } else {
            // Redirect to the products list page with an error message
            header("location: package.php?message=delete_error");
            exit;
        }
    }
}

// Fetch all products records from the database
$query = "SELECT product_id,product_name,description,status,fee FROM installment_document_system.products";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query was successful
if ($result) {
    $productsList = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
        <h1>Product</h1>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header card-navy card-outline">
            <div align="right">
                <!-- Add Product Button -->
<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addProductModal">
    <i class="fa fa-user-plus"></i> Add Product
</button>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="products/add_products.php" method="POST">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="fee">fee</label>
                        <input type="number" class="form-control" id="fee" name="fee" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
        <br>
        <div class="card-body p-1">
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($_GET['message']) && $_GET['message'] == 'delete_success') { ?>
                        <div class="alert alert-success">
                            products record has been deleted successfully.
                        </div>
                    <?php } elseif (isset($_GET['message']) && $_GET['message'] == 'delete_error') { ?>
                        <div class="alert alert-danger">
                            Error occurred while deleting the products record.
                        </div>
                    <?php } ?>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ชื่อผลิตภัณฑ์</th>
                                <th>ภาษี</th>
                                <th>คำอธิบาย</th>
                                <th>สถานะ</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productsList as $products) { ?>
                                <tr>
                                    <td><?php echo $products['product_id']; ?></td>
                                    <td><?php echo $products['product_name']; ?></td>
                                    <td><?php echo $products['fee']; ?></td>
                                    <td><?php echo $products['description']; ?></td>
                                    <td><?php echo $products['status']; ?></td>
                                    <td>
                                        <!-- Edit Button -->
<a href="edit_products.php?ID=<?php echo $products['product_id']; ?>&ACTION=EDIT" class="btn btn-success">
    <i class="fas fa-edit"></i> Edit
</a>

<!-- Delete Button -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteConfirmationModal<?php echo $products['product_id']; ?>">
    <i class="fas fa-trash"></i> Delete
</button>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteConfirmationModal<?php echo $products['product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete products Record</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this products record?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?deleteID=' . $products['product_id']; ?>" method="POST">
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
