



<?php
require "includes/header.php";
require "includes/db.php";


?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">404 Page we can not find page that you are looking for.    </h1>
                <!-- <p class="lead">Save time and leave the groceries to us.</p> -->

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <!-- Display payment success message -->
                            <h1>404 Page we can not find page that you are looking for. </h1>
                            <p><?php echo APPURL;?>home page </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require "includes/footer.php";
?>
