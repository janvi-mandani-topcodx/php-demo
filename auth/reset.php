<?php
include '../connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_POST['reset_user'])) {
    $email = $_POST['email'];
    $old_password = md5($_POST['old_password']);
    $new_password = md5($_POST['new_password']);

    if (!empty($email) && !empty($old_password) && !empty($new_password) && !empty(md5($_POST['confirm_password']))) {
        $confirm_password = md5($_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            $error = "New password and Confirm password not match";
        } else {
            $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$old_password'";
            $result = $con->query($sql);
            if ($result && $result->num_rows === 1) {
                $update = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
                if ($con->query($update)) {
                    header("Location: login.php");
                    exit();
                }
            } else {
                $error = "Invalid email or old password";
            }
        }
    } else {
        $error = "Please fill in all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    include '../bootstrap.php';
    ?>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="backgroundColor">
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Reset Password</h3>
                            <?php if (isset($error)) :
                                echo "<h5 style='color:red'>
                                    $error
                                </h5>";
                            ?>
                            <?php endif; ?>
                            <form method="post" action="reset.php">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label lable">Email address</label>
                                    <input type="email" class="form-control email" id="exampleFormControlInput1 email" name="email" placeholder="Enter Email....">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label lable">Old Password</label>
                                    <input type="password" class="form-control password" id="exampleFormControlInput1 password" name="old_password" placeholder="Enter Old Password....">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label lable">New Password</label>
                                    <input type="password" class="form-control password" id="exampleFormControlInput1 password" name="new_password" placeholder="Enter New Password....">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label lable">Confirm Password</label>
                                    <input type="password" class="form-control password" id="exampleFormControlInput1 password" name="Confirm_password" placeholder="Enter Confirm Password....">
                                </div>

                                <a href="login.php" class="forgot">Back To Loged in?</a>
                                <button type="login" class="submit btn btn-info fs-5" name="reset_user">Reset Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>