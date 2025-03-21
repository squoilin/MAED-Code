<?php
    error_reporting(~E_NOTICE);
    session_start();
    // Set default MAED type if cookie is not set
    if (!isset($_COOKIE['maedtype'])) {
        $maedtype = 'maedd'; // Default to maedd type since we're accessing maedd_geninf.php
    } else {
        $maedtype = $_COOKIE['maedtype'];
    }
    define('LOGIN',1);
    define('ROOT_FOLDER', dirname(__FILE__));
    define("BASE_PATH", ROOT_FOLDER . "/");
    define("CLASS_PATH", ROOT_FOLDER . "/classes/");
    define("DATA_FILE_PATH", ROOT_FOLDER . '/storage/' . $maedtype . "/data/");
    define("COMMON_DATA_FILE_PATH", DATA_FILE_PATH . "common/");
    define("PROJECT_DATA_FILE_PATH", DATA_FILE_PATH . "projects/");
    define("BACKUP_PATH", DATA_FILE_PATH . "backup/");
    define("DATA_FILE_EXT", "xml");
    define("XML_FILE_HEAD", '<?xml version="1.0" encoding="utf-8"?>');
    define("USER_CASE_PATH", PROJECT_DATA_FILE_PATH . $_SESSION['gr'] . '/');
?>
