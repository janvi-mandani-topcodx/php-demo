<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_discount'])) {

    $discountName = $_POST['discount'];
    $discountValue = $_POST['discountValue'];
    $amounttype = $_POST['amountType'];
    $Minimum_requirements = $_POST['minimum_amounts'];
    $quantityAmount = $_POST['quantityAmount'];
    $discount_apply_type = $_POST['limitnumbers'];
    $limitdiscount = $_POST['limitdiscount'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $enabled = isset($_POST['enabled']) ? 1 : 0;
    $specificProduct = $_POST['allProducts'];
    $specificCustomer = $_POST['allCustomers'];

    $selectedCustomerId = isset($_POST['selected_customer_name']) && $_POST['selected_customer_name'] != '' ? $_POST['selected_customer_name'] : NULL;
    $selectedProductId = isset($_POST['selected_product_id']) && $_POST['selected_product_id'] != ''  ? $_POST['selected_product_id'] :  NULL;


    if ($Minimum_requirements === 'minimum purchase amount') {
        $quantityAmount = $_POST['purchaseAmount'];
    } elseif ($Minimum_requirements === 'minimum quantity of items') {
        $quantityAmount = $_POST['quantityAmount'];
    } else {
        $quantityAmount = NULL;
    }

    if ($discount_apply_type === 'limit number of times') {
        $limitdiscount = $_POST['limitdiscount'];
    } else {
        $limitdiscount = NULL;
    }
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        if ($specificProduct == 'all Products') {
            $selectedProductId = NULL;
        }
        if ($selectedCustomerId == 'everyone') {
            $selectedCustomerId = NULL;
        }
        $sql = $con->query("UPDATE discounts SET  code='$discountName', amount='$discountValue', type='$amounttype', Minimum_requirements = '$Minimum_requirements', minimum_amount='$quantityAmount' , specific_customer='$specificCustomer', user_id='$selectedCustomerId', specific_product='$specificProduct', product_id='$selectedProductId', discount_apply_type='$discount_apply_type' ,discount_type_number='$limitdiscount', start_date='$startDate', end_date='$endDate' , enabled='$enabled'
            WHERE id='$editId'");
    } else {
        $discount = $con->query("INSERT INTO discounts(code, amount, type, Minimum_requirements, minimum_amount, specific_customer ,
        user_id , specific_product, product_id , discount_apply_type, discount_type_number , start_date , end_date, enabled) 
                        VALUES ('$discountName', '$discountValue', '$amounttype', '$Minimum_requirements', '$quantityAmount', '$specificCustomer' , '$selectedCustomerId' , '$specificProduct' ,'$selectedProductId', '$discount_apply_type', '$limitdiscount','$startDate' , '$endDate' , '$enabled')");
    }
}

$result = $con->query("SELECT * FROM discounts");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All discount Data</title>
    <?php include '../bootstrap.php'; ?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>

<body>


    <?php
    include '../header.php';
    ?>
    <div class="width-90">
        <h2>Discounts</h2>

        <div class="d-flex justify-content-between">
            <!-- <input type="text" id="search" placeholder="search...." class="searchButton"> -->
            <a class="btn btn-outline-info" href="discount.php" role="button">Create</a>
        </div>

        <table id="result" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Discount Code</th>
                    <th>Type</th>
                    <th>Discount Value</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Enabled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($discount = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $discount['id'] . "</td>";
                    echo "<td>" . $discount['code'] . "</td>";
                    echo "<td>" . $discount['type'] . "</td>";
                    echo "<td>" . $discount['amount'] . "</td>";
                    echo "<td>" . $discount['start_date'] . "</td>";
                    echo "<td>" . $discount['end_date'] . "</td>";
                    echo "<td>" . ($discount['enabled'] ? 'Yes' : 'No')  . "</td>";
                    echo "<td>
                                    <form action='deletediscount.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='delete_id' value='" . $discount['id'] . "'>
                                        <button type='submit ' class='btn btn-info' >Delete</button>
                                    </form>
                                    <button class='btn btn-info'><a href='editdiscount.php?id=" . $discount['id'] . "' class='text-dark edit'>Edit</a></button>

                                </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>



    </div>
</body>

</html>