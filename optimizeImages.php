<?php
session_start();
require_once("session.php");
require_once("log.php");
include_once("optimizeImage.php");
include_once ("enumToInt.php");

if (enumToInt($_SESSION["perm_lvl"]) < 3) {
    header("Location: library.php");
}


$query = "SELECT tn_path FROM books";
$sqlResult = $conn->query($query);
if ($sqlResult->num_rows > 0) {
    while ($row = $sqlResult->fetch_assoc()) {
        optimizeImage($conn, $row["tn_path"], $_SESSION["id"]);
    }

} else {
    linLog($conn, "[ERROR!] " . $conn->error, $id);
}


