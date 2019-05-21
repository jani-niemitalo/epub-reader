<?php
//echo "cleaning up <br>";
function cleanUp($targetDir)
{
    //echo $targetDir;


    $files = glob($targetDir . "*"); // get all file names
    foreach($files as $file){ // iterate files
        echo "<script>console.log('Cleaning up ".$file . "');</script>";
        if(is_file($file))
            unlink($file); // delete file
    }
}