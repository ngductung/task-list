<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="./fonts/fontawesome-free-6.1.1-web/fontawesome-free-6.1.1-web/css/all.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="login">
        <h1>Sign Up</h1>
        <form action="signup.php" method="post">
            <div class="input">
                <span class="icon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </span>
                <input type="text" placeholder="Username" name="username" class="input_field">
            </div>
            <div class="input">
                <span class="icon">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </span>
                <input type="password" placeholder="Password" name="password" class="input_field">
                <span class="icon show">
                    <i class="fa fa-eye" aria-hidden="true" style="display: block;"></i>
                    <i class="fa fa-eye-slash" aria-hidden="true" style="display: none;"></i>
                </span>
            </div>
            <div class="btn_submit">
                <input type="submit" value="Create" name="submit-btn" class="btn">
            </div>
        </form>
        <div class="footer">
            <h5>Do you already have an account? </h5>
            <a href="./login.php">Sign In</a>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['submit-btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username != '' && $password != '') {
        $conn = new mysqli('localhost', 'root', 'root', 'users');
        $queryInsert = 'insert into users(username, password) values (?, ?)';
        $querySelect = 'select id from users where username = ?';
        $prepare = $conn->prepare($querySelect);
        $prepare->bind_param('s', $username);
        $prepare->execute();
        $result = $prepare->get_result();
        $checkUser = true;
        if ($result->num_rows !== 0 and $result !== false) {
            $checkUser = false;
        }
        if (!$checkUser) {
            echo '<h1 style="color: red;">Đã tồn tại user: ' . $username. '</h1>';
        } else {
            $prepare = $conn->prepare($queryInsert);
            $prepare->bind_param('ss', $username, $password);
            $prepare->execute();
            header("Location: login.php");    
            exit;
        }
    } else {
        echo '<h1>Nhập username và password!</h1>';
    }
}
?>