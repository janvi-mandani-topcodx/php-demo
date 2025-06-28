<?php
include '../connection.php';
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $con->query("SELECT * FROM posts WHERE id = $id");
    if ($result->num_rows == 0) {
        header("Location: postdata.php");
        exit;
    }
    $post = $result->fetch_assoc();
    $id = $post['id'];
    $title = $post['title'];
    $slug = $post['slug'];
    $description = $post['description'];
    $image = $post['image'];
    $status = $post['status'];
}
include 'post.php';
include '../comments/comment_post.php';
