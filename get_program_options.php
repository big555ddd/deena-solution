<?php
// Assuming you have established a database connection
include 'config/database.php';
$connection = connectDB();

// Retrieve the selected callback1 value from the AJAX request
$selectedCallback1 = $_POST['callback1'];

// Query to fetch the program options based on the selected callback1
$query = "SELECT product_name FROM products WHERE callback1 = '$selectedCallback1'";
$result = mysqli_query($connection, $query);

// Store the program options in an array
$programOptions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $programOptions[] = $row;
}

// Send the program options as a JSON response
echo json_encode($programOptions);

// Close the database connection
mysqli_close($connection);
?>
