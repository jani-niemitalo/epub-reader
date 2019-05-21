<?php
require_once("epub.php");
require_once("mime2ext.php");

function parseFile($file, $dir)
{
    $id = rand();
    $returnVar = "";
    try {
        $epub = new EPub($file);
    } catch (Exception $e) {
        echo $e;
    }

    $img = $epub->Cover();

    if ($img['found']) {
        //echo getcwd();
        $img_path = $dir.$id .".". mime2ext($img['mime']);
        //echo $img_path;
        file_put_contents($img_path , $img['data']);
    }


    $returnVar .= "
<div class='up_content'>
    <div style='max-width: 250px'>
        <img class='up_img' src='" . $img_path . "'>
    </div>
        <div style='display: flex; flex-direction: column;'>
            <div class='up_content_sub'><h3>Title</h3>".htmlspecialchars($epub->Title())."</div>
            <div class='up_content_sub'><h3>Subjects</h3>".htmlspecialchars(join($epub->Subjects()))."</div>
            <div class='up_content_sub'><h3>ISBN</h3>".htmlspecialchars($epub->ISBN())."</div>
            <div class='up_content_sub'><h3>Authors</h3>".htmlspecialchars(join($epub->Authors()))."</div>
        </div>

</div>";

    return $returnVar;
}

