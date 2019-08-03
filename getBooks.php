<?php
session_start();
include("session.php");
require_once("cover.php");
require_once("enumToInt.php");
$num = mysqli_real_escape_string($conn, $_GET['from']);
$booksQuery = "SELECT * FROM books LIMIT 30 OFFSET ". $num;
$booksQueryResult = $conn->query($booksQuery);
$count = 0;
if ($booksQueryResult->num_rows > 0) {
    if ($booksQueryResult->num_rows < 30){
        http_response_code(206);
    }
    $returnSTR = "<div class=\"grid\">";
    // output data of each row
    while ($row = $booksQueryResult->fetch_assoc()) {

        if (enumToInt($row["permission_lvl"]) <= enumToInt($_SESSION["perm_lvl"]))
            $returnSTR .= coverFN2($row, 'info');
        else if ($row["uploader"] == $user_id)
            $returnSTR .= coverFN2($row, 'info');
    }
    $returnSTR .= "</div>";
    echo $returnSTR;
}
else
    http_response_code(206);



