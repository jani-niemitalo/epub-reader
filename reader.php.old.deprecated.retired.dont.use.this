<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        require_once('epub.php');
        require_once("mysqlConnection.php");
        $book_id = mysqli_real_escape_string($conn, $_GET['id']);
        //$booksQuery = "SELECT * FROM books WHERE id=$book_id";
        //$booksQueryResult = $conn->query("SELECT * FROM books WHERE id=$book_id");
        $result = $conn->query("SELECT * FROM books WHERE id=$book_id")->fetch_assoc();
        $path = $result["path"];
        //echo $path;


    ?>

    <script src="epub.min.js"></script>
    <script src="jszip-3.1.5/dist/jszip.min.js"></script>
    <div class="book_view_container">
    <div id="area"></div>
    <a id="prev" href="#prev" class="arrow">‹</a>
    <a id="next" href="#next" class="arrow">›</a>
    </div>
    <script type="text/javascript" src="storage.js"></script>
    <script>
        var next = document.getElementById("next");
        var prev = document.getElementById("prev");
        var currentPage = document.getElementById("current-percent");
        var book = ePub("<?php echo $path;?>");
        var rendition = book.renderTo("area", { flow: "scrolled-doc", width: "85vw", height: "97vh", display: "block", color: "#fff" });
        var loc = "";
        var displayed;
        var xhttp2;
        xhttp2 = new XMLHttpRequest();
        xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                loc = this.responseText;
                if (loc == "") {
                    console.log("Line 44 " + loc);
                    displayed = rendition.display();
                }else {
                    console.log("Line 47 " + loc);
                    displayed = rendition.display(loc);
                }

            }
        };
        xhttp2.open("GET", "bookmark.php?id=<?php echo $book_id;?>&location=", true);
        xhttp2.send();
        //console.log(loc);
        console.log("Line 56 " + loc);
        //displayed = rendition.display(loc);



        var next = document.getElementById("next");
		next.addEventListener("click", function(e){
			rendition.next();
			e.preventDefault();
		}, false);
        var prev = document.getElementById("prev");
		prev.addEventListener("click", function(e){
			rendition.prev().then();
			e.preventDefault();
		}, false);
        var keyListener = function(e){
			// Left Key
			if ((e.keyCode || e.which) == 37) {
				rendition.prev();
			}
			// Right Key
			if ((e.keyCode || e.which) == 39) {
				rendition.next();
			}
		};
        rendition.on("keyup", keyListener);
        document.addEventListener("keyup", keyListener, false);



        book.ready.then(function(){
            displayed.then(function(){
                rendition.themes.register("dark", "styles.css");
                rendition.themes.select("dark");
            });
            rendition.on('relocated', function(location){
                console.log("Line 81 " + rendition.currentLocation().start.cfi);
                var xhttp;
                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log("Line 86 " + this.responseText);
                    }
                };
                console.log("Line 89 Sending location " + rendition.currentLocation().start.cfi + " To database ")
                xhttp.open("GET", "bookmark.php?id=<?php echo $book_id;?>&location="+rendition.currentLocation().start.cfi, true);
                xhttp.send();
            });

        });


    </script>
</body>
</html>
