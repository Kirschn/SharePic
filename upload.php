<?php
session_start();
/**
 * Created by PhpStorm.
 * User: mcwmc
 * Date: 06.05.2016
 * Time: 01:32
 */
include "config.php";
if (isset($_FILES["file"])) {
    $file = $_FILES['file'];
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
        if ($file["type"] == "image/gif") {
            move_uploaded_file($file['tmp_name'], $storage.$rand.".gif");
            echo $rand.".gif";
        } else {
            move_uploaded_file($file['tmp_name'], $storage."temp/".$rand);
            $imagick = new Imagick();
            $imagick->readImage($storage."temp/".$rand);
            $imagick->writeImages($storage.$rand.".png", false); $imagick = new Imagick();
            unlink($storage."temp/".$rand);
            echo $rand.".png";
        }
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
        #drop {
            height: 93%;
            width: 100%;
            position: absolute;
            top: 7%;
            text-align: center;
        }
        .dz-preview {
            width: 100%;
            text-align: center;
            padding-top: 20%;
        }
        .dz-details {
            position: absolute;
            width: 100%;
        }
        .dz-image {
            top: 20%;
        }
        .dz-upload {
            display: none;
        }
        .dz-progress {
            display: none;
        }
        .dz-success-mark {
            display: none;
        }
        .dz-error-mark {
            display: none;
        }
        .dz-error-message {
            display: none;
        }
        #progress {
            position: absolute;
            left: 30%;
            width: 40%;
            height: 30px;
            background-color: #ddd;
            z-index: 1001;
            border-radius: 0.2%;
        }

        #progressBar {
            position: absolute;
            width: 0;
            height: 100%;
            background-color: #4CAF50;
        }

        #progressBarLabel {
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
    <script src="dropzone.js"></script>
    <script>
        function startDrop() {
            var uploader = new Dropzone("div#drop", { url: "upload.php",
                maxFilesize: 30,
                autoProcessQueue: true,
                parallelUploads: 1,
                acceptedFiles: "image/*,application/pdf"
                });
            uploader.on("sending", function(file, xhr, formData) {
               console.log("Filesize: " + file.size);
            });
            uploader.on("uploadprogress", function (progress, bytesSent) {
                console.log("Progress: " + progress.upload.progress);
                if (progress.upload.progress !== 100) {
                    document.getElementById("progressBar").style.width = progress.upload.progress + "%";
                    document.getElementById("progressBarLabel").innerHTML = Math.floor(progress.upload.progress) + "%";
                } else {
                    document.getElementById("progressBar").style.width = "100%";
                    document.getElementById("progressBarLabel").innerHTML = "Processing...";
                }
            });
            uploader.on("success", function (file, response) {
               console.log(response);
                document.getElementById("progressBarLabel").innerHTML = "Ready!";
                document.getElementsByClassName("dz-filename")[0].innerHTML = "<br>Your Image can be found here: <a href='view.php?q=" + response +"'>picdrop.tk/view.php?q="+response+"</a>";
                uploader.disable();
            });
            uploader.on("addedfile", function (file) {
                document.getElementsByClassName("innerText")[0].innerHTML = "";
                uploader.clickable = false;
                document.getElementsByClassName("dz-filename")[0].innerHTML += '\n<div id="progress"><div id="progressBar"><div id="progressBarLabel">0%</div></div></div>';
            })
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
        <span style="font-size: 18pt;"><br>or select one by clicking</span>
    </div>
</div>
<div id="drop">

</div>

</body>
</html>