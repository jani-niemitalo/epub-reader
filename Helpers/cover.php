<?php
function coverFN($db_book, $target = "bookPreview.php?id=", $col = "id")
{
    require_once("adjustColor.php");
    $returnSTR = "";
    $imgPath = "";
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    $color_dark = adjustBrightness($color, -0.70);
    $color_pure = adjustBrightness($color, 0.80);
    $coverNotFound = "
    <div class=\"content_subtitle\">
        Cover art not found!
    </div>
    ";

    if ($db_book["tn_path"] != "") {
       $imgPath = $db_book["tn_path"];
       $coverNotFound = "";
    }
    else {
      $imgPath = "ebook/icons/sad.png";
    }

    $returnSTR =
    "<a class=\"content\" style=\"background-color:". $color_dark ." ;color:". $color_pure .";text-decoration: none;\" href=\"". $target. $db_book[$col]. "\">
    ". $coverNotFound ."

    <div class =\"cover\">
        <div class=\"content_title\">
        <b>
            ". $db_book["title"]."
        </b>
        </div>".
        "<img class=\"main_img\"src=\"". $imgPath ."\" alt=\"bookPreview.php?id=". $db_book["id"] ."\">
        <div class=\"shadow\"></div>
    </div>
    </a>";
    return $returnSTR;
}
?>
