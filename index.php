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
    <h1><i class="nav-icon fas fa-address-card"></i> <?php echo $user['full_name']; ?></h1>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <div class="card">
    <div class="card-header card-navy card-outline">
      <div align="right"> 
        <a href="https://devtai.com/">      
          <!-- <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-user-plus"></i> เพิ่มข้อมูล สมาชิก</button> -->
        </a>   
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

          // Query to fetch data from the "users" table
          $query = "SELECT user_id, full_name, email, username, password, user_type, phone_number FROM users";

          // Execute the query
          $result = mysqli_query($connection, $query);

          // Check if the query was successful
          if ($result) {
              // Create the table
              echo '<table id="example2" class="table table-bordered table-hover">';
              echo '<thead>';
              echo '<tr>';
              echo '<th>ID</th>';
              echo '<th>ชื่อ</th>';
              echo '<th>อีเมลล์</th>';
              echo '<th>เบอร์โทร</th>';
              echo '<th>Username</th>';
              echo '<th>Password</th>';
              echo '<th>ระดับ</th>';
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
                  echo '<a href="edit_user.php?ID='.$row['user_id'].'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a> ';
                  echo '<button type="button" class="btn btn-danger btn-sm delete-btn" data-user-id="'.$row['user_id'].'"><i class="fa fa-trash"></i> Delete</button>';
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
$(function () {
  $(".datatable").DataTable();

  // Confirm deletion
  $(".delete-btn").click(function () {
    var userId = $(this).data("user-id");
    Swal.fire({
      title: 'Confirm Deletion',
      text: 'Are you sure you want to delete this user?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "delete_user.php?ID=" + userId;
      }
    });
  });
});
</script>
</body>
</html>
