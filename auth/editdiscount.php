<?php
include '../connection.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $con->query("SELECT * FROM discounts WHERE id = $id");
    if ($result->num_rows == 0) {
        header("Location: discountdata.php");
        exit;
    }
    $discount = $result->fetch_assoc();
    $id = $discount['id'];
    $discountName = $discount['code'];
    $discountValue = $discount['amount'];
    $amounttype = $discount['type'];
    $Minimum_requirements =  $discount['Minimum_requirements'];
    $quantityAmount =  $discount['minimum_amount'];
    $selectedCustomerId = $discount['user_id'];
    $specificCustomer = $discount['specific_customer'];
    $specificProduct = $discount['specific_product'];
    $selectedProductId = $discount['product_id'];
    $discount_apply_type = $discount['discount_apply_type'];
    $limitdiscount = $discount['discount_type_number'];
    $startDate = $discount['start_date'];
    $endDate = $discount['end_date'];
    $enabled = $discount['enabled'];

    if (!empty($purchaseAmount)) {
        $check = 'checked';
    }
}
include 'discount.php';
