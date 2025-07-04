<?php
include  '../connection.php';
session_start();
if (isset($_POST['customer_name'])) {
    $search = $_POST['customer_name'];

    $sql =  "SELECT * FROM users WHERE 
            first_name LIKE '%$search%' OR 
            last_name LIKE '%$search%'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p class='select-user' data-id='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    exit;
}

if (isset($_POST['products'])) {
    $search = $_POST['products'];

    $sql = "SELECT * FROM products WHERE
                title LIKE '%$search%'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($products = $result->fetch_assoc()) {
            echo "<p class='select-product' data-id='" . $products['id'] . "'>" . $products['title'] . "</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
    exit;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="backgroundColor">
    <?php include '../header.php'; ?>
    <div class=" vh-100">
        <section class="vh-100">
            <div class="container h-100">
                <form method="post" action="discountdata.php">
                    <input type="hidden" name="edit_id" value="<?= isset($id) ? $id : '' ?>">
                    <div class="row d-flex justify-content-center mb-3">
                        <div class="col-6 col-md-6 col-lg-6 col-xl-6 mt-5">
                            <div class="card shadow-2-strong" style="border-radius: 1rem;">
                                <div class="card-body p-3">
                                    <div class="form-group  pe-2">
                                        <label for="discount" class='lable_setting pb-2 lable fw-bold'>Discount Code</label>
                                        <input type="text" class="form-control input py-2" id=" " name="discount" placeholder="e.g. SPRINGSALE" value="<?= isset($discountName) ? $discountName : ''; ?>">
                                        <small class="text-secondary ">Customers Will enter this discount code at checkout.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6 col-lg-6 col-xl-6 mt-5 ">
                            <div class="card shadow-2-strong pb-4" style="border-radius: 1rem;">
                                <div class="card-body p-3 text-center">
                                    <label for="shipping" class='lable_setting pb-2 lable fw-bold'>Types</label>
                                    <div class="form-check text-start">
                                        <input class="form-check-input single-checkbox" type="checkbox" value="percentage" name="amountType" id="flexCheckDefault" <?= isset($amounttype) && $amounttype === 'percentage' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="flexCheckDefault">
                                            Percentage
                                        </label>
                                    </div>
                                    <div class="form-check pb-2">
                                        <input class="form-check-input single-checkbox" type="checkbox" value="fixed" name="amountType" id="flexCheckChecked" <?= isset($amounttype) && $amounttype === 'fixed' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="flexCheckChecked">
                                            Fixed Amount
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mb-3">
                        <div class="col-6 col-md-6 col-lg-6 col-xl-6 my-1">
                            <div class="card shadow-2-strong" style="border-radius: 1rem; height: 340px;">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold lable fs-6 m-0 pb-0">Value</h6>
                                    <div class="form-group  pe-2">
                                        <label for="value" class='lable_setting pb-2 lable '>Discount Value</label>
                                        <input type="text" class="form-control input py-2 m-0" id="" name="discountValue" value="<?= isset($discountValue) ? $discountValue : ''; ?>">
                                    </div>
                                    <label for="value" class='lable_setting pb-2 lable pt-5 text-dark'>APPLIES TO</label>
                                    <div class="form-check">
                                        <input class="form-check-input applyTo" type="checkbox" value="all Products" name="allProducts" id="apply" <?= isset($specificProduct) && $specificProduct === 'all Products' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6 " for="apply">
                                            All Products
                                        </label>
                                    </div>
                                    <?php
                                    $productname = '';
                                    if (!empty($selectedProductId)) {
                                        $result = $con->query("SELECT title FROM products WHERE id = " . $selectedProductId);
                                        while ($user = $result->fetch_assoc()) {
                                            $productname = $user['title'];
                                        }
                                    }

                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input applyTo" type="checkbox" value="specific Products" name="allProducts" id="apply1" <?= isset($specificProduct) && $specificProduct === 'specific Products' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="apply1">
                                            Specific Products
                                        </label>
                                        <div class="form-group pe-2" id="products">
                                            <div class="position-relative">
                                                <input type="text" class="form-control input py-2 m-0" id="products_search" name="products" value="<?= isset($productname) ? $productname : ''; ?>">
                                                <div id="product_name" class="dropdown-menu show w-100 shadow-sm mt-1" style="max-height: 200px; overflow-y: auto; display:none;"></div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="selected_product_id" id="selected_product_id" value="<?= isset($selectedProductId) ? $selectedProductId : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6 col-lg-6 col-xl-6 my-1">
                            <div class="card shadow-2-strong" style="border-radius: 1rem;">
                                <div class="card-body p-3 text-center minimum_amount" style="height: 338px;">
                                    <label for="shipping" class='lable_setting pb-2 lable fw-bold'>Minimum Requirements</label>
                                    <div class="form-check">
                                        <input class="form-check-input minimumReq" type="checkbox" value="none" name="minimum_amounts" id="minimum1" <?= isset($Minimum_requirements) && $Minimum_requirements === 'none' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6 " for="minimum1">
                                            None
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input minimumReq" type="checkbox" value="minimum purchase amount" name="minimum_amounts" id="minimum2" <?= isset($Minimum_requirements) && $Minimum_requirements === 'minimum purchase amount' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="minimum2">
                                            Minimum Purchase Amount ($)
                                        </label>
                                        <div class="form-group pe-2" id="minimum">
                                            <input type="text" class="form-control input py-2 m-0" id="purchaseAmount" name="purchaseAmount" value="<?= isset($Minimum_requirements) && $Minimum_requirements === 'minimum purchase amount' ? $quantityAmount : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input minimumReq" type="checkbox" name="minimum_amounts" value="minimum quantity of items" id="minimum3" <?= isset($Minimum_requirements) && $Minimum_requirements === 'minimum quantity of items' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="minimum3">
                                            Minimum Quantity Of Items
                                        </label>
                                        <div class="form-group pe-2" id="minimumQuantity">
                                            <input type="text" class="form-control input py-2 m-0" id="qantityAmount" name="quantityAmount" value="<?= isset($Minimum_requirements) && $Minimum_requirements === 'minimum quantity of items' ? $quantityAmount : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mb-3">
                        <div class="col-6 col-md-6 col-lg-6 col-xl-6 my-1">
                            <div class="card shadow-2-strong pb-5" style="border-radius: 1rem; height: 200px;">
                                <div class="card-body p-3 text-center pb-4">
                                    <label for="shipping" class='lable_setting pb-2 lable fw-bold'>Customer Eligibility</label>
                                    <div class="form-check text-start">
                                        <input class="form-check-input customerEligibility" type="checkbox" value="everyone" name="allCustomers" id="customer" <?= isset($specificCustomer) && $specificCustomer === 'everyone' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="customer">
                                            Everyone
                                        </label>
                                    </div>
                                    <?php
                                    $customerName = '';
                                    if (!empty($selectedCustomerId)) {
                                        $customers = $con->query("SELECT * FROM users WHERE id = '" . $selectedCustomerId . "'");
                                        while ($user = $customers->fetch_assoc()) {
                                            $customerName = $user['first_name'] . ' ' . $user['last_name'];
                                        }
                                    }
                                    ?>
                                    <div class="form-check pb-4">
                                        <input class="form-check-input customerEligibility" type="checkbox" value="spacific customers" name="allCustomers" id="customer1" <?= isset($specificCustomer) && $specificCustomer === 'spacific customers' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="customer1">
                                            Spacific customers
                                        </label>
                                        <div class="form-group pe-2" id="customerList">
                                            <div class="position-relative">
                                                <input type="text" class="form-control input py-2 m-0" id="customers" name="customer_name" value="<?= isset($customerName) ? $customerName : '';  ?>">
                                                <div id="user_name" class="dropdown-menu show w-100 shadow-sm mt-1" style="max-height: 200px; overflow-y: auto; display:none;"></div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="selected_customer_name" id="selected_customer_name" value="<?= isset($selectedCustomerId) ? $selectedCustomerId : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6 col-lg-6 col-xl-6 my-1">
                            <div class="card shadow-2-strong pb-5" style="border-radius: 1rem; height: 200px;">
                                <div class="card-body p-3 text-center">
                                    <label for="shipping" class='lable_setting pb-2 lable fw-bold'>Usage Limit</label>
                                    <div class="form-check">
                                        <input class="form-check-input usageLimit" type="checkbox" value="limit number of times" name="limitnumbers" id="usage1" <?= isset($discount_apply_type) && $discount_apply_type === 'limit number of times' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6 " for="usage1">
                                            Limit Number of times this discount can be used in total
                                        </label>
                                        <div class="form-group pe-2" id="usage">
                                            <input type="text" class="form-control input py-2 m-0" id="limitDiscount" name="limitdiscount" value="<?= isset($limitdiscount) ? $limitdiscount : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input usageLimit" type="checkbox" value="limit one use per customer" name="limitnumbers" id="usage2" <?= isset($discount_apply_type) && $discount_apply_type === 'limit one use per customer' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="usage2">
                                            Limit to one use per customer
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input usageLimit" type="checkbox" value="new customers only" name="limitnumbers" id="usage3" <?= isset($discount_apply_type) && $discount_apply_type === 'new customers only' ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="usage3">
                                            New customers only
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-3 text-center" style="height: 200px;">
                            <div class="row d-flex justify-content-center">
                                <div class="col-6 col-md-6 col-lg-6 col-xl-6 my-1">
                                    <label for="date" class='lable_setting lable fw-bold pb-3'>Active Dates</label>
                                    <div class="form-group">
                                        <label for="date" class='lable_setting lable p-0'>START DATE</label>
                                        <input type="date" class="form-control input py-2 m-0" id=" " name="startDate" value="<?= isset($startDate) ? $startDate : date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-check pe-2">
                                        <input class="form-check-input" type="checkbox" name="" id="enddate" value="<?= isset($endDate) ? $endDate : ''; ?>" <?= isset($endDate) ? 'checked' : '' ?>>
                                        <label class="lable p-0 ms-2 fs-6" for="enddate">
                                            Set end date
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" name="enabled" <?= isset($enabled) && $enabled == 1 ? 'checked' : '' ?>>
                                        <label class="lable p-0  fs-5" for="flexSwitchCheckChecked">enabled</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 col-lg-6 col-xl-6 my-5">
                                    <div class="form-group pt-1" id="enddate1">
                                        <label for="date" class='lable_setting lable p-0'>END DATE</label>
                                        <input type="date" class="form-control input py-2 m-0" id="endDate" name="endDate" value="<?= isset($endDate) ? $endDate : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="acc fs-4 my-2 py-1" type="submit" name="create_discount" id="submit_discount" style="width: 20%;">
                        <?php
                        if (isset($_GET['id'])) {
                            echo "Edit Discount";
                        } else {
                            echo "Create Discount";
                        }
                        ?>
                    </button>
                </form>
            </div>
        </section>
    </div>
</body>
<script>
    $(document).ready(function() {
        $("#minimum").hide();
        $("#minimumQuantity").hide();
        $("#customerList").hide();
        $("#products").hide();
        $("#usage").hide();
        $("#enddate1").hide();

        $(document).on('click', '.select-product', function() {
            var name = $(this).data('id');
            var title = $(this).text();
            $('#selected_product_id').val(name);
            $('#products_search').val(title);
            $('#product_name').html('');
        });
        $(document).on('click', '.select-user', function() {
            var name = $(this).data('id');
            var title = $(this).text();
            $('#selected_customer_name').val(name);
            $('#customers').val(title);
            $('#user_name').html('');
        });
        if ($('#purchaseAmount').val() !== "" && $('#purchaseAmount').val() != "0") {
            $('#minimum2').prop('checked', true);
            $('#minimum').show();
        }
        if ($('#qantityAmount').val() !== "" && $('#qantityAmount').val() != "0") {
            $('#minimum3').prop('checked', true);
            $('#minimumQuantity').show();
        }

        if ($('#customers').val() !== "" && $('#customers').val() != "0") {
            $('#customer1').prop('checked', true);
            $('#customerList').show();
        }

        if ($('#products_search').val() != "" && $('#products_search').val() != "0") {
            $('#apply1').prop('checked', true);
            $('#products').show();
        }

        if ($('#limitDiscount').val() != "" && $('#limitDiscount').val() != "0") {
            $('#usage1').prop('checked', true);
            $('#usage').show();
        }

        if ($('#endDate').val() != "") {
            $('#enddate').prop('checked', true);
            $('#enddate1').show();
        }


        $('.minimumReq').on('change', function() {
            $('.minimumReq').not(this).prop('checked', false);
            $('#minimum').hide();
            if ($('#minimum2').prop('checked')) {
                console.log("show");
                $('#minimum').show();
            } else {
                console.log("hide");
                $("#minimum").hide();
            }

            if ($('#minimum3').prop('checked')) {
                console.log("show");
                $('#minimumQuantity').show();
            } else {
                console.log("hide");
                $("#minimumQuantity").hide();
            }
        });


        $(document).on('change', '.customerEligibility', function() {
            $('.customerEligibility').not(this).prop('checked', false);
            $('#customerList').hide();
            if ($('#customer1').prop('checked')) {
                console.log("show");
                $('#customerList').show();
            } else {
                console.log("hide");
                $("#customerList").hide();
                $('#customers').val(null)
                $('#selected_customer_name').val(null)
            }
        });

        $(document).on('change', '.applyTo', function() {
            $('.applyTo').not(this).prop('checked', false);
            $('#products').hide();
            if ($('#apply1').prop('checked')) {
                console.log("show");
                $('#products').show();
            } else {
                console.log("hide");
                $("#products").hide();
                $('#products_search').val(null)
                $('#selected_product_id').val(null)
            }
        });

        $(document).on('change', '.usageLimit', function() {
            $('.usageLimit').not(this).prop('checked', false);
            $('#usage').hide();
            if ($('#usage1').prop('checked')) {
                console.log("show");
                $('#usage').show();
            } else {
                console.log("hide");
                $("#usage").hide();
            }
        });

        $(document).on('click', '#usage1', function() {
            if (this.checked) {
                console.log("show");
                $("#usage").show();
            } else {
                console.log("hide");
                $("#usage").hide();
            }
        });

        $(document).on('click', '#enddate', function() {
            if (this.checked) {
                console.log("show");
                $("#enddate1").show();
            } else {
                console.log("hide");
                $("#enddate1").hide();
                $("#endDate").val(null)

            }
        });

        $(document).on('keyup', '#customers', function() {
            var query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: "discount.php",
                    type: "POST",
                    data: {
                        customer_name: query
                    },
                    success: function(data) {
                        $("#user_name").html(data).show();

                    },
                });
            } else {
                $("#user_name").html("");
            }
        });

        $(document).on('keyup', '#products_search', function() {
            var query = $(this).val();

            if (query.length > 1) {
                $.ajax({
                    url: "discount.php",
                    type: "POST",
                    data: {
                        products: query
                    },
                    success: function(data) {
                        $("#product_name").html(data).show();
                    },
                });
            } else {
                $("#product_name").html("");
            }
        });
        $('.single-checkbox').on('change', function() {
            $('.single-checkbox').not(this).prop('checked', false);

        });
    })
</script>

</html>