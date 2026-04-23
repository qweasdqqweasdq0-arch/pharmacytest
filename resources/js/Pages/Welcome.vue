 <script setup>
import { Head, Link } from '@inertiajs/vue3';

// تعريف الخصائص القادمة من لارافيل
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
    },
    phpVersion: {
        type: String,
    },
});
</script>

<template>
    <Head title="مرحباً بكم في نظام الصيدلية" />

    <div class="min-h-screen bg-gray-50 text-gray-900 font-sans" dir="rtl">
        <!-- الشريط العلوي -->
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center px-8 lg:px-16">
            <div class="flex items-center gap-2">
                <div class="bg-emerald-600 p-2 rounded-lg text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                </div>
                <span class="text-xl font-bold text-emerald-800">صيدليتي الذكية</span>
            </div>
            
            <div v-if="canLogin" class="flex gap-4">
                <!-- هذا السطر تم تصحيحه باستخدام علامة الاستفهام لمنع خطأ undefined -->
                <Link v-if="$page.props.auth?.user" :href="route('dashboard')" class="text-emerald-600 font-medium px-4 py-2 hover:bg-emerald-50 rounded-lg">لوحة التحكم</Link>
                
                <template v-else>
                    <Link :href="route('login')" class="px-4 py-2 text-gray-600 hover:text-emerald-600 transition">تسجيل الدخول</Link>
                    <Link v-if="canRegister" :href="route('register')" class="px-5 py-2 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 shadow-md transition">ابدأ الآن</Link>
                </template>
            </div>
        </nav>

        <!-- القسم الرئيسي -->
        <header class="bg-white pt-16 pb-24">
            <div class="container mx-auto px-6 flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2 text-right">
                    <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        رعايتكم الصحية تبدأ من <span class="text-emerald-600">هنا</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        نظام متكامل يربط المرضى بالصيدليات. ابحث عن دوائك، احجز موعدك، وتابع حالتك الصحية بكل سهولة وأمان من مكانك.
                    </p>
                    <div class="flex gap-4">
                        <Link :href="route('register')" class="px-8 py-4 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg">سجل الآن</Link>
                        <button class="px-8 py-4 bg-white border-2 border-emerald-600 text-emerald-600 rounded-xl font-bold hover:bg-emerald-50 transition">تعرف علينا</button>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <img src="https://img.freepik.com/free-vector/pharmacist-concept-illustration_114360-2646.jpg" alt="Pharmacy Hero" class="rounded-2xl w-full">
                </div>
            </div>
        </header>

        <!-- الخدمات (بناءً على مخطط الـ Use Case) -->
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold mb-12">خدمات المريض والصيدلي</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition text-right border-t-4 border-emerald-500">
 <h3 class="text-xl font-bold mb-2">البحث عن دواء</h3>
                        <p class="text-gray-500">إمكانية البحث السريع عن توفر الأدوية واختبارها ورؤية المعلومات الدقيقة.</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition text-right border-t-4 border-blue-500">
                        <h3 class="text-xl font-bold mb-2">إدارة المواعيد</h3>
                        <p class="text-gray-500">للمريض: حجز مواعيد الصيدلية. للصيدلي: إدارة وتنظيم هذه المواعيد بسهولة.</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition text-right border-t-4 border-purple-500">
                        <h3 class="text-xl font-bold mb-2">معلومات الصيدليات</h3>
                        <p class="text-gray-500">رؤية الصيدليات القريبة منك، تقييمها، والاطلاع على أحدث المعلومات المحدثة.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-10 text-center text-gray-400 text-sm border-t">
            نظام صيدليتي الذكي © 2026 - يعمل بواسطة Laravel & Vue
        </footer>
    </div>
</template>