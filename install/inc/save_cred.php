<?php
require('../../inc/config.php');

$conn = new mysqli($config_db_ip, $config_db_user, $config_db_pass, $config_db_name, $config_db_port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString; // generateRandomString()
}
$random = generateRandomString();
$securepass = password_hash($_POST['password'], PASSWORD_DEFAULT);
// prepare and bind
$stmt = $conn->prepare("INSERT INTO accounts (username, password, email, authkey) VALUES (?, ?, 'email', ?)");
$stmt->bind_param("sss", $_POST['username'], $securepass, $random );
$stmt->execute();

echo "ok";