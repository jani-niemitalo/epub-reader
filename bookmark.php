<?php
require_once("mysqlConnection.php");
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = "123";
$location = mysqli_real_escape_string($conn, $_GET['location']);
if ($location == "") {
    //get bookmark
    $location = $conn->query("SELECT * FROM bookmarks WHERE user_id=$user_id AND  book_id=$book_id")->fetch_assoc()["location"];
    echo $location;
    return;
}

$sqlInsert= "REPLACE INTO bookmarks (user_id, book_id, location) VALUES ($user_id, $book_id, '" . $location . "')";
$sqlResult = $conn->query($sqlInsert);
if ($sqlResult){
    echo "[OK]";
}
else{
    echo "[ERR]";
    echo $conn->error;
}

?>
