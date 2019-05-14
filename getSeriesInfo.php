<?php
session_start();
include("session.php");
require_once("enumToInt.php");
if (enumToInt($_SESSION["perm_lvl"]) < 3) {
    header("Location: library.php");
}
$result = $conn->query("SELECT id FROM books");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Epub-Reader - <?php require("version.txt") ?></title>
    <meta charset="utf-8"/>
    <meta name="google-signin-client_id"
          content="800988841068-vd3v312eikhgvi9g9k51r3t6c8kn5meu.apps.googleusercontent.com">
    <link rel="stylesheet" href="styles.css">
    <script>
        async function f() {
            /*
            */
            <?php
            $phpArray = array();
            while ($row = $result->fetch_assoc()) {
                $phpArray[] = $row["id"];
            }
            $js_array = json_encode($phpArray);
            echo "var javascript_array = " . $js_array . ";\n";
            ;?>
            //document.getElementById("results").innerHTML += javascript_array;
            delayedIteration(0, javascript_array);

        }

        function delayedIteration(index, iterableArray) {
            if (index >= iterableArray.length) {
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("GET", "goodreadsHandler.php?id=" + iterableArray[index], true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlhttp.onload = function () {
                //console.log('Signed in as: ' + xmlhttp.responseText);

                document.getElementById("results").innerHTML += xmlhttp.responseText + "<br >";

                //window.location = "library.php";
            };
            //console.log(input);


            //console.log(data);
            xmlhttp.send();


            sleep(5000);
            //console.log(iterableArray[index]);
            index += 1;
            setTimeout(delayedIteration.bind({}, index, iterableArray), 3500);
        }

        const sleep = (milliseconds) => {
            return new Promise(resolve => setTimeout(resolve, milliseconds))
        }


    </script>
</head>
<body style="height: 1080px; ">
<div id="results" onclick="f()" style="height: 100%;">

</div>
</body>
