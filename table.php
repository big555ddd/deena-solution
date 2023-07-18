<?php

$menu = "table";

?>


<?php include("header.php"); ?>


<?php phpinfo();?>



<?php include('footer.php'); ?>

<script>
  $(function() {
    $(".datatable").DataTable();
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    // http://fordev22.com/
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    // });
  });
</script>

</body>

</html>