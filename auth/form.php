<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php include '../bootstrap.php'; ?>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="backgroundColor">
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-12 col-lg-9 col-xl-8">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <?php
                            if (isset($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo $_SESSION['error_message'];
                                echo '</div>';
                                unset($_SESSION['error_message']);
                            }
                            ?>
                            <div>
                                <div>
                                    <h3 id="header">
                                        <?php
                                        if (isset($_GET['id'])) {
                                            echo "Edit Account";
                                        } else {
                                            echo "Create Account";
                                        }
                                        ?>
                                    </h3>
                                    <form action="userdata.php" method="post" onsubmit="return submitval()">
                                        <input type="hidden" name="edit_id" value="<?= isset($id) ? $id : '' ?>">

                                        <div class="mb-3">
                                            <label for="first" class="form-label lable">First Name</label>
                                            <input type="text" class="form-control py-2 input" value="<?= isset($firstName) ? $firstName : ''  ?>" id="exampleFormControlInput1 first" name="first_name" placeholder="Enter Your First Name....">
                                            <div class="valid" id="first_n"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="last" class="form-label lable">Last Name</label>
                                            <input type="text" class="form-control py-2 input" id="exampleFormControlInput1 last" value="<?= isset($lastName) ? $lastName : ''  ?>" name="last_name" placeholder="Enter Your Last Name....">
                                            <div class="valid" id="last_n"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1 email" class="form-label lable">Email address</label>
                                            <input type="email" class="form-control py-2 input" id="exampleFormControlInput1 email" name="email" value="<?= isset($email) ? $email : ''  ?>" placeholder="Enter Your Email....">
                                            <div class="valid" id="mail"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1 pass" class="form-label lable">Password</label>
                                            <div class="d-flex justify-content-center position-relative">
                                                <input type="password" class="form-control py-2 input" id="pass" name="password" placeholder="Enter Your Password....">
                                                <img src="../img/eye.png" alt="" width="20" class="eye" onclick="eye()" id="eyeimg" />
                                            </div>
                                            <div class="valid" id="passw"></div>
                                        </div>


                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1 pass" class="form-label lable">Conform Password</label>
                                            <div class="d-flex justify-content-center position-relative">
                                                <input type="password" class="form-control py-2 input " id="conform" name="conform" placeholder="Enter Your conform Password....">
                                                <img src="../img/eye.png" alt="" width="20" class="eye " id="eyeimg2" onclick="eye2()" />
                                            </div>
                                            <div class="valid" id="cpass"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1 phone" class="form-label lable">Phone Number</label>
                                            <input type="number" class="form-control py-2 input" id="exampleFormControlInput1 phone" name="phone" value="<?= isset($phone) ? $phone : ''  ?>" placeholder="XXXX XXX XXX">
                                            <div class="valid" id="phone_num"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1 dob" class="form-label lable">Date of Birth</label>
                                            <input type="date" class="form-control py-2 input" id="exampleFormControlInput1 dob" value="<?= isset($dob) ? $dob : '' ?>" name="dob" placeholder="Enter your date of birth">
                                            <div class="valid" id="date"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="gender" class="form-label lable">Gender</label>
                                            <div class="d-flex justify-content-between">
                                                <input type="button" name="male" class="form-control py-2 gen mx-2 fs-5" id="male" value="Male" onclick="selecteGender('male')" />
                                                <input type="button" name="female" class="form-control py-2 gen mx-2 fs-5" id="female" value="Female" onclick="selecteGender('female')" />
                                                <input type="button" name="other" class=" form-control py-2 gen mx-2 fs-5" id="other" value="Other" onclick="selecteGender('other')" />
                                            </div>
                                            <input type="hidden" name="gender" id="gender" value="<?= isset($gender) ? $gender : ''  ?>" />
                                        </div>


                                        <h4 class="medicare my-4">Medicare card</h4>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-around">
                                                <input type="button" name="yes" class="btn_form btn_yes  mx-2 fs-4 " onclick="btn('btny')" id="btny" value="Yes">
                                                <input type="button" name="no" class="btn_form  mx-2 fs-4" onclick="btn('btnn')" id="btnn" value="No">
                                                <input type="hidden" value="" name="medicare" id="medicare">
                                            </div>
                                        </div>

                                        <div id="visi">

                                            <div class="mb-3">
                                                <label for="card" class="form-label lable">Card Number</label>
                                                <input type="text" class="form-control py-2 input" id="exampleFormControlInput1 card" value="<?= isset($card) ? $card : ''  ?>" name="card" placeholder="Enter Your card number....">
                                                <div class="valid" id="card_number"></div>
                                            </div>

                                            <div class="d-flex justify-content-around">
                                                <label for="irn" class="form-label lable align">IRN</label>
                                                <label for="valid" class="form-label lable align">Valid to</label>
                                            </div>

                                            <div class="d-flex justify-content-around  mb-2">
                                                <input type="text" name="irn" placeholder="enter IRN" id="irn" value="<?= isset($irn) ? $irn : ''  ?>" class="form-control py-2 input" />

                                                <input type="text" name="valid" placeholder="enter correct expire date" value="<?= isset($valid) ? $valid : ''  ?>" id="valid" class="form-control py-2 input" />

                                            </div>

                                            <div class="d-flex justify-content-around">
                                                <div class="valid" id="irn_number"></div>
                                                <div class="valid" id="validto_number"></div>
                                            </div>
                                        </div>

                                        <div class="check">
                                            <input type="checkbox" name="" id="check" class="checkbox" /><span class="span">I agree with the <span class="red">Terms of Use</span> and <span class="red">Privacy Policy</span></span>
                                        </div>


                                        <div class="check">
                                            <input type="checkbox" name="" id="check2" class="checkbox" /><span class="span">Stay updated with news and spacial offers</span>
                                        </div>

                                        <button class="acc" type="submit" name="create" id="submit_button">
                                            <?php
                                            if (isset($_GET['id'])) {
                                                echo "Edit Account";
                                            } else {
                                                echo "Create Account";
                                            }
                                            ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>