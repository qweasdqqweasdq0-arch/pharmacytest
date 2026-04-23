<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// استقبال البيانات من الكنترولر
const props = defineProps({
    pharmacy: Object,
});

// فورم الحجز
const form = useForm({
    medicine_id: null,
});

// دالة تأكيد الحجز
const reserveMedicine = (medicine) => {
    if (confirm(`هل تريد بالتأكيد طلب حجز دواء: ${medicine.name}؟`)) {
        form.medicine_id = medicine.id;
        form.post(route('medicine.reserve'), {
            preserveScroll: true,
            onSuccess: () => alert('تم تقديم طلب الحجز بنجاح. يمكنك متابعة حالة الطلب من صفحة حجوزاتي.'),
        });
    }
};
</script>

<template>
    <Head :title="`صيدلية ${pharmacy.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-white leading-tight">تفاصيل الصيدلية والأدوية</h2>
                <Link :href="route('patient.pharmacies.index')" class="text-white bg-white/20 px-4 py-2 rounded-lg hover:bg-white/30 transition">
                    ← العودة للقائمة
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 transform hover:shadow-md transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center text-4xl shadow-inner border border-blue-200">
                                🏥
                            </div>
                            <div>
                                <h1 class="text-3xl font-extrabold text-blue-950">صيدلية {{ pharmacy.name }}</h1>
                                <div class="flex flex-col sm:flex-row sm:gap-6 text-gray-600 mt-2">
                                    <p class="flex items-center gap-2">
                                        <span>📍</span> {{ pharmacy.address }}
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <span>📞</span> {{ pharmacy.phone || 'لا يوجد رقم هاتف' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 text-white p-6 rounded-2xl shadow-lg text-center min-w-[150px]">
                            <p class="text-sm opacity-90 font-medium">تقييم الصيدلية</p>
                            <h3 class="text-5xl font-black mt-1">{{ pharmacy.rating || '0.0' }}</h3>
                            <span class="text-lg">⭐⭐⭐</span>
                        </div>
                    </div>
                </div>

                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-3 h-8 bg-green-500 rounded-full"></span>
                        <h4 class="text-xl font-semibold text-gray-900">الأدوية المتاحة للحجز الحين:</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="medicine in pharmacy.medicines" :key="medicine.id" 
                             class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                            
                            <div>
                                <div class="flex justify-between items-start gap-2">
                                    <h5 class="text-xl font-bold text-gray-950 group-hover:text-green-900 transition-colors">{{ medicine.name }}</h5>
                                    <span class="text-2xl">💊</span>
                                </div>
                                <p class="text-gray-600 mt-2 text-sm leading-relaxed">{{ medicine.description || 'لا يوجد وصف متاح لهذا الدواء.' }}</p>
                            </div>

                            <div class="mt-6 pt-5 border-t border-gray-100 space-y-4">
                                <div class="flex justify-between items-end bg-gray-50 p-4 rounded-xl">
                                    <div>
                                        <span class="text-xs text-gray-500 block">السعر</span>
                                        <span class="font-black text-2xl text-green-600">{{ medicine.price }} <span class="text-sm font-normal text-gray-500">ر.س</span></span>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-xs text-gray-500 block">المتوفر</span>
                                        <span :class="medicine.stock > 0 ? 'text-blue-700' : 'text-red-600'" class="font-bold text-lg">
                                            {{ medicine.stock > 0 ? medicine.stock + ' قطعة' : 'نفذت الكمية' }}
                                        </span>
                                    </div>
                                </div>

                                <button @click="reserveMedicine(medicine)" 
        :disabled="medicine.stock <= 0 || form.processing"
        class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold transition-all duration-150 text-white shadow-md"
        :class="medicine.stock > 0 && !form.processing
            ? 'bg-green-600 hover:bg-green-700 hover:shadow-lg active:bg-green-800' 
            : 'bg-gray-300 cursor-not-allowed'">
    
    <span v-if="form.processing">جاري الطلب...</span>
    <span v-else-if="medicine.stock <= 0">غير متوفر</span>
    <span v-else class="flex items-center gap-2"><span>📝</span> اطلب حجز الدواء الحين</span>
</button>
                            </div>
                        </div>
                    </div>

                    <div v-if="!pharmacy.medicines || pharmacy.medicines.length === 0" class="text-center py-16 bg-white rounded-3xl border border-dashed border-gray-300 mt-6">
                        <span class="text-6xl block mb-4">📦</span>
                        <p class="text-xl text-gray-500 font-medium">لا توجد أدوية مضافة في هذه الصيدلية حالياً.</p>
                        <p class="text-gray-400 mt-1">يرجى التحقق مرة أخرى في وقت لاحق.</p>
                    </div>
                </section>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* خط Cairo العربي لإعطاء لمسة جمالية (اختياري لو مضاف عندك عالمشروع) */
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
:deep(body), :deep(html) {
    font-family: 'Cairo', sans-serif;
}
</style>