<?php
include_once("inc/config.php");
include_once('lib/global.php');

$domainname = ""; // depreciated, this is checked in view.php now to support moving domains better

$key = $_GET['key'];
$type = "UNDEFINED";
$directlinking = false;

if ($stmt = $conn->prepare('SELECT directlinking FROM settings WHERE domain = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param("s", $config_site_domain);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}
if ($stmt->num_rows > 0) {
    // key exists in the database so there's a user allowed to store files
    $stmt->bind_result($directlinking);
    $stmt->fetch();
}
$stmt->close();



//
//
//
//
//
//

if ($stmt = $conn->prepare('SELECT id, username FROM accounts WHERE authkey = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $key);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}

if ($stmt->num_rows > 0) {
    // key exists in the database so there's a user allowed to store files
    $stmt->bind_result($id, $storedkey);
    $stmt->fetch();
    upload();


} else {
    header("HTTP/1.1 401 Unauthorized");
}
$stmt->close();



function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes'; // what are you even doing here :(
    }

    return $bytes;
}

function upload() {

    global $uploadhost;
    global $conn;
    global $id;
    global $config_extension_image;
    global $config_extension_videos;
    global $key;
    global $config_site_domain;
    global $directlinking;
    global $domainname;

    // [0] is the file name, [1] the file type
    $parts = explode(".", $_FILES['file']['name']);
    $file_extension = "." . $parts[1];
    $imagefolder = "images/";
    $file_name = bin2hex(openssl_random_pseudo_bytes(4));
    $name_and_extension = $file_name . $file_extension;
    $full_location = $domainname . '/' . $imagefolder . $name_and_extension;
    $mimetype = $_FILES['file']['type'];
    $filesize = formatSizeUnits($_FILES['file']['size']);
    if(file_exists($imagefolder . $file_name . $file_extension)) {
        upload();
    } else {
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $imagefolder . $file_name . $file_extension)) {
            // file upload and movement has been succesful at this point


            if (in_array(strtolower($file_extension), array_map('strtolower', $config_extension_image))) {
                if ($stmt2 = $conn->prepare('UPDATE accounts SET total_images = total_images+1 WHERE authkey = ?')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt2->bind_param('s', $key);
                    $stmt2->execute();
                    $type = "image";
                }
                $stmt2->close();
            } else if (in_array(strtolower($file_extension), array_map('strtolower', $config_extension_videos))) {
                if ($stmt3 = $conn->prepare('UPDATE accounts SET total_videos = total_videos + 1 WHERE authkey = ?')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt3->bind_param('s', $key);
                    $stmt3->execute();
                    $type = "video";
                }
                $stmt3->close();
            } else {
                if ($stmt4 = $conn->prepare('UPDATE accounts SET total_files = total_files + 1 WHERE authkey = ?')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt4->bind_param('s', $key);
                    $stmt4->execute();
                    $type = "file";
                }
                $stmt4->close();
            }

            if ($stmt = $conn->prepare('INSERT INTO uploads (filename, full_location, date_uploaded, owned_by, filetype, mimetype, filesize ) VALUES (?, ?, ?, ?, ?, ?, ?)')) {
                // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                $date = date("Y-m-d H:i:s");
                $stmt->bind_param('sssssss', $file_name, $full_location, $date, $id, $type, $mimetype, $filesize);
                $stmt->execute();

            }
            $stmt->close();
            if ($directlinking) {
               	echo $full_location;
            }else{
                
 		echo $config_site_domain . '/view/' . $type . "/" . $file_name;
            }



        }else{
           // header('Location: '. $domainname .' Upload error (Ensure your directory has 777 permissions). Target file was '.$target);
        }
    }
}
$conn->close();
