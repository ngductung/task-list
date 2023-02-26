<?php
session_start();

if (empty($_SESSION)) {
    header("location: index.php");
}

if (isset($_POST['btn-logout'])) {
    echo $_SESSION['id'];
    // unset($_SESSION['id']);
    session_destroy();
    var_dump($_SESSION);
    header("location: index.php");
}


$id = $_SESSION['id'];
$conn = new mysqli('localhost', 'root', 'root', 'users');
$er = '';

// add task to database

if (isset($_POST['submit'])) {
    $task = $_POST['task'];
    var_dump($task);
    if (empty($task)) {
        $er = 'Nhập task!!!';
    } else {
        $checkTask = 'select stt from task where task = ? and id = ?;';
        $prepareCheck = $conn->prepare($checkTask);
        $prepareCheck->bind_param('si', $task, $id);
        $prepareCheck->execute();
        $result = $prepareCheck->get_result();
        if ($result->num_rows > 0 && $result != false) {
            $row = $result->fetch_assoc();
            $er = 'Đã tồn tại task này!!!';
        } else {
            $query = 'insert into task (id, task) values (?, ?);';
            $prepare = $conn->prepare($query);
            $prepare->bind_param('is', $id, htmlspecialchars($task));
            $prepare->execute();
            header("location: index.php");
        }
    }
}

//delete data 

if (isset($_GET['del_task'])) {
    $task = $_GET['del_task'];
    $queryUpdate = 'delete from task where task = ? and id = ?;';
    $prepare = $conn->prepare($queryUpdate);
    $prepare->bind_param('si', $task, $id);
    $prepare->execute();
}


//show data to table
$query = 'select * from task where stt=0 and id=?';
$prepare = $conn->prepare($query);
$prepare->bind_param('i', $id);
$prepare->execute();
$result = $prepare->get_result();
// while ($row = $result->fetch_assoc()) {
//     var_dump($row);
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="./fonts/fontawesome-free-6.1.1-web/fontawesome-free-6.1.1-web/css/all.min.css">
    <title>Task List</title>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="header-text">Task List</h1>
        </div>

        <form action="" method="post">
            <p>New task</p>
            <!-- <input type="submit" value="Log Out" name="btn-logout"> -->
            <div class="input-task">
                <input type="text" class="input-text" placeholder="Enter task name" name="task">
                <button class="btn-add" name="submit" type="submit">
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                </button>
            </div>
        </form>
        <?php if (!empty($er)) { ?>
            <p style="padding-left: 100px;padding-bottom: 20px;">ERROR: <?php echo $er; ?></p>
        <?php } ?>
        <p>Current Tasks: </p>
        <table>
            <!-- <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead> -->

            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td> <?php echo $row['task']; ?></td>
                        <td style="width: 60px;">
                            <a href="./taskList.php?del_task=<?php echo $row['task'] ?>">
                                <!-- <i class="fa fa-plus " aria-hidden="true"></i> -->
                                <i class="fa fa-trash delete-task" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <form action="" method="post">
        <!-- <input type="submit" value="Log Out" name="btn-logout"> -->
        <button type="submit" name="btn-logout" class="btn-logout">Log Out</button>
    </form>
</body>

</html>