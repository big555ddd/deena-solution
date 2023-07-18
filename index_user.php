<?php
session_start();
if (!isset($_SESSION['user_login'])) { // If not logged in
    header("location: login.php"); // Redirect to login.php
    exit;
}
$user = $_SESSION['user_login'];
$menu = "index";
?>
<?php include("header.php"); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid"> 
    <h1><i class="nav-icon fas fa-address-card"></i> <?php echo $user['fullname']; ?></h1>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="card">
      <div class="card-header card-navy card-outline">
        <div align="right"> 
          <a href="https://devtai.com/">      
          <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-user-plus"></i> เพิ่มข้อมูล สมาชิก</button></a>   
        </div>
      </div>
      <br>
      <div class="card-body p-1">
        <div class="row">
          <div class="col-md-1">
          </div>
          <div class="col-md-12">
            <?php
            include 'config/database.php';
            $connection = connectDB();

            // Query to fetch data from the "regis" table
            $query = "SELECT user_id,full_name,email,username,password,user_type,phone_number FROM installment_document_system.users";

            // Execute the query
            $result = mysqli_query($connection, $query);

            // Check if the query was successful
            if ($result) {
                // Create the table
                echo '<table id="example2" class="table table-bordered table-hover">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Fullname</th>';
                echo '<th>E-mail</th>';
                echo '<th>Phone</th>';
                echo '<th>username</th>';
                echo '<th>password</th>';
                echo '<th>user_type</th>';
                echo '<th>Actions</th>'; // Added column for buttons
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Fetch and display the data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>'.$row['user_id'].'</td>'; 
                    echo '<td>'.$row['full_name'].'</td>';
                    echo '<td>'.$row['email'].'</td>';
                    echo '<td>'.$row['phone_number'].'</td>';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>'.$row['password'].'</td>';
                    echo '<td>'.$row['user_type'].'</td>';
                    echo '<td>';
                    echo '<a href="pdf_maker.php?ID=' . $row['user_id'] . '&ACTION=VIEW" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> View PDF</a> &nbsp;&nbsp;';
                    echo '<a href="pdf_maker.php?ID=' . $row['user_id'] . '&ACTION=IMAGE" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> View Image</a> &nbsp;&nbsp;';
                    echo '</td>';
                    echo '</tr>';
                }

                // Close the table
                echo '</tbody>';
                echo '</table>';
            } else {
                // Display an error message if the query fails
                echo "Error: " . mysqli_error($connection);
            }

            // Close the database connection
            mysqli_close($connection);
            ?>
          </div>
          <div class="col-md-1">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
<?php include('footer.php'); ?>
<script>
$(function () {
  $(".datatable").DataTable();
});
</script>
</body>
</html>
