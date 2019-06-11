<?php
session_start();
include("session.php");
include_once ("log.php");
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = mysqli_real_escape_string($conn, $_SESSION['id']);
$location = mysqli_real_escape_string($conn, $_GET['location']);
if ($location == "") {
    //get bookmark
    $location = $conn->query("SELECT * FROM bookmarks WHERE user_id=$user_id AND  book_id=$book_id")->fetch_assoc()["location"];
    echo $location;
    return;
}
$time = time();
$sqlInsert= "REPLACE INTO bookmarks (user_id, book_id, location, ts) VALUES ($user_id, $book_id, '" . $location . "', $time)";
$sqlResult = $conn->query($sqlInsert);
if ($sqlResult){
    echo "[OK! Bookmark Added!]";
}
else{
    echo "[ERROR!] Failed to add bookmark: contact adming with following information: [" . time() ." ". $user_id . "]";
    linLog($conn, "[ERR]". $sqlInsert . " : " . $conn->error, $user_id);
}

?>
