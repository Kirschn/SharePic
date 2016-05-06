<?php
/**
 * Created by PhpStorm.
 * User: mcwmc
 * Date: 06.05.2016
 * Time: 01:32
 */
if (isset($_GET["q"])) {
    if (!empty($_GET["q"])) {
        include "view.php";
    } else {
        include "upload.php";
    }
} else {
    include  'upload.php';
}