<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue'; // استيراد ref للتحكم في الحالة

const props = defineProps({
    reservations: Array
});

// متغير لتخزين النجمة التي يمر فوقها الماوس حالياً
const hoveredStar = ref(0);

const statusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800 border border-yellow-200';
        case 'approved': return 'bg-green-100 text-green-800 border border-green-200';
        case 'confirmed': return 'bg-green-100 text-green-800 border border-green-200';
        case 'rejected': return 'bg-red-100 text-red-800 border border-red-200';
        case 'cancelled': return 'bg-red-100 text-red-800 border border-red-200';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const statusTranslation = (status) => {
    switch (status) {
        case 'pending': return 'قيد الانتظار';
        case 'approved': return 'تمت الموافقة';
        case 'confirmed': return 'تمت الموافقة';
        case 'rejected': return 'مرفوض';
        case 'cancelled': return 'ملغى';
        default: return status;
    }
};

const submitRating = (pharmacyId, stars) => {
    router.post(route('pharmacy.rate'), {
        pharmacy_id: pharmacyId,
        stars: stars
    }, {
        preserveScroll: true,
        onSuccess: () => {
            alert('شكراً لتقييمك!');
            hoveredStar.value = 0; // إعادة التصفير بعد الإرسال
        }
    });
};
</script>

<template>
    <Head title="حجوزاتي" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">قائمة حجوزاتي</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    
                    <div v-if="reservations.length === 0" class="text-center py-10">
                        <div class="text-5xl mb-4 text-gray-300">📅</div>
                        <p class="text-gray-500 text-lg">ليس لديك أي حجوزات حالياً.</p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-blue-50 border-b border-blue-100">
                                    <th class="p-4 font-bold text-gray-700">الدواء</th>
                                    <th class="p-4 font-bold text-gray-700">الصيدلية</th>
                                    <th class="p-4 font-bold text-gray-700">التاريخ</th>
                                    <th class="p-4 font-bold text-gray-700">الحالة</th>
                                    <th class="p-4 font-bold text-gray-700">التقييم</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="res in reservations" :key="res.id" class="border-b hover:bg-gray-50 transition">
                                    <td class="p-4 font-bold text-blue-700">
                                        <span class="ml-2">💊</span>{{ res.medicine?.name }}
                                    </td>
                                    <td class="p-4 text-gray-600">
                                        <div class="font-medium text-gray-900">{{ res.pharmacy?.name || 'غير محدد' }}</div>
                                        <div class="text-xs text-gray-400">فرع الصيدلية الأساسي</div>
                                    </td>
                                    <td class="p-4 text-gray-500 text-sm">
                                        {{ new Date(res.created_at).toLocaleDateString('ar-EG', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                    </td>
                                    <td class="p-4">
                                        <span :class="statusClass(res.status)" class="px-4 py-1 rounded-full text-xs font-bold inline-block shadow-sm">
                                            {{ statusTranslation(res.status) }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div v-if="res.status === 'approved' || res.status === 'confirmed'" 
                                             class="flex items-center gap-1 group"
                                             @mouseleave="hoveredStar = 0">
                                            <button 
                                                v-for="star in 5" 
                                                :key="star"
                                                @mouseover="hoveredStar = star"
                                                @click="submitRating(res.pharmacy_id, star)"
                                                class="transition-all duration-200 text-2xl outline-none focus:outline-none"
                                                :class="star <= hoveredStar ? 'text-yellow-400 scale-125' : 'text-gray-300'"
                                                title="اضغط للتقييم"
                                            >
                                                ★
                                            </button>
                                        </div>
                                        <span v-else class="text-xs text-gray-300 italic">متاح بعد القبول</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
* { font-family: 'Cairo', sans-serif; }
table th, table td { text-align: right; }
</style>