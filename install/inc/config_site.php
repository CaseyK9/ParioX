<?php
include('../../inc/config.php');
include('../../lib/global.php');

$_POST['site_name'];
$_POST['site_header'];
$_POST['site_subtext'];


$stmt = $conn->prepare("UPDATE settings SET site_name = ?, site_header = ?, site_subtext = ?");
$stmt->bind_param("sss", $_POST['site_name'], $_POST['site_header'], $_POST['site_subtext'] );
$stmt->execute();
echo 'ok';