<?php
/**
 * Created by PhpStorm.
 * User: mcwmc
 * Date: 06.05.2016
 * Time: 01:32
*/
include "config.php";
if (isset($_FILES["file"])) {
    $file = $_FILES['file'];
    if (!empty($file['name']) && ((strpos($file["type"], "image/") !== false) || (strpos($file["type"], "video/") !== false)))
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            .'0123456789');
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
        $filename = "";
        $video = 0;
        if ($file["type"] == "image/gif") {
            move_uploaded_file($file['tmp_name'], $storage . $rand . ".gif");
            $filename = $rand . ".gif";
        } elseif ($file["type"] == "image/png") {
            move_uploaded_file($file['tmp_name'], $storage . $rand . ".png");
            $filename = $rand . ".png";
        } elseif ($file["type"] == "image/jpeg") {
            move_uploaded_file($file['tmp_name'], $storage . $rand . ".jpg");
            $filename = $rand . ".jpg";
        } elseif ($file["type"] == "video/webm") {
            move_uploaded_file($file['tmp_name'], $storage . $rand . ".webm");
            $filename = $rand . ".webm";
            $video = true;
        } elseif ($file["type"] == "video/mp4") {
            move_uploaded_file($file['tmp_name'], $storage . $rand . ".mp4");
            $filename = $rand . ".mp4";
            $video = true;
        } else {
            move_uploaded_file($file['tmp_name'], $tmpdir.$rand);
            $imagick = new Imagick();
            $imagick->readImage($tmpdir.$rand);
            $imagick->writeImages($storage.$rand.".png", false); $imagick = new Imagick();
            unlink($tmpdir.$rand);
            $filename = $rand.".png";
        }
        $sqlconnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        $sqlconnection->set_charset("utf8");
        $sql = "INSERT INTO images (uniqid, imageurl, video) VALUES (\"".mysqli_real_escape_string($sqlconnection, $rand)."\", \"".mysqli_real_escape_string($sqlconnection, $cdnhostname.$filename)."\", ".$video.");";
        mysqli_query($sqlconnection, $sql);
        mysqli_close($sqlconnection);
        echo $rand;
        die();

    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>
    <script>
        function startDrop() {
            var uploader = new Dropzone("div#drop", { url: "https://sharepic.moe/upload.php",
                paramName: "file",
                maxFilesize: 30,
                autoProcessQueue: true,
                parallelUploads: 1,
                acceptedFiles: "image/*,video/webm,video/mp4"
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
                //response = JSON.parse(response)["cdnFilename"];
                document.getElementsByClassName("dz-filename")[0].innerHTML = "<br>Share this link with your friends: <a href='/" + response +"'>sharepic.moe/"+response+"</a><br>or this one if you want the raw image:  <a href='/" + response +"/raw'>sharepic.moe/"+response+"/raw</a>";
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
        <b><a href="https://sharepic.moe" style="color: black; text-decoration: none">SharePic</a></b>
        <div style="text-align: right; width: 70%"><a href="faq.html">FAQ</a> | <a href="legal.html">Legal / TOS</a> | <a href="/">Upload</a></div>
    </span>
</div>
<div class="uploadcontainer" id="uploadcontainer">
    <div class="innerText">
        <h1><i class="fa fa-picture-o" aria-hidden="true"></i></h1>
        Drop an Image here
        <span style="font-size: 18pt;"><br>or select one by clicking. <br> By uploading content, you accept our Terms of service.</span>
    </div>
</div>
<div id="drop">

</div>

</body>
</html>
