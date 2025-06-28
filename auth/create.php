<?php
include 'form.php';


?>
<script>
    document.getElementById("visi").style.display = " none"

    function eye() {
        let password = document.getElementById("pass");
        let eye = document.getElementById("eyeimg");

        if (password.type === "password") {
            password.type = "text";
            eye.src = "../img/eye-c.svg";
        } else {
            password.type = "password";
            eye.src = "../img/eye.png";
        }
    }

    function eye2() {
        let password = document.getElementById("conform");
        let eye2 = document.getElementById("eyeimg2");

        if (password.type === "password") {
            password.type = "text";
            eye2.src = "../img/eye-c.svg";
        } else {
            password.type = "password";
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
        if (first == "") {
            document.getElementById("first_n").innerHTML = "please enter your first name"
            return false;
        }
        if (last == "") {
            document.getElementById("last_n").innerHTML = "please enter your last name"
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
                btn.style.backgroundColor = "transparent";
                btn.style.color = "#4e4e4e";
                btn.style.border = "1px solid #7d7d7d";

            }
        });
    }
</script>