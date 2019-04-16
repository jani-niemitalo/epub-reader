<?php
session_start();
include("session.php");
$conn->query("UPDATE users SET permission_lvl = 'admin' where id = '".$_SESSION["id"]."'");
header("Location: library.php");