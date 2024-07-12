<?php

namespace Models;

use PDO;





class Order extends Model
{
    public $id;
    public $product_id;
    public $quantity;
    public $product_price;
    public $total;
    public $status;
    public $customer_note = 'Đặt mua';
    public $seller_note = 'Đang chờ xử lý';
    public $customer_address_id = 0;
    public $payment_method_id = 0;
    public $user_id;
    public $updated_at;
    public $created_at;

    //get product
    public function product()
    {
        return Product::find($this->product_id);
    }

    //get user
    public function user()
    {
        return User::find($this->user_id);
    }

    //get customer address
    public function customerAddress()
    {
        return CustomerAddress::find($this->customer_address_id);
    }

    //get payment method
    public function paymentMethod()
    {
        return PaymentMethod::find($this->payment_method_id);
    }
}
