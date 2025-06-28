<?php
include '../connection.php';
session_start();
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $_SESSION['email'] = $email;
    $_SESSION['first_name'] = $firstName;
    $_SESSION['last_name'] = $lastName;
    header("Location: userdata.php");
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
                            <h3 id="header">Register User</h3>
                            <form action="userdata.php" method="post" onsubmit="return submitval()">

                                <div class="mb-3">
                                    <label for="first" class="form-label lable">First Name</label>
                                    <input type="text" class="form-control py-2 input" id="exampleFormControlInput1 first" name="first_name" placeholder="Enter Your First Name....">
                                    <div class="valid" id="first_n"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="last" class="form-label lable">Last Name</label>
                                    <input type="text" class="form-control py-2 input" id="exampleFormControlInput1 last" name="last_name" placeholder="Enter Your Last Name....">
                                    <div class="valid" id="last_n"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1 email" class="form-label lable">Email address</label>
                                    <input type="email" class="form-control py-2 input" id="exampleFormControlInput1 email" name="email" placeholder="Enter Your Email....">
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
                                    <div class="d-flex justify-content-center position-relative pos">
                                        <input type="password" class="form-control py-2 input input" id="conform" name="conform" placeholder="Enter Your conform Password....">
                                        <img src="../img/eye.png" alt="" width="20" class="eye " id="eyeimg2" onclick="eye2()" />
                                    </div>
                                    <div class="valid" id="cpass"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1 phone" class="form-label lable">Phone Number</label>
                                    <input type="number" class="form-control py-2 input" id="exampleFormControlInput1 phone" name="phone" placeholder="XXXX XXX XXX">
                                    <div class="valid" id="phone_num"></div>
                                </div>


                                <div class="mb-3">
                                    <label for="exampleFormControlInput1 dob" class="form-label lable">Date of Birth</label>
                                    <input type="date" class="form-control py-2 input" id="exampleFormControlInput1 dob" name="dob" placeholder="Enter your date of birth">
                                    <div class="valid" id="date"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label lable">Gender</label>
                                    <div class="d-flex justify-content-between">
                                        <input type="button" name="male" class="form-control py-2 gen mx-2 fs-5" id="male" value="Male" onclick="selecteGender('male')" />
                                        <input type="button" name="female" class="form-control py-2 gen mx-2 fs-5" id="female" value="Female" onclick="selecteGender('female')" />
                                        <input type="button" name="other" class=" form-control py-2 gen mx-2 fs-5" id="other" value="Other" onclick="selecteGender('other')" />
                                    </div>
                                    <input type="hidden" name="gender" id="gender" />
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
                                        <input type="text" class="form-control py-2 input" id="exampleFormControlInput1 card" name="card" placeholder="Enter Your card number....">
                                        <div class="valid" id="card_number"></div>
                                    </div>


                                    <div class="d-flex justify-content-around">
                                        <label for="irn" class="form-label lable align">IRN</label>
                                        <label for="valid" class="form-label lable align">Valid to</label>
                                    </div>
                                    <div class="d-flex justify-content-around  mb-2">
                                        <input type="text" name="irn" placeholder="enter IRN" id="irn" class="form-control py-2 input" />

                                        <input type="text" name="valid" placeholder="enter correct expire date" id="valid" class="form-control py-2 input" />

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

                                <button class="acc" type="submit" id="submit_button" name="register">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.getElementById("visi").style.display = " none"

        function eye() {
            let pass = document.getElementById("pass");
            let eye = document.getElementById("eyeimg");

            if (pass.type === "password") {
                pass.type = "text";
                eye.src = "../img/eye-c.svg";
            } else {
                pass.type = "password";
                eye.src = "../img/eye.png";
            }
        }

        function eye2() {
            let pass = document.getElementById("conform");
            let eye2 = document.getElementById("eyeimg2");

            if (pass.type === "password") {
                pass.type = "text";
                eye2.src = "../img/eye-c.svg";
            } else {
                pass.type = "password";
                eye2.src = "../img/eye.png";
            }
        }

        function btn(selectedId) {
            const buttons = ['btnn', 'btny'];

            buttons.forEach(id => {
                const btn = document.getElementById(id);

                if (id === selectedId) {
                    btn.style.backgroundColor = "maroon";
                    btn.style.color = "white";
                    btn.style.border = "none";
                    document.getElementById("visi").style.display = "block";
                    document.getElementById("medicare").value = "Yes";
                } else {
                    btn.style.backgroundColor = "white";
                    btn.style.color = "#4e4e4e";
                    btn.style.border = "1px solid #7d7d7d";
                    document.getElementById("visi").style.display = "none";
                    document.getElementById("medicare").value = "No";

                }
            });
        }
        let selectgen = ""

        function submitval() {
            let email = document.getElementById("email").value;
            let password = document.getElementById("pass").value;
            let conform = document.getElementById("conform").value;
            let first = document.getElementById("first").value;
            let last = document.getElementById("last").value;
            let phone = document.getElementById("phone").value;
            let dob = document.getElementById("dob").value;
            let cardNumber = document.getElementById("card").value;
            let irn = document.getElementById("irn").value;
            let validTo = document.getElementById("valid").value;
            let medicare = document.getElementById("medicare").value;
            if (first == "") {
                document.getElementById("first_n").innerHTML = "please enter your first name"
                return false;
            }
            if (last == "") {
                document.getElementById("last_n").innerHTML = "please enter your last name"
                return false;
            }
            if (email == "") {
                document.getElementById("mail").innerHTML = "please enter email";
                return false;
            }
            if (password == "") {
                document.getElementById("passw").innerHTML = "please enter password";
                return false;
            }
            if (conform == "") {
                document.getElementById("cpass").innerHTML = "please enter conform password"
                return false;
            }
            if (password != conform) {
                document.getElementById("cpass").innerHTML = "please enter valid conform password"
                return false;
            }
            if (phone == "") {
                document.getElementById("phone_num").innerHTML = "please enter your phone number"
                return false;
            }
            if (phone.length != 10) {
                document.getElementById("phone_num").innerHTML = "please enter 10 digit phone number"
                return false;
            }
            if (medicare === "Yes") {
                if (cardNumber === "") {
                    document.getElementById("card_number").innerHTML = "Please enter a valid Card number.";
                    return false;
                }
                if (irn === "") {
                    document.getElementById("irn_number").innerHTML = "Please enter IRN number.";
                    return false;
                }

                if (validTo === "") {
                    document.getElementById("validto_number").innerHTML = "Please enter the Valid To date.";
                    return false;
                }
            }
            let formData = {
                email: email,
                password: password,
                confirmPassword: conform,
                firstName: first,
                lastName: last,
                phone: phone,
                dob: dob,
                gender: selectgen,
                cardNumber: cardNumber,
                irn: irn,
                validTo: validTo
            };

            if (editindex !== null) {
                data[editindex] = formData;
                editindex = null;
            } else {
                data.push(formData);
            }
            return false;
        }

        function selecteGender(selectedId) {
            selectgen = selectedId;

            document.getElementById("gender").value = selectgen;

            const buttons = ['male', 'female', 'other'];
            console.log(selectedId);
            buttons.forEach(id => {
                const btn = document.getElementById(id);

                if (id === selectedId) {
                    btn.style.backgroundColor = "maroon";
                    btn.style.color = "white";
                    btn.style.border = "none";

                } else {
                    btn.style.backgroundColor = "white";
                    btn.style.color = "#4e4e4e";
                    btn.style.border = "1px solid #7d7d7d";

                }
            });
        }
    </script>
</body>

</html>