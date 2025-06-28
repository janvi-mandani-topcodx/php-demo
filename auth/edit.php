<?php
include '../connection.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $con->query("SELECT * FROM users WHERE id = $id");
    if ($result->num_rows == 0) {
        header("Location: userdata.php");
        exit;
    }
    $user = $result->fetch_assoc();
    $id = $user['id'];
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $email = $user['email'];
    $password = $user['password'];
    $phone = $user['phone_number'];
    $dob = $user['date_of_birth'];
    $gender = $user['gender'];
    $card = $user['card_number'];
    $irn = $user['irn'];
    $valid = $user['valid_to'];

    if (!empty($card) && !empty($irn) && !empty($valid)) {
        $yes = 'btny';
    } else {
        $yes = 'btnn';
    }
}

include 'form.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        selecteGender("<?php echo $gender; ?>");
        btn("<?php echo $yes; ?>");
    });

    document.getElementById("visi").style.display = " none";


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
                btn.style.backgroundColor = "transparent";
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

        if (password != conform) {
            document.getElementById("cpass").innerHTML = "please enter valid conform password"
            return false;
        }
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
        if (phone.length != 10) {
            document.getElementById("phone_num").innerHTML = "please enter 10 digit phone number";
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
        if (password != '') {
            if (conform == "") {
                document.getElementById("cpass").innerHTML = "please enter conform password"
                return false;
            }
            if (password != conform) {
                document.getElementById("cpass").innerHTML = "please enter valid conform password"
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
        return true;
    }

    function selecteGender(selectedId) {
        selectgen = selectedId;

        document.getElementById("gender").value = selectgen;

        const buttons = ['male', 'female', 'other'];
        buttons.forEach(id => {
            const btn = document.getElementById(id);

            if (id === selectgen) {
                btn.style.backgroundColor = "maroon";
                btn.style.color = "white";
                btn.style.border = "none";
            } else {
                btn.style.backgroundColor = "transparent";
                btn.style.color = "#4e4e4e";
                btn.style.border = "1px solid #7d7d7d";
            }
        });
    }
</script>