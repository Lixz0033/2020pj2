<?php
session_start();
unset($_SESSION['Username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
    <link rel="stylesheet" href="css/Reset.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="bootstrap-4.5.0-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row mx-auto">
        <img class="rounded img-fluid mx-auto" src="../img/nav/user_default_image.jpg"
             width="70" height="70" alt="这是用户默认头像">
    </div>
    <div class="text-center">
        <h4>Sign in for Fisher</h4>
    </div>
</div>
<div class="container bg-light w-25" style="border-radius: 20px;">
    <form action="" method="post">
        <div class="form-group">
            <label for="usr">Username:</label>
            <input type="text" class="form-control" id="usr" placeholder="用户名" name="usr" required>
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" placeholder="密码" name="pwd" required>
        </div>
        <div class="form-group">
            <label class="text-danger">
                <?php
                require_once('config.php');

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (validLogin()) {
                        $_SESSION['Username'] = $_POST['usr'];
                        header("Location:index.php");
                    } else {
                        echo '用户名或密码不正确';
                    }
                }
                ?>
            </label>
        </div>
        <div class="form-group">
            <input class="btn btn-success btn-block" type="submit" value="Sign in">
        </div>
    </form>
</div>
<div class="container text-center">
    <p>New to Fisher?<a href="register.php">Create a new account?</a></p>
</div>
<div class="card-footer bg-dark text-white fixed-bottom">
    <p class="align-text-bottom" style="margin-bottom: -8em;margin-top: -1em;">Copyright &copy; 2019-2020 Web
        fundamental.All Rights Reserved.L00335856757576465465465464747</p>
</div>
<?php
echo "<script src='bootstrap-4.5.0-dist/js/bootstrap.min.js'></script>";
echo "<script src='jQuery/jquery-3.3.1.min.js'></script>";
?>
</body>
</html>
<?php
function validLogin()
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql = "SELECT * FROM traveluser WHERE UserName =:usr and Pass =:pwd";

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':usr', $_POST['usr']);
    $statement->bindValue(':pwd', $_POST['pwd']);
    $statement->execute();
    if ($statement->rowCount() === 1) {
        $pdo = null;
        return true;
    }
    $pdo = null;
    return false;
}

?>
