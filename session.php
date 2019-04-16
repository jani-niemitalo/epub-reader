<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 14.4.2019
 * Time: 18:18
 */
require_once("mysqlConnection.php");
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    //echo "Login First!";
    header("Location: index.php");
    exit();
}

$user = $conn->query("SELECT * FROM users WHERE id = '".$_SESSION["id"]."' limit 1")->fetch_assoc();
$_SESSION['perm_lvl'] = $user["permission_lvl"];


?>
