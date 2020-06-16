<?php
session_start();
require_once('config.php');

if (!empty($_FILES['file']['tmp_name'])) {
    $file = $_FILES['file'];

    $name = $file['name'];
    $type = strtolower(substr($name, strrpos($name, '.') + 1));

    $allow_type = array('jpg', 'jpeg', 'gif', 'png', 'pjpeg', 'bmp');

    if (!in_array($type, $allow_type)) {
        echo "<script>alert('该图片格式不在允许上传的类型内！');</script>";
        echo '<script>window.location="uploadm.php?id=' . $_POST['upload'] . '"</script>';
    }

    $upload_path = IMAGE_ROOT;

    if (file_exists($upload_path . $_FILES["file"]["name"])) {
        echo "<script>alert('该图片名称已存在，请更改文件名称重新上传!');</script>";
        echo '<script>window.location="uploadm.php?id=' . $_POST['upload'] . '"</script>';
    }
    if (isset($_POST['thm']) && isset($_POST['ctr']) && isset($_POST['cty'])) {
        if (move_uploaded_file($file['tmp_name'], $upload_path . $file['name'])) {
            upload($file);
            delete();
            header("Location:my_photo.php");
        } else {
            echo "<script>alert('a上传失败!');</script>";
            echo '<script>window.location="uploadm.php?id=' . $_POST['upload'] . '"</script>';
        }
    } else {
        echo "<script>alert('有未填写的信息!');</script>";
        echo '<script>window.location="uploadm.php?id=' . $_POST['upload'] . '"</script>';
    }
} else {
    if (isset($_POST['thm']) && isset($_POST['ctr']) && isset($_POST['cty'])) {
        update();
        header("Location:my_photo.php");
    } else {
        echo "<script>alert('有未填写的信息!');</script>";
        echo '<script>window.location="uploadm.php?id=' . $_POST['upload'] . '"</script>';
    }
}

function update()
{
    if (isset($_POST['upload'])) {
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE `travelimage` SET `Title`= :tit ,`Description`= :des,
            `CityCode`= :cty,`CountryCodeISO`= :ctr,`Content`= :thm WHERE ImageID = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':tit', $_POST['tit']);
            $statement->bindValue(':des', $_POST['des']);
            $statement->bindValue(':cty', $_POST['cty']);
            $statement->bindValue(':ctr', $_POST['ctr']);
            $statement->bindValue(':thm', $_POST['thm']);
            $statement->bindValue(':id', $_POST['upload']);
            $result = $statement->execute();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}

function upload($file)
{
    if (isset($_POST['upload'])) {
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT * FROM `travelimage` ORDER BY `ImageID` DESC limit 1';
            $result = $pdo->query($sql);
            $max = $result->fetch();
            $newID = 1 + $max['ImageID'];
            $sql = "INSERT INTO `travelimage`(`ImageID`, `Title`, `Description`, `Latitude`, `Longitude`, `CityCode`, `CountryCodeISO`, `UID`, `PATH`, `Content`) 
VALUES (:id ,:tit,:des,NULL,NULL,:cty,:ctr,:uid,:path,:thm)";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $newID);
            $statement->bindValue(':tit', $_POST['tit']);
            $statement->bindValue(':des', $_POST['des']);
            $statement->bindValue(':cty', $_POST['cty']);
            $statement->bindValue(':ctr', $_POST['ctr']);
            $statement->bindValue(':path', $file['name']);
            $statement->bindValue(':uid', getUser()['UID']);
            $statement->bindValue(':thm', $_POST['thm']);
            $result = $statement->execute();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}

function delete()
{
    if (isset($_POST['upload'])) {
        try {
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM travelimage WHERE UID = :uid AND ImageID = :id ";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':uid', getUser()['UID']);
            $statement->bindValue(':id', $_POST['upload']);
            $result = $statement->execute();
            $pdo = null;
            return $result;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
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
