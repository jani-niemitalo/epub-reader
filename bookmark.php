<?php
session_start();
include("session.php");
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
echo $time;
$sqlInsert= "REPLACE INTO bookmarks (user_id, book_id, location, ts) VALUES ($user_id, $book_id, '" . $location . "', $time)";
$sqlResult = $conn->query($sqlInsert);
if ($sqlResult){
    echo "[OK]". $sqlInsert;
}
else{
    echo "[ERR]". $sqlInsert;
    echo $conn->error;
}

?>
