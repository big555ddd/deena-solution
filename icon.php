<?php

$menu = "icon";


?>

<?php include("header.php"); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <h1><?php
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        echo "Your IP Address is " . $ipaddress;        
        ?></h1>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

</section>
<!-- /.content -->


<?php include('footer.php'); ?>

<script>
  $(function() {
    $(".datatable").DataTable();
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    // http://fordev22.com/
    // });
  });
</script>

</body>

</html>