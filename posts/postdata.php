<?php
include '../connection.php';
session_start();
function slugify($text)
{
    $text = strtolower($text);
    $text = str_replace(' ', '-', $text);
    return trim($text, '-');
}

if (isset($_POST['post']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    $fileName = $_FILES["fileupload"]["name"];
    $tempName = $_FILES["fileupload"]["tmp_name"];

    $folder = "../img/" . $fileName;
    move_uploaded_file($tempName, $folder);

    $user_id =  $_SESSION['id'];
    $title = $_POST['title'];
    $slug = slugify($title);
    $description = $_POST['description'];
    $status = $_POST['status'];
    if ($status == 'active') {
        $status = 1;
    } else {
        $status = 0;
    }

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        $postQuery = "SELECT image FROM posts WHERE id = '$editId'";
        $post = $con->query($postQuery);
        $row = $post->fetch_assoc();
        if ($post && $row) {
            $oldImage = $row['image'];

            if (isset($oldImage) && $oldImage != $folder) {
                unlink($oldImage);
            }
        }
        $sql = "UPDATE posts SET 
            user_id='$user_id', title='$title', slug='$slug', description='$description', image = '$folder', status='$status' WHERE id='$editId'";
    } else {
        $sql = "INSERT INTO posts (user_id, title, slug, description, image, status) 
        VALUES ('$user_id', '$title', '$slug', '$description', '$folder', $status)";
    }
    if ($con->query($sql)) {
    } else {
    }
}
$posts = $con->query("SELECT * FROM posts");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include '../bootstrap.php'; ?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php
    include '../header.php';
    ?>
    <div class="width-90">

        <h2>Posts</h2>

        <div class="d-flex justify-content-between">
            <input type="text" id="search_post" placeholder="search...." class="searchButton">
            <a class="btn btn-outline-info" href="../posts/post.php" role="button">Create</a>
        </div>



        <table id="result_posts" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($post = $posts->fetch_assoc()) {
                    echo "<tr>
                                <td>" . $post['id'] . "</td>
                                <td>" . $post['user_id'] . "</td>
                                <td>" . $post['title'] . "</td>
                                <td>" . $post['slug'] . "</td>
                                <td>" . $post['description'] . "</td>
                                <td>
                                    <img src='" . $post['image'] . "'  height='100px' width='100px' alt='Image'> 
                                </td>
                                <td>" . ($post['status'] == 1 ? 'Active' : 'InActive') . "</td>

                                <td>
                                        <form action='delete_post.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='delete_post' value='" . $post['id'] . "'>
                                            <button type='submit' class='btn btn-info' >Delete</button>
                                        </form>
                                        <button class='btn btn-info'><a href='edit_post.php?id=" . $post['id'] . "' class='text-dark'>Edit</a></button>

                                </td>
                            </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $('#search_post').on('keyup', function() {
                var query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "search_post.php",
                        type: "POST",
                        data: {
                            search: query
                        },
                        success: function(data) {
                            $("#result_posts tbody").html(data);
                        },
                    });
                } else {
                    loadAllRecords();
                }
            });

            function loadAllRecords() {
                $.ajax({
                    url: "search_post.php",
                    type: "POST",
                    data: {
                        search: ""
                    },
                    success: function(data) {
                        $("#result_posts tbody").html(data);
                    },
                });
            }
        });
    </script>
</body>

</html>