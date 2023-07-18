<?php
$menu = "button";
include("header.php");
include_once('config/database.php');

// Retrieve the ID from the URL parameter
// Retrieve the file_name from the URL parameter
if (isset($_GET['file_name'])) {
  $file_name = $_GET['file_name'];
} else {
  echo "File name not specified.";
  exit();
}

$conn = connectDB();
$sql = "SELECT pdf_data FROM installment WHERE file_name = '$file_name'"; // Adjust the query to fetch the desired PDF file
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $pdfContent = $row['pdf_data'];

  // Display the PDF file
  echo '<section class="content">';
  echo '<div class="pdf-container">';
  echo '<iframe src="data:application/pdf;base64,' . base64_encode($pdfContent) . '" frameborder="0" width="100%" height="600px"></iframe>';
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

  <style>
    #sig {
      border: 2px;
      padding: 10px;
      border-radius: 4px;
    }

    /* Add frame effect */
    #sig canvas {
      border: 1px solid #ccc;
      box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);
      border-radius: 4px;
    }

    /* Adjust clear button style */
    #clear-btn {
      background-color: greenyellow;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 5px 10px;
      color: #333;
      cursor: pointer;
      text-decoration: none;
    }
  </style>

</head>

<body class="bg-light ">
  <form method="post" action="update_signature.php">
    <div class="container p-4 ">
      <div class="row">
        <div class="col-md-12">
          <label class="" for="">Signature:</label>
          <br />
          <div id="signature">
            <canvas id="sig-canvas" width="300" height="100"></canvas>
          </div>
          <br />
          <textarea id="signature-data" name="signature" style="display: none"></textarea>
          <div class="col-12">
            <button type="button" class="btn btn-sm btn-warning" id="clear-btn">&#x232B; Clear Signature</button>
          </div>
          <label for="myCheckbox">
            <input type="checkbox" id="myCheckbox" name="myCheckbox" value="<?php echo $file_name; ?>">
            ฉันยินยอมในการทำใบคำขอสินเชื่อค่าเบี้ยประกันภัย
          </label>
        </div>
        <div class="col-12">
          <button type="submit" id="submitButton" class="w-50 btn btn-lg btn-primary btn-block">Save Signature</button>

        </div>
      </div>
    </div>
  </form>

  <div class="container">
    <div class="row">
      <div class="col-md-12 pt-4">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1190033123418031" crossorigin="anonymous"></script>

        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1190033123418031" data-ad-slot="5335471635" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Signature Pad
      var canvas = document.querySelector('canvas');
      var signaturePad = new SignaturePad(canvas, {
        penColor: 'blue', // Set pen color to blue
        backgroundColor: 'white', // Set background color to white
        minWidth: 1, // Set minimum pen stroke width
        maxWidth: 1 // Set maximum pen stroke width
      });

      // Add frame effect
      canvas.style.border = '1px solid #ccc';
      canvas.style.boxShadow = '2px 2px 2px rgba(0, 0, 0, 0.3)';
      canvas.style.borderRadius = '4px';

      // Clear Signature
      document.getElementById('clear-btn').addEventListener('click', function() {
        signaturePad.clear();
      });

      // Save Signature
      document.getElementById('submitButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission

        var signatureData = signaturePad.toDataURL();
        document.getElementById('signature-data').value = signatureData;

        // Submit the form programmatically
        document.querySelector('form').submit();
      });
    });
  </script>

</body>

</html>