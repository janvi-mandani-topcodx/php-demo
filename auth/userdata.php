<?php
include '../connection.php';
session_start();
$register  = 'register.php';
if (!isset($register)) {
    if (!isset($_SESSION['email'])) {
        header('Location: ../auth/login.php');
        exit();
    }
}
// if (!isset($_SESSION['email'])) {
//     header('Location: login.php');
//     exit();
// } 


if (isset($_POST['create']) || isset($_POST['register'])) {

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $phoneNumber = $_POST['phone'];
    $dateOfBirth = $_POST['dob'];
    $gender = lcfirst($_POST['gender']);
    $cardNumber = $_POST['card'];
    $irn = $_POST['irn'];
    $validTo = $_POST['valid'];
    $medicare = $_POST['medicare'];

    if ($medicare == 'No') {
        $cardNumber = null;
        $irn = null;
        $validTo = null;
    }
    if (!empty($cardNumber) && !empty($irn) && !empty($validTo)) {
        $medicare = "Yes";
    }
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        $emailcheck = "SELECT id FROM users WHERE email = '$email' AND id  != '$editId'";
        $result = $con->query($emailcheck);

        if ($result && $result->fetch_assoc()) {
            $_SESSION['error_message'] = "Error: Email already exists.";
            header("Location: edit.php?id=$editId");
            exit();
        }

        $userQuery = "SELECT id FROM users WHERE id  = '$editId'";
        $user = $con->query($userQuery);

        if ($user && $user->num_rows === 1) {
            $password = !empty($password) ? md5($password) : $user['password'];
        }

        $sql = "UPDATE users SET 
            first_name='$firstName', last_name='$lastName', email='$email', password = '$password', phone_number='$phoneNumber', 
            date_of_birth='$dateOfBirth', gender='$gender', card_number='$cardNumber', irn='$irn', valid_to='$validTo'
            WHERE id='$editId'";
    } else {
        $emailcheck = "SELECT id FROM users WHERE email = '$email'";
        $result = $con->query($emailcheck);

        if ($result && $result->fetch_assoc()) {
            $_SESSION['error_message'] = "Error: Email already exists.";
            header("Location: create.php");
            exit();
        }

        $sql = "INSERT INTO users (first_name, last_name, email, password, phone_number, date_of_birth, gender, card_number, irn, valid_to) 
                        VALUES ('$firstName', '$lastName', '$email', '$password', '$phoneNumber', '$dateOfBirth', '$gender', '$cardNumber', '$irn', '$validTo')";
    }




    if ($con->query($sql)) {

        if (isset($_POST['register'])) {
            $_SESSION['email'] = $email;
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;
            $_SESSION['id'] = $con->insert_id;
        }
    } else {
        echo "Error: " . $con->error;
    }
}




$result = $con->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All User Data</title>
    <?php include '../bootstrap.php'; ?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>

<body>


    <?php
    include '../header.php';
    ?>
    <div class="width-90">
        <h2>Users</h2>

        <div class="d-flex justify-content-between">
            <input type="text" id="search" placeholder="search...." class="searchButton">
            <a class="btn btn-outline-info" href="create.php" role="button">Create</a>
        </div>

        <table id="result" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Card</th>
                    <th>IRN</th>
                    <th>Valid To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($user = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    echo "<td>" . $user['first_name'] . "</td>";
                    echo "<td>" . $user['last_name'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>" . $user['phone_number'] . "</td>";
                    echo "<td>" . $user['date_of_birth'] . "</td>";
                    echo "<td>" . ucfirst($user['gender']) . "</td>";
                    echo "<td>" . $user['card_number'] . "</td>";
                    echo "<td>" . $user['irn'] . "</td>";
                    echo "<td>" . $user['valid_to'] . "</td>";
                    echo "<td>
                                    <form action='delete.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='delete_id' value='" . $user['id'] . "'>
                                        <button type='submit ' class='btn btn-info' >Delete</button>
                                    </form>
                                    <button class='btn btn-info'><a href='edit.php?id=" . $user['id'] . "' class='text-dark edit'>Edit</a></button>

                                </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>



    </div>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "search.php",
                        type: "POST",
                        data: {
                            search: query
                        },
                        success: function(data) {
                            $("#result tbody").html(data);
                        },
                    });
                } else {
                    loadAllRecords();
                }
            });

            function loadAllRecords() {
                $.ajax({
                    url: "search.php",
                    type: "POST",
                    data: {
                        search: ""
                    },
                    success: function(data) {
                        $("#result tbody").html(data);
                    },
                });
            }
        });
    </script>
</body>

</html>