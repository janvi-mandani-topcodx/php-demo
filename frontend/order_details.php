<?php
include '../connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <?php include '../bootstrap.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php
    include 'header_product.php';
    ?>
    <div class="container">
        <h2 class='text-center py-4'>Order Details </h2>
        <?php
        $order_details = $con->query("SELECT * from orders where id = '" . $_GET['id'] . "'");
        $row = $order_details->fetch_assoc();
        $shipping = json_decode($row['shipping_address']);
        echo "<h4>Customer Name</h4>";
        echo $shipping->first_name;
        echo " ";
        echo $shipping->last_name;
        echo "<h4 class='pt-5'>Shipping Address</h4>";
        echo $shipping->address . " ,<br> " . $shipping->state  . " , " . $shipping->country;
        ?>
        <table class='my-5'>
            <thead>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </thead>
            <tbody style="border: 1px solid black;">
                <?php
                $order_history = $con->query("SELECT  products.title, order_items.price, order_items.quantity
                                      FROM orders
                                      JOIN order_items ON orders.id = order_items.order_id
                                      JOIN products ON order_items.product_id = products.id WHERE orders.id = '" . $_GET['id'] . "'");

                while ($order = $order_history->fetch_assoc()) {
                    $total = $order['price'] * $order['quantity'];
                    echo "<tr>
                        <td>" . $order['title'] . "</td>
                        <td>" . $order['price'] . "</td>
                        <td>" . $order['quantity'] . "</td>
                        <td>" . $total . "</td>
                  </tr>";
                }
                $result = $con->query("SELECT * FROM settings");
                $shipping = $result->fetch_assoc();
                if ($shipping['key'] == 'shipping_cost') {
                    $shippingCost = $shipping['value'];
                }

                echo "
                
                <tr>
                    <td colspan='3' class='text-start fw-bold'>shipping</td>
                    <td class='fw-bold'> " . $shippingCost . " </td>
                </tr>
                <tr>
                    <td colspan='3' class='text-start fw-bold'>Total</td>
                    <td class='fw-bold'> " . $row['total'] . " </td>
                </tr>";
                ?>

            </tbody>
        </table>
    </div>
</body>

</html>