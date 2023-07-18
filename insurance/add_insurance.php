<?php
session_start();
include_once '../config/database.php';
$connection = connectDB();
// File upload path
$targetDir = "upload/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $short = $_POST['short'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $status = $_POST['status'];

    $logo_path = 'upload/Logo_of_Twitter.png'; // Replace with the path to your default logo image

    if (!empty($_FILES["logo"]["name"])) {
        $logo_name = basename($_FILES["logo"]["name"]);
        $logo_tmp_name = $_FILES["logo"]["tmp_name"];
        $logo_targetFilePath = $targetDir . $logo_name;
        $logoFileType = pathinfo($logo_targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($logoFileType, $allowTypes)) {
            if (move_uploaded_file($logo_tmp_name, $logo_targetFilePath)) {
                $logo_path = $logo_targetFilePath;
            } else {
                $_SESSION['statusMsg'] = "Sorry, there was an error uploading the logo file.";
                header("location: ../insurance.php");
                exit;
            }
        } else {
            $_SESSION['statusMsg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed for the logo.";
            header("location: ../insurance.php");
            exit;
        }
    }

    $insert = $connection->query("INSERT INTO insurance (company_name, company_shortname, logo, address, contact_number, email, website, status) VALUES ('".$company_name."', '".$short."', '".$logo_path."', '".$address."', '".$contact_number."', '".$email."', '".$website."', '".$status."')");

    if ($insert) {
        $_SESSION['statusMsg'] = "Insurance added successfully";
        header("location: ../insurance.php");
        exit;
    } else {
        $_SESSION['statusMsg'] = "An error occurred while adding the insurance";
        header("location: ../insurance.php");
        exit;
    }
} else {
    $_SESSION['statusMsg'] = "Invalid request";
    header("location: ../insurance.php");
    exit;
}
?>
