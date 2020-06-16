<?php
session_start();
require_once('config.php');
$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My favor</title>
    <link rel="stylesheet" href="css/Reset.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/my_photo.css">
    <link rel="stylesheet" href="bootstrap-4.5.0-dist/css/bootstrap.min.css">
</head>
<body>
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
            if (!isset($_SESSION['Username'])) {
                header("Location:index.php");
            } else {
                paintLogin();
            }
            ?>
        </li>
    </ul>
</div>
<br>
<div class="container bg-light" style="border-radius: 20px">
    <p class="text-muted" style="height: 2em">&nbsp;My favor</p>
    <?php
    if (!isset($_SESSION['Username'])) {
        header("Location:index.php");
    } else {
    $user = getUser($connection);
    $sql = 'select * from travelimage where ImageID in (select ImageID from travelimagefavor where UID = ' . $user['UID'] . ');';
    $result = mysqli_query($connection, $sql);

    if ($result)
        $totalCount = $result->num_rows;
    else
        $totalCount = 0;

    if ($totalCount == 0)
        echo '<h1 class="text-info">“您还没有收藏照片，赶紧去收藏一张吧！”<h1><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
    else {
        //分页实现
        $pageSize = 3;
        $remain = $totalCount;
        $totalPage = (int)(($totalCount % $pageSize == 0) ? ($totalCount / $pageSize) : ($totalCount / $pageSize + 1));

        if (!isset($_GET['page']))
            $currentPage = 1;
        else
            $currentPage = $_GET['page'];

        $mark = ($currentPage - 1) * $pageSize;
        $remain = $remain - ($currentPage - 1) * $pageSize;
        $firstPage = 1;
        $page2 = ($totalPage - $currentPage > 0) ? $currentPage + 1 : null;
        $page3 = ($totalPage - $currentPage > 1) ? $currentPage + 2 : null;
        $page4 = ($currentPage > 1) ? $currentPage - 1 : null;
        $page5 = ($currentPage > 2) ? $currentPage - 2 : null;
        $prePage = ($currentPage > 1) ? $currentPage - 1 : null;
        $nextPage = ($totalPage - $currentPage > 0) ? $currentPage + 1 : null;

        $sql = 'select * from travelimage where ImageID in (select ImageID from travelimagefavor where UID = ' . $user['UID'] . ') limit ' . $mark . "," . $pageSize . ';';
        $result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);
        if ($remain < $pageSize) {
            for ($j = 0; $j < $remain; $j++) {
                $id = mysqli_fetch_assoc($result);
                paintSingleResult($id);
            }
            paintBlank();
        } else
            for ($j = 0; $j < $pageSize; $j++) {
                $id = mysqli_fetch_assoc($result);
                paintSingleResult($id);
            }
        ?>
        <div class="row">
            <ul class="pagination pagination-sm mx-auto">
                <?php
                if ($prePage != null)
                    echo '<li class="page-item"><a class="page-link"
                                     href="favor.php?page=' . $prePage . ' ">&lt;</a></li>';
                else
                    echo '<li class="page-item disabled"><a class="page-link"
                                     href="favor.php?page=' . $prePage . ' ">&lt;</a></li>';
                if ($page5 != null)
                    echo '<li class="page-item"><a class="page-link"
                                     href="favor.php?page=' . $page5 . ' "> ' . $page5 . '</a></li>';
                if ($page4 != null)
                    echo '<li class="page-item"><a class="page-link"
                                     href="favor.php?page=' . $page4 . ' "> ' . $page4 . '</a></li>';
                ?>
                <li class="page-item active">
                    <a class="page-link"
                       href="favor.php?page=<?php echo $currentPage; ?>"><?php echo $currentPage ?></a>
                </li>
                <?php
                if ($page2 != null)
                    echo '<li class="page-item"><a class="page-link"
                                     href="favor.php?page=' . $page2 . ' "> ' . $page2 . '</a></li>';
                if ($page3 != null)
                    echo '<li class="page-item"><a class="page-link"
                                     href="favor.php?page=' . $page3 . ' "> ' . $page3 . '</a></li>';
                if ($nextPage != null)
                    echo '<li class="page-item"><a class="page-link"
                                     href="favor.php?page=' . $nextPage . ' ">&gt;</a></li>';
                else
                    echo '<li class="page-item disabled"><a class="page-link"
                                     href="favor.php?page=' . $nextPage . ' ">&gt;</a></li>';
                ?>
            </ul>
        </div>
        <?php
    }//end else 语句
    ?>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!cancelFavor($user['UID'])) {
        echo '<script>window.alert("异常！ 取消收藏失败!")</script>';
    } else {
        echo '<script>window.location="favor.php"</script>';
    }
}
?>
<form action="" method="post" id="hiddenForm"
      style="display: none">
    <input type="text" name="cancelFavor" id="cancelFavor">
    <input type="text" name="FavorID" id="FavorID">
</form>
<br>
<div class="modal-footer bg-dark text-white">
    <p style="height: 2em">Copyright &copy; 2019-2020 Web
        fundamental.All Rights Reserved.L00335856757574747</p>
</div>
<?php
echo "<script src='bootstrap-4.5.0-dist/js/bootstrap.min.js'></script>";
echo "<script src='jQuery/jquery-3.3.1.min.js'></script>";
mysqli_free_result($result);
}
mysqli_close($connection);
?>
</body>
</html>
<?php
function cancelFavor($uid)
{
    $id = $_POST['FavorID'];
    $cancel = $_POST['cancelFavor'];
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($cancel == 'cancel') {
        $sql1 = 'DELETE FROM travelimagefavor WHERE UID = :uid AND ImageID = :id ';
    } else return false;

    $statement = $pdo->prepare($sql1);
    $statement->bindValue(':uid', $uid);
    $statement->bindValue(':id', $id);
    $statement->execute();

    $pdo = null;
    return true;
}

function getUser($connection)
{
    $username = $_SESSION['Username'];
    $sql = "select * from traveluser where Username = '$username';";
    $result = mysqli_query($connection, $sql);
    return mysqli_fetch_assoc($result);
}

function paintSingleResult($id)
{
    echo '<div class="row h-25">';
    echo '<div class="col-5 text-dark"><div class="crop mx-auto">';
    echo '<a href="detail.php?id=' . $id['ImageID'] . '"><img class="rounded img-fluid" src="' . IMAGE_ROOT . $id['PATH'] . '"></a>';
    echo '</div></div>';
    echo '<div class="col-7 text-dark">';
    echo '<h4>' . $id['Title'] . '</h4>';
    if ($id['Description'] === null) echo '<p>No description</p>';
    else echo '<p>' . $id['Description'] . '</p>';
    echo '<div class="" style="margin-left: 20%">';
    echo '<input class="btn btn-outline-danger" type="button" value="取消收藏" onclick="';
    echo 'document.getElementById(\'cancelFavor\').value=\'cancel\';';
    echo 'document.getElementById(\'FavorID\').value=\'' . $id['ImageID'] . '\';';
    echo 'document.getElementById(\'hiddenForm\').submit()">';
    echo '</div></div></div><hr><br>';
}

function paintBlank()
{
    echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
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
