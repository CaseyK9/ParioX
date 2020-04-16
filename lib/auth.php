<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('../inc/config.php');
include_once('global.php');
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
// silly person bypassed the client side protection and now we will ignore him/her.
}

if ($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();
    if (password_verify($_POST['password'], $password)) {
        session_regenerate_id();
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        echo 'OK';
    } else {
       echo 'passwrong';
    }
} else {
    echo 'userwrong';
}
$stmt->close();