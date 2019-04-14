<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 7.4.2019
 * Time: 4:10
 */
require_once("mysqlConnection.php");
echo $_GET['q'] . "<br>";
$search_query = mysqli_real_escape_string($conn,$_GET['q']);
$sql = $search_query;
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
echo "<br>";
echo "<table border='1'>";
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    echo "<tr>";
    foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
        echo "<td>" . $value . "</td>"; // I just did not use "htmlspecialchars()" function.
    }
    echo "</tr>";
}
echo "</table>";