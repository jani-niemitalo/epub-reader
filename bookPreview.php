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
            xmlhttp.onload = function() {
                console.log("Book permissions updated:\n"+ xmlhttp.responseText);
            };
            var data = ''
                + 'id=' + "<?php echo $db_book["id"]?>"
                + '&value=' + document.getElementById("permSelector").value;
            console.log(data);
            xmlhttp.send(data);


        }
    </script>
</head>
<body>
<?php

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
            <h3 class="book_info_H3">Title </h3> <?php echo htmlspecialchars($epub->Title()) ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">Genre </h3> <?php echo htmlspecialchars(join($epub->Subjects())) ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">Publisher </h3> <?php echo htmlspecialchars($epub->Publisher()) ?>
        </div>
        <div class="book_info_ta">
            <h3 class="book_info_H3">ISBN </h3> <?php echo htmlspecialchars($epub->ISBN()) ?>
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

        if (enumToInt($_SESSION["perm_lvl"]) >= 2) {
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
