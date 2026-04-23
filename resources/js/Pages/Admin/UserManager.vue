 <script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: Array
});

const form = useForm({});

// دالة حذف مستخدم
const deleteUser = (id) => {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم نهائياً؟')) {
        form.delete(route('admin.users.delete', id));
    }
};

// دالة تغيير حالة التفعيل
const toggleStatus = (id) => {
    form.post(route('admin.users.toggle', id));
};
</script>

<template>
    <Head title="إدارة النظام" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">لوحة تحكم المدير العام</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen" dir="rtl">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">إدارة المستخدمين</h3>
                            <p class="text-gray-500 mt-1">يمكنك تفعيل، تعطيل، أو حذف حسابات المستخدمين من هنا.</p>
                        </div>
                        <div class="bg-blue-50 px-4 py-2 rounded-2xl border border-blue-100">
                            <span class="text-blue-700 font-bold">إجمالي المستخدمين: {{ users.length }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600">
                                    <th class="p-4 rounded-r-2xl">الاسم والبريد</th>
                                    <th class="p-4">نوع الحساب</th>
                                    <th class="p-4">الحالة</th>
                                    <th class="p-4 rounded-l-2xl text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="user in users" :key="user.id" class="hover:bg-blue-50/30 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-gray-950">{{ user.name }}</div>
                                        <div class="text-sm text-gray-500">{{ user.email }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span :class="user.role === 'pharmacist' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'" 
                                              class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                                            {{ user.role === 'pharmacist' ? 'صيدلي' : (user.role === 'admin' ? 'مدير' : 'مريض') }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span :class="user.is_active ? 'text-green-600' : 'text-red-500'" class="flex items-center gap-1 font-medium">
                                            <span class="w-2 h-2 rounded-full" :class="user.is_active ? 'bg-green-600' : 'bg-red-500'"></span>
                                            {{ user.is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-center gap-3">
 <button @click="toggleStatus(user.id)" 
                                                    class="px-4 py-1.5 rounded-lg text-sm font-bold border border-gray-200 hover:bg-gray-100 transition">
                                                {{ user.is_active ? 'تعطيل' : 'تفعيل' }}
                                            </button>
                                            <button @click="deleteUser(user.id)" 
                                                    v-if="user.role !== 'admin'"
                                                    class="px-4 py-1.5 rounded-lg text-sm font-bold bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition">
                                                حذف
                                            </button>
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