<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // تحديد الحقول التي يسمح بتعبئتها
    protected $fillable = [
        'user_id',
        'medicine_id',
        'status',
        'pharmacy_id'
    ];
    protected $casts = [
        'status' => 'string',
    ];
    /**
     * علاقة الحجز بالمريض (المستخدم)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة الحجز بالدواء
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    public function pharmacy(){
        return $this->belongsTo(Pharmacy::class);
    }
}