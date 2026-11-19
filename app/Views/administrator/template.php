<?php 
if ((string) session()->get('level') === '') {
    echo "<script>window.location.href='" . base_url('administrator') . "';</script>";
} else {
  $iden = portal_context()->getIdentitas();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo esc((string) ($iden['nama_website'] ?? '')); ?></title>
  <meta name="author" content="Diskominfo Pematangsiantar">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/dist/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/summernote/summernote-bs4.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style type="text/css">.checkbox-scroll { border:1px solid #ccc; width:100%; height: 114px; padding-left:8px; overflow-y: scroll; }</style>
  <!--<script src="asset/ckeditor/ckeditor.js"></script>-->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/plugins/daterangepicker/daterangepicker-bs3.css">
  <script type="text/javascript" src="<?php echo base_url(); ?>asset/admin_mu/plugins/daterangepicker/moment.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <?php echo view('administrator/include/navbar'); ?>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-success">
    <?php echo view('administrator/include/sidebar'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          <ol class='breadcrumb float-sm-right'>
          <?php 
            if(uri_segment(2, '')!='home' AND uri_segment(2, '')!=''){
              echo "<li class='breadcrumb-item'><a href='".base_url().uri_segment(1, 'administrator')."'>Home</a></li>
                    <li class='breadcrumb-item active'>".uri_segment(2, '')."</li>";
            }else{
              echo "<li class='breadcrumb-item'>Haloo, Selamat Datang Admin!</li>";
            }
          ?>
          </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php echo $contents; ?>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer text-sm">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a target='_BLANK' href="http://Diskominfo Pematangsiantar/"><?php echo esc((string) ($iden['nama_website'] ?? '')); ?></a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
    <a style='color:#cecece !important' href="<?php echo base_url().uri_segment(1, 'administrator'); ?>/upgrade"><b>Version</b> 3.0.0</a>
    </div>
  </footer>
</div>
<!-- ./wrapper -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
$('#rangepicker').daterangepicker();
</script>
<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>asset/admin_mu/dist/js/adminlte.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="<?php echo base_url(); ?>asset/admin_mu/dist/js/demo.js"></script>
<!-- PAGE PLUGINS -->
<script src="<?php echo base_url(); ?>asset/summernote/summernote-bs4.min.js"></script>
<script type="text/javascript">
$(function () {
  var adminCsrfName = '<?= csrf_token() ?>';
  var adminCsrfHash = '<?= csrf_hash() ?>';

  function uploadImage(image) {
      var data = new FormData();
      data.append("image", image);
      data.append(adminCsrfName, adminCsrfHash);
      $.ajax({
          url: "<?php echo site_url('administrator/upload_image')?>",
          cache: false,
          contentType: false,
          processData: false,
          data: data,
          type: "POST",
          success: function(url, textStatus, xhr) {
              var nextToken = xhr ? xhr.getResponseHeader('X-CSRF-TOKEN') : null;
              if (nextToken) { adminCsrfHash = nextToken; }
              $('#editor1').summernote("insertImage", url);
          },
          error: function(xhr) {
              var nextToken = xhr ? xhr.getResponseHeader('X-CSRF-TOKEN') : null;
              if (nextToken) { adminCsrfHash = nextToken; }
              console.log(xhr);
          }
      });
  }

  function deleteImage(src) {
      var payload = {src : src};
      payload[adminCsrfName] = adminCsrfHash;
      $.ajax({
          data: payload,
          type: "POST",
          url: "<?php echo site_url('administrator/delete_image')?>",
          cache: false,
          success: function(response, textStatus, xhr) {
              var nextToken = xhr ? xhr.getResponseHeader('X-CSRF-TOKEN') : null;
              if (nextToken) { adminCsrfHash = nextToken; }
              console.log(response);
          },
          error: function(xhr) {
              var nextToken = xhr ? xhr.getResponseHeader('X-CSRF-TOKEN') : null;
              if (nextToken) { adminCsrfHash = nextToken; }
          }
      });
  }

  if ($('#editor1').length) {
      $('#editor1').summernote({
          height: "300px",
          callbacks: {
              onImageUpload: function(image) {
                  uploadImage(image[0]);
              },
              onMediaDelete: function(target) {
                  deleteImage(target[0].src);
              }
          }
      });
  }
});
</script>

<!-- jQuery Mapael -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/chart.js/Chart.min.js"></script>
<!-- PAGE SCRIPTS -->

<script src="<?php echo base_url(); ?>asset/admin_mu/dist/js/jquery.nestable.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>

  <script>
  /** add active class and stay opened when selected */
var url = window.location;

// for sidebar menu entirely but not cover treeview
$('ul.nav-sidebar a').filter(function() {
    return this.href == url;
}).addClass('active');

// for treeview
$('ul.nav-treeview a').filter(function() {
    return this.href == url;
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open') .prev('a').addClass('active');
</script>



<?php if (uri_segment(2, '')!='menuwebsite'){ ?>
  <!--<script>
    /* CKEDITOR.replace('editor1' ,{
      filebrowserImageBrowseUrl : '<?php // echo base_url('asset/kcfinder'); ?>'
    }); */
  </script>-->
<?php } ?>
</body>
</html>
<?php } ?>
