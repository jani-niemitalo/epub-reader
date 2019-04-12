<!DOCTYPE html>
<html>
<head>
  <?php
  require_once("DB/mysqlConnection.php");
  require_once("Helpers/cover.php");
  ?>
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
function showResults() {

    var input = document.getElementById("search").value;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("search_results").innerHTML = "<div class=\"separator\" id=\"searchSeparator\">\n" +
                "  <h1 class=\"separator_t\"> Search Results </h1>\n" +
                "</div>";
            document.getElementById("search_results").innerHTML += this.responseText;
        }

    }
    ;
    xmlhttp.open("GET", "Helpers/search.php?q=" + input, true);
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
      <input type="text" id="search" placeholder="Search" style="height: 90%;">
      <button type="submit" style="height: 100%;" onclick="showResults()">Search</button>
  </div>
  <div class="grid" id="search_results" id="recent_lib" style="margin-top: 50px">
  </div>
  <div class="separator">
      <h1 class="separator_t"> Recently read </h1>
  </div>

<div class="grid" id="recent_lib" >
  <?php
    $user_id = "123";
    $latest_query = " SELECT * FROM bookmarks
                      INNER JOIN books
                      ON bookmarks.book_id = books.id
                      WHERE user_id = $user_id
                      ORDER BY ts DESC";
    $latest = $conn->query($latest_query);
    if ($latest->num_rows > 0) {
      while($row2 = $latest->fetch_assoc()) {
        echo coverFN($row2, "reader.php?id=");
      }
    }
    ?>
</div>
<div class="separator">
  <h1 class="separator_t"> All Books </h1>
</div>
<div class="grid">


<?php
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
