<?php
namespace Modules\Coupon\Models;

use App\BaseModel;

class CouponBookings extends BaseModel
{
    protected $table = 'bravo_booking_coupons';
    protected $fillable = [
        'booking_id',
        'booking_status',
        'object_id',
        'object_model',
        'coupon_code',
        'without_service_fees',
        'coupon_amount',
        'coupon_data',
        'service_amount',
        'from',
        'to',
    ];
    protected $casts = [
        'coupon_data' => 'array',
    ];

    public function clean($coupon_id)
    {
        $query = $this->where("booking_id", $coupon_id);
        $query->get();
        if (!empty($query)) {
            $query->delete();
        }
    }
}
