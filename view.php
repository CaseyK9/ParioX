<?php

// using htaccess rewrite for this
// pariox.com/view/image/id gets rewritten to pariox.com/view(.php)?level1=foo&level2=bar

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include("inc/config.php");
include("lib/global.php");
$content = '<h2 style="color: red">No media type or id specified.</h2>';
if(isset($_GET['level1'])){
    $contenttype = strtolower($_GET['level1']);
    $content = '<h2 style="color: red">mediatype specified but no id.</h2>';
}
if(isset($_GET['level2'])){
    $contentid = strtolower($_GET['level2']);

    if ($stmt = $conn->prepare('SELECT filename, full_location, date_uploaded, filetype, owned_by, mimetype, filesize FROM uploads WHERE filename = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_GET['level2']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
    }

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($filename, $full_location, $date_uploaded, $filetype, $owned_by, $mimetype, $filesize);
        $stmt->fetch();

        switch($filetype):
            case "image":
                $content = '<img class="displaymedia" src="//' . $config_site_domain . $full_location . '">';
                break;
            case "video":
                $content = '<video controls class="displaymedia" src="//' . $config_site_domain . $full_location . '">';
                break;
            case "file":
                $content = 'file';
                break;
            default:
                echo 'error';
endswitch;


$downloadlocation = $full_location;

        if ($stmt2 = $conn->prepare('SELECT username FROM accounts WHERE id = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt2->bind_param('s', $owned_by);
            $stmt2->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt2->store_result();
        }
        if ($stmt2->num_rows > 0) {
            $stmt2->bind_result($display_owner);
            $stmt2->fetch();
        }
        $stmt2->close();






            }else{
        $content = '<h2 style="color: red">Pariox was unable to locate \'' . $contenttype . '\' with  ID \''. $contentid . '\'</h2>';
    }

}






?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>view</title>
    <link href="/inc/css/bootstrap.css" rel="stylesheet">
    <link href="/inc/css/animate.css" rel="stylesheet">
    <link href="/inc/css/view.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

</head>

<body>
<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Pariox</h3>
        </div>

        <ul class="list-unstyled components">
            <p>Information</p>
        <hr>
            <li></li>
            <li>
                <a class="fa fa-calendar">  <span><?php echo date("d-m-Y | H:i", strtotime($date_uploaded)) ?></span></a>
            </li>
            <li>
                <a class="fa fa-user">  <span><?php echo $display_owner ?></span></a>
            </li>
            <li>
                <a class="fa fa-hdd-o">  <span><?php echo $filesize ?></span></a>
            </li>
            <li>
                <a class="fa fa-tag">  <span><?php echo $mimetype ?></span></a>
            </li>



        <ul class="list-unstyled CTAs">
            <li>
                <a href="//<?php global $downloadlocation; echo $config_site_domain . $downloadlocation ?>" class="download" download>Download file</a>
            </li>
        </ul>
            <div id="helpertext">
                <small class="text-muted">This quality of this image might be degraded in order to fit the screen, the original image remains unaltered.</small>
            </div>
            <div id="creditstext">
                <small style="color:darkblue;" class="text-muted"><a href="https://mgroeneveld.nl/">Pariox - 2019 | mgroeneveld.nl</a> </small>
            </div>
    </nav>
    <!-- Page Content  -->
    <div id="content">
        <button type="button" id="sidebarCollapse" class="btn btn-secondary" style="margin-bottom: 25px; align-left;">
            <i class="fa fa-bars"></i>
            <span>Toggle Sidebar</span>
        </button>





                <div id="mediawrapper">

                <?php echo $content ?>

                </div>
            </div>
</div>

<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>
</body>
</html>