<!DOCTYPE html>
<html>
<head>
<?php
require_once("mysqlConnection.php");
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = $conn->query("SELECT * FROM books WHERE id=$book_id")->fetch_assoc();
$path = $result["path"];
$title = $result["title"]

?>
<title><?php echo $title;?></title>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/detect_swipe/2.1.1/jquery.detect_swipe.min.js"></script>
<meta charset="utf-8"
      name='viewport'
      content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'
/>
<link rel="stylesheet" href="styles.css">
<style type="text/css">
  body {
    display: flex;
    -webkit-align-items: center;
    -webkit-justify-content: center;
  }
  #viewer {
    width: 290px;
    height: 580px;
    box-shadow: 0 0 4px #ccc;
    padding: 10px 10px 0px 10px;
    margin: 5px auto;
    background: white;
  }
  @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 667px) {
      #viewer {
        height: 96.5%;

      }
      .arrow {
        //position: inherit;
        //display: none;
      }
  }
</style>
</head>
<body>
    <script src="epub.min.js"></script>
    <script src="jszip-3.1.5/dist/jszip.min.js"></script>
    <div class="book_view_container">
    <div id="area" class="scrolled"></div>
    <a id="prev" href="#prev" class="arrow">‹</a>
    <a id="next" href="#next" class="arrow">›</a>
    </div>
    <script type="text/javascript" src="storage.js"></script>
    <script>
        var next = document.getElementById("next");
        var prev = document.getElementById("prev");
        var currentPage = document.getElementById("current-percent");
        var book = ePub("<?php echo $path;?>");
        if (window.screen.width >= 1100)
        {
            var rendition = book.renderTo("area", { flow: "paginated", width: "85vw", height: "97vh", display: "block", color: "#fff" });
        }
        else {
            var rendition = book.renderTo("area", { flow: "scrolled-doc", width: "100vw", height: "97vh", display: "block", color: "#fff" });
        }
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
            if ((e.keyCode || e.which) == 27) {
                window.location.href = "library.php";
            }
		};
        rendition.on("keyup", keyListener);
        document.addEventListener("keyup", keyListener, false);
        document.addEventListener('touchstart', handleTouchStart, false);
        document.addEventListener('touchmove', handleTouchMove, false);

        var xDown = null;
        var yDown = null;

        function getTouches(evt) {
          return evt.touches ||             // browser API
                 evt.originalEvent.touches; // jQuery
        }

        function handleTouchStart(evt) {
            const firstTouch = getTouches(evt)[0];
            xDown = firstTouch.clientX;
            yDown = firstTouch.clientY;
        };

        function handleTouchMove(evt) {
            if ( ! xDown || ! yDown ) {
                return;
            }

            var xUp = evt.touches[0].clientX;
            var yUp = evt.touches[0].clientY;

            var xDiff = xDown - xUp;
            var yDiff = yDown - yUp;

            if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
                if ( xDiff > 0 ) {
                    console.log("Left Swipe");
                    rendition.next();
                } else {
                    console.log("Right Swipe");
                    rendition.prev();
                }
            } else {
                if ( yDiff > 0 ) {
                    /* up swipe */
                } else {
                    /* down swipe */
                }
            }
            /* reset values */
            xDown = null;
            yDown = null;
        };
        rendition.on('relocated', function(location){
            console.log(location);
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
        book.ready.then(function(){
            displayed.then(function(){
                rendition.themes.register("dark", "styles.css");
                rendition.themes.select("dark");
                rendition.themes.fontSize("100%");

            });
        });



    </script>
</body>
</html>
