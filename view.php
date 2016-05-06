<?php
/**
 * Created by PhpStorm.
 * User: mcwmc
 * Date: 06.05.2016
 * Time: 01:32
 */
if (isset($_GET["q"])) {
if (!empty($_GET["q"])) {
    $_GET["q"] = substr_replace($_GET["q"],"/", 0, 1);
    include "config.php";
    $sqlconnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $sqlconnection->set_charset("utf8");
    if ((strpos($_GET["q"], "/raw") !== false)) {
        $imgid = mysqli_real_escape_string($sqlconnection, explode("/", $_GET["q"])[0]);
        $sql = "SELECT imageurl FROM images WHERE uniqid='$imgid';";
        $result = mysqli_fetch_assoc(mysqli_query($sqlconnection, $sql));
        header("Location: //".$result["imageurl"]);
        die();
    }
    $imgid = mysqli_real_escape_string($sqlconnection, $_GET["q"]);
    $sql = "SELECT imageurl FROM images WHERE uniqid='$imgid';";
    $result = mysqli_fetch_assoc(mysqli_query($sqlconnection, $sql));

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

            .lepicture {
                text-align: center;
            }
            }
            .ycenter {
                display: table-cell;
                vertical-align: middle;

            }
        </style>
        <script>


        </script>
    </head>
    <body>
    <div class="header">
    <span class="ycenter">
        <b>PicDrop</b>
        <div style="text-align: right; width: 70%"><a href="/">Upload</a></div>
    </span>
    </div>
    <div class="uploadcontainer" id="uploadcontainer">
        <div class="innerText">
            <?php if (isset($result["imageurl"])) {
            ?>
                <img src="//<?php echo $result["imageurl"]; ?>">
        <?php
    } else { ?>
            <h1><i class="fa fa-picture-o" aria-hidden="true"></i></h1>
            Couldn't find this image :c
            <span style="font-size: 18pt;"><br>or select one by clicking</span> <?php } ?>
        </div>
    </div>
    <div id="drop">

    </div>

    </body>
    </html>

    <?php
}
}