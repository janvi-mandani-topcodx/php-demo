<?php
include '../connection.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (!empty($email)) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $con->query($sql);

        if ($result && $result->num_rows === 1) {
            header("Location: reset.php");
            exit();
        } else {
            $error = "Invalid email!";
        }
    } else {
        $error = "Please enter email.";
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

                            <h3 class="mb-5">Forgot Password</h3>
                            <?php if (isset($error)) :
                                echo "<h5 style='color:red'>
                                    $error
                                </h5>";
                            ?>
                            <?php endif; ?>
                            <form method="post" action="forgot.php">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label lable">Email address</label>
                                    <input type="email" class="form-control email" id="exampleFormControlInput1 email" name="email" placeholder="Enter Email....">
                                </div>

                                <a href="login.php" class="forgot">Back To Loged in?</a>
                                <button type="login" class="submit btn btn-info fs-5" name="login_user">Password reset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>