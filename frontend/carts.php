<?php
include '../connection.php';
session_start();

if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $delete = "DELETE FROM carts WHERE id = $deleteId";

    if ($con->query($delete)) {
        $userId = $_SESSION['id'];
        $cart = $con->query("SELECT * FROM carts WHERE user_id = '$userId'");

        if ($cart->num_rows == 0) {
            echo "redirect";
        } else {
            echo "notRedirect";
        }
    } else {
        echo "error";
    }
}

if (isset($_POST['voucherCode'])) {
    include 'voucher.php';

    $voucherInput = $_POST['voucherCode'];
    $subtotal = $_POST['subtotal'];
    $cartCount = $_POST['cartCount'];
    $discountUserId = $_SESSION['id'];

    $discounts = new voucherCode();

    $result = $discounts->voucher($voucherInput, $subtotal, $cartCount, $discountUserId, $con);
    $discount = $con->query("SELECT * FROM discounts WHERE code = '" . $voucherInput . "'");
    if ($discount_data = $discount->fetch_assoc()) {

        $amount = $discount_data['amount'];
        $type = $discount_data['type'];
        $code = $discount_data['code'];


        $jsonString = json_encode([
            'user_id' => $discountUserId,
            'code' => $code
        ]);
//        $orderDiscounts = $con->query("SELECT * FROM orders");
//        if ($orderDiscounts->num_rows > 0) {
//            $sql = $con->query("UPDATE order_discounts SET amount='$amount' , type='$type'  WHERE code = " . $code);
//        }
//        else{
            $sql = $con->query("INSERT INTO order_discounts (discount, type, amount)
                                VALUES ('$jsonString', '$type', '$amount')");
//        }
    }
}

if (isset($_POST['title'])) {
    $user_id = $_SESSION['id'];
    $product_id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];
    $check = $con->query("SELECT * FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'");
    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $cart = $row['id'];
        $quantity = $row['quantity'] + 1;
        $update = "UPDATE carts SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id' AND id = '$cart'";
        if ($con->query($update)) {
            echo "<div class='d-flex justify-content-between align-items-center my-4 cartDiv'  id='cart_div-" . $product_id . "' data-id='" . $cart . "' >
                <img src='" . $image . "' width='50px'>
                <div>
                    <span class='text-white ps-3 title'>" . $title . "</span>
                    <div class='d-flex ps-3'>
                        <span>$</span>
                        <p class='text-white price' id='price_cart'>" . $price . "</p> 
                    </div>
                </div>  
                <div class='text-bg-light mx-2 mt-1 plus dicrement'  data-id='" . $cart . "'  data-product='" . $product_id . "'>-</div>
                <input type='text' class='qty' value='" . $quantity . "' data-id='" . $cart . "' data-old='" . $quantity . "' data-product='" . $product_id . "' id='header_qty'>
                <div class='text-bg-light mx-2 mt-1 plus increment'  data-id='" . $cart . "'  data-product='" . $product_id . "'>+</div>
                <button class='delete' data-id='" . $cart . "' id='deleted'  data-product='" . $product_id . "'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                            <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                        </svg>
                    </button>
            </div>";
        } else {
            echo "";
        }
    } else {
        $sql = "INSERT INTO carts (user_id, product_id, quantity) 
                VALUES ('$user_id','$product_id','$quantity')";
        if ($con->query($sql)) {
            $cart_id = $con->insert_id;
            echo "<div class='d-flex justify-content-between align-items-center my-4 cartDiv' id='cart_div-" . $product_id . "' data-id='" . $cart_id . "' >
                    <img src='" . $image . "' width='50px'>
                    <div>
                        <span class='text-white ps-3 title'>" . $title . "</span>
                        <div class='d-flex ps-3'>
                                <span>$</span>
                                <p class='text-white price' id='price_cart'>" . $price . "</p> 
                            </div>
                    </div>      
                    <div class='text-bg-light mx-2 mt-1 plus dicrement  data-id='" . $cart_id . "'  data-product='" . $product_id . "'>-</div>
                    <input type='text' class='qty' value='" . $quantity . "' data-id='" . $cart_id . "' data-old='" . $quantity . "' data-product='" . $product_id . "' id='header_qty'>
                    <div class='text-bg-light mx-2 mt-1 plus increment'  data-id='" . $cart_id . "'  data-product='" . $product_id . "'>+</div>
                    <button class='delete' data-id='" . $cart_id . "' id='deleted'  data-product='" . $product_id . "'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                            </svg>
                        </button>
                </div>";
        } else {
            echo "";
        }
    }
} elseif (isset($_POST['quantity'])) {

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $product_id = $_POST['id'];
        $user_id = $_SESSION['id'];

        $quantity = $_POST['quantity'];
        $edit_id = $_POST['edit_id'];
        $check = $con->query("SELECT * FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'");
        if ($check->num_rows > 0) {
            $row = $check->fetch_assoc();
            $cart = $row['id'];
            $update = "UPDATE carts SET quantity='$quantity' WHERE user_id = '$user_id' AND product_id='$product_id' AND id='$edit_id'";
            if ($con->query($update)) {

                echo "<div class='d-flex justify-content-between align-items-center my-4 cartDiv' id='cart_div-" . $product_id . "' data-id='" . $cart . "' >
                        <div class='text-bg-light mx-2 mt-1 plus dicrement ' data-id='" . $cart . "' data-product='" . $product_id . "'>-</div>
                        <input type='text' class='qty' value='" . $quantity . "' data-id='" . $cart . "' data-old='" . $quantity . "' data-product='" . $product_id . "' id='header_qty'>
                        <div class='text-bg-light mx-2 mt-1 plus increment ' data-id='" . $cart . "' data-product='" . $product_id . "'>+</div>
                        <button class='delete' data-id='" . $cart . "' id='deleted'  data-product='" . $product_id . "'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                            </svg>
                        </button>
                </div>";
            }
        }
    }
}
