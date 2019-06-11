<?php
function optimizeImage($conn, $path, $id){
    require ("vendor/autoload.php");
    try {
        $api = new ImageOptim\API("kxvmgbkkbj");
        $imageData = $api->imageFromPath($path)->resize(250, 340, 'fit')->getBytes();

    } catch (Exception $e) {
        linLog($conn, $e, $id);
        return(1);
    }

    file_put_contents($path, $imageData);
    linLog($conn, "[OK!] Optimized Image: " . $path, $id);
    return(0);
}