<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 7.4.2019
 * Time: 4:10
 */
  require_once("mysqlConnection.php");
  require_once("cover.php");
$input = mysqli_real_escape_string($conn, $_GET['q']);
//$input = mysqli_real_escape_string($conn, $input);
$search_query = " SELECT * FROM books
                  where title LIKE '%$input%'";
$resultFromSearch = $conn->query($search_query);
if ($resultFromSearch->num_rows > 0) {
    while ($row_s = $resultFromSearch->fetch_assoc()) {
        echo coverFN($row_s);
    }
}
else{
    echo "Empty Result";
}
