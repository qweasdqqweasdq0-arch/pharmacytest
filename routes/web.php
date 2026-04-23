<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. الصفحة الرئيسية (Welcome Page)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.users.toggle');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.delete');
});
// 2. المسارات المحمية (Dashboard & Profile)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // هذا المسار يعرض التقييمات والإحصائيات (دالة index)
    Route::get('/dashboard', [PharmacistController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. مسارات الصيدلي (Pharmacist)
Route::middleware(['auth'])->prefix('pharmacist')->group(function () {
    
    // إدارة الأدوية - تم التوجيه لدالة manageMedicines ليعمل الجدول
    Route::get('/medicines', [PharmacistController::class, 'manageMedicines'])->name('pharmacist.index'); 
    
    Route::post('/medicines/store', [PharmacistController::class, 'store'])->name('pharmacist.medicines.store');
    Route::put('/medicines/{id}', [PharmacistController::class, 'update'])->name('pharmacist.medicines.update');
    Route::delete('/medicines/{id}', [PharmacistController::class, 'destroy'])->name('pharmacist.medicines.destroy');
    
    // إدارة الحجوزات
    Route::get('/reservations', [PharmacistController::class, 'manageReservations'])->name('pharmacist.reservations.index');
    Route::patch('/reservations/{id}/status', [PharmacistController::class, 'updateReservationStatus'])->name('pharmacist.reservations.update');
});

// داخل مجموعة مسارات المريض (patient) في web.php

Route::middleware(['auth'])->prefix('patient')->group(function () {
    // رؤية الصيدليات
    Route::get('/pharmacies', [PatientController::class, 'index'])->name('patient.pharmacies.index');
    
    // رؤية معلومات صيدلية معينة وأدويتها
    Route::get('/pharmacies/{id}', [PatientController::class, 'showPharmacy'])->name('patient.pharmacies.show');

    // البحث (الذي فعلناه سابقاً)
    Route::get('/search', [PatientController::class, 'search'])->name('medicine.search');
    
    // الحجز والتقييم (تم إنشاؤها سابقاً)
    Route::post('/reserve', [PatientController::class, 'reserve'])->name('medicine.reserve'); 
    Route::get('/my-reservations', [PatientController::class, 'myReservations'])->name('patient.reservations');
    Route::post('/rate-pharmacy', [PatientController::class, 'storeRating'])->name('pharmacy.rate');
});
require __DIR__.'/auth.php';