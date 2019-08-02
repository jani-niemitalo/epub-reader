<?php
require_once("cover.php");
require_once("enumToInt.php");
if(!isset($_SESSION))
{
    session_start();
}
//include("session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>


    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="194x194" href="/favicon-194x194.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="Epub-Reader">
    <meta name="application-name" content="Epub-Reader">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#333">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Epub-Reader - <?php require("version.txt") ?></title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="styles.css">
    <script type="text/javascript">
        var glob_ID = -1;

        function closeModal(modal) {
            document.getElementById("modal-content1").innerHTML = "";
            modal.style.display = "none";
        }

        function parseLibrary() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (confirm("OK! Reload?"))
                        location.reload();
                }
            };
            xmlhttp.open("GET", "parseLibrary.php", true);
            xmlhttp.send();


        }

        function parse(id) {
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
                + 'id=' + id
                + '&value=' + document.getElementById("permSelector").value
                + '&col=permission_lvl';
            console.log(data);
            xmlhttp.send(data);


        }

        function search(query) {
            var input = query;
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
                    closeModal(modal3);
                    closeModal(modal2);
                    closeModal(modal);
                    window.scrollTo(0, 0);
                }

            }
            ;
            xmlhttp.open("GET", "search.php?q=" + input, true);
            xmlhttp.send();
        }

        function showResults() {


            var input = document.getElementById("search").value;
            search(input);
        }


        function info(id) {
            glob_ID = id;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("modal-content1").innerHTML = this.responseText;
                }

            }
            ;
            xmlhttp.open("GET", "bookInfo.php?id=" + id, true);
            xmlhttp.send();
            modal.style.display = "block";
        }

        function contMenu() {
            modal2.style.display = "block";
        }
        function seriesDD() {
            modal3.style.display = "block";
        }

        function reader(id) {
            window.location.replace("reader.php?id=" + id);
        }

        var initialData = "";

        function updateData(selection, id) {
            if (document.getElementById(selection).value === initialData)
                return;
            console.log(selection);
            var userIsSure = confirm("ARE YOU SURE YOU WANT TO UPDATE DATA?");
            if (!userIsSure)
                return;
            switch (selection) {
                case "Title":
                    uploadData("title", document.getElementById(selection).value, id);
                    //console.log("Title");
                    break;
                case "ISBN" :
                    uploadData("isbn", document.getElementById(selection).value, id);
                    //console.log("ISBN");
                    break;
                case "series":
                    uploadData("series", document.getElementById(selection).value, id);
                    //console.log("series");
                    break;

                default:
                    console.log("This Shouldn't Happen...");


            }

            function uploadData(col, value, id) {
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
                    + 'id=' + id
                    + '&value=' + value.trim()
                    + '&col=' + col;
                console.log(data);
                xmlhttp.send(data);
            }


        }

        function querySeries(id) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("GET", "goodreadsHandler.php?id=" + id, true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlhttp.onload = function () {
                switch (xmlhttp.responseText) {
                    case "0":
                        console.log("Success");
                        break;
                    case "1":
                        console.log("TODO: Implement search with no ISBN;")
                        break;
                    case "2":
                        console.log("No goodreads_bookid found");
                        break;
                    case "3":
                        console.log("No goodreads_workid found");
                        break;
                    case "4":
                        console.log("No goodreads_series found");
                        break;
                    case "5":
                        console.log("SQL ERROR! With Title");
                        break;
                    case "6":
                        console.log("SQL ERROR! With SN");
                        break;
                    case "7":
                        console.log("No Input");
                        break;
                }
                if (glob_ID === id) {
                    info(id);
                }

                //location.reload();
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

        function searchSeries(series) {
            search(series);

        }

    </script>
</head>
<body>
<div id="header">
    <div type="button" class="button" id="menu-icon" style="height: 100%; font-size: 200%" onclick="seriesDD()">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
    </div>

    <input type="text" id="search" placeholder="Search" style="height: 90%;">

    <div type="button" class="button" id="menu-icon" style="height: 100%; font-size: 200%" onclick="contMenu()">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
    </div>
    <script>
        function logout() {
            window.location = "logout.php"
        }
    </script>
</div>
<div class="grid" id="search_results" id="recent_lib" style="margin-top: 50px">
</div>

<div class="grid" id="recent_lib">
    <?php
    $user_id = mysqli_escape_string($conn, $_SESSION['id']);
    $latest_query = " SELECT * FROM bookmarks
                      INNER JOIN books
                      ON bookmarks.book_id = books.id
                      WHERE user_id = $user_id
                      ORDER BY ts DESC limit 6";
    $latest = $conn->query($latest_query);
    if ($latest->num_rows > 0) {
        echo '<div class="separator" >
                    <h1 class="separator_t" > Recently read </h1 >
                </div >
                <div id="recent_lib_sub">';
        while ($row2 = $latest->fetch_assoc()) {
            echo coverFN2($row2, "reader");
        }
        echo '</div>';
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
    $count = 0;
    if ($booksQueryResult->num_rows > 0) {
        // output data of each row
        while ($row = $booksQueryResult->fetch_assoc()) {

            if (enumToInt($row["permission_lvl"]) <= enumToInt($_SESSION["perm_lvl"]))
                echo coverFN2($row, 'info');
            else if ($row["uploader"] == $user_id)
                echo coverFN2($row, 'info');
        }
        //echo $count;
    } else {
        echo "0 results";
    }
    ?>
</div>
<div id="myModal" class="modal">
    <div class="modal-content" id="modal-content1">
    </div>
</div>
<div id="contMenu" class="modal">
    <div class="modal-content" id="modal-content2">
        <?php
        if (enumToInt($_SESSION["perm_lvl"]) > 0) {
            echo '<div class="button" onclick="window.location = \'upload.php\'">Upload</div>';
        }
        ?>
        <div class="button" onclick="alert('This does nothing yet, but you can keep clicking it OwO')" >Settings</div>
        <div class="button" onclick="logout()" >Logout</div>
        <?php
        if (enumToInt($_SESSION["perm_lvl"]) >= 2)
            echo '<div id=\"parseButton\" class="button" onclick="parseLibrary()">Change Content</div>';
        ?>
    </div>
</div>
<div id="seriesDD" class="modal">
    <div class="modal-content" id="modal-content3">
        <?php
        $seriesQuery = "select distinct series from books where series != \"\" order by series";
        $q1 = $conn->query($seriesQuery);
        if ($q1->num_rows > 0) {
        while ($row = $q1->fetch_assoc()) {
                echo '
<div class="button" id="seriesButton" onclick="searchSeries(\''.$row["series"].'\')">
    '.$row["series"].'
</div>';

            }
        }

        ?>
    </div>
</div>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    var modal2 = document.getElementById("contMenu");
    var modal3 = document.getElementById("seriesDD");

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            closeModal(modal);
        }
        if (event.target == modal2)
        {
            closeModal(modal2);
        }
        if (event.target == modal3)
        {
            closeModal(modal3);
        }
    };
    // Get the input field
    var input = document.getElementById("search");

    // Execute a function when the user releases a key on the keyboard
    input.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.key === "Enter") {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            //document.getElementById("myBtn").click();
            showResults();
        }
    });

    window.addEventListener("keyup", function (event) {

        if (event.key === "Escape"){
            closeModal(modal);
            closeModal(modal2);
            closeModal(modal3)
        }
    });
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                // Registration was successful
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }


</script>
</body>

</html>
