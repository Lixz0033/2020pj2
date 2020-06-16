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
        <title>Search</title>
        <link rel="stylesheet" href="css/Reset.css">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/search.css">
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
                    paintNotLogin();
                } else {
                    paintLogin();
                }
                ?>
            </li>
        </ul>
    </div>
    <br>
    <div class="container bg-light" style="border-radius: 20px;">
        <p class="text-muted" style="height: 2em">&nbsp;Search</p>
        <form action="" method="get">
            <div class="form-group radio">
                <label><input type="radio" name="optradio" value="title" checked>Filter by Title</label>
                <label for="search-tit"></label>
                <input type="text" class="form-control" id="search-tit" name="title" placeholder="默认选择模式：标题">
            </div>
            <div class="form-group radio">
                <label><input type="radio" name="optradio" value="description">Filter by Description</label>
                <label for="search-des"></label>
                <textarea class="form-control" rows="3" id="search-des" name="description"
                          placeholder="其他选择模式：内容描述"></textarea>
            </div>
            <div class="form-group">
                <input class="btn btn-success btn-block" type="submit" value="Filter">
            </div>
        </form>
    </div>
    <div class="container bg-light" style="border-radius: 20px">
        <p class="text-muted" style="height: 2em">&nbsp;Result</p>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['optradio'])) {

            $filter = getFilterCondition();

            $sql = 'select * from travelimage ' . $filter . ';';
            $result = mysqli_query($connection, $sql);

            if ($result)
                $totalCount = $result->num_rows;
            else
                $totalCount = 0;

            if ($totalCount == 0)
                echo '<h1 class="text-info">&nbsp;&nbsp;“对不起，未找到相关结果”<h1><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
            else {
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

                $sql = 'select * from travelimage ' . $filter . ' limit ' . $mark . "," . $pageSize . ';';
                $result = mysqli_query($connection, $sql, MYSQLI_USE_RESULT);

                $str = '&optradio=' . $_GET['optradio'] . '&title=' . $_GET['title'] . '&description=' . $_GET['description'];
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
                                     href="search.php?page=' . $prePage . $str . ' ">&lt;</a></li>';
                        else
                            echo '<li class="page-item disabled"><a class="page-link"
                                     href="search.php?page=' . $prePage . $str . ' ">&lt;</a></li>';
                        if ($page5 != null)
                            echo '<li class="page-item"><a class="page-link"
                                     href="search.php?page=' . $page5 . $str . ' "> ' . $page5 . '</a></li>';
                        if ($page4 != null)
                            echo '<li class="page-item"><a class="page-link"
                                     href="search.php?page=' . $page4 . $str . ' "> ' . $page4 . '</a></li>';
                        ?>
                        <li class="page-item active">
                            <a class="page-link"
                               href="search.php?page=<?php echo $currentPage; ?>&<?php echo $str ?>"><?php echo $currentPage ?></a>
                        </li>
                        <?php
                        if ($page2 != null)
                            echo '<li class="page-item"><a class="page-link"
                                     href="search.php?page=' . $page2 . $str . ' "> ' . $page2 . '</a></li>';
                        if ($page3 != null)
                            echo '<li class="page-item"><a class="page-link"
                                     href="search.php?page=' . $page3 . $str . ' "> ' . $page3 . '</a></li>';
                        if ($nextPage != null)
                            echo '<li class="page-item"><a class="page-link"
                                     href="search.php?page=' . $nextPage . $str . ' ">&gt;</a></li>';
                        else
                            echo '<li class="page-item disabled"><a class="page-link"
                                     href="search.php?page=' . $nextPage . $str . ' ">&gt;</a></li>';
                        ?>
                    </ul>
                </div>
                <?php
                mysqli_free_result($result);
            }
            mysqli_close($connection);
        } else echo '<h1 class="text-info">&nbsp;&nbsp;“当前没有搜索结果”</h1><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
        ?>
    </div>
    <br>
    <div class="modal-footer bg-dark text-white">
        <p style="height: 2em">Copyright &copy; 2019-2020 Web
            fundamental.All Rights Reserved.L00335856757576465465465464747</p>
    </div>
    <?php
    echo "<script src='bootstrap-4.5.0-dist/js/bootstrap.min.js'></script>";
    echo "<script src='jQuery/jquery-3.3.1.min.js'></script>";
    ?>
    </body>
    </html>

<?php

function getFilterCondition()
{
    $filter = "";
    $judge = $_GET['optradio'];
    $title = changeStr($_GET['title']);
    $description = changeStr($_GET['description']);
    if ($judge == 'title') {
        if (!empty($title))
            $filter = " WHERE Title like '%" . $title . "%' ORDER BY Title";
        else echo '<label class="text-danger">由于标题未输入任何信息，当前自动显示所有结果</label>';
    } else {
        if (!empty($description))
            $filter = " WHERE Description like '%" . $description . "%' ORDER BY Description";
        else echo '<label class="text-danger">由于内容描述未输入任何信息，当前自动显示所有结果</label>';
    }
    return $filter;
}

function changeStr($str){
    $str = str_replace("'","\'",$str);
    $str = str_replace('"','\"',$str);
    return $str;
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
    echo '</div></div><hr><br>';
}

function paintBlank()
{
    echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
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

function paintNotLogin()
{
    echo '<div><a href="login.php">Log in</a></div>';
}

?>