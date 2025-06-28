<?php
include '../connection.php';
session_start();
if (isset($_POST['delete_id'])) {
    $userId  = $_POST['delete_id'];
    $sql = "DELETE FROM post_comments WHERE id = $userId ";

    if ($con->query($sql)) {
        echo "Successfully deleting comment";
    } else {
        echo "Error deleting comment: " . $con->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])  && isset($_POST['postId'])) {
    $user_id = $_SESSION['id'];
    $comment = $_POST['comment'];
    $postId = $_POST['postId'];
    $created_at = date("h:ia");

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        $sql = "UPDATE post_comments SET 
            user_id='$user_id', post_id='$postId', comment='$comment', created_at='$created_at' WHERE id='$editId'";
    } else {
        $sql = "INSERT INTO post_comments (user_id, post_id, comment, created_at) 
                VALUES ('$user_id','$postId','$comment', '$created_at')";
    }

    if ($con->query($sql)) {
        $post_comment_id = $con->insert_id;

        $user_query = $con->query("SELECT first_name, last_name FROM users WHERE id = $user_id");
        $comment_post = $user_query->fetch_assoc();
        $firstname = substr($comment_post['first_name'], 0, 1);

        $lastname = substr($comment_post['last_name'], 0, 1);
        $fullname = $firstname . $lastname;

        echo "<div class='my-5 dots rounded p-2' id='commentData-" . $post_comment_id . "'>
                    <div class='d-flex align-items-center justify-content-between'>
                        <div class='d-flex align-items-center'>
                            <div class='full_name d-flex justify-content-center align-items-center'>
                            <b>" . $fullname . "</b>
                            </div>
                            <b class='mx-3'>" . $comment_post['first_name'] . " " . $comment_post['last_name'] . "</b><br>
                        </div>

                    <div class='dropdown'>
                            <button type='button' class='border-0 dropdown-toggle hide'   data-bs-toggle='dropdown'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-three-dots-vertical' viewBox='0 0 16 16'>
                                <path d='M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0'/>
                            </svg>
                            </button>
                            <ul class='dropdown-menu'>
                                <li class='mb-2'>
                                            <input type='hidden' name='edit_comment' value='" . $post_comment_id . "'>
                                            <input type='hidden' name='postId' value='" . $postId . "'>
                                            <span  class=' edit_btn' data-comment = '" . $comment  . "'> Edit </span>
                                
                                </li>
                                <li>
                                            <span id='delete_data' data-comment='" . $post_comment_id . "'>Delete</span>
                                </li>
                            </ul>
                    </div>
       
                </div>
                    <div class='d-flex justify-content-between'>
                        <span class='mx-5'>" . $comment . "</span>
                        <span>" . $created_at . "</span>
                    </div>
                </div>";
    }
}
