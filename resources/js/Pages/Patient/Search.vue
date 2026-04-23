<script setup>
import { ref } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// استلام البيانات من الكنترولر
const props = defineProps({ 
    results: Array, 
    filters: Object,
    auth: Object
});

const searchQuery = ref(props.filters?.query || '');

// دالة البحث
const handleSearch = () => {
    router.get(route('medicine.search'), { query: searchQuery.value }, { 
        preserveState: true,
        replace: true,
        preserveScroll: true
    });
};

// دالة الحجز المحدثة لإرسال معرف الصيدلية ومعرف الدواء
const reserveMedicine = (medicine) => {
    if (confirm(`هل أنت متأكد من رغبتك في حجز "${medicine.name}"؟`)) {
        router.post(route('medicine.reserve'), { 
            medicine_id: medicine.id,
            pharmacy_id: medicine.pharmacy_id // ضروري جداً لربط الحجز بالصيدلية
        }, {
            onSuccess: () => {
                alert('تم إرسال طلب الحجز بنجاح! يرجى انتظار موافقة الصيدلية.');
            },
            onError: (errors) => {
                console.error(errors);
                alert('حدث خطأ أثناء الحجز، تأكد من تسجيل الدخول والمحاولة لاحقاً.');
            }
        });
    }
};
</script>

<template>
    <Head title="البحث عن دواء" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
                البحث عن الأدوية وحجزها
            </h2>
        </template>

        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8" dir="rtl">
            <div class="max-w-5xl mx-auto">
                
                <!-- الهيدر الخاص بالبحث -->
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-extrabold text-blue-900 mb-2">🔍 ابحث عن دوائك</h1>
                    <p class="text-gray-600">تصفح الأدوية المتوفرة في جميع الصيدليات المشتركة</p>
                </div>

                <!-- شريط البحث -->
                <div class="relative max-w-2xl mx-auto mb-12">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="اكتب اسم الدواء هنا..." 
                        class="w-full pl-12 pr-6 py-4 rounded-2xl border-none shadow-lg focus:ring-4 focus:ring-blue-100 transition-all text-lg font-medium text-right"
                        @keyup.enter="handleSearch"
                    >
                    <button 
                        @click="handleSearch"
                        class="absolute left-3 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition-colors font-bold shadow-md"
                    >
                        بحث
                    </button>
                </div>

                <!-- عرض النتائج -->
                <div v-if="results && results.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="medicine in results" 
                        :key="medicine.id" 
                        class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group"
                    >
                        <!-- أيقونة افتراضية -->
                        <div class="h-32 bg-blue-50 flex items-center justify-center text-5xl group-hover:scale-110 transition-transform">
                            💊
                        </div>

                        <div class="p-6 text-right">
                            <div class="flex justify-between items-start mb-2 flex-row-reverse">
                                <h3 class="font-bold text-xl text-gray-900">{{ medicine.name }}</h3>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                                    {{ medicine.price }} $
                                </span>
                            </div>

                            <div class="space-y-2 mb-6 text-sm">
                                <p class="flex items-center gap-2 text-gray-600 justify-end">
                                    <span>{{ medicine.pharmacy?.name || 'غير محدد' }}</span>
                                    <span class="text-blue-500">🏨</span>
                                </p>
                                <p class="flex items-center gap-2 text-gray-600 justify-end">
                                    <span>الكمية المتاحة: 
                                        <span :class="medicine.stock > 5 ? 'text-gray-900' : 'text-red-500 font-bold'">
                                            {{ medicine.stock }} قطعة
                                        </span>
                                    </span>
                                    <span class="text-blue-500">📦</span>
                                </p>
                            </div>
                            
                            <button 
                                @click="reserveMedicine(medicine)"
                                :disabled="medicine.stock <= 0"
                                class="w-full py-3 rounded-2xl font-bold transition-all shadow-sm flex items-center justify-center gap-2"
                                :class="medicine.stock > 0 
                                    ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-100' 
                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            >
                                <span>{{ medicine.stock > 0 ? 'حجز الدواء الآن' : 'غير متوفر حالياً' }}</span>
                                <span v-if="medicine.stock > 0">📝</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- حالة عدم وجود نتائج -->
                <div v-else class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="text-6xl mb-4">🧪</div>
                    <h3 class="text-xl font-bold text-gray-800">لا توجد أدوية مطابقة لبحثك</h3>
                    <p class="text-gray-500 mt-2">جرب البحث بكلمات أخرى أو تصفح الأدوية المتاحة.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* إضافة خط كايرو لجمالية الواجهة العربية */
* {
    font-family: 'Cairo', sans-serif;
}
</style>