<!DOCTYPE html>
<html>
<head>
<title>Epub-Reader - <?php require("version.txt")?></title>
<meta charset="utf-8"/>
<link rel="stylesheet" href="Helpers/styles.css">
<script type="text/javascript">
function parse() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML = "OK!";
            }
        };
        xmlhttp.open("GET", "parseLibrary.php", true);
        xmlhttp.send();

}
</script>
</head>
<body>

<div id="header">
<button id="parseButton" type="button" style="
height: 100%;" onclick="parse()">Change Content</button>
<div id="result" style="color: #fff; height: 100%;">
</div>
</div>
<div style="height: 50px;">

</div>

<div class="grid">


<?php
require_once("DB/mysqlConnection.php");
require_once("Helpers/cover.php");


$booksQuery = "SELECT * FROM books";
$booksQueryResult = $conn->query($booksQuery);

if ($booksQueryResult->num_rows > 0) {
    // output data of each row
    while($row = $booksQueryResult->fetch_assoc()) {
        echo coverFN($row);
    }
} else {
    echo "0 results";
}
$conn->close();
?>
</div>
</body>

</html>
