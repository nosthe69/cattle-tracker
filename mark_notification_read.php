<?php
require_once 'db_connect.php';

if (isset($_POST['id'])) {
    $notificationId = $_POST['id'];
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = '$notificationId'";
    mysqli_query($conn, $sql);
}
?>
