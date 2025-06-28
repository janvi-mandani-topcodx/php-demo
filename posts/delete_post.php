<?php
include '../connection.php';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_post'])) {
    $deleteId = $_POST['delete_post'];

    $sql = "DELETE FROM posts WHERE id = $deleteId";

    if ($con->query($sql)) {
        header("Location: postdata.php");
        exit();
    } else {
        echo "Error deleting user: ";
    }
}
