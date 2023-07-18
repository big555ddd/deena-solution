<?php
session_start();
if (!isset($_SESSION['profile']) && !isset($_SESSION['user_login'])) { // If neither profile nor user_login is set
  header("location: login.php"); // Redirect to login.php
  exit;
}
$user = $_SESSION['user_login'];
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-navy elevation-4">
  <!-- Brand Logo -->
  <a href="" class="brand-link bg-navy">
    <img src="assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Admin | LTE V.3</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php if (!empty($user['picture'])) : ?>
          <img src="<?php echo $user['picture']; ?>" class="img-circle elevation-2" alt="User Image">
        <?php else : ?>
          <img src="assets/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
        <?php endif; ?>
      </div>

      <div class="info">
        <a href="setting.php" target="_bank" class="d-block"><?php echo $user['full_name'] ?></a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MENU</li>
        <?php if ($user['level'] == 'admin') : ?>
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if ($menu == "index") {
                                                  echo "active";
                                                } ?>">
              <i class="nav-icon fas fa-address-card"></i>
              <p>กำหนดสิทธิ์</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="installment.php" class="nav-link <?php if ($menu == "installment") {
                                                        echo "active";
                                                      } ?>">
              <i class="nav-icon fas fa-address-card"></i>
              <p>เอกสารผ่อน-admin</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="insurance.php" class="nav-link <?php if ($menu == "insurance") {
                                                      echo "active";
                                                    } ?>">
              <i class="nav-icon fas fa-store-alt"></i>
              <p>บริษัทประกัน</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="package.php" class="nav-link <?php if ($menu == "package") {
                                                    echo "active";
                                                  } ?>">
              <i class="nav-icon fas fa-hamburger"></i>
              <p>ผลิตภัณฑ์</p>
            </a>
          </li>
        <?php elseif ($user['level'] == 'manager') : ?>
          <li class="nav-item">
            <a href="insurance.php" class="nav-link <?php if ($menu == "insurance") {
                                                      echo "active";
                                                    } ?>">
              <i class="nav-icon fas fa-store-alt"></i>
              <p>ออกใบคำขอ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="package.php" class="nav-link <?php if ($menu == "package") {
                                                    echo "active";
                                                  } ?>">
              <i class="nav-icon fas fa-hamburger"></i>
              <p>Product</p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="doc.php" class="nav-link <?php if ($menu == "doc") {
                                              echo "active";
                                            } ?>">
            <i class="nav-icon fas fa-file-pdf"></i>
            <p>ใบคำขอ</p>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a href="from.php" class="nav-link <?php if ($menu == "from") {
                                                echo "active";
                                              } ?>">
            <i class="nav-icon fas fa-apple-alt"></i>
            <p>Form</p>
          </a>
        </li> -->
        <!-- <li class="nav-item">
          <a href="table.php" class="nav-link <?php if ($menu == "table") {
                                                echo "active";
                                              } ?>">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Tables</p>
          </a>
        </li> -->
        <li class="nav-item">
          <a href="button.php" class="nav-link <?php if ($menu == "button") {
                                                  echo "active";
                                                } ?>">
            <i class="nav-icon fas fa-check-square"></i>
            <p>เอกสารผ่อน-user</p>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a href="icon.php" class="nav-link <?php if ($menu == "icon") {
                                                echo "active";
                                              } ?>">
            <i class="nav-icon fas fa-icons"></i>
            <p>Icon</p>
          </a>
        </li> -->
        <div class="user-panel mt-2 pb-3 mb-2 d-flex"></div>
        <li class="nav-item">
          <a href="logout.php" class="nav-link text-danger">
            <i class="nav-icon fas fa-power-off"></i>
            <p>ออกจากระบบ</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>