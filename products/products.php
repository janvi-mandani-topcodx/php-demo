<?php
include '../connection.php';
function slugify($text)
{
    $text = strtolower($text);
    $text = str_replace(' ', '-', $text);
    return trim($text, '-');
}

if (isset($_POST['product']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $slug = slugify($title);
}
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

<body class="backgroundColor">
    <?php
    include '../header.php';
    ?>
    <section class="mt-2">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col-12 col-md-12 col-lg-9 col-xl-10">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h2 id="header" class="text-center">
                                <?php
                                if (isset($_GET['id'])) {
                                    echo "Edit Product";
                                } else {
                                    echo "Create Product";
                                }
                                ?>
                            </h2>
                            <form method="post" action="productdata.php" class="form-center" enctype="multipart/form-data" onsubmit="return submitval()">
                                <div class="mb-3">
                                    <input type="hidden" name="edit_id" value="<?= isset($id) ? $id : '' ?>">
                                    <label for="title" class="form-label lable">Title</label>
                                    <input type="text" class="form-control py-2 input" value="<?= isset($title) ? $title : ''  ?>" id="title" name="title" onchange="title_slug()" placeholder="Enter Your title ....">
                                    <div class="valid" id="title_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label lable">Slug</label>
                                    <input type="text" class="form-control py-2 input" value="<?= isset($slug) ? $slug : ''  ?>" id="slug" name="slug" placeholder="Enter Your Slug ....">
                                    <div class="valid" id="slug_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label lable">Description</label>
                                    <textarea class="form-control py-2 input" id="exampleFormControlInput1 description" name="description" placeholder="Enter Your description ...."><?= isset($description) ? $description : ''  ?></textarea>
                                    <div class="valid" id="description_error"></div>
                                </div>

                                <div class="form-group col-md-12 mb-3">
                                    <label for="file" class="form-label lable">Image</label>
                                    <input type="file" class="form-control" name="fileupload" id="fileupload" />
                                </div>
                                <?php
                                if (isset($image) && $image) {
                                    echo "<img src='$image' id='imgPreview' height='300' width='300'>";
                                } else {
                                    echo '<img src="" id="imgPreview">';
                                }
                                ?>

                                <div class="mb-3">
                                    <label for="price" class="form-label lable">Price</label>
                                    <input type="number" class="form-control py-2 input" value="<?= isset($price) ? $price : ''  ?>" id="exampleFormControlInput1 price" name="price" placeholder="Enter Your price ....">
                                    <div class="valid" id="price_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="categories" class="form-label lable">Categories</label>
                                    <input type="text" class="form-control py-2 input" value="<?= isset($categories) ? $categories : ''  ?>" id="exampleFormControlInput1 categories" name="categories" placeholder="Enter Your categories ....">
                                    <div class="valid" id="categories_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label lable">Tags</label>
                                    <input type="text" class="form-control py-2 input" value="<?= isset($tags) ? $tags : ''  ?>" id="exampleFormControlInput1 tags" name="tags" placeholder="Enter Your tags ....">
                                    <div class="valid" id="tags_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label lable">Status:</label>
                                    <div class="d-flex justify-content-start align-item-center">
                                        <input class="form-check-input" type="radio" name="status" value="active" <?php echo isset($status) && $status == 1 ? 'checked' : ''; ?> id="radioDefault1">
                                        <label class="form-check-label mx-2" for="radioDefault1">
                                            Active
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-start align-item-center">
                                        <input class="form-check-input" type="radio" name="status" value="inactive" <?php echo isset($status) && $status == 0 ? 'checked' : ''; ?> id="radioDefault2">
                                        <label class="form-check-label mx-2" for="radioDefault2">
                                            Inactive
                                        </label>
                                    </div>
                                </div>

                                <?php
                                if (isset($_GET['id'])) {
                                    if ($_SESSION['id']) {
                                        echo " <button type='submit' name='product' class='acc'> Edit product </button>";
                                    }
                                } else {
                                    echo " <button type='submit' name='product' class='acc'> Create product </button>";
                                }

                                ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function title_slug() {
            let title = document.getElementById('title').value;
            document.getElementById('slug').value = title;
        }

        function submitval() {
            let title = document.getElementById('title').value;
            let slug = document.getElementById('slug').value;
            let description = document.getElementById('description').value;
            let price = document.getElementById('price').value;
            let categories = document.getElementById('categories').value;
            let tags = document.getElementById('tags').value;
            if (title == '') {
                document.getElementById('title_error').innerHTML = "Please enter title";
                return false;
            }
            if (slug == '') {
                document.getElementById('slug_error').innerHTML = "Please enter slug";
                return false;
            }
            if (description == '') {
                document.getElementById('description_error').innerHTML = "Please enter description";
                return false;
            }
            if (price == '') {
                document.getElementById('price_error').innerHTML = "Please enter price";
                return false;
            }
            if (categories == '') {
                document.getElementById('categories_error').innerHTML = "Please enter categories";
                return false;
            }
            if (tags == '') {
                document.getElementById('tags_error').innerHTML = "Please enter tags";
                return false;
            }
            return true;

        }
    </script>
</body>

</html>