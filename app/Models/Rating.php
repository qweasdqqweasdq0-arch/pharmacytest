<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // هذه الأسطر هي الحل: تحديد الأعمدة المسموح بتعبئتها
    protected $fillable = [
        'user_id',
        'pharmacy_id',
        'stars',
        'comment'
    ];

    // علاقة التقييم بالصيدلية
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    // علاقة التقييم بالمستخدم (المريض)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}