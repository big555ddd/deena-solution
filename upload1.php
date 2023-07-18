<?php
include_once('config/database.php');

// Check if the form is submitted
if (isset($_POST['upload'])) {
    // Get the ID
    $id = $_POST['id'];

    // Check if a file is uploaded
    if (!empty($_FILES['pdf']['name'])) {
        // Get the uploaded PDF file
        $pdf = $_FILES['pdf'];

        // Database connection
        $conn = connectDB();

        // Check if the file was uploaded without any error
        if ($pdf['error'] === UPLOAD_ERR_OK) {
            // Retrieve file information
            $fileName = $pdf['name'];
            $tmpFilePath = $pdf['tmp_name'];

            // Read the content of the PDF file
            $pdfContent = file_get_contents($tmpFilePath);

            // Prepare the query to update the database
            $updateQuery = "UPDATE installment SET status = 1, pdf_signed = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $pdfContent, $id);

            // Execute the query
            if ($stmt->execute()) {
                // File upload and database update successful
                echo "File uploaded and database updated successfully.";
            } else {
                // Error occurred while updating the database
                echo "Error updating the database.";
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            // Error occurred while uploading the file
            echo "Error uploading file: " . $pdf['error'];
        }

        // Close the database connection
        $conn->close();
    } else {
        // No file uploaded
        echo "No file uploaded.";
    }
}
?>
