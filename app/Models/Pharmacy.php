<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'user_id'];

    // هذا السطر يضمن إرسال المتوسط إلى واجهة Vue تلقائياً
    protected $appends = ['average_rating']; 

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }
    
    public function getAverageRatingAttribute() {
        // تأكد من استخدام stars (اسم العمود في جدول التقييمات)
        return round($this->ratings()->avg('stars'), 1) ?: 0;
    }
}