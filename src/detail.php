<?php
session_start();
require_once('config.php');

if (isset($_GET['id']))
    $id = $_GET['id'];
else header("Location:index.php");
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT * FROM `travelimage` WHERE ImageID = :id';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();

    $result1 = $statement->fetch(PDO::FETCH_ASSOC);

    $author = $result1['UID'];
    $sql = 'SELECT * FROM `traveluser` WHERE UID = :uid';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':uid', $author);
    $statement->execute();

    $result2 = $statement->fetch(PDO::FETCH_ASSOC);

    $pdo = null;
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>details</title>
        <link rel="stylesheet" href="css/Reset.css">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/details.css">
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
                if (!isset($_SESSION['Username'])) {
                    paintNotLogin();
                } else {
                    paintLogin();
                }
                ?>
            </li>
        </ul>
    </div>
    <br><br>
    <div class="container bg-light" style="border-radius: 20px;min-height: 750px">
        <p class="text-muted" style="height: 2em">&nbsp;Detail</p>
        <div class="row mx-auto w-50">
            <?php
            echo '<h2 id="img-tit">' . $result1['Title'];
            echo '<small style="font-size: small">&nbsp;&nbsp;&nbsp;by&nbsp;</small>';
            echo '<small id="usr" style="font-size: small">' . $result2['UserName'] . '</small></h2>';
            ?>
        </div>
        <br><br><br><br><br>
        <div class="row">
            <div class="col-7">
                <div class="container w-75" style="margin: 0px auto;">
                    <img class="rounded img-fluid w-auto"
                         src="<?php echo IMAGE_ROOT . $result1['PATH'] ?>"
                         id="img" alt="图片已损坏">
                </div>
            </div>
            <div class="col-5">
                <div class="card w-75">
                    <div class="card-header">Like number</div>
                    <div class="card-body text-danger text-center" style="font-size: xx-large;">
                        <span id="img-fvr"><?php echo getFavorNum($id) ?></span>
                    </div>
                </div>
                <br>
                <div class="card w-75">
                    <div class="card-header">Image Details</div>
                    <div class="card-body">
                        <label for="img-thm">Content:</label><span id="img-thm"><?php echo $result1['Content'] ?></span>
                        <hr>
                        <label for="img-ctr">Country:</label><span
                                id="img-ctr"><?php echo $result1['CountryCodeISO'] ?></span>
                        <hr>
                        <label for="img-cty">City:</label><span id="img-cty"><?php echo $result1['CityCode'] ?></span>
                    </div>
                </div>
                <br>
                <?php
                if (isset($_SESSION['Username'])) {
                    $usr = getUser();
                    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = 'SELECT * FROM `travelimagefavor` WHERE UID = ' . $usr['UID'] . ' and ImageID = ' . $id;
                    $statement = $pdo->prepare($sql);
                    $statement->execute();
                    if ($statement->rowCount() > 0) $result3 = true;
                    else $result3 = false;
                    if ($result3)
                        paintFavor();
                    else paintNotFavor();
                    $pdo = null;
                }
                echo '<label class="text-danger">';
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (!changeFavor($id, $usr['UID'])) {
                        echo '收藏失败!';
                    } else {
                        header("Location:detail.php?id=$id");
                    }
                }
                echo '</label>';
                ?>
            </div>
        </div>
        <div class="row mx-auto w-75">
            <div class="col-sm-12"><br>
                <p id="img-des">
                    <?php
                    if ($result1['Description'] === null)
                        echo '<p>No description</p>';
                    else
                        echo '<p>' . $result1['Description'] . '</p>';
                    ?>
                </p>
            </div>
        </div>
    </div>
    <form action="" method="post" id="hiddenForm"
          style="display: none">
        <input type="text" name="changeFavor" id="changeFavor">
    </form>
    <br><br>
    <div class="modal-footer bg-dark text-white">
        <p style="height: 2em">Copyright &copy; 2019-2020 Web
            fundamental.All Rights Reserved.L00335856757576465465465464747</p>
    </div>
    <?php
    echo "<script src='bootstrap-4.5.0-dist/js/bootstrap.min.js'></script>";
    echo "<script src='jQuery/jquery-3.3.1.min.js'></script>";
    echo "<script src='js/detail.js'></script>";
    ?>
    </body>
    </html>
<?php
function changeFavor($id, $uid)
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $isFavor = $_POST['changeFavor'];

    if ($isFavor == 'favor') {
        $sql = 'DELETE FROM travelimagefavor WHERE UID = :uid AND ImageID = :id ';
    } elseif ($isFavor == 'favored') {
        $sql = 'INSERT INTO travelimagefavor (FavorID, UID,ImageID) VALUES (NULL,:uid,:id)';
    } else return false;

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':id', $id);
    $statement->execute();
    if ($statement->rowCount() == 0) {
        $pdo = null;
        return false;
    }
    $pdo = null;
    return true;
}

function getFavorNum($id)
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql2 = "SELECT * FROM travelimagefavor WHERE ImageID = $id";
    $result = $pdo->query($sql2);
    $favor = $result->rowCount();
    $pdo = null;
    return $favor;
}

function paintFavor()
{
    echo '<button type="button" class="btn btn-outline-danger btn-block w-75" 
    onclick="document.getElementById(\'changeFavor\').value=\'favor\';document.getElementById(\'hiddenForm\').submit()">♥ 已收藏</button>';
}

function paintNotFavor()
{
    echo '<button type="button" class="btn btn-danger btn-block w-75" 
    onclick="document.getElementById(\'changeFavor\').value=\'favored\';document.getElementById(\'hiddenForm\').submit()">♥ 点击收藏</button>';
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

?>