<?php
$menu = "button";
include("header.php");
include_once('config/database.php');

// Retrieve the PDF files from the database
$conn = connectDB();
$userID = $user['id'];
$sql = "SELECT * FROM installment WHERE user_id = $userID ORDER BY created_at"; // Adjust the query to fetch the desired data
$result = $conn->query($sql);
?>

<section class="content">
  <div class="card">
    <div class="card-header bg-navy">
      <h3 class="card-title">Your Data</h3>
    </div>
    <br>
    <div class="card-body p-1">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-12">
          <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
              <tr role="row" class="info">
                <th>ลำดับ</th>

                <?php if ($user['level'] == 'admin') : ?>
                  <th>ชื่อไฟล์</th>
                <?php endif; ?>
                <th>ทะเบียนรถยนต์</th>
                <th>จำนวนงวด</th>
                <th>วันที่สร้าง</th>
                <th>PDF</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $orderNumber = 1; // Manually set the initial order number
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $id = $row['id'];
                  $fileName = $row['file_name'];
                  $vehicle_plate= $row['vehicle_plate'];
                  $round = $row['round'];
                  $createdAt = $row['created_at'];
                  $status = $row['status'];
              ?>
                  <tr>
                    <td><?php echo $orderNumber; ?></td>
                    <?php if ($user['level'] == 'admin') : ?>
                      <td><?php echo $fileName; ?></td>
                    <?php endif; ?>
                    <td><?php echo $vehicle_plate; ?></td>
                    <td><?php echo $round; ?></td>
                    <td><?php echo $createdAt; ?></td>
                    <td>
                      <?php
                      if ($status == 0) {
                        echo '<a href="buttons.php?file_name=' . $fileName . '" class="btn btn-primary btn-sm" style="background-color: red; border-color: red;">เปิดดู</a>';
                      } elseif ($status == 1) {
                        echo '<a href="from.php?file_name=' . $fileName . '" class="btn btn-primary btn-sm" style="background-color: green; border-color: green;">เปิดดู</a>';
                        
                      }
                      ?>


                    </td>

                  </tr>
              <?php
                  $orderNumber++; // Increment the order number
                }
              } else {
                echo '<tr><td colspan="5">No data found.</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>







  </div>
  <!-- /.col -->
  </div>



</section>

<?php
$conn->close();
?>