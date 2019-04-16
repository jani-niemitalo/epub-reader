<!DOCTYPE html>
<html>
<head>
    <?php
    session_start();
    require_once("session.php");
    require_once("enumToInt.php");
    if (enumToInt($_SESSION["perm_lvl"]) < 3) {
        header("Location: library.php");
    }
    ?>
</head>
<title>Epub-Reader - <?php require("version.txt") ?></title>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css">
<script type="text/javascript">
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
                document.getElementById("search_results").innerHTML += this.responseText;
            }

        }
        ;
        xmlhttp.open("GET", "Helpers/search2.php?q=" + input, true);
        xmlhttp.send();
    }
</script>
<body>
<textarea rows="10" id="search" placeholder="Search" style="height: 90%;">
</textarea>
<button type="submit" style="height: 100%;" onclick="showResults()">Search</button>
<div id="search_results">

</div>