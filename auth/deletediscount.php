<?php
include '../connection.php';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    $sql = "DELETE FROM discounts WHERE id = $deleteId";

    if ($con->query($sql)) {
        header("Location: discountdata.php");
        exit();
    } else {
        echo "Error deleting user: ";
    }
}
