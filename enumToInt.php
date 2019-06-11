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
        else if ($string == "user")
            return 1;
        else if ($string == "uploader")
            return 2;
        else if ($string == "admin")
            return 3;
        else
            return -1;

    }