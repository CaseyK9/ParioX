<?php
include_once('../inc/config.php');
include_once('../lib/global.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false){
    header("Location: ../");
    die(); // just in case;
}

// saves us making another file, but we should seperate this sometime soon to be honest.
if (isset($_POST['directlinking'])){
    if ($stmt2 = $conn->prepare('UPDATE settings SET directlinking = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt2->bind_param('s', $_POST['directlinking']);
        $stmt2->execute();
        echo 'OK';
    }
$stmt2->close();
die();
}

if (isset($_POST['enable_maxfoldersize'])){

    if ($stmt2 = $conn->prepare('UPDATE settings SET maxfoldersize_enabled = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt2->bind_param('s', $_POST['enable_maxfoldersize']);
        $stmt2->execute();
        echo 'OK';
    }
    $stmt2->close();
    die();
}

if (isset($_POST['enable_toggle_deleteafter'])){

    if ($stmt2 = $conn->prepare('UPDATE settings SET deleteafterxdays_enabled = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt2->bind_param('s', $_POST['enable_toggle_deleteafter']);
        $stmt2->execute();
        echo 'OK';
    }
    $stmt2->close();
    die();
}
if (isset($_POST['maxfoldersize_amountinmb'])){

    if ($stmt2 = $conn->prepare('UPDATE settings SET maxfoldersize_inmb = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt2->bind_param('s', $_POST['maxfoldersize_amountinmb']);
        $stmt2->execute();
        echo 'OK';
    }
    $stmt2->close();
    die();
}


if (isset($_POST['deleteafterxdays_amount'])){

    if ($stmt2 = $conn->prepare('UPDATE settings SET deleteafterxdays_amount = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt2->bind_param('s', $_POST['deleteafterxdays_amount']);
        $stmt2->execute();
        echo 'OK';
    }
    $stmt2->close();
    die();
}









if ($stmt = $conn->prepare('SELECT directlinking, maxfoldersize_enabled, maxfoldersize_inmb, deleteafterxdays_enabled, deleteafterxdays_amount FROM settings')) {
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}
if ($stmt->num_rows > 0) {
    $stmt->bind_result($directlinking, $maxfoldersize_enabled, $maxfoldersize_inmb, $deleteafterxdays_enabled, $deleteafterxdays_amount);
    $stmt->fetch();
}



if($directlinking){ $directlinking_checked = 'checked="checked"'; } else { $directlinking_checked = "";}
if($maxfoldersize_enabled){ $maxfoldersize_enabled_checked = 'checked="checked"'; } else { $maxfoldersize_enabled_checked = "";}
if($deleteafterxdays_enabled){ $deleteafterxdays_enabled_checked = 'checked="checked"'; } else { $deleteafterxdays_enabled_checked = "";}




?>






<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pariox Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include('header.php'); ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php include('topbar.php'); ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <div class="col-lg-6">



                        <!-- Basic Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Website settings</h6>
                            </div>
                            <div class="card-body">
                                You can manually change the front-page settings through the database if you with do so so. This settings page will be updated when the front page is actually functional!
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Storage Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col"</div>
                                        <div class="w-100"></div>
                                        <div class="col"></div></div>
                                        <div class="col"></div>
                                    </div>


                                <div class="row">
                                    <div class="col-6 col-sm-4"><input type="checkbox" class="form-check-input" id="toggle_maxfoldersize" <?php echo $maxfoldersize_enabled_checked ?>>
                                        <label class="form-check-label" for="toggle_maxfoldersize"> Enable max folder size(In MB)</label></div>
                                    <div class="col-6 col-sm-4"><input type="checkbox" class="form-check-input" id="toggle_deleteafter" <?php echo $deleteafterxdays_enabled_checked ?>>
                                        <label class="form-check-label" for="toggle_deleteafter">Delete after X days</label></div>

                                    <!-- Force next columns to break to new line at md breakpoint and up -->
                                    <div class="w-100 d-none d-md-block"></div>

                                    <div class="col-6 col-sm-4"><input id="maxfoldersize_amount" name="maxfoldersize_amount" type="number" min="10" value="<?php echo $maxfoldersize_inmb ?>"></div>
                                    <div class="col-6 col-sm-4"><input id="deleteafterxdays_amount" name="deleteafterxdays_amount" type="number" min="1" value="<?php echo $deleteafterxdays_amount ?>"></div>
                                </div>


                                </div>
                            </div>


                    </div>





                        <!-- Basic Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Uploading settings</h6>
                            </div>
                            <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-4">Enable directlinking</label>
                                        <div class="col-8">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input name="directlinking" id="directlinking_0" type="checkbox" <?php echo $directlinking_checked ?> class="custom-control-input" value="rabbit" aria-describedby="directlinkingHelpBlock">
                                                <label for="directlinking_0" class="custom-control-label">Rabbit</label>
                                            </div>
                                            <span id="directlinkingHelpBlock" class="form-text text-muted">If enabled, sharex will recieve the direct file URL(eg. http://yoursite.com/image/12346.jpg) If you disable this, a view url will be returned that will display your image/file alongside other information(date, size, etc) or a download button in case of a file.</span>
                                        </div>
                                    </div>

                            </div>
                        </div>






                </div>



            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="../inc/js/notify.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>
<script src="settingspage.js"></script>
</body>

</html>
