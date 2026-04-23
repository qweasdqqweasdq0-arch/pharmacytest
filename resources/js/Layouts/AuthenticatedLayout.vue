<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const userRole = computed(() => user.value?.role); 

const menuItems = computed(() => {
    // 1. القائمة الأساسية للكل
    let items = [
        { name: 'الرئيسية', icon: '🏠', href: route('dashboard'), active: 'dashboard' },
    ];

    // 2. إذا كان المستخدم صيدلي
    if (userRole.value === 'pharmacist') {
        items.push(
            { name: 'إدارة الأدوية', icon: '💊', href: route('pharmacist.index'), active: 'pharmacist.index' },
            { name: 'طلبات الحجز', icon: '📥', href: route('pharmacist.reservations.index'), active: 'pharmacist.reservations.index' }
        );
    }

    // 3. إذا كان المستخدم مريض
    if (userRole.value === 'patient') {
        items.push(
            { name: 'بحث عن دواء', icon: '🔍', href: route('medicine.search'), active: 'medicine.search' },
            { name: 'حجوزاتي', icon: '📝', href: route('patient.reservations'), active: 'patient.reservations' }
        );
    }

    // 4. الملف الشخصي للجميع
    items.push({ name: 'الملف الشخصي', icon: '👤', href: route('profile.edit'), active: 'profile.edit' });

    return items;
});

const welcomeTitle = computed(() => {
    if (userRole.value === 'pharmacist') return 'لوحة تحكم الصيدلي';
    if (userRole.value === 'patient') return 'بوابة المريض';
    return 'النظام الطبي';
});
</script>

<template>
    <Head :title="welcomeTitle" />

    <div class="min-h-screen bg-gray-50 flex" dir="rtl">
        <aside class="w-64 bg-white border-l border-gray-200 flex flex-col shadow-sm fixed h-full">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <span class="text-3xl">🏥</span>
                <h1 class="text-xl font-bold text-blue-900">صيدليتي</h1>
            </div>

            <nav class="flex-grow p-4 space-y-2">
                <Link v-for="item in menuItems" :key="item.name" :href="item.href"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current(item.active) 
                        ? 'bg-blue-600 text-white shadow-md' 
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'">
                    <span class="text-xl">{{ item.icon }}</span>
                    <span class="font-medium">{{ item.name }}</span>
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <Link :href="route('logout')" method="post" as="button"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-all font-bold">
                    <span>🔙</span> تسجيل الخروج
                </Link>
            </div>
        </aside>

        <div class="flex-1 mr-64">
            <header class="bg-white border-b border-gray-200 py-4 px-8 flex items-center justify-between sticky top-0 z-10">
                <h2 class="text-lg font-bold text-gray-800">{{ welcomeTitle }}</h2>
                <div class="flex items-center gap-3 bg-gray-50 px-4 py-1.5 rounded-full border border-gray-100">
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900 leading-none">{{ user?.name }}</p>
                        <span class="text-[10px] text-blue-600 font-bold uppercase">{{ userRole }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        {{ user?.name?.charAt(0).toUpperCase() }}
                    </div>
                </div>
            </header>

            <main class="p-8">
                <slot />
            </main>
        </div>
    </div>
</template>