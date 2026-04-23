<template>
    <Head title="طلبات الحجز" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center" dir="rtl">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">📦 طلبات حجز الأدوية</h2>
                <Link 
                    :href="route('pharmacist.index')" 
                    class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition"
                >
                    🔙 العودة لإدارة الأدوية
                </Link>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-6">قائمة الطلبات المستلمة</h3>
                    
                    <div v-if="reservations.length === 0" class="text-center py-10 text-gray-500 italic">
                        لا توجد طلبات حجز حالياً من قبل المرضى.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-right">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">اسم المريض</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">الدواء</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="res in reservations" :key="res.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ res.user.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold">
                                        {{ res.medicine.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(res.created_at).toLocaleDateString('ar-EG') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800': res.status === 'pending',
                                            'bg-green-100 text-green-800': res.status === 'approved',
                                            'bg-red-100 text-red-800': res.status === 'rejected'
                                        }" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            {{ res.status === 'pending' ? 'قيد الانتظار' : (res.status === 'approved' ? 'مقبول' : 'مرفوض') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div v-if="res.status === 'pending'" class="flex gap-2">
                                            <button 
                                                @click="updateStatus(res.id, 'approved')"
                                                class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition"
                                            >
                                                قبول
                                            </button>
                                            <button 
                                                @click="updateStatus(res.id, 'rejected')"
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition"
                                            >
                                                رفض
                                            </button>
                                        </div>
                                        <div v-else class="text-gray-400 italic text-xs">
                                            تمت المعالجة
                                        </div>
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    reservations: Array,
    auth: Object
});

// دالة تحديث الحالة
const updateStatus = (id, status) => {
    const actionText = status === 'approved' ? 'قبول' : 'رفض';
    if (confirm(`هل أنت متأكد من ${actionText} هذا الحجز؟`)) {
        router.patch(route('pharmacist.reservations.update', id), { 
            status: status 
        }, {
            preserveScroll: true,
            onSuccess: () => alert(`تم ${actionText} الطلب بنجاح.`)
        });
    }
};
</script>

<style scoped>
/* لإضافة طابع لغة عربية متناسق */
* {
    font-family: 'Cairo', sans-serif;
}
</style>