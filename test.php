<?php
$conn = new mysqli('localhost', 'root', 'root', 'users');
$query = 'select * from users;';
$result = $conn->query($query);

if ($result->num_rows > 0 && $result != false) {
    while ($row = $result->fetch_assoc()) {
        var_dump($row);
    }
}
