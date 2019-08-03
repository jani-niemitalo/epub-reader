<?php
session_start();
include("session.php");
include("adjustColor.php");


function coverFN2($db_book, $target)
{
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    $target = $target . "(". $db_book["id"]. ")";
    $coverNotFound = "
    <div class=\"content_subtitle\">
        Cover art not found!
    </div>
    ";

    if ($db_book["tn_path"] != "") {
        $imgPath = $db_book["tn_path"];
        $coverNotFound = "";
    } else {
        $imgPath = "ebook/icons/sad.png";
    }
    $returnSTR =
        "<div class=\"content\" onclick='".$target."'> 
        " . $coverNotFound . "    
            <div class =\"cover\">
                <div class=\"content_title\" style='background-color: ".adjustBrightness($color, -0.8)."!important;'>
                <b>
                    " . $db_book["title"] . "
                </b>
                </div>" .
                "<img class=\"main_img\"src=\"" . $imgPath . "\" alt='Thumbnail of the book ".$db_book["title"]."'>
                <div class=\"shadow\"></div>
            </div>
        </div>";
    return $returnSTR;

}

?>
