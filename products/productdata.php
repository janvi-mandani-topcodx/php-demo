<?php
include '../connection.php';
session_start();
function slugify($text)
{
    $text = strtolower($text);
    $text = str_replace(' ', '-', $text);
    return trim($text, '-');
}

if (isset($_POST['product']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $fileName = $_FILES["fileupload"]["name"];
    $tempName = $_FILES["fileupload"]["tmp_name"];

    $folder = "../img/" . $fileName;
    move_uploaded_file($tempName, $folder);
    $title = $_POST['title'];
    $slug = slugify($title);
    $description = $_POST['description'];
    $price = $_POST['price'];
    $categories = $_POST['categories'];
    $tags = $_POST['tags'];
    $status = $_POST['status'];
    if ($status == 'active') {
        $status = 1;
    } else {
        $status = 0;
    }

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        $postQuery = "SELECT image FROM products WHERE id = '$editId'";
        $post = $con->query($postQuery);
        $row = $post->fetch_assoc();
        if ($post && $row) {
            $oldImage = $row['image'];

            if (isset($oldImage) && $oldImage != $folder) {
                unlink($oldImage);
            }
        }
        $sql = "UPDATE products SET 
            title='$title', slug='$slug', description='$description', price='$price', image='$folder', categories='$categories', tags='$tags',status='$status' WHERE id='$editId'";
    } else {
        $sql = "INSERT INTO products (title, slug, description, price, image, categories, tags, status) 
                            VALUES ('$title', '$slug', '$description', '$price', '$folder', '$categories', '$tags', $status)";
    }
    $con->query($sql);
}
$products = $con->query("SELECT * FROM products");

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

        <h2>Products</h2>

        <div class="d-flex justify-content-between">
            <input type="text" id="search_post" placeholder="search...." class="searchButton">
            <a class="btn btn-outline-info" href="products.php" role="button">Create</a>
        </div>



        <table id="result_posts" class="table table-striped">
            <thead>
                <th>Id</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Categories</th>
                <th>Tags</th>
                <th>Status</th>
                <th>Actions</th>
            </thead>
            <tbody>

                <?php
                while ($product = $products->fetch_assoc()) {
                    echo "<tr>
                                    <td>" . $product['id'] . "</td>
                                    <td>" . $product['title'] . "</td>
                                    <td>" . $product['slug'] . "</td>
                                    <td>" . $product['description'] . "</td>
                                    <td>" . $product['price'] . "</td>
                                    <td>
                                        <img src='" . $product['image'] . "'  height='100px' width='100px' alt='Image'> 
                                    </td>
                                    <td>" . $product['categories'] . "</td>
                                    <td>" . $product['tags'] . "</td>
                                    <td>" . ($product['status'] == 1 ? 'Active' : 'InActive') . "</td>

                                    <td >
                                            <form action='delete_product.php' method='POST' style='display:inline;'>
                                                <input type='hidden' name='delete_product' value='" . $product['id'] . "'>
                                                <button type='submit' class='btn btn-info' >Delete</button>
                                            </form>
                                            <button class='btn btn-info'><a href='edit_product.php?id=" . $product['id'] . "'           class='text-dark'>Edit</a></button>

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
                        url: "search_product.php",
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
                    url: "search_product.php",
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