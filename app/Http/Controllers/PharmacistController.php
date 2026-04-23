<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PharmacistController extends Controller
{
    // 1. دالة لوحة التحكم - تعرض التقييمات فقط
    public function index()
    {
        $user = Auth::user();
        $pharmacy = Pharmacy::where('user_id', $user->id)->first();
        
        $ratingStats = [
            'average' => $pharmacy ? round($pharmacy->ratings()->avg('stars'), 1) : 0,
            'count' => $pharmacy ? $pharmacy->ratings()->count() : 0,
        ];

        return Inertia::render('Dashboard', [
            'ratingStats' => $ratingStats
        ]);
    }

    // 2. الدالة التي كان ينقصك تغيير اسمها - تعرض جدول الأدوية
    public function manageMedicines()
    {
        $user = Auth::user();
        $pharmacy = Pharmacy::where('user_id', $user->id)->with('medicines')->first();
        
        return Inertia::render('Pharmacist/ManageMedicines', [
            'medicines' => $pharmacy ? $pharmacy->medicines : []
        ]);
    }

    // 3. دالة إضافة دواء جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $pharmacy = Pharmacy::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => 'صيدلية ' . $user->name, 'address' => 'عنوان افتراضي']
        );

        Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        return back()->with('success', 'تم إضافة الدواء بنجاح');
    }

    // 4. دالة تحديث دواء
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->all());

        return back();
    }

    // 5. دالة حذف دواء
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return back();
    }

    // 6. إدارة الحجوزات
    public function manageReservations()
    {
        $user = Auth::user();
        $pharmacy = Pharmacy::where('user_id', $user->id)->first();

        $reservations = $pharmacy 
            ? Reservation::where('pharmacy_id', $pharmacy->id)
                ->with(['user', 'medicine'])
                ->latest()
                ->get()
            : [];
        
        return Inertia::render('Pharmacist/Reservations', [
            'reservations' => $reservations
        ]);
    }

    // 7. تحديث حالة الحجز
    public function updateReservationStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $reservation = Reservation::findOrFail($id);
        
        $reservation->update(['status' => $request->status]);
    
        if($request->status === 'approved') {
            $reservation->medicine()->decrement('stock');
        }
    
        return back();
    }
}