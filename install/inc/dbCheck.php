<?php



$testconnection = new MySQLi( $_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], null, $_POST['db_port']);
if ($testconnection->connect_error) {
    echo "Something went wrong:" . $testconnection->connect_error;
}
else {
    echo "Ok";
}