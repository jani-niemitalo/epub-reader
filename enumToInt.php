<?php
/**
 * Created by PhpStorm.
 * User: aqua
 * Date: 16.4.2019
 * Time: 16:50
 */

    function enumToInt($string)
    {
        if ($string == "guest")
            return 0;
        if ($string == "user")
            return 1;
        if ($string == "uploader")
            return 2;
        if ($string == "admin")
            return 3;

    }