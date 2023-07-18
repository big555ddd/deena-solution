<?php
// Get the file name from the query parameter
$fileName = $_GET['file'];

// Set the path to the PDF file
$pdfFilePath = 'pdf_files/' . $fileName . '.pdf';

// Set the appropriate headers for the file download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $fileName . '.pdf"');
header('Content-Length: ' . filesize($pdfFilePath));

// Output the file content
readfile($pdfFilePath);
