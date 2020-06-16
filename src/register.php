<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="css/Reset.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="bootstrap-4.5.0-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row mx-auto">
        <img class="rounded img-fluid mx-auto" src="../img/nav/user_default_image.jpg" width="70" height="70"
             alt="这是用户默认头像">
    </div>
    <div class="text-center">
        <h4>Sign up for Fisher</h4>
    </div>
</div>
<div class="container bg-light w-25" style="border-radius: 20px;">
    <form action="" method="post">
        <div class="form-group">
            <label for="usr">Username:</label>
            <input type="text" class="form-control" id="usr" pattern="^[A-Za-z0-9._]{3,20}$"
                   placeholder="用户名：3~20位字母数字组合" required name="usr">
        </div>
        <div class="form-group">
            <label for="eml">E-mail:</label>
            <input type="text" class="form-control" id="eml"
                   pattern="^[a-zA-Z0-9]+([a-zA-Z0-9_.-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$"
                   placeholder="邮箱" required name="eml">
        </div>
        <div class="form-group">
            <label for="pwd">Password:&nbsp;&nbsp;&nbsp;<span id="showStrength"></span></label>
            <input type="password" class="form-control" id="pwd" pattern="^[a-zA-Z0-9\s\S]{8,16}$"
                   placeholder="密码：8~16数字字母字符组合" required name="pwd">
        </div>
        <div class="form-group">
            <label for="cpwd">Confirm You Password:</label>
            <input type="password" class="form-control" id="cpwd" pattern="^[a-zA-Z0-9\s\S]{8,16}$"
                   placeholder="确认密码" required name="cpwd">
        </div>
        <div class="form-group">
            <label class="text-danger">
                <?php
                require_once('config.php');
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username = $_POST['usr'];
                    $email = $_POST['eml'];
                    $password = $_POST['pwd'];
                    $check_password = $_POST['cpwd'];
                    if ($password !== $check_password) echo '两次密码不一致';
                    else {
                        if (validRegister($username, $email, $password)) {
                            header("Location:login.php");
                        } else echo '用户名已存在';
                    }
                }
                ?></label>
        </div>
        <div class="form-group">
            <input class="btn btn-success btn-block" type="submit" value="Sign in">
        </div>
    </form>
</div>
<div class="card-footer bg-dark text-white fixed-bottom">
    <p class="align-text-bottom" style="margin-bottom: -8em;margin-top: -1em;">Copyright &copy; 2019-2020 Web
        fundamental.All Rights Reserved.L00335856757576465465465464747</p>
</div>
<?php
echo "<script src='bootstrap-4.5.0-dist/js/bootstrap.min.js'></script>";
echo "<script src='jQuery/jquery-3.3.1.min.js'></script>";
echo '<script src="js/register.js"></script>';
?>
</body>
</html>
<?php
function validRegister($usr, $eml, $psw)
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql = 'SELECT * FROM traveluser WHERE UserName =:usr';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':usr', $usr);
    $statement->execute();
    if ($statement->rowCount() === 0) {
        $sql = 'INSERT INTO `traveluser` (`UID`, `Email`, `UserName`, `Pass`, `State`, `DateJoined`, `DateLastModified`)
 VALUES (NULL , :eml, :usr, :pwd , 1, NULL, NULL)';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':eml', $eml);
        $statement->bindValue(':usr', $usr);
        $statement->bindValue(':pwd', $psw);
        $result = $statement->execute();
        $pdo = null;
        if ($result) {
            return true;
        }
    }
    return false;
}

?>