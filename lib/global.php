<?php
$conn = mysqli_connect($config_db_ip, $config_db_user, $config_db_pass, $config_db_name, $config_db_port);

if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
