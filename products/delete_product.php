<?php
include '../connection.php';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_product'])) {
    $deleteId = $_POST['delete_product'];

    $sql = "DELETE FROM products WHERE id = $deleteId";

    if ($con->query($sql)) {
        header("Location: productdata.php");
        exit();
    } else {
        echo "Error deleting user: ";
    }
}
