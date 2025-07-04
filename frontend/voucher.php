<?php
class voucherCode
{
    public $discount_data;
    public $voucherCode;
    public $discountAmount;
    public $isvalid;
    public function voucher($voucherInput,$userId, $con)
    {
        $discount = $con->query("SELECT * FROM discounts WHERE code = '" . $voucherInput . "'");
        if ($this->discount_data = $discount->fetch_assoc()) {
            if ($this->discount_data['code'] == $voucherInput) {
                return $this->discountAmount = $this->discount_data['amount'];
            } else {
                return $this->isvalid = false;
            }
            if ($this->discount_data['code'] ) {

            }
        }
    }
}
