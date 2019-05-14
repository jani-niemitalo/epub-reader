<?php
session_start();
include("session.php");
require_once('epub.php');
require_once("cover.php");
require_once("enumToInt.php");

$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$booksQuery = "SELECT * FROM books WHERE id=$book_id";
$booksQueryResult = $conn->query($booksQuery);
$db_book = $booksQueryResult->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>

    <title><?php echo $db_book["title"]; ?></title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="styles.css">
    <script>
        function parse() {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("POST", "updateBookPermission.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlhttp.onload = function () {
                console.log("Book permissions updated:\n" + xmlhttp.responseText);
            };
            var data = ''
                + 'id=' + "<?php echo $db_book["id"]?>"
                + '&value=' + document.getElementById("permSelector").value
                + '&col=permission_lvl';
            console.log(data);
            xmlhttp.send(data);


        }

        var initialData = "";

        function updateData(selection) {
            if (document.getElementById(selection).value === initialData)
                return;
            console.log(selection);
            var userIsSure = confirm("ARE YOU SURE YOU WANT TO UPDATE DATA?");
            if (!userIsSure)
                return;
            switch (selection) {
                case "Title":
                    uploadData("title", document.getElementById(selection).value);
                    //console.log("Title");
                    break;
                case "ISBN" :
                    uploadData("isbn", document.getElementById(selection).value);
                    //console.log("ISBN");
                    break;
                case "series":
                    uploadData("series", document.getElementById(selection).value);
                    //console.log("series");
                    break;

                default:
                    console.log("This Shouldn't Happen...");


            }

            function uploadData(col, value) {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.open("POST", "updateBookPermission.php", true);
                xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlhttp.onload = function () {
                    console.log("Book permissions updated:\n" + xmlhttp.responseText);
                };
                var data = ''
                    + 'id=' + "<?php echo $db_book["id"]?>"
                    + '&value=' + value.trim()
                    + '&col=' + col;
                console.log(data);
                xmlhttp.send(data);
            }


        }

        function querySeries() {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("GET", "goodreadsHandler.php?id=" + <?php echo $db_book["id"]?>, true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlhttp.onload = function () {
                //console.log('Signed in as: ' + xmlhttp.responseText);

                location.reload();

                //window.location = "library.php";
            };
            //console.log(input);


            //console.log(data);
            xmlhttp.send();
        }

        function saveInitialValue(selection) {
            var lel = document.getElementById(selection).value;
            console.log(lel);
            initialData = lel;
        }
    </script>
</head>
<body>
<?php
$authorized = false;
if (enumToInt($_SESSION["perm_lvl"]) >= 2) {
    $authorized = true;
}

try {
    $epub = new EPub($db_book["path"]);
} catch (Exception $e) {
    echo $e;
}

if ($db_book["tn_path"] == "") {
    $var = "lelkek";
} else {
    $var = "keklel";
}

?>
<div class="bookViewWrapper">
    <?php echo coverFN($db_book, "reader.php?id="); ?>
    <div class="book_info">
        <div class="book_info_ta">
            <h3 class="book_info_H3">Title </h3> <?php
            if ($authorized) {
                echo '<input 
                id="Title" 
                type="text" 
                onfocus="saveInitialValue(`Title`)"
                onfocusout="updateData(`Title`)"
                value="' . htmlspecialchars($db_book["title"]) . '">';
            } else {
                echo htmlspecialchars($db_book["title"]);
            }
            ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">Genre </h3> <?php echo htmlspecialchars(join($epub->Subjects())); ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">Publisher </h3> <?php echo htmlspecialchars($epub->Publisher()); ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">ISBN </h3> <?php
            if ($authorized) {
                echo '<input 
                id="ISBN" 
                type="text" 
                onfocus="saveInitialValue(`ISBN`)"
                onfocusout="updateData(`ISBN`)" 
                value="' . htmlspecialchars($db_book["isbn"]) . '">';
            } else {
                echo htmlspecialchars($db_book["isbn"]);
            }
            ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">Series </h3> <?php
            if ($authorized) {
                if (htmlspecialchars($db_book["series"]) == "") {
                    echo "<div id=\"button\" onclick=\"querySeries()\">Query series info</div>";
                } else {
                    echo '<input 
                    id="series" 
                    type="text" 
                    onfocus="saveInitialValue(`series`)" 
                    onfocusout="updateData(`series`)" 
                    value="' . htmlspecialchars($db_book["series"]) . '">';
                }

            } else {

                echo htmlspecialchars($db_book["series"]);
            }

            ?>
        </div>

        <?php

        $bookPerm1 = "";
        $bookPerm2 = "";
        $bookPerm3 = "";
        $bookPerm4 = "";
        if (enumToInt($db_book["permission_lvl"]) == 0)
            $bookPerm1 = "selected";
        if (enumToInt($db_book["permission_lvl"]) == 1)
            $bookPerm2 = "selected";
        if (enumToInt($db_book["permission_lvl"]) == 2)
            $bookPerm3 = "selected";
        if (enumToInt($db_book["permission_lvl"]) == 3)
            $bookPerm4 = "selected";

        if ($authorized) {
            echo "<div>
                      <h3 class=\"book_info_H3\">Permission LVL </h3>
                      <select onchange=\"parse()\" id=\"permSelector\">
                          <option value=\"guest\"       $bookPerm1>1</option>
                          <option value=\"user\"        $bookPerm2>2</option>
                          <option value=\"uploader\"    $bookPerm3>3</option>
                          <option value=\"admin\"       $bookPerm4>4</option>
                      </select> 
                  </div>
                    ";
        }
        ?>

    </div>
    <div class="description">
        <?php echo $epub->Description() ?>
    </div>


</div>
;
</body>
</html>
