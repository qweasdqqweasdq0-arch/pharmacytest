<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Reservation; // تأكد من استيراد مودل الحجوزات
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    // عرض الإحصائيات في الداشبورد
    public function index()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'users_count' => User::count(),
                'pharmacies_count' => Pharmacy::count(),
                'pending_reservations' => Reservation::where('status', 'pending')->count(),
            ]
        ]);
    }

    // عرض صفحة إدارة المستخدمين
    public function users()
    {
        $users = User::with('pharmacy')->latest()->get();
        return Inertia::render('Admin/UsersManager', [
            'users' => $users
        ]);
    }

    // تفعيل أو تعطيل المستخدم
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active; 
        $user->save();

        return back()->with('message', 'تم تحديث حالة المستخدم بنجاح');
    }

    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // منع المدير من حذف نفسه بالخطأ
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي');
        }

        $user->delete();
        return back()->with('message', 'تم حذف المستخدم نهائياً');
    }
}