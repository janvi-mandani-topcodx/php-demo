<?php
include '../connection.php';
include '../bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../jquery-3.7.1.min.js"></script>
</head>

<body class="backgroundColor">
    <?php
    include 'header_product.php';
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $cart = $con->query("SELECT carts.id , carts.quantity, products.id AS product_id, products.title, products.image , products.price 
                                FROM carts 
                                JOIN products ON carts.product_id = products.id WHERE carts.user_id = " . $user_id);
        $cartCount = 0;
        $count = $con->query("SELECT SUM(quantity) AS totalQuantity from carts WHERE user_id =" . $user_id);
        while ($countCart = $count->fetch_assoc()) {
            $cartCount = $countCart['totalQuantity'];
        }
    } else {
        $cart = $con->query("SELECT carts.id , carts.quantity, products.title, products.image , products.price 
                                FROM carts 
                                JOIN products ON carts.product_id = products.id");
    }

    if (isset($_POST['checkout']) && $_SERVER["REQUEST_METHOD"] == "POST") {

        $userId =  $_SESSION['id'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $address = $_POST['address'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $delivery = $_POST['delivery'];
        $mobile_number = $_POST['mobile'];
        $pincode = $_POST['pincode'];
        $createAt = date("Y-m-d");
        $totalPrice = 0;
        $cartItems = $cart = $con->query("SELECT carts.product_id AS id, carts.quantity, products.price 
                                FROM carts 
                                JOIN products ON carts.product_id = products.id WHERE carts.user_id = " . $userId);

        while ($products = $cartItems->fetch_assoc()) {
            $shippingThreashold = 0;
            $shippingCost = 0;

            $result = $con->query("SELECT * FROM settings");
            while ($row = $result->fetch_assoc()) {
                if ($row['key'] == 'free_shipping_threashold') {
                    $shippingThreashold = $row['value'];
                } elseif ($row['key'] == 'shipping_cost') {
                    $shippingCost = $row['value'];
                }
            }

            $total = $products['price'] * $products['quantity'];
            $totalPrice += $total;
            if ($totalPrice >= $shippingThreashold) {
                $maintotal = $totalPrice + $shippingCost;
                $showShipping = true;
            } else {
                $showShipping = false;
                $maintotal = $totalPrice;
            }
        }
        $jsonString = json_encode([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'address' => $address,
            'state' => $state,
            'country' => $country
        ]);
        $sql = "INSERT INTO orders (user_id , shipping_address , delivery , mobile_number , pincode ,created_at , total , shipping_cost) 
                        VALUES ('$userId' ,'$jsonString', '$delivery' , '$mobile_number' , '$pincode', '$createAt' , '$maintotal' , '$shippingCost')";
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
            $con->query("DELETE FROM carts WHERE user_id = $userId");
        }
    }
    ?>
    <div>
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-7 col-md-7 col-lg-7 col-xl-7">
                        <div class=" shadow-2-strong h-100" style="border-radius: 1rem;">
                            <h3 class="text-start ps-5">Shipping Details</h3>
                            <div class="py-3 px-5 text-center">
                                <form method="post" id='submit_form'>
                                    <div class="mb-3">
                                        <label for="first" class="form-label lable">First Name</label>
                                        <input type="text" class="form-control py-2 input" value="<?php echo $_SESSION['first_name']  ?>" id="first" name="first_name">
                                        <div class="valid" id="first_n"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="last" class="form-label lable">Last Name</label>
                                        <input type="text" class="form-control py-2 input" value="<?php echo $_SESSION['last_name']  ?>" id="last" name="last_name">
                                        <div class="valid" id="last_n"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label lable">Address</label>
                                        <input type="text" class="form-control py-2 input" id="address" name="address" placeholder="Address">
                                        <div class="valid" id="Address_n"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="state" class="form-label lable">State</label>
                                        <input type="text" class="form-control py-2 input" id="state" name="state" placeholder="State">
                                        <div class="valid" id="state_n"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label lable">Country</label>
                                        <input type="text" class="form-control py-2 input" id="country" name="country" placeholder="Country">
                                        <div class="valid" id="country_n"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label lable">Mobile Number</label>
                                        <input type="number" class="form-control py-2 input" id="mobile" name="mobile" placeholder="Mobile">
                                        <div class="valid" id="mobile_n"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pincode" class="form-label lable">Pincode</label>
                                        <input type="number" class="form-control py-2 input" id="pincode" name="pincode" placeholder="Pincode">
                                        <div class="valid" id="pincode_n"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="country" class="form-label lable">Delivery</label>
                                        <textarea name="delivery" id="delivery" placeholder="Delivery Notes " class="w-100 input"></textarea>
                                    </div>
                                    <button class="btn btn-secondary btn-block btn-lg w-100" type="submit" name="checkout" id="submit_button">Checkout</button>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="col-5 col-md-5 col-lg-5 col-xl-5 ">
                        <div class="card shadow-2-strong h-100" style="border-radius: 1rem;">
                            <div class="card-body p-3 text-center">
                                <div id="appendDataCheckout" class="h-100">
                                    <?php $totalPrice = 0;
                                    $maintotal = 0;
                                    echo "<div id='appendDivCheckout' class='cart_product '>";
                                    while ($productCart = $cart->fetch_assoc()) {

                                        $shippingThreashold = 0;
                                        $shippingCost = 0;

                                        $result = $con->query("SELECT * FROM settings");
                                        while ($row = $result->fetch_assoc()) {
                                            if ($row['key'] == 'free_shipping_threashold') {
                                                $shippingThreashold = $row['value'];
                                            } elseif ($row['key'] == 'shipping_cost') {
                                                $shippingCost = $row['value'];
                                            }
                                        }

                                        $total = $productCart['price'] * $productCart['quantity'];
                                        $totalPrice += $total;
                                        if ($totalPrice >= $shippingThreashold) {
                                            $maintotal = $totalPrice + $shippingCost;
                                            $showShipping = true;
                                        } else {
                                            $showShipping = false;
                                            $maintotal = $totalPrice;
                                        }
                                        if (isset($_SESSION['id'])) {
                                            $product_id = $productCart['product_id'];
                                            echo "<div class='d-flex justify-content-between align-items-center my-3 cartDivCheckout' id='cartDivCheckout-" . $product_id . "' data-id='" . $productCart['id'] . "'>
                                                <img src='" . $productCart['image'] . "' width='50px'>
                                                <div>
                                                    <span class='ps-3 title'>" . $productCart['title'] . "</span>
                                                    <div class='d-flex ps-3'>
                                                        <span>$</span>
                                                        <p class='price' id='price_cart'>" . $productCart['price'] . "</p> 
                                                    </div>
                                                </div>
                                                <div class='text-bg-light mx-2 mt-1 plus dicrementCheckout '  data-id='" . $productCart['id'] . "' data-product='" . $product_id . "'>-</div>
                                                <input type='text' class='qtyCheckout' value='" . $productCart['quantity'] . "' data-id='" . $productCart['id'] . "' data-old='" . $productCart['quantity'] . "' data-product='" . $product_id . "'>
                                                <div class='text-bg-light mx-2 mt-1 plus incrementCheckout'  data-id='" . $productCart['id'] . "' data-product='" . $product_id . "'>+</div>
                                                <button class='deleteButton' data-id='" . $productCart['id'] . "' id='deletedCheckout'  data-product='" . $product_id . "'> 
                                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                                        <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                                                    </svg>
                                                </button>
                                            </div>";
                                        } else {
                                            echo "";
                                            $totalPrice = 0;
                                        }
                                    }
                                    echo "</div>
                                        <div class='w-100'>
                                            <div class='position-absolute bottom-0 w-100 pe-4'>
                                                <hr><div class='row align-items-center mx-2'>
                                                    <div class='mb-3 col-6'>
                                                        <input type='text' class='form-control py-2 input' value='' id='exampleFormControlInput1 first' name='first_name' placeholder='Enter Voucher Code'>
                                                    </div>
                                                    <div class='col-6 mb-3'>
                                                        <button  type='button' class='btn btn-secondary w-100'>Apply</button>
                                                    </div>
                                                </div>
                                                <hr><div class='d-flex justify-content-between'>
                                                    <p class='fw-bold'>Subtotal</p>
                                                    <div class='d-flex'> 
                                                        <span>$</span>
                                                        <span id='subTotalCheckout' class='me-2'>" .  $totalPrice . "</span>
                                                    </div>
                                                </div>
                                                <div id='shipping_detailsCheckout' style='display: " . ($showShipping ? "block" : "none") . "'>
                                                    <div class='d-flex justify-content-between'>
                                                        <p class=''>Shipping</p> 
                                                        <div class='d-flex'> 
                                                            <input type='hidden' class='form-control' id='costCheckout' name='shipping_cost' value='" . $shippingCost . "'>
                                                            <input type='hidden' class='form-control' id='free_costCheckout' name='shippingThreashold' value='" . $shippingThreashold . "'>
                                                            <span>+$</span>
                                                            <span class='me-2'>" . $shippingCost . "</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class='hr_checkout'><div class='d-flex justify-content-between'>
                                                    <h5>Total</h5>
                                                    <div class='d-flex'> 
                                                        <span>$</span>
                                                        <span id='totalPriceCheckout' class='me-2'>" .  $maintotal . "</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            function countCheckout() {
                let totalCount = 0;
                $('.qtyCheckout').each(function() {
                    let qty = parseInt($(this).val());
                    totalCount += qty;
                });
                $('#count_increment').val(totalCount);
            }

            $('#submit_form').on('submit', function(e) {
                e.preventDefault();

                let firstname = $("#first").val();
                console.log(firstname);

                let lastname = $("#last").val();
                let address = $("#address").val();
                let state = $("#state").val();
                let phoneNumber = $("#mobile").val();
                let country = $("#country").val();
                let pincode = $("#pincode").val();
                let delivery = $("#delivery").val();

                $.ajax({
                    url: 'checkout.php',
                    type: 'POST',
                    data: {
                        first_name: firstname,
                        last_name: lastname,
                        address: address,
                        state: state,
                        mobile: phoneNumber,
                        country: country,
                        pincode: pincode,
                        delivery: delivery,
                        checkout: true
                    },
                    success: function(response) {
                        console.log(response);
                        $('#subTotalCheckout').text('0');
                        $('#sub_total').text('0');
                        $('#totalPriceCheckout').text('0');
                        $('#total_price').text('0');
                        $('#shipping_details').hide();
                        $('#shipping_detailsCheckout').hide();
                        $('#appendDivCheckout').remove();
                        $('#append_div').remove();
                        $('#count_increment').val('0');
                        window.location.href = './index.php';
                    }
                });
            });


            function updateTotalCheckout() {
                let totalPrice = 0;
                let maintotal = 0;
                $('.qtyCheckout').each(function() {
                    let quantity = parseInt($(this).val());
                    console.log(quantity);
                    let price = $(this).closest('.cartDivCheckout').find('.price').text();
                    console.log(price);
                    total = quantity * price;
                    totalPrice += total;
                    let shippingThreashold = $('#free_costCheckout').val();
                    let shipping_cost = $('#costCheckout').val();
                    if (totalPrice >= shippingThreashold) {
                        $('#shipping_detailsCheckout').show();
                        maintotal = parseInt(totalPrice) + parseInt(shipping_cost);
                    } else {
                        $('#shipping_detailsCheckout').hide();
                        maintotal = totalPrice;
                    }
                });
                $('#subTotalCheckout').text(totalPrice);
                $('#totalPriceCheckout').text(maintotal);
                $('#total_price').text(maintotal);
                $('#sub_total').text(totalPrice);
            }
            $(document).on("click", ".incrementCheckout", function() {
                let row = $(this).parent('.cartDivCheckout');
                let input = row.find('.qtyCheckout');
                var currentValue = parseInt(input.val());
                input.val(currentValue + 1);
                let quantity = input.val();
                console.log(quantity);
                edit_id = $(this).data('id');
                $("input.qty[data-id='" + edit_id + "']").val(quantity);
                let id = $(this).data('product');
                console.log(id);
                $.ajax({
                    url: 'carts.php',
                    type: 'POST',
                    data: {
                        quantity: quantity,
                        edit_id: edit_id,
                        id: id
                    },
                    success: function(data) {
                        countCheckout();
                        updateTotalCheckout()
                    }
                });
            });
            $(document).on('click', '.dicrementCheckout', function() {
                let row = $(this).parent('.d-flex');
                let input = row.find('.qtyCheckout');
                let currentValue = parseInt(input.val());
                let quantity = currentValue - 1;
                let edit_id = $(this).data('id');
                let id = $(this).data('product');

                if (quantity < 1) {
                    $.ajax({
                        url: 'carts.php',
                        type: 'POST',
                        data: {
                            delete_id: edit_id,
                            id: id
                        },
                        success: function(data) {
                            $('#cart_div-' + id).remove();
                            $('#cartDivCheckout-' + id).remove();
                            countCheckout()
                            updateTotalCheckout()
                        }
                    });

                } else {
                    input.val(quantity);
                    $("input.qty[data-id='" + edit_id + "' ]").val(quantity);

                    $.ajax({
                        url: 'carts.php',
                        type: 'POST',
                        data: {
                            quantity: quantity,
                            edit_id: edit_id,
                            id: id
                        },
                        success: function(data) {
                            countCheckout()
                            updateTotalCheckout()
                        }
                    });
                }
            });


            $(document).on('keyup', '.qtyCheckout', function() {
                let row = $(this).parent('.d-flex');
                let input = row.find('.qtyCheckout');
                var currentValue = parseInt(input.val());
                console.log("current=" + currentValue);
                quantity = input.val()
                console.log(" quantity=" + quantity);
                let oldQuantity = parseInt(input.data('old'))
                console.log(" old value=" + oldQuantity);
                let id = $(this).data('product');
                console.log(" id : " + id);

                edit_id = $(this).data('id');
                if (quantity <= 0) {
                    $.ajax({
                        url: 'carts.php',
                        type: 'POST',
                        data: {

                            delete_id: edit_id,
                            id: id
                        },
                        success: function(data) {
                            $('#cart_div-' + id).remove();
                            $('#cartDivCheckout-' + id).remove();
                            input.data('old', currentValue);
                            countCheckout()
                            updateTotalCheckout()
                        }
                    });
                } else {
                    $.ajax({
                        url: 'carts.php',
                        type: 'POST',
                        data: {
                            quantity: quantity,
                            edit_id: edit_id,
                            id: id
                        },
                        success: function(data) {
                            input.data('old', currentValue);
                            $(" input.qty[data-id='" + edit_id + "' ]").val(currentValue);
                            countCheckout()
                            updateTotalCheckout()
                        }
                    });
                }
            });

            $(document).on('click', '#deletedCheckout', function() {
                let row = $(this).closest('.cartDivCheckout');
                let quantity = parseInt(row.find('.qtyCheckout').val());
                let delete_id = $(this).data('id');
                console.log("delete_id:", delete_id);
                let id = $(this).data('product');
                console.log("product_id:", id);
                $.ajax({
                    url: 'carts.php',
                    type: 'POST',
                    data: {
                        delete_id: delete_id,
                        id: id
                    },
                    success: function(data) {
                        if (data === 'redirect') {
                            $('#cart_div-' + id).remove();
                            $('#cartDivCheckout-' + id).remove();
                            countCheckout();
                            updateTotalCheckout();
                            window.location.href = './index.php';
                        } else if (data === 'notRedirect') {
                            $('#cart_div-' + id).remove();
                            $('#cartDivCheckout-' + id).remove();
                            countCheckout();
                            updateTotalCheckout();
                        } else {
                            console.log("Error");
                        }
                    }
                });
            });

        });
    </script>
</body>

</html>