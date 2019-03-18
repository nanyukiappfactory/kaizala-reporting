<?php
$success = $this->session->flashdata('success');
$error = $this->session->flashdata('error');
?>
<!doctype html>
<html lang="en">

<head>
    <?php $this->load->view('admin/layouts/includes/header');?>
</head>

<body>
    <div class="pre-loader"></div>

    <!-- navigation -->
    <?php echo $this->load->view('admin/layouts/includes/navigation'); ?>

    <!-- sidebar -->
    <?php echo $this->load->view('admin/layouts/includes/sidebar'); ?>

    <div class="main-container">
		<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
			<div class="min-height-200px">

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
        </div>
    </div>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/custom/themes/vendor/styles/style.css">

    
    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url(); ?>assets/custom/themes/vendor/scripts/script.js"></script>

</body>

</html>