<?php
session_start();
include("session.php");
$conn->query("UPDATE users SET permission_lvl = 'admin' where id = '".mysqli_real_escape_string($conn,$_SESSION["id"])."'");
header("Location: library.php");