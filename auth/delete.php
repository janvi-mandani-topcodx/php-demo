<?php
include '../connection.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('location: ../auth/login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    $sql = "DELETE FROM users WHERE id = $deleteId";

    if ($con->query($sql)) {
        header("Location: userdata.php");
        exit();
    } else {
        echo "Error deleting user: ";
    }
}
