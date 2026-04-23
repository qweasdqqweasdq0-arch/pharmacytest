<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

// استلام الأدوية من الـ PharmacistController
const props = defineProps({
    medicines: Array,
});

// حالة للتحكم في وضع النموذج (إضافة أم تعديل)
const isEditing = ref(false);
const showAddForm = ref(false);

// إعداد النموذج (Form) 
const form = useForm({
    id: null, // نحتاجه فقط عند التعديل
    name: '',
    price: '',
    stock: '',
    description: '',
});

// وظيفة لفتح نموذج التعديل وتعبئة البيانات
const editMedicine = (medicine) => {
    isEditing.value = true;
    showAddForm.value = true;
    
    form.id = medicine.id;
    form.name = medicine.name;
    form.price = medicine.price;
    form.stock = medicine.stock;
    form.description = medicine.description;
    
    // التمرير للأعلى لرؤية النموذج
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// وظيفة لإعادة ضبط النموذج عند الإلغاء أو النجاح
const resetForm = () => {
    form.reset();
    isEditing.value = false;
    showAddForm.value = false;
};

// وظيفة الإرسال (تتعامل مع الإضافة والتعديل)
const submit = () => {
    if (isEditing.value) {
        // إرسال طلب تحديث (PUT)
        form.put(route('pharmacist.medicines.update', form.id), {
            onSuccess: () => {
                resetForm();
                alert('تم تحديث بيانات الدواء بنجاح! ✅');
            },
        });
    } else {
        // إرسال طلب إضافة (POST)
        form.post(route('pharmacist.medicines.store'), {
            onSuccess: () => {
                resetForm();
                alert('تم إضافة الدواء إلى مخزنك بنجاح! 🎉');
            },
        });
    }
};

// وظيفة الحذف
const deleteMedicine = (id) => {
    if (confirm('هل أنت متأكد من حذف هذا الدواء نهائياً؟')) {
        form.delete(route('pharmacist.medicines.destroy', id), {
            onSuccess: () => alert('تم حذف الدواء من المخزن.'),
        });
    }
};
</script>

<template>
    <Head title="إدارة المخزن" />

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8" dir="rtl">
        <div class="max-w-6xl mx-auto">
            
            <!-- الهيدر -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">📦 إدارة مخزن الأدوية</h1>
                    <p class="text-gray-500 mt-1">قم بإضافة وتحديث الأدوية المتوفرة في صيدليتك.</p>
                </div>
                <button 
                    @click="isEditing ? resetForm() : (showAddForm = !showAddForm)"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all"
                >
                    <span>{{ showAddForm ? 'إلغاء' : 'إضافة دواء جديد' }}</span>
                    <span class="text-xl">{{ showAddForm ? '✖️' : '➕' }}</span>
                </button>
            </div>

            <!-- نموذج إضافة/تعديل دواء -->
            <transition name="fade">
                <div v-if="showAddForm" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-10">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="bg-blue-100 p-2 rounded-lg text-blue-600 text-sm">📋</span>
                        {{ isEditing ? 'تعديل بيانات الدواء' : 'بيانات الدواء الجديد' }}
                    </h2>
                    
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">اسم الدواء</label>
                            <input v-model="form.name" type="text" placeholder="مثلاً: Panadol 500mg" 
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500" required>
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">السعر ($)</label>
                            <input v-model="form.price" type="number" step="0.01" placeholder="0.00" 
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">الكمية المتوفرة (Stock)</label>
                            <input v-model="form.stock" type="number" placeholder="كم قطعة لديك؟" 
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">وصف قصير (اختياري)</label>
                            <input v-model="form.description" type="text" placeholder="ملاحظات إضافية..." 
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2 flex justify-end gap-3 mt-4">
                            <button type="submit" :disabled="form.processing"
                                class="bg-green-600 hover:bg-green-700 text-white px-10 py-3 rounded-xl font-bold shadow-md disabled:opacity-50 transition-all">
                                {{ form.processing ? 'جاري الحفظ...' : (isEditing ? 'تحديث البيانات' : 'حفظ الدواء في النظام') }}
                            </button>
                        </div>
                    </form>
                </div>
            </transition>

            <!-- جدول عرض الأدوية -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800 text-lg">قائمة الأدوية الحالية</h2>
                    <span class="text-sm bg-gray-100 px-3 py-1 rounded-full text-gray-500 font-medium">
                        إجمالي الأدوية: {{ medicines ? medicines.length : 0 }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase">
                                <th class="p-4 font-bold">الدواء</th>
                                <th class="p-4 font-bold">السعر</th>
                                <th class="p-4 font-bold text-center">المخزن</th>
                                <th class="p-4 font-bold">تاريخ الإضافة</th>
                                <th class="p-4 font-bold text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="medicine in medicines" :key="medicine.id" class="hover:bg-blue-50/30 transition-colors group">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-xl">💊</div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ medicine.name }}</div>
                                            <div class="text-xs text-gray-400">{{ medicine.description || 'لا يوجد وصف' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-green-600 font-bold">${{ medicine.price }}</td>
                                <td class="p-4 text-center">
                                    <span 
                                        class="px-3 py-1 rounded-full text-xs font-bold"
                                        :class="medicine.stock > 10 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                    >
                                        {{ medicine.stock }} قطعة
                                    </span>
                                </td>
                                <td class="p-4 text-gray-500 text-sm">
                                    {{ new Date(medicine.created_at).toLocaleDateString('ar-EG') }}
                                </td>
                                <td class="p-4 text-center">
                                    <!-- أزرار الإجراءات الفعالة -->
                                    <button 
                                        @click="editMedicine(medicine)"
                                        class="text-blue-500 hover:text-blue-700 font-bold ml-3"
                                    >
                                        تعديل
                                    </button>
                                    <button 
                                        @click="deleteMedicine(medicine.id)"
                                        class="text-red-400 hover:text-red-600 font-bold"
                                    >
                                        حذف
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!medicines || medicines.length === 0">
                                <td colspan="5" class="p-20 text-center text-gray-400">
                                    <div class="text-5xl mb-4">📭</div>
                                    <p>لا يوجد أدوية في مخزنك حالياً، ابدأ بإضافة أول دواء!</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');

:deep(html), :deep(body) {
    font-family: 'Cairo', sans-serif;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>