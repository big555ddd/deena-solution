<?php
require_once('tcpdf/tcpdf.php');
include_once('config/database.php');
require_once('src/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

// Retrieve the PDF file from the database
$conn = connectDB();
$data = $_POST;
$signatureData = $data['signature'];
$id = $data['myCheckbox'];
$sql = "SELECT pdf_data FROM installment WHERE file_name = '$id'";
$roundQuery = "SELECT round FROM installment WHERE file_name = '$id'";
$resultRound = $conn->query($roundQuery);
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $pdfContent = $row['pdf_data'];
  $rowRound = $resultRound->fetch_assoc();
  $round = (int)$rowRound['round'];
  // Create a temporary file to store the PDF content
  $tempFilePath = 'temp/file.pdf';
  file_put_contents($tempFilePath, $pdfContent);

  // Create a new TCPDF instance
  $pdf = new Fpdi();

  // Set the source file
  $pageCount = $pdf->setSourceFile($tempFilePath);
  // Convert the signature image data from base64 to binary format
  $signatureImage = base64_decode(explode(',', $signatureData)[1]);

  // Set the position and dimensions of the signature
  $signatureWidth = 50;  // Modify as needed
  $signatureHeight = 10;  // Modify as needed
  $pagesToAddSignature = [1, 2, 3, 4, 5, 6, 7];
  // Add the signature image to each page
  for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $templateId = $pdf->importPage($pageNo);
    $pdf->AddPage();
    $pdf->useTemplate($templateId);
    if (in_array($pageNo, [1])) {
      $signatureX = 50;  // Modify as needed
      $signatureY = 150 + ($round * 7) ;  // Modify as needed

      // Add the signature image to the page
      $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
  } else if (in_array($pageNo, [2])) {
    $signatureX = 20;  // Modify as needed
    $signatureY = 213 + (6 * 7) ;  // Modify as needed

    // Add the signature image to the page
    $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
} else if (in_array($pageNo, [3])) {
    $signatureX = 20;  // Modify as needed
    $signatureY = 212 + (6 * 7) ;  // Modify as needed

    // Add the signature image to the page
    $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
}else if (in_array($pageNo, [4])) {
  $signatureX = 55;  // Modify as needed
  $signatureY = 192 ;  // Modify as needed

  // Add the signature image to the page
  $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
} else if (in_array($pageNo, [5])) {
  $signatureX = 20;  // Modify as needed
  $signatureY = 213 + (6 * 7) ;  // Modify as needed

  // Add the signature image to the page
  $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
}else if (in_array($pageNo, [6])) {
  $signatureX = 20;  // Modify as needed
  $signatureY = 212 + (6 * 7) ;  // Modify as needed

  // Add the signature image to the page
  $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
}else if (in_array($pageNo, [7])) {
  $signatureX = 55;  // Modify as needed
  $signatureY = 192 ;  // Modify as needed

  // Add the signature image to the page
  $pdf->Image('@' . $signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
}
  }
  // Delete the temporary PDF file
  unlink($tempFilePath);
  $pdf->Output('output.pdf', 'I');
  $uniqueName = substr(uniqid(), 0, 20);
  // Output the modified PDF to the browser
  $pdfContent =$pdf->Output('output.pdf', 'S');
  $pdfFilePath = 'pdf_' . $uniqueName . '.pdf';
  file_put_contents($pdfFilePath, $pdfContent);

// Read the PDF file content
$binaryData = file_get_contents($pdfFilePath);

// Prepare the PDF file content for insertion into the database
$escapedData = $conn->real_escape_string($binaryData);
  // Use the retrieved user_id in your INSERT query
  $ipAddress = $_SERVER['REMOTE_ADDR'];
$sql = "UPDATE installment SET pdf_signed = '$escapedData', status = 1, ip = '$ipAddress' WHERE file_name = '$id'";
  if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

} else {
  echo "No PDF file found.";
}

$conn->close();
