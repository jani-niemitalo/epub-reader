<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 7.4.2019
 * Time: 4:10
 */
session_start();
include("session.php");
require_once("cover.php");
require_once("enumToInt.php");
$input = mysqli_real_escape_string($conn, $_GET['q']);
//$input = mysqli_real_escape_string($conn, $input);
$search_query = " SELECT * FROM books WHERE 
                                      title LIKE '%$input%' OR 
                                      author LIKE '%$input%' OR
                                      series LIKE '%$input%'";
$resultFromSearch = $conn->query($search_query);
if ($resultFromSearch->num_rows > 0) {
    while ($row_s = $resultFromSearch->fetch_assoc()) {
        if (enumToInt($row_s["permission_lvl"]) <= enumToInt($_SESSION["perm_lvl"]))
            echo coverFN2($row_s, "info");
    }
}
else{
    echo "Empty Result";
}

