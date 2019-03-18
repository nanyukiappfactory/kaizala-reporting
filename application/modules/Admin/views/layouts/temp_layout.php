<?php
$success = $this->session->flashdata('success');
$error = $this->session->flashdata('error');
?>
<!doctype html>
<html lang="en">

<head>
    <?php $this->load->view('admin/layouts/includes/header');?>
</head>

<body id="page-top">
    <div class="spinner-wrap">
        <div class="leftside"></div>
        <div class="rightside"></div>
        <div class="spinner"></div>
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            <?php echo $this->load->view('admin/layouts/includes/sidebar'); ?>
            <!-- End of Sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <?php echo $this->load->view('admin/layouts/includes/navigation'); ?>
                    <!-- End of Topbar -->
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <!-- Content here -->
                        <?php if (!empty($success)) {?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                        </div>
                        <?php }
						if (!empty($error)) {?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                        <?php }?>

                        <?php echo $content; ?>
                    </div>
                    <!-- End of Main Content -->
                    <!-- Footer -->
                    <?php echo $this->load->view('admin/layouts/includes/footer'); ?>
                    <!-- End of Footer -->
                </div>
                <!-- End of Content Wrapper -->
            </div>
        </div>
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    </div>
    <!-- Logout Modal-->
    <?php echo $this->load->view('admin/layouts/includes/logout-modal'); ?>
    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url(); ?>assets/vendor/js/script.min.js"></script>

</body>

</html>