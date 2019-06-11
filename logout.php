<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 14.4.2019
 * Time: 18:35
 */
session_start();
include("session.php");
include_once ("log.php");
linLog($conn, "User logged out", $_SESSION["id"]);
$_SESSION = array();
session_destroy();
header("location: index.php?logout=true");
