<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 16.4.2019
 * Time: 17:18
 */

session_start();
require_once ("session.php");
require_once ("enumToInt.php");
if (enumToInt($_SESSION["perm_lvl"]) < 3) {
    header("Location: library.php");
}
$id = mysqli_real_escape_string($conn, $_POST["id"]);
$value = mysqli_real_escape_string($conn, $_POST["value"]);
$col = mysqli_real_escape_string($conn, $_POST["col"]);

$returnvalue =$id . ", " . $value;
//echo ($returnvalue);
$sql ="update books set ".$col."='".$value."' where id='".$id."'";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully". $sql;
} else {
    echo "Error updating record: " . $conn->error;
}
