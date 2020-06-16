<?php
session_start();
require_once('config.php');
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Upload</title>
        <link rel="stylesheet" href="css/Reset.css">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/upload.css">
        <link rel="stylesheet" href="bootstrap-4.5.0-dist/css/bootstrap.min.css">
    </head>
    <body>
    <div class="navigation fixed-top">
        <ul>
            <img id="user_default_image" class="nav_list_left rounded" src="../img/nav/user_default_image.jpg"
                 width="40"
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
                    header("Location:index.php");
                } else {
                    paintLogin();
                }
                ?>
            </li>
        </ul>
    </div>
    <br><br>
    <div class="container bg-light w-50" style="border-radius: 20px;min-height: 700px">
        <p class="text-muted" style="height: 2em">&nbsp;Upload</p><br>
        <div class="container w-25" style="margin: 0px auto;">
            <img class="rounded img-fluid w-100" src="" id="show" alt="图片未上传">
            <label class="text-danger" id="img-message"> </label>
        </div>
        <form action="download.php" method="post" enctype="multipart/form-data">
            <div class="form-group custom-file">
                <input type="file" class="custom-file-input" id="file" name="file" onchange="readAsDataURL()">
                <label class="custom-file-label" for="customFile">选择文件</label>
            </div>
            <div class="form-group">
                <label for="img-tit">图片标题:</label>
                <input type="text" class="form-control" id="img-tit" required name="tit">
            </div>
            <div class="form-group">
                <label for="img-des">图片描述:</label>
                <textarea class="form-control" rows="3" id="img-des" required name="des"></textarea>
            </div>
            <div class="form-group">
                <label for="img-thm">图片主题:</label>
                <select class="form-control" id="slt-thm" name="thm">
                    <option value="" selected disabled></option>
                    <option value="scenery"> scenery</option>
                    <option value="city"> city</option>
                    <option value="people"> people</option>
                    <option value="animal"> animal</option>
                    <option value="building"> building</option>
                    <option value="wonder"> wonder</option>
                    <option value="other"> other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="slt-ctr">拍摄国家或地区:</label>
                <select class="form-control" name="ctr" id="slt-ctr" onchange="setSelectCity()">
                    <option value="" selected disabled></option>
                    <?php
                    try {
                        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sql = 'SELECT * FROM `geocountries` ORDER BY `geocountries`.`Population` DESC limit 75';
                        $result = $pdo->query($sql);

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row['CountryName'] . '">' . $row['CountryName'] . '</option>';
                        }
                        $pdo = null;
                    } catch (PDOException $e) {
                        die($e->getMessage());
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" style="display: none" name="cty" id="upload-cty">
                <label for="slt-cty">拍摄城市:</label>
                <select class="form-control" name="slt-cty" id="slt-cty"
                        onchange="let slt = document.getElementById('slt-cty');
                    let index = slt.selectedIndex;
                    document.getElementById('upload-cty').value = slt.options[index].text;">
                    <option value="" selected disabled></option>
                </select>
            </div>
            <br>
            <input type="text" style="display: none" name="upload" value="true">
            <button type="submit" class="btn btn-primary btn-block">上传</button>
            <br>
        </form>
    </div>
    <br><br>
    <div class="modal-footer bg-dark text-white">
        <p style="height: 2em">Copyright &copy; 2019-2020 Web
            fundamental.All Rights Reserved.L00335856757576465465465464747</p>
    </div>
    <script src="js/city.js"></script>
    <script src="js/upload.js" type="text/javascript"></script>
    <script src="bootstrap-4.5.0-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="jQuery/jquery-3.3.1.min.js" type="text/javascript"></script>
    </body>
    </html>
<?php
function getUser()
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $username = $_SESSION['Username'];
    $sql = "select * from traveluser where Username = '$username';";
    $result = $pdo->query($sql);
    $pdo = null;
    return $result->fetch(PDO::FETCH_ASSOC);
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