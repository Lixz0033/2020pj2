<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="css/Reset.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="bootstrap-4.5.0-dist/css/bootstrap.min.css">
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="1">
<div class="navigation fixed-top">
    <ul>
        <img id="user_default_image" class="nav_list_left rounded" src="../img/nav/user_default_image.jpg" width="40"
             height="40">
        <li id="nav_home" class="nav_list_left">
            <a href="index.php?">Home</a>
        </li>
        <li id="nav_browser" class="nav_list_left">
            <a href="browser.php?">Browser</a>
        </li>
        <li id="nav_search" class="nav_list_left">
            <a href="search.php?">Search</a>
        </li>
        <li class="nav_list_right">
            <?php
            require_once('config.php');
            if (!isset($_SESSION['Username'])) {
                paintNotLogin();
            } else {
                paintLogin();
            }
            ?>
        </li>
    </ul>
</div>
<div class="container" style="overflow: hidden;width: 800px;height: 400px;">
    <div class="wrap" style="">
        <?php getHead(); ?>
    </div>
</div>
<div class="container">
    <?php getRandomImage(); ?>
</div>
<div class="modal-footer bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-3 col-lg-3">
                <a href="https://www.baidu.com/index.php" style=" color: white;">关于我们</a>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
                <a href="https://www.baidu.com/index.php" style=" color: white;">联系方式</a>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
                <a href="https://www.baidu.com/index.php" style=" color: white;">更多</a>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
                <img class="rounded" src="../img/index/home_egg.PNG" width="100em" height="100em" alt="二维码彩蛋">
            </div>
        </div>
        <p class="" style="margin-top: -2em;">Copyright &copy; 2019-2020 Web fundamental.All Rights
            Reserved.19302010033╮(￣▽￣)╭</p>
    </div>
</div>
<div id="aside">
    <img src="../img/index/refresh.png" width="80" height="80" alt="refresh"
         id="btn-refresh" onclick="window.location.reload()">
    <br>
    <a href="#" target="_parent">
        <img src="../img/index/upward.png" width="80" height="80" alt="upward">
    </a>
</div>
<?php
echo "<script src='bootstrap-4.5.0-dist/js/bootstrap.min.js'></script>";
echo "<script src='jQuery/jquery-3.3.1.min.js'></script>";
?>
</body>
</html>
<?php
function getRandomImage()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select * from travelimage order by rand() limit 6';
        $result = $pdo->query($sql);

        echo '<div class="row">';
        for ($j = 0; $j < 3; $j++) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            paintSingle($row);
        }
        echo '<div class="row"></div>&nbsp;&nbsp;';
        for ($j = 3; $j < 6; $j++) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            paintSingle($row);
        }
        echo '</div>';

        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function paintSingle($id)
{
    echo '<div class="col-sm-4 col-md-4 col-lg-4 text-dark">';
    echo '<div class="crop mx-auto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    echo '<a href="detail.php?id=' . $id['ImageID'] . '"><img class="rounded img-fluid" src="' . IMAGE_ROOT . $id['PATH'] . '" alt="图片损坏"></a>';
    echo '</div><hr>';
    echo '<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $id['Title'] . '</h5>';
    if ($id['Description'] === null)
        echo '<p>No description</p>';
    else
        echo '<p>' . $id['Description'] . '</p>';
    echo '<hr></div>';
}

function getHead()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT ImageID, count( * ) AS count FROM travelimagefavor GROUP BY ImageID ORDER BY count DESC';
        $result = $pdo->query($sql);
        $row1 = $result->fetch(PDO::FETCH_ASSOC);
        $sql = 'SELECT * FROM travelimage WHERE ImageID = ' . $row1['ImageID'] . ';';
        $result = $pdo->query($sql);
        $row2 = $result->fetch(PDO::FETCH_ASSOC);
        echo '<a href="detail.php?id=' . $row1['ImageID'] . '"><img class="rounded" src="' . IMAGE_ROOT . $row2['PATH'] . '" alt="损坏"></a>';
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function paintNotLogin()
{
    echo '<div><a href="login.php">Log in</a></div>';
}

function paintLogin()
{
    echo '<div><a href="#">My account ▼</a>';
    echo '<ul><li id="upload_image" class="nav_list_right_li">';
    echo '<a href="upload.php?">';
    echo '<img src="../img/nav/upload.png" width="16" height="16" alt="图标1">Upload</a>';
    echo '</li><li id="photo_image" class="nav_list_right_li">';
    echo '<a href="my_photo.php?">';
    echo '<img src="../img/nav/photo.png" width="16" height="16" alt="图标2">My Photo</a>';
    echo '</li><li id="favor_image" class="nav_list_right_li">';
    echo '<a href="favor.php?">';
    echo '<img src="../img/nav/favor.png" width="16" height="16" alt="图标3">My Favorite</a>';
    echo '</li><li id="logout_image" class="nav_list_right_li">';
    echo '<a href="logout.php">';
    echo '<img src="../img/nav/logout.png" width="16" height="16" alt="图标4">Log out</a>';
    echo '</li></ul></div>';
}

?>
