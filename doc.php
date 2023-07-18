<?php
$menu = "doc";
?>

<?php include("header.php"); ?>

<!-- HTML code -->
<!DOCTYPE html>
<html>

<head>
    <title>Form with Signature Pad | E-Signature Pad using Jquery UI and PHP - bootstrapfriendly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature-pad.css">

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

<body class="bg-light">
    <section class="content">
        <div class="tabs">
            <a class="tab active" href="doc.php">ประเภท 1</a>
            <a class="tab" href="doc1.php">ประเภท 2</a>
            <a class="tab" href="doc2.php">ประเภท 3</a>
            <a class="tab" href="doc3.php">ประเภท 4</a>
            <a class="tab" href="doc4.php">ประเภท 5</a>
            <?php if ($user['level'] == 'admin') : ?>
                <a class="tab" href="doc5.php">Settings</a>
            <?php endif; ?>
        </div>
        <form method="POST" action="upload.php">
            <h1>ใบคำขอสินเชื่อค่าเบี้ยประกันภัย</h1>
            <?php
            // Assuming you have established a database connection
            include 'config/database.php';
            $connection = connectDB();
            // Query to fetch active products from the "installment_document_system" table
            $userQuery = "SELECT full_name FROM users ";
            $userResult = mysqli_query($connection, $userQuery);
            $productQuery = "SELECT product_name, fee FROM products WHERE status = 'Active'";
            $productResult = mysqli_query($connection, $productQuery);
            // Query to fetch active insurance companies from the "installment_document_system" table
            $insuranceQuery = "SELECT company_name FROM insurance WHERE status = 'Active'";
            $insuranceResult = mysqli_query($connection, $insuranceQuery);
            ?>
            <script>
                function updateFee(select) {
                    // Retrieve the fee value from the selected option
                    var fee = select.options[select.selectedIndex].getAttribute('data-fee');

                    // Update the fee field
                    document.getElementById('fee').value = fee;
                }
            </script>


            <!-- <div class="col-md-12">
                    <label for="program" class="form-label">ผลิตภัณฑ์ประกันรถยนต์ที่ขอผ่อน</label>
                    <select class="form-control" id="name" name="name">
                        <?php while ($row = mysqli_fetch_assoc($userResult)) : ?>
                            <option value="<?php echo $row['full_name']; ?>"><?php echo $row['full_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div> -->
            <input type="hidden" id="name" name="name" value="<?php echo $user['full_name']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Day" class="form-label">วันที่เขียน</label>
                    <input type="date" class="form-control" id="Day" name="Day">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Dayl" class="form-label">วันที่คุ้มครอง</label>
                    <input type="date" class="form-control" id="Dayl" name="Dayl">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" id="Name" name="Name">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" id="Lname" name="Lname">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Card" class="form-label">บัตรประชาชน </label>
                    <input type="number" class="form-control" id="Card" name="Card">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Birth" class="form-label">วันเดือนปีเกิด</label>
                    <input type="date" class="form-control" id="Birth" name="Birth">
                </div>

            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">เบอร์โทร</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['phone_number']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Num" class="form-label">ทะเบียนรถยนต์</label>
                    <input type="text" class="form-control" id="Num" name="Num" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="callback" class="form-label">บริษัทประกัน</label>
                    <select class="form-control" id="callback" name="callback">
                        <?php while ($row = mysqli_fetch_assoc($insuranceResult)) : ?>
                            <option value="<?php echo $row['company_name']; ?>"><?php echo $row['company_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="program" class="form-label">ผลิตภัณฑ์ประกันรถยนต์ที่ขอผ่อน</label>
                    <select class="form-control" id="program" name="program" onchange="updateFee(this)">
                        <?php while ($row = mysqli_fetch_assoc($productResult)) : ?>
                            <option value="<?php echo $row['product_name']; ?>" data-fee="<?php echo $row['fee']; ?>"><?php echo $row['product_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <label for="message" class="form-label">ที่อยู่ปัจจุบัน</label>
                <textarea class="form-control" id="message" rows="3" name="message"><?php echo $user['address']; ?></textarea>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="money" class="form-label">จำนวนเงิน</label>
                        <input type="tel" class="form-control" id="money" name="money" value="10000" onchange="calculateRemainingBalance()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="round" class="form-label">จำนวนงวด</label>
                        <input type="tel" class="form-control" id="round" name="round" value="6">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="first" class="form-label">งวดแรก</label>
                    <input type="tel" class="form-control" id="first" name="first" value="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fee" class="form-label">ค่าธรรมเนียม</label>
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
                    <input type="number" class="form-control" id="fee" name="fee" value="<?php echo $fee; ?>" readonly>
                </div>
                <div class="col-md-12">
                    <label class="" for="">ลายเซ็นต์:</label>
                    <br />
                    <div id="signature-pad">
                        <canvas id="sig-canvas" width="300" height="100"></canvas>
                    </div>
                    <br />
                    <textarea id="signature-data" name="signature" style="display: none"></textarea>
                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-warning" id="clear-btn">&#x232B; Clear Signature</button>
                    </div>
                </div>
                <div class="row">

                </div>
                <?php
                function nowThai()
                {
                    $m = array(
                        "",
                        "มกราคม",
                        "กุมภาพันธ์",
                        "มีนาคม",
                        "เมษายน",
                        "พฤษภาคม",
                        "มิถุนายน",
                        "กรกฎาคม",
                        "สิงหาคม",
                        "กันยายน",
                        "ตุลาคม",
                        "พฤศจิกายน",
                        "ธันวาคม"
                    );
                    $r = date("d") . " " . $m[(int)date("m")] . " " . (date("Y") + 543);
                    return $r;
                }

                $date_today = date("Y-m-d H:i:s");
                ?>

                <label for="myCheckbox">
                    <input type="checkbox" id="myCheckbox" name="myCheckbox" value="<?php echo nowThai(); ?>" required>
                    ฉันยินยอมในการทำใบคำขอสินเชื่อค่าเบี้ยประกันภัย
                </label>

                <input type="hidden" id="action" name="action" value="1">
                <br />
                <?php
                echo "<input type='submit' id='submitButton' name='submitButton' value='ยืนยัน' class='w-100 btn btn-lg btn-primary btn-block' onchange='callAlert()' value='Submit'>";
                ?>
        </form>
    </section>
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
            document.getElementById('submitButton').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    // Signature is empty
                    document.getElementById('signature-data').value = null;
                } else {
                    // Signature is not empty
                    var signatureData = signaturePad.toDataURL();
                    document.getElementById('signature-data').value = signatureData;
                }
            });
        });
    </script>

</body>

</html>