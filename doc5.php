<?php
$menu = "doc";
session_start();
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}

include 'config/database.php';
$connection = connectDB();

// Retrieve the user ID from the session
$userID = $_SESSION['user_login']['id'];

// Query to fetch the user record based on the user ID
$query = "SELECT * FROM setting WHERE id = '1'";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query was successful and if the record exists
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Redirect to the login page if the user record is not found
    header("location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $fee = $_POST['fee'];
    // Update the user settings in the database
    $updateQuery = "UPDATE setting SET 
                    fee = '$fee'
                    WHERE id = '1'";

    // Execute the update query
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        // Update the user data in the session


        // Redirect to the setting page with a success message
        header("location: doc5.php?message=edit_success");
        exit;
    } else {
        // Redirect to the setting page with an error message
        header("location: doc5.php?message=edit_error");
        exit;
    }
}

// Close the database connection
mysqli_close($connection);
?>

<?php include("header.php"); ?>

<!-- HTML code -->
<!DOCTYPE html>
<html>

<head>
    <title>Form with Signature Pad | E-Signature Pad using Jquery UI and PHP
        - bootstrapfriendly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->


    <style>
        #sig {
            border: 2px solid blue;
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

        .tabs {
            display: flex;
            margin-bottom: 10px;
        }

        .tab {
            cursor: pointer;
            padding: 10px;
            background-color: lightgray;
            border: 1px solid gray;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            margin-right: 5px;
            font-weight: bold;
            color: gray;
            transition: background-color 0.3s, color 0.3s;
            text-decoration: none;
        }

        .tab:hover {
            background-color: #f0f0f0;
            color: #333;
        }

        .tab.active {
            background-color: #333;
            color: white;
        }

        .panel {
            display: none;
            padding: 10px;
            border: 1px solid gray;
            border-top: none;
            background-color: #f0f0f0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .active {
            display: block;
        }
    </style>

</head>

<body class="bg-light ">
    <section class="content">
        <div class="tabs">
            <a class="tab " href="doc.php">ประเภท 1</a>
            <a class="tab" href="doc1.php">ประเภท 2</a>
            <a class="tab" href="doc2.php">ประเภท 3</a>
            <a class="tab" href="doc3.php">ประเภท 4</a>
            <a class="tab " href="doc4.php">ประเภท 5</a>
            <?php if ($user['level'] == 'admin') : ?>
                <a class="tab active" href="doc5.php">Settings</a>
            <?php endif; ?>
        </div>
        <?php
        // Check if a success message is present in the URL
        if (isset($_GET['message']) && $_GET['message'] == "edit_success") {
            echo '<div class="alert alert-success"> updated successfully.</div>';
        }
        // Check if an error message is present in the URL
        elseif (isset($_GET['message']) && $_GET['message'] == "edit_error") {
            echo '<div class="alert alert-danger">Error updating . Please try again.</div>';
        }
        ?>
        <form method="POST" action="doc5.php">
            <div class="mb-3">
                <label for="fee" class="form-label">Fee</label>
                <?php
                $conn = connectDB();
                $sql = "SELECT fee FROM setting WHERE id = 1";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $fee = $row['fee'];
                } else {
                    $fee = ""; // Default value if no record found
                }

                $conn->close();
                ?>
                <input type="number" class="form-control" id="fee" name="fee" value="<?php echo $fee; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </section>

</body>

</html>