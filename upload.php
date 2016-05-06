<?php
session_start();
/**
 * Created by PhpStorm.
 * User: mcwmc
 * Date: 06.05.2016
 * Time: 01:32
 */
include "config.php";
echo var_dump($_FILES);
echo var_dump($_POST);
if (isset($_FILES["uploadfile"])) {
    $file = $_FILES['uploadfile'];
    if (!empty($file['name']))
    {
        ;
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            .'0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
        $_SESSION["picdrop_lastUploadFileName"] = $rand;
        move_uploaded_file($file['tmp_name'], $storage."temp/".$rand);
        $imagick->readImage($storage."temp/".$rand);
        $imagick->writeImages($storage.$rand.".png", false); $imagick = new Imagick();
        echo "Upload ok";
        die();

    }
}


//$sqlconnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
//$sqlconnection->set_charset("utf8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
        }
        .header {
            display: table;
            background: white;
            position: absolute;
            height: 7%;
            width: 85%;
            box-shadow: 0 20px 75px gray;
            color: black;
            z-index: 100;
            line-height: 7%;
            border-bottom: 1px solid grey;
            padding-left: 15%;
        }
        .uploadcontainer {
            top: 7%;
            z-index: 0;
            background: #BFBFBF;
            color: grey;
            position: absolute;
            height: 93%;
            width: 100%;
        }
        .innerText {
            text-align: center;
            width: 100%;
            position: absolute;
            top: 20%;
            font-size: 40pt;
        }
        .ycenter {
            display: table-cell;
            vertical-align: middle;

        }
    </style>
    <script src="dropzone.js"></script>
    <script>
        function startDrop() {
            var myDropzone = new Dropzone("div#uploadcontainer", { url: "upload.php", autoProcessQueue: true});
        }
    </script>
</head>
<body onload="startDrop()">
<div class="header">
    <span class="ycenter">
        <b>PicDrop</b>
        <div style="text-align: right; width: 70%">lololo</div>
    </span>
</div>
<div class="uploadcontainer" id="uploadcontainer">
    <div class="innerText">
        <h1><i class="fa fa-picture-o" aria-hidden="true"></i></h1>
        Drop an Image here
        <span style="font-size: 18pt;"><br>or Upload</span>
        <span id="progress"></span>
    </div>
</div>
</body>
</html>
