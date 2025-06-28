<?php
include '../connection.php';
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $con->query("SELECT * FROM products WHERE id = $id");
    if ($result->num_rows == 0) {
        header("Location: productdata.php");
        exit;
    }
    $product = $result->fetch_assoc();
    $id = $product['id'];
    $title = $product['title'];
    $slug = $product['slug'];
    $description = $product['description'];
    $price = $product['price'];
    $image = $product['image'];
    $categories = $product['categories'];
    $tags = $product['tags'];
    $status = $product['status'];
}
include 'products.php';
