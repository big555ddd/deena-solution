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
    $productID = $_GET['ID'];

    // Query to fetch the product record based on the provided ID
    $query = "SELECT * FROM products WHERE product_id = $productID";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful and if the record exists
    if ($result && mysqli_num_rows($result) > 0) {
        $productData = mysqli_fetch_assoc($result);
    } else {
        // Redirect to the product list page if the record is not found
        header("location: products.php");
        exit;
    }
} else {
    // Redirect to the product list page if the ID parameter is not provided
    header("location: products.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $productName = $_POST['product_name'];
    $description = $_POST['description'];
    $fee = $_POST['fee'];
    $status = $_POST['status'];

    // Update the product record in the database
    $updateQuery = "UPDATE products SET 
                    product_name = '$productName', description = '$description',fee = '$fee', status = '$status'
                    WHERE product_id = $productID";

    // Execute the update query
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // Redirect to the product list page with a success message
        header("location: products.php?message=edit_success");
        exit;
    } else {
        // Redirect to the product list page with an error message
        header("location: products.php?message=edit_error");
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
        <h1>Edit Product</h1>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header card-navy card-outline">
            <div align="right">
                <a href="package.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <br>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ID=' . $productID; ?>" method="POST">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required value="<?php echo $productData['product_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="description">fee</label>
                    <input type="number" class="form-control" id="fee" name="fee" required value="<?php echo $productData['fee']; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required><?php echo $productData['description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Active" <?php if ($productData['status'] == 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Inactive" <?php if ($productData['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
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
