<?php
include_once("inc/config.php");
include_once('lib/global.php');

$domainname = ""; // depreciated, this is checked in view.php now to support chaning the domain name.

$key = $_GET['key'];
$type = "UNDEFINED";

$directlinking = false;

$maxfoldersize_enabled = 0;
$maxfoldersize_inmb = 0;

$deleteafterxdays_enabled = 0;
$deleteafterxdays_amount = 0;

if ($stmt = $conn->prepare('SELECT directlinking, maxfoldersize_enabled, maxfoldersize_inmb, deleteafterxdays_enabled, deleteafterxdays_amount FROM settings')) {
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}
if ($stmt->num_rows > 0) {
    // key exists in the database so there's a user allowed to store files
    $stmt->bind_result($directlinking, $maxfoldersize_enabled, $maxfoldersize_inmb, $deleteafterxdays_enabled, $deleteafterxdays_amount);
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
    upload($maxfoldersize_enabled, $maxfoldersize_inmb, $deleteafterxdays_enabled, $deleteafterxdays_amount);


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
        $bytes = '0 bytes';
    }

    return $bytes;
}

function folderSize ()
{
    $size = 0;
    $dir = "images/";
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }
    $size = number_format($size  / 1048576, 2); // in megabytes
    return $size;
}

function cleanup($conn, $maxfoldersize_enabled, $maxfoldersize_inmb, $deleteafterxdays_enabled, $deleteafterxdays_amount) {
    global $remove_fromlocation;
    if ($stmt = $conn->prepare('SELECT full_location FROM uploads ORDER BY id ASC LIMIT 1')) {
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
    }
    if ($stmt->num_rows > 0) {
        // key exists in the database so there's a user allowed to store files
        $stmt->bind_result($remove_fromlocation);
        $stmt->fetch();
        unlink($remove_fromlocation);
        upload($maxfoldersize_enabled, $maxfoldersize_inmb, $deleteafterxdays_enabled, $deleteafterxdays_amount);
    }
}

function remove_olderthan($conn)
{
    global $deleteafterxdays_amount;
    global $fullpath_to_delete;

    if ($stmt = $conn->prepare('SELECT full_location FROM uploads WHERE `date_uploaded` + INTERVAL ? DAY < NOW()')) {
        $stmt->bind_param("s", $deleteafterxdays_amount);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt_result = $stmt->get_result();
    }

    if ($stmt_result->num_rows > 0) {
        while ($row_data = $stmt_result->fetch_assoc()) {
            $fullpath_to_delete = $row_data['full_location'];
            //if (unlink($fullpath_to_delete)) {


           // }
            if ($stmt = $conn->prepare('DELETE FROM uploads WHERE full_location = ?')) {
                $stmt->bind_param("s", $fullpath_to_delete);
                $stmt->execute();
            }
        }
    }
}






function upload($maxfoldersize_enabled, $maxfoldersize_inmb, $deleteafterxdays_enabled, $deleteafterxdays_amount) {

    global $uploadhost;
    global $conn;
    global $id;
    global $config_extension_image;
    global $config_extension_videos;
    global $key;
    global $config_site_domain;
    global $directlinking;
    global $domainname;
    global $file_name;
    global $full_location;
    global $date;
    global $type;
    global $mimetype;
    global $filesize;

    $cancontinue = true;
    // [0] is the file name, [1] the file type
    $parts = explode(".", $_FILES['file']['name']);
    $file_extension = "." . $parts[1];
    $imagefolder = "images/";
    $file_name = bin2hex(openssl_random_pseudo_bytes(4));
    $name_and_extension = $file_name . $file_extension;
    $full_location = $domainname . '/' . $imagefolder . $name_and_extension;
    $mimetype = $_FILES['file']['type'];
    $filesize = formatSizeUnits($_FILES['file']['size']);

    if ($maxfoldersize_enabled) {
        //if ($maxfoldersize_inmb < folderSize() ){
       //     $cancontinue = false;
     //       cleanup($conn);
   //     }
    }


    if ($cancontinue) {

        if (file_exists($imagefolder . $file_name . $file_extension)) {
            upload();
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $imagefolder . $file_name . $file_extension)) {
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
                    echo $config_site_domain . $full_location;
                } else {

                    echo $config_site_domain . '/view/' . $type . "/" . $file_name;
                }


            } else {
                header_status(500); // This is because Pariox wasn't able to find a file in the temp PHP directory! Check upload_tmp_dir in php.ini!
            }
        }
    } else {
        cleanup();
    }
    if ($deleteafterxdays_enabled) {
        remove_olderthan($conn);
    }
}
$conn->close();
