<?php

include '../connection.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
}
$firstname = substr($_SESSION['first_name'], 0, 1);
$lastname = substr($_SESSION['last_name'], 0, 1);
$fullnameLogin = $firstname . $lastname;
$var = isset($comment) ? $comment : '';
$comment = $con->query("SELECT post_comments.id , post_comments.comment, post_comments.created_at, users.first_name, users.last_name 
                        FROM post_comments 
                        JOIN users ON post_comments.user_id = users.id 
                        WHERE post_comments.post_id = $postId");

?>

<section>
    <div class="container py-2 ">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-12 col-lg-9 col-xl-10">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <h3 class="text-danger">Comments</h3>
                        <form method="post" action="../posts/edit_post.php?id=<?php echo  $postId ?>" enctype="multipart/form-data">
                            <div class="row d-flex ">
                                <div class="form-group d-flex justify-content-center align-items-center  col-md-10">
                                    <?php
                                    echo "<div class='full_name d-flex justify-content-center align-items-center mx-2'>
                                                <b>" . $fullnameLogin . "</b>
                                            </div>"
                                    ?>
                                    <input type="text" class="form-control input" id="comment" name="comment" placeholder="Comment....." value="<?php echo $var ?>">
                                    <div id="title_error"></div>
                                </div>
                                <div class="col-md-2 d-flex text-end">

                                    <button type='button' name='comment_btn' id='comment_add' class='acc fs-5 p-2 '>
                                        Comment
                                    </button>


                                    <button type='button' name='clear' id='clear_btn' class='acc fs-5 p-2 d-none'>
                                        clear
                                    </button>
                                </div>
                            </div>
                        </form>


                        <div id="comments_post">
                            <div class="my-2 " id="comment_div">
                                <?php while ($post = $comment->fetch_assoc()) {
                                    $firstname = substr($post['first_name'], 0, 1);
                                    $lastname = substr($post['last_name'], 0, 1);
                                    $fullname = $firstname . $lastname;
                                    echo "<div class='my-5 dots rounded p-2' id='commentData-" . $post['id'] . "'>
                                    <div class='d-flex align-items-center justify-content-between'>
                                        <div class='d-flex align-items-center'>
                                            <div class='full_name d-flex justify-content-center align-items-center'>
                                            <b>" . $fullname . "</b>
                                            </div>
                                            <b class='mx-3'>" . $post['first_name'] . " " . $post['last_name'] . "</b><br>
                                        </div>";

                                    if ($fullnameLogin === $fullname) {
                                        echo "<div class='dropdown'>
                                            <button type='button' class='border-0 dropdown-toggle hide' data-bs-toggle='dropdown'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-three-dots-vertical' viewBox='0 0 16 16'>
                                                <path d='M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0'/>
                                            </svg>
                                            </button>
                                            <ul class='dropdown-menu'>
                                                <li class='mb-2'>
                                                            <input type='hidden' name='edit_comment' value='" . $post['id'] . "'>
                                                            <input type='hidden' name='postId' value='" . $postId . "'>
                                                            <span class='edit_btn' data-action='" . $post['id'] . "' data-comment='" . $post['comment']  . "'> Edit </span>
                                            
                                                </li>
                                                <li>
                                                    <span id='delete_data' data-comment='" . $post['id'] . "'>Delete</span>
                                                </li>
                                            </ul>
                                        
                                    </div>";
                                    }
                                    echo "</div>
                                    <div class='d-flex justify-content-between'>
                                        <span class='mx-5'>" . $post['comment'] . "</span>
                                        <span>" . $post['created_at'] . "</span>
                                    </div>
                                </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {

        $(document).on('click', '#comment_add', function() {
            console.log("append data");

            const comment = $("#comment").val();
            const postId = "<?php echo $postId; ?>";
            if (comment.length > 0) {
                $.ajax({
                    url: "../comments/insert_update_comment.php",
                    type: "POST",
                    data: {
                        comment: comment,
                        postId: postId
                    },
                    success: function(data) {

                        $("#comment").val("");
                        $("#comments_post #comment_div").append(data);
                    }
                });
            } else {
                loadAllRecords();
            }
        });
        $(document).on('click', '#update_btn', function() {
            const comment = $("#comment").val().trim();
            const postId = "<?php echo $postId; ?>";
            const edit_id = $('.edit_btn').attr('data-action');
            console.log("edit = " + edit_id);

            if (comment.length > 0) {
                $.ajax({
                    url: "../comments/insert_update_comment.php",
                    type: "POST",
                    data: {
                        comment: comment,
                        postId: postId,
                        edit_id: edit_id
                    },
                    success: function(data) {
                        $("#comment").val("");
                        if (edit_id) {
                            $("#commentData-" + edit_id).replaceWith(data);
                            $("#comment").val("");
                            $('#update_btn').text('Comment').attr('id', 'comment_add');
                            $('#clear_btn').addClass('d-none');
                        }
                    }
                });
            } else {
                loadAllRecords();
            }
        });


        $(document).on('click', '.edit_btn', function() {
            const commentText = $(this).data('comment');
            const commentId = $(this).attr('data-action');
            console.log("comment = " + commentId);

            $("#comment").val(commentText);
            $("input[name='edit_comment']").val(commentId);

            $('#comment_add').text('Update').attr('id', 'update_btn');
            $('#clear_btn').removeClass('d-none');
        });


        $(document).on('click', '#delete_data', function() {
            let deleteId = $(this).data('comment');

            $.ajax({
                url: "../comments/insert_update_comment.php",
                type: "POST",
                data: {
                    delete_id: deleteId
                },
                success: function(data) {
                    console.log(deleteId);

                    console.log($("#commentData-" + deleteId));
                    $("#commentData-" + deleteId).remove();
                }
            });

        });

        function loadAllRecords() {
            $.ajax({
                url: "../comments/insert_update_comment.php",
                type: "POST",
                data: {
                    comment: "",
                    postId: ""
                },
                success: function(data) {
                    $("#result_posts tbody").append(data);
                },
            });
        }
        $("#clear_btn").on('click', function() {
            $("#comment").val("");
            $('#update_btn').text('Comment').attr('id', 'comment_add');
            $('#clear_btn').addClass('d-none');
        });
    });
</script>