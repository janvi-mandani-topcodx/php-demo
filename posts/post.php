<?php
include '../connection.php';
function slugify($text)
{
    $text = strtolower($text);
    $text = str_replace(' ', '-', $text);
    return trim($text, '-');
}

if (isset($_POST['post']) && $_SERVER["REQUEST_METHOD"] == "POST") {
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

                            <h2 id="header" class="text-center mt-5">
                                <?php
                                if (isset($_GET['id'])) {
                                    echo "Edit Post";
                                } else {
                                    echo "Create Post";
                                }
                                ?>
                            </h2>
                            <form method="post" action="postdata.php" class="form-center" enctype="multipart/form-data" onsubmit="return submitval()">
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
                                    if ($_SESSION['id'] == $post['user_id']) {
                                        echo " <button type='submit' name='post' class='acc'> Edit Post </button>";
                                    }
                                } else {
                                    echo " <button type='submit' name='post' class='acc'> Create Post </button>";
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
            let fileupload = document.getElementById('fileupload').files[0];
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
            return true;

        }
        $(document).ready(function() {

            $(document).on('change', '#fileupload', function() {
                console.log(this.files);

                file = this.files[0];
                console.log(file);
                if (file) {
                    let reader = new FileReader();
                    console.log("reader = ", reader);
                    reader.onload = function(e) {
                        $("#imgPreview")
                            .attr("src", e.target.result).attr("height", 300).attr("width", 300);
                        console.log(e.target);

                    };

                    reader.readAsDataURL(file);

                }

            })
        })
    </script>
</body>

</html>