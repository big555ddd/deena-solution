<?php
$menu = "button";
include("header.php");
include_once('config/database.php');

// Retrieve the ID from the URL parameter
// Retrieve the file_name from the URL parameter
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo "File name not specified.";
  exit();
}


$conn = connectDB();
$sql = "SELECT pdf_signed FROM installment WHERE id = '$id'"; // Adjust the query to fetch the desired PDF file
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $pdfContent = $row['pdf_signed'];

  // Display the PDF file
  echo '<section class="content">';
  echo '<div class="pdf-container">';
  echo '<iframe src="data:application/pdf;base64,' . base64_encode($pdfContent) . '" frameborder="0" width="100%" height="1000px"></iframe>';
  echo '</div>';
  echo '</section>';
} else {
  echo "No PDF file found.";
}

$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script type="text/javascript" src="asset/js/jquery.signature.min.js"></script>
  <link rel="stylesheet" type="text/css" href="asset/css/jquery.signature.css">

  <style>
    .kbw-signature {
      width: 300px;
      height: 100px;
    }

    #sig canvas {
      width: 100% !important;
      height: auto;
    }
  </style>

</head>
</html>