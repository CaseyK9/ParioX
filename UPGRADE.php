<?php
include("inc/config.php");
include("lib/global.php");

$UPGRADEVERSION = "0.2";
$upgrade_from_01_to_02_done = false;

function upgradefrom_01_to_02($conn){
    global $upgrade_from_01_to_02_done;
    $file = 'inc/config.php';
// Open the file to get existing content
    $current = file_get_contents($file) . PHP_EOL;
// Append a new person to the file
    $current .= '$installed_version = 0.2;';
// Write the contents back to the file
    file_put_contents($file, $current);
//
    if ($stmt = $conn->prepare('ALTER TABLE `settings` ADD `maxfoldersize_enabled` BOOLEAN NOT NULL DEFAULT FALSE AFTER `directlinking`, ADD `maxfoldersize_inmb` INT NOT NULL DEFAULT \'5000\' AFTER `maxfoldersize_enabled`, ADD `deleteafterxdays_enabled` BOOLEAN NOT NULL AFTER `maxfoldersize_inmb`, ADD `deleteafterxdays_amount` INT NOT NULL AFTER `deleteafterxdays_enabled`; 

')) {
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
    }
    Echo 'Performed upgrades from 0.1 to 0.2!';
    $upgrade_from_01_to_02_done = true;
}



function performversionchecks($conn){
    global $upgrade_from_01_to_02_done;

    if(!$upgrade_from_01_to_02_done) {
        global $installed_version;
        if (isset($installed_version)) {

        } else {
            upgradefrom_01_to_02($conn);

        }
    }
}
performversionchecks($conn);