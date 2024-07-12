<?php

namespace Models;


class PaymentMethod extends Model
{

    protected static $table = 'payment_methods';
    public $id;
    public $type;
    public $detail;
    public $user_id;
    public $updated_at;
    public $created_at;

    //get user
    public function user()
    {
        return User::find($this->user_id);
    }
}
