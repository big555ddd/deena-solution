<?php
$menu = "installment";
include("header.php");
include_once('config/database.php');

// Retrieve the PDF files from the database
$conn = connectDB();
$userID = $user['id'];
$sql = "SELECT * FROM installment ORDER BY created_at"; // Adjust the query to fetch the desired data
$result = $conn->query($sql);
?>

<section class="content">
    <div class="card">
        <br>
        <div class="card-body p-1">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-12">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr role="row" class="info">
                                <th>ลำดับ</th>
                                <th>ชื่อ</th>
                                <th>ทะเบียนรถยนต์</th>
                                <th>จำนวนงวด</th>
                                <th>วันที่สร้าง</th>
                                <th>สร้างโดย</th>
                                <th>สถานะ</th>
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
                                    $name = $row['name'];
                                    $vehicle_plate= $row['vehicle_plate'];
                                    $round = $row['round'];
                                    $createdAt = $row['created_at'];
                                    $userId = $row['user_id'];
                                    // Fetch user information based on user_id to get the user's name
                                    $userQuery = "SELECT full_name FROM users WHERE user_id = '$userId'";
                                    $userResult = $conn->query($userQuery);
                                    $userRow = $userResult->fetch_assoc();
                                    $userName = $userRow['full_name'];

                                    // Generate unique IDs for file input and file-chosen elements
                                    $fileInputId = 'pdf_' . $id;
                                    $fileChosenId = 'file_chosen_' . $id;
                            ?>
                                    <tr>
                                        <td><?php echo $orderNumber; ?></td>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $vehicle_plate; ?></td>
                                        <td><?php echo $round; ?></td>
                                        <td><?php echo $createdAt; ?></td>
                                        <td><?php echo $userName; ?></td>
                                        <td>
                                            <?php if ($row['status'] == 0) : ?>
                                                <span class="status">ยังไม่เซ็นต์</span>
                                            <?php else : ?>
                                                <span class="status">เซ็นต์แล้ว</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] == 0) : ?>
                                                <form action="upload1.php" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                    <label for="<?php echo $fileInputId; ?>" class="btn btn-primary btn-sm" style="background-color: green; color: white; border-color: green;">
                                                        เลือกไฟล์
                                                        <input type="file" name="pdf" id="<?php echo $fileInputId; ?>" accept=".pdf" style="display: none;">
                                                    </label>
                                                    <span id="<?php echo $fileChosenId; ?>">ยังไม่ได้เชื่อไฟล์</span>
                                                    <input type="submit" name="upload" value="อัพโหลด" class="btn btn-primary btn-sm">
                                                </form>
                                                <script>
                                                    document.getElementById('<?php echo $fileInputId; ?>').addEventListener('change', function() {
                                                        var fileChosen = document.getElementById('<?php echo $fileInputId; ?>').files[0].name;
                                                        document.getElementById('<?php echo $fileChosenId; ?>').innerHTML = fileChosen;
                                                    });
                                                </script>
                                            <?php else : ?>
                                                <a href="froms.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">เปิดดู</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php
                                    $orderNumber++; // Increment the order number
                                }
                            } else {
                                echo '<tr><td colspan="7">No data found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
$conn->close();
?>