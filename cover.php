<?php
session_start();
include("session.php");

function coverFN2($db_book, $target)
{
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
        "<div class=\"content\" onclick='".$target."' id='".$db_book["id"]."'> 
        " . $coverNotFound . "    
            <div class =\"cover\">
                <div class=\"content_title\">
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
