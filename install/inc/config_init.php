<?php

/* ParioX is Developed and maintained by Marcel Groeneveld - 2019

   This project is distributed as is with no rights to technical support,
   You are free to use, distribute, modify this code to your liking, as
   long as you do not use the same name as the source code does (Pariox)
   As discribed by the "Do what the fuck you want to" Public license.
   A copy of this license can be found in the root directory of this project.


   feel free to report any issues on my Github, which you
   can find a link for via my website:
   https://mgroeneveld.nl/
*/

/// The purpose of this file is to enter the database information into the config
/// and insert the database tables afterwards

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['track'])){

}


$host = $_POST['db_host'];
$port = $_POST['db_port'];
$user = $_POST['db_user'];
$pass = $_POST['db_pass'];
$name = $_POST['db_name'];
$domain = $_POST['domain'];

$config = '../../inc/config.php';

$content = file_get_contents($config);


$invoer = '
<?php
/* ParioX is Developed and maintained by Marcel Groeneveld

   This project is distributed as is with no rights to technical support,
   You are free to use, distribute, modify this code to your liking, as
   long as you do not use the same name as the source code does (Pariox)
   As discribed by the "Do what the fuck you want to" Public license.
   A copy of this license can be found in the root directory of this project.


   however you can feel free to report any issues on my Github, which you
   can find a link for via my website.
   https://mgroeneveld.nl/
*/

/*
 * ====================
 *  Database settings
 * ====================
 */


// Database engine configuration
// Currently only only MySQL/MariaDB (You really should use mariaDB) is supported
// We recommend you don\'t change this value.
$config_db_engine = \'mysqli\';

// Database host
// Leave this on localhost if the database is on the same server.
$config_db_ip = \'' . $host . '\';

// the port your sql server listens on. 3066 is default
$config_db_port = \'' . $port . '\';

// The username to access your database
$config_db_user = \'' . $user . '\';

// The password to access your database
$config_db_pass = \'' . $pass . '\';

// The name of your database
$config_db_name = \'' . $name . '\';

$config_site_domain = \'' . $domain . '\';

/*
 * ====================
 *  File settings
 *
 * ====================
 */
 
 
$config_extension_image = array(
    // Here are all the file types we will consider as an image
    // ensure the last value doesn\'t have an endquote!
    ".jpg",
    ".jpeg",
    ".png",
    ".gif",
    ".bmp",
    ".svg"
);
$config_extension_videos = array(
    // Here are all the file types we will consider as a video
    // ensure the last value doesn\'t have an endquote!
    ".mp4",
    ".webm",
    ".ogg"
);






';

$input = $invoer;



file_put_contents($config, $input);


$conn = new mysqli($host, $user, $pass, null, $port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE " . $name;
if ($conn->query($sql) === TRUE) {
    // database was succesfully created so lets populate it
    $conn->close();
} else {
    
}
// if this isnt true we wil just assume user has no permissions to create a database(shared hosts do this stuff)

    $conn2 = new mysqli($host, $user, $pass, $name, $port);
    if ($conn2->connect_error){
        die("Failed to insert data into the created DB: " . $conn->connect_error);
    }
    $sql2 = "
        CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authkey` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_images` int(11) DEFAULT 0,
  `total_videos` int(11) DEFAULT 0,
  `total_files` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $sql3 = "
   CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `defaultimg` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
    $sql4 = "
CREATE TABLE IF NOT EXISTS `settings` (
  `domain` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_name` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_header` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_subtext` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `directlinking` tinyint(1) NOT NULL DEFAULT 0,
  UNIQUE KEY `domain` (`domain`) USING HASH
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $sql5 =
        "
    CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `owned_by` int(11) NOT NULL,
  `filetype` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mimetype` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `filesize` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";


$sql6 = "
INSERT INTO `settings` (`domain`, `site_name`, `site_header`, `site_subtext`, `directlinking`) VALUES
('" . $domain . "', NULL, NULL, NULL, 0);
";
    if (($conn2->query($sql2) === TRUE) && ($conn2->query($sql3) === TRUE) && ($conn2->query($sql4) === TRUE) && ($conn2->query($sql5) === TRUE) && ($conn2->query($sql6) === TRUE)) {
        echo 'ok';
        $_SESSION['check'] = 1;
        $conn2->close();
    }else {
        echo "Error creating database: " . $conn2->error;
        $conn2->close();
    }
