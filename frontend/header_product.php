<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit();
}
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



?>
<div class=" header navbar-light bg-light">
    <nav class="navbar navbar-expand-lg  d-flex justify-content-between px-5">
        <div>
            <a class="navbar-brand" href="../frontend/" class="logo">LOGO</a>

        </div>
        <div>
            <div class="btn-group">
                <button class="dropdown-toggle bg-light  border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle icon" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>
                </button>
                <ul class="dropdown-menu">
                    <?php if (isset($_SESSION['email'])) : ?>
                        <p>
                            <b>Welcome</b><br>
                            <strong>
                                <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?><br>
                                <?php echo $_SESSION['email']; ?>
                            </strong>
                        </p>
                        <form method="POST" style="display:inline;">
                            <button type="submit" name="logout" class="dropdown-item">Logout</button>
                        </form>
                    <?php endif ?>
                </ul>

            </div>
            <button type='button' class="border-0 bg-light" data-bs-toggle="offcanvas" data-bs-target="#demo">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
                <div>
                    <input type="text" class='count' name='countQuantity' value="<?= isset($cartCount) ?  $cartCount :  0; ?>" readonly id='count_increment'>
                </div>
            </button>
        </div>
    </nav>
</div>
<div class="offcanvas offcanvas-end text-bg-dark" id="demo">
    <div class="offcanvas-header">
        <h1 class="offcanvas-title">Shopping Cart</h1>
        <button type="button" class="btn-close bg-light" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div id="append_data" class="h-100">
            <?php $totalPrice = 0;
            echo "<div id='append_div' class='cart_product '>";
            while ($productCart = $cart->fetch_assoc()) {


                $total = $productCart['price'] * $productCart['quantity'];
                $totalPrice += $total;

                if (isset($_SESSION['id'])) {
                    $product_id = $productCart['product_id'];
                    echo "<div class='d-flex justify-content-between align-items-center my-3 cartDiv' id='cart_div-" . $product_id . "' data-id='" . $productCart['id'] . "'>
                        <img src='" . $productCart['image'] . "' width='50px'>
                        <div>
                            <span class='text-white ps-3 title'>" . $productCart['title'] . "</span>
                            <div class='d-flex ps-3'>
                                <span>$</span>
                                <p class='text-white  price' id='price_cart'>" . $productCart['price'] . "</p> 
                            </div>
                        </div>
                        <div class='text-bg-light mx-2 mt-1 plus dicrement'  data-id='" . $productCart['id'] . "' data-product='" . $product_id . "'>-</div>
                        <input type='text' class='qty' value='" . $productCart['quantity'] . "' data-id='" . $productCart['id'] . "' data-old='" . $productCart['quantity'] . "' data-product='" . $product_id . "' id='header_qty'>
                        <div class='text-bg-light mx-2 mt-1 plus increment' data-id='" . $productCart['id'] . "' data-product='" . $product_id . "'>+</div>
                        <button class='delete' data-id='" . $productCart['id'] . "' id='deleted'  data-product='" . $product_id . "'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                            </svg>
                        </button>
                    </div>";
                } else {
                    echo "";
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
                            <div class-'d-flex'> 
                                <span>$</span>
                                <span id='sub_total' class='me-2'>" .  $totalPrice . "</span>
                            </div>
                        </div>
                        <hr class='hr'><div class='d-flex justify-content-between'>
                            <h5>Total</h5>
                            <div class-'d-flex'> 
                                <span>$</span>
                                <span id='total_price' class='me-2'>" .  $totalPrice . "</span>
                            </div>
                        </div>
                        <div class='mx-3 mb-3'> 
                            <a href='checkout.php'>
                                <button  type='button' data-mdb-button-init data-mdb-ripple-init class='btn btn-secondary btn-block btn-lg w-100'>Checkout</button>
                            </a>
                        </div>
                    </div>
                </div>";
            ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function count() {
            let totalCount = 0;
            $('.qty').each(function() {
                let qty = parseInt($(this).val());
                totalCount += qty;
            });
            $('#count_increment').val(totalCount);
        }

        function updateTotal() {
            let totalPrice = 0;
            $('.qty').each(function() {
                let quantity = parseInt($(this).val());
                console.log(quantity);
                let price = $(this).closest('.cartDiv').find('.price').text();
                console.log(price);
                total = quantity * price;
                totalPrice += total;
            });
            $('#subTotalCheckout').text(totalPrice);
            $('#totalPriceCheckout').text(totalPrice);
            $('#total_price').text(totalPrice);
            $('#sub_total').text(totalPrice);
        }
        $(document).on('click', '.btnAddAction', function() {

            const image = $(this).data('image');
            console.log(image);
            const title = $(this).data('title');
            console.log(title);
            const price = $(this).data('price');
            const id = $(this).data('id');
            console.log("id = " + id);

            const cartId = $('#cart_div-' + id).data('id');
            console.log("cart Id: " + cartId);

            $.ajax({
                url: 'carts.php',
                type: 'POST',
                data: {
                    image: image,
                    title: title,
                    price: price,
                    quantity: 1,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $('#append_div').find('[data-id="' + cartId + '"]').closest('#cart_div-' + id).remove();
                    $('#append_div').append(data);

                    let currentCount = parseInt($('#count_increment').val());
                    $('#count_increment').val(currentCount + 1);
                    updateTotal();
                }
            });
        });
        $(document).on('click', '.increment', function() {
            let row = $(this).parent('.d-flex');
            let input = row.find('.qty');
            var currentValue = parseInt(input.val());
            input.val(currentValue + 1);
            quantity = input.val();
            edit_id = $(this).data('id');
            console.log("edit_id = " + edit_id);
            $("input.qtyCheckout[data-id='" + edit_id + "']").val(quantity);
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
                    updateTotal();
                    count()
                }
            });
        });
        $(document).on('click', '.dicrement', function() {
            let row = $(this).parent('.d-flex');
            let input = row.find('.qty');
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
                        $('#cartDivCheckout-' + id).remove()
                        count()
                        updateTotal();
                    }
                });

            } else {
                input.val(quantity);
                $("input.qtyCheckout[data-id='" + edit_id + "']").val(quantity);

                $.ajax({
                    url: 'carts.php',
                    type: 'POST',
                    data: {
                        quantity: quantity,
                        edit_id: edit_id,
                        id: id
                    },
                    success: function(data) {
                        count()

                        updateTotal();
                    }
                });
            }
        });



        $(document).on('keyup', '.qty', function() {
            let row = $(this).parent('.d-flex');
            let input = row.find('.qty');
            var currentValue = parseInt(input.val());
            console.log("current  = " + currentValue);
            quantity = input.val()
            console.log("quantity = " + quantity);
            let oldQuantity = parseInt(input.data('old'))
            console.log("old value = " + oldQuantity);
            let id = $(this).data('product');
            console.log("id : " + id);

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
                        row.remove();
                        $('#cartDivCheckout-' + id).remove();
                        input.data('old', currentValue);
                        count()
                        updateTotal();
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
                        $("input.qtyCheckout[data-id='" + edit_id + "']").val(currentValue);
                        updateTotal();
                        count()
                    }

                });
            }
        });

        $(document).on('click', '#deleted', function() {
            let row = $(this).parent('.cartDiv');
            console.log(row);
            let quantity = parseInt(row.find('.qty').val());
            delete_id = $(this).data('id');
            console.log(delete_id);
            let id = $(this).data('product');
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
                        count()
                        updateTotal();
                        window.location.href = './index.php';
                    } else if (data === 'notRedirect') {
                        $('#cart_div-' + id).remove();
                        $('#cartDivCheckout-' + id).remove();
                        count()
                        updateTotal();
                    } else {
                        console.log("Error");
                    }
                }
            });
        });
    });
</script>