<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
// Check if the form is submitted
if (isset($_POST['product_name']) && isset($_POST['description']) && isset($_POST['status']) && isset($_POST['fee'])) {
    // Get the form data
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $fee = $_POST['fee'];

    // Include the database connection file
    include '../config/database.php';
    $connection = connectDB();

    // Prepare the SQL query
    $query = "INSERT INTO products (product_name, description, status, fee) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $statement = mysqli_prepare($connection, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($statement, "ssss", $product_name, $description, $status, $fee);

    // Execute the statement
    if (mysqli_stmt_execute($statement)) {
        // Success message using SweetAlert2
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    icon: "success",
                    title: "Product added successfully",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function(result) {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location = "../package.php";
                    }
                });
            }, 500);
        </script>';
    } else {
        // Error message using SweetAlert2
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    icon: "error",
                    title: "An error occurred",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function(result) {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location = "../package.php";
                    }
                });
            }, 500);
        </script>';
    }

    // Close the statement and database connection
    mysqli_stmt_close($statement);
    mysqli_close($connection);
}
?>
