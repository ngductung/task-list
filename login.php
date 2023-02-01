<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="./fonts/fontawesome-free-6.1.1-web/fontawesome-free-6.1.1-web/css/all.css">
    <title>Login</title>
</head>

<body>
    <div class="login">
        <h1>Login</h1>
        <form action="" method="POST">
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
                <input type="password" placeholder="Password" name="password" class="input_field" id="input_password">
                <span class="icon show">
                    <i class="fa fa-eye" id="see" aria-hidden="true" style="display: block;"></i>
                    <i class="fa fa-eye-slash" id="hide" aria-hidden="true" style="display: none;"></i>
                </span>
            </div>
            <div class="btn_submit">
                <input type="submit" value="Login" name="submit-btn" class="btn">
            </div>
        </form>
        <div class="footer">
            <h5>Don't have an account? </h5>
            <a href="./signup.php">Sign Up</a>
        </div>
    </div>
    <!-- <div class="login">
        abc
    </div> -->
</body>

</html>

<script>
    var input_password = document.getElementById('input_password');
    var icon = document.querySelector('.show');
    var icon_see = document.getElementById('see');
    var icon_hide = document.getElementById('hide');
    // input_password.addEventListener("click", function() {
    //     if (input_password.getAttribute)
    // })
    icon.addEventListener("click", function() {
        var status = input_password.getAttribute('type');
        if (status == 'password') {
            input_password.setAttribute('type', 'text');
            icon_see.setAttribute('style', 'display: none;');
            icon_hide.setAttribute('style', 'display: block;');
        } else {
            input_password.setAttribute('type', 'password');
            icon_see.setAttribute('style', 'display: block;');
            icon_hide.setAttribute('style', 'display: none;');
        }
    })
</script>

<?php
session_start();
// var_dump($_SESSION['id']);
if (!empty($_SESSION['id'])) {
    header('location: taskList.php');
    // echo $_SESSION['id'];
}

if (isset($_POST['submit-btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username != '' && $password != '') {
        $conn = new mysqli('localhost', 'root', 'root', 'users');
        $query = 'select id from users where username = ? and password = ?';
        $prepare = $conn->prepare($query);
        $prepare->bind_param('ss', $username, $password);
        $prepare->execute();
        $result = $prepare->get_result();
        if ($result->num_rows !== 0 and $result !== false) {
            $row = $result->fetch_assoc();
            // echo 'success';
            session_start();
            $_SESSION['id'] = $row["id"];
            header('location: taskList.php');
        } else {
            echo '<h1 style="color: red;">Wrong username and password!</h1>';
        }
    } else {
        echo '<h1 style="color: red;">Điền username và password</h1>';
    }
}

?>