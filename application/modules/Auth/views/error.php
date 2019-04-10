<!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/custom/themes/vendor/fontawesome/css/all.css"> -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<!-- Custom styles for this template-->
<link href="<?php echo base_url(); ?>assets/themes/custom/styles.css" rel="stylesheet">
<!-- Custom styles for this template-->
<link href="<?php echo base_url(); ?>assets/custom/themes/vendor/styles/error.css" rel="stylesheet">

<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2><?php echo $http_code; ?></h2>
                <div class="error-details">
                    Sorry, an error has occured, <?php echo $message; ?>
                </div>
                <div class="error-actions">
                    <a href="<?php echo base_url(); ?>login" class="btn btn-primary btn-lg"><span
                            class="glyphicon glyphicon-home"></span>
                        Take Me To Login </a>
                </div>
            </div>
        </div>
    </div>
</div>