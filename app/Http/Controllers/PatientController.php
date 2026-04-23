<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Rating;
use App\Models\Pharmacy;
class PatientController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->input('query');

        $results = $term 
            ? Medicine::with('pharmacy')
                ->where('name', 'like', "%$term%")
                ->get()
            : [];

        return Inertia::render('Patient/Search', [
            'results' => $results,
            'filters' => $request->only(['query'])
        ]);
    }

    public function myReservations()
    {
        // جلب الحجوزات مع التأكد من وجود العلاقات في مودل Reservation
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['medicine', 'pharmacy'])
            ->latest()
            ->get();
    
        return Inertia::render('Patient/MyReservations', [
            'reservations' => $reservations
        ]);
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        if ($medicine->stock <= 0) {
            return back()->with('error', 'عذراً، هذا الدواء غير متوفر حالياً.');
        }

        Reservation::create([
            'user_id'     => Auth::id(),
            'medicine_id' => $medicine->id,
            'pharmacy_id' => $medicine->pharmacy_id,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'تم تقديم طلب الحجز بنجاح.');
    }
    public function storeRating(Request $request)
{
    $request->validate([
        'pharmacy_id' => 'required|exists:pharmacies,id',
        'stars' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500'
    ]);

    // تحديث التقييم إذا كان موجوداً أو إنشاء واحد جديد
    \App\Models\Rating::updateOrCreate(
        ['user_id' => auth()->id(), 'pharmacy_id' => $request->pharmacy_id],
        ['stars' => $request->stars, 'comment' => $request->comment]
    );

    return back()->with('success', 'شكراً لتقييمك!');
}
// PatientController.php

// 1. عرض قائمة بكل الصيدليات
public function index() {
    $pharmacies = Pharmacy::with(['ratings'])->get()->map(function($pharmacy) {
        return [
            'id' => $pharmacy->id,
            'name' => $pharmacy->name,
            'address' => $pharmacy->address,
            'phone' => $pharmacy->phone,
            'rating' => round($pharmacy->ratings()->avg('stars'), 1) ?: 0,
        ];
    });

    return Inertia::render('Patient/PharmaciesList', [
        'pharmacies' => $pharmacies
    ]);
}

// 2. عرض معلومات صيدلية محددة وأدويتها (رؤية معلومات الصيدلية + الدواء)
public function showPharmacy($id) {
    // جلب الصيدلية مع الأدوية الخاصة بها
    $pharmacy = Pharmacy::with(['medicines', 'ratings'])->findOrFail($id);
    
    return Inertia::render('Patient/PharmacyDetails', [
        'pharmacy' => [
            'id' => $pharmacy->id,
            'name' => $pharmacy->name,
            'address' => $pharmacy->address,
            'phone' => $pharmacy->phone,
            'rating' => round($pharmacy->ratings()->avg('stars'), 1) ?: 0,
            'medicines' => $pharmacy->medicines // تأكد أن المودل يحتوي على وصف وسعر
        ]
    ]);
}
}