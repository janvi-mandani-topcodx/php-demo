<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../jquery-3.7.1.min.js"></script>
</head>

<body>
</body>

</html>
<?php
include '../connection.php';
include '../bootstrap.php';
include 'header_product.php';
if (isset($_POST['checkout']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $userId =  $_SESSION['id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $delivery = $_POST['delivery'];
    $totalPrice = 0;
    $cartItems = $cart = $con->query("SELECT carts.product_id AS id, carts.quantity, products.price 
                                FROM carts 
                                JOIN products ON carts.product_id = products.id WHERE carts.user_id = " . $userId);

    while ($products = $cartItems->fetch_assoc()) {
        $total = $products['quantity'] * $products['price'];
        $totalPrice += $total;
    }
    $jsonString = json_encode([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'address' => $address,
        'state' => $state,
        'country' => $country
    ]);
    $sql = "INSERT INTO orders (user_id , shipping_address , delivery , total) 
                        VALUES ('$userId' ,'$jsonString','$delivery' , '$totalPrice')";
    if ($con->query($sql)) {
        $orderId = $con->insert_id;
        $cartItems = $cart = $con->query("SELECT carts.product_id AS id, carts.quantity, products.price 
                                FROM carts 
                                JOIN products ON carts.product_id = products.id WHERE carts.user_id = " . $userId);

        while ($products = $cartItems->fetch_assoc()) {
            $quantity =  $products['quantity'];
            $price =  $products['price'];
            $productId = $products['id'];
            $data = $con->query("INSERT INTO order_items (order_id , product_id , quantity , price)
                VALUES  ('$orderId', '$productId' , '$quantity' , '$price')");
        }
    }
}

?>