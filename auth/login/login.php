<?php
/*
 * Password hashing with PBKDF2.
 * Author: havoc AT defuse.ca
 * www: https://defuse.ca/php-pbkdf2.htm
 */
require_once("../../config.php");
require_once("constants.php");
require_once("validate.php");
require_once("pbkdf2.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a log file in the current directory
$log_file = fopen(dirname(__FILE__) . "/login_debug.log", "a");

// username and password sent from form 
$username=$_POST['username'];
$password=$_POST['password'];
fwrite($log_file, date('Y-m-d H:i:s') . " Login attempt for user: " . $username . "\n");
fwrite($log_file, "Password used: " . $password . "\n");

$users=array();

//get users
$url=ROOT_FOLDER.'/auth/us.json';
$file = (file_get_contents($url));
fwrite($log_file, "Loading users from: " . $url . "\n");
fwrite($log_file, "File contents: " . $file . "\n");
$users=json_decode($file, true);
if ($users === null) {
    fwrite($log_file, "Error parsing users JSON: " . json_last_error_msg() . "\n");
}

//validate
$login=false;
for ($i=0; $i<count($users); $i++) {
    if (strtolower($username)==strtolower($users[$i]["username"])) {
        fwrite($log_file, "Found matching username\n");
        fwrite($log_file, "Stored hash: " . $users[$i]["password"] . "\n");
        
        // Debug PBKDF2 parameters
        $params = explode(":", $users[$i]["password"]);
        fwrite($log_file, "Hash parts: algorithm=" . $params[0] . 
                         ", iterations=" . $params[1] . 
                         ", salt=" . $params[2] . "\n");
        
        $login=validate_password($password, $users[$i]["password"]);
        fwrite($log_file, "Password validation result: " . ($login ? "success" : "failed") . "\n");
        if ($login){
            $group=$users[$i]["usergroup"];
            $isadmin=$users[$i]["isadmin"];
            $maedtype=$users[$i]["maedtype"];
            $language=$users[$i]["language"];
            $decimal=$users[$i]['decimal'];
        }
    }
}

if ($login) {
    fwrite($log_file, "Login successful\n");
    if($maedtype==null) $maedtype="maedd";
    if($language==null) $language="en";
    if($decimal==null) $decimal=3;
    $_SESSION['us'] = $username; 
    $_SESSION['gr'] = $group; 
    $_SESSION['isadmin'] = $isadmin; 
    setcookie("maedtype", $maedtype,time() + (86400 * 30), "/");
    setcookie("langCookie", $language, time() + (86400 * 30), "/");
    setcookie("decimal", $decimal,time() + (86400 * 30), "/");
    $url="../../app.html";
}else{
    fwrite($log_file, "Login failed\n");
    $url="../../index.html?e=1";
}

fwrite($log_file, "Redirecting to: " . $url . "\n");
fwrite($log_file, "----------------------------------------\n");
fclose($log_file);

echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
?>