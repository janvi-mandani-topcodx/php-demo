<?php
include '../connection.php';
session_start();
if (isset($_POST['login_user'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $con->query($sql);

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            header("Location: userdata.php");
            exit();
        } else {
            $error = "Invalid email or password";
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

                            <h3 class="mb-5">Login</h3>
                            <?php if (isset($error)) :
                                echo "<h5 style='color:red'>
                                    $error
                                </h5>";
                            ?>
                            <?php endif; ?>
                            <form method="post" action="login.php">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label lable">Email address</label>
                                    <input type="email" class="form-control email" id="exampleFormControlInput1 email" name="email" placeholder="Enter Email....">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label lable">Password</label>
                                    <input type="password" class="form-control password" name="password" id="exampleInputPassword1 password" name="email" placeholder="Enter Password....">
                                </div>


                                <a href="forgot.php" class="forgot">Forgot password?</a>
                                <button type="login" class="submit btn btn-info fs-5" name="login_user">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>