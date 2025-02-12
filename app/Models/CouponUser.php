<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponUser extends Pivot {
    protected $table = 'coupon_user';

    protected $fillable = ['coupon_id', 'user_id'];
}

