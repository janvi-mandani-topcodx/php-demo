<?php
class voucherCode
{
    public function voucher($voucherInput, $subtotal, $cartCount, $dsicountUserId, $con)
    {
        $discount = $con->query("SELECT * FROM discounts WHERE code = '" . $voucherInput . "'");

        if ($discount_data = $discount->fetch_assoc()) {
            $minAmount = $discount_data['minimum_amount'];
            $voucherUserId = $discount_data['user_id'];
            $voucherProductId = $discount_data['product_id'];
            $enabled = $discount_data['enabled'];
            $startDate = $discount_data['start_date'];
            $endDate = $discount_data['end_date'];
            $currentDate = date("Y-m-d");
            if ($discount_data['code'] != $voucherInput) {
                echo "Invalid code";
                return false;
            }
            if($enabled == 0){
                echo "Invalid enabled";
                return false;
            }
            if (!empty($startDate)) {
                if ($currentDate < $startDate) {
                    echo "Voucher not yet active";
                    return false;
                }

                if ($endDate != '0000-00-00') {
                    if ($endDate < $startDate) {
                        echo "Invalid date range: end date is before start date";
                        return false;
                    }

                    if ($currentDate > $endDate) {
                        echo "Voucher expired";
                        return false;
                    }
                }
            }

            if (!empty($voucherUserId) &&  $dsicountUserId != $voucherUserId) {
                echo "Invalid user";
                return false;
            }
            $cart = $con->query("SELECT * from carts where user_id = '".$_SESSION['id']."' and product_id = '".$voucherProductId."'");
            $fetch  = $cart->fetch_assoc();
            if (!empty($voucherProductId) && $fetch['product_id'] != $voucherProductId) {
                echo "Invalid product";
                return false;
            }
            $order = $con->query("SELECT COUNT(user_id) FROM orders where user_id = " . $_SESSION['id']);
            while($orderId  = $order->fetch_assoc()){
                if($discount_data['discount_apply_type'] == 'new customers only'){
                    if($orderId['COUNT(user_id)']==0){
                        echo "Invalid count of new customers";
                        return false;
                    }
                }

                if($discount_data['discount_apply_type'] == 'limit one use per customer'){
                    if($orderId['COUNT(user_id)']<1){
                        echo "Invalid count of limit per customer";
                        return false;
                    }
                }

                if($discount_data['discount_apply_type'] == 'limit number of times' && !empty($discount_data['discount_type_number'])){
                    if($orderId['COUNT(user_id)']>= $discount_data['discount_type_number']){
                        echo "Invalid count of limit time customer ";
                        return false;
                    }
                }
            }

            if ($subtotal >= $minAmount && $discount_data['Minimum_requirements'] =='minimum purchase amount') {
                return [
                    'amount' => $discount_data['amount'],
                    'type' => $discount_data['type'],
                    'code' => $discount_data['code'],
                ];
            }

            if ($cartCount >= $minAmount && $discount_data['Minimum_requirements'] =='minimum quantity of items') {
                return [
                    'amount' => $discount_data['amount'],
                    'type' => $discount_data['type'],
                    'code' => $discount_data['code'],
                ];
            }
            if($discount_data['Minimum_requirements'] =='none'){
                return [
                    'amount' => $discount_data['amount'],
                    'type' => $discount_data['type'],
                    'code' => $discount_data['code'],
                ];
            }

            echo "Conditions not metch";
            return false;
        }

        echo "Voucher not found";
        return false;
    }
}