[07/04/2026 02:19 ص] ابراهيم خميس: <script setup>
import { computed } from 'vue';
import { usePage, Head, Link } from '@inertiajs/vue3';

const page = usePage();

// 1. استخراج بيانات المستخدم والصلاحيات بشكل آمن
const user = computed(() => page.props.auth?.user);
const userRole = computed(() => user.value?.role); 

// 2. تعريف القوائم الجانبية باستخدام أسماء المسارات (Routes Names)
const menuItems = computed(() => {
    const baseItems = [
        { name: 'الرئيسية', icon: '🏠', href: route('dashboard'), active: 'dashboard' },
    ];

    // خيارات الصيدلي
    if (userRole.value === 'pharmacist') {
        return [
            ...baseItems,
            { name: 'إدارة الأدوية', icon: '💊', href: route('pharmacist.index'), active: 'pharmacist.index' },
            { name: 'إدارة الحجوزات', icon: '📅', href: route('pharmacist.reservations.index'), active: 'pharmacist.reservations.index' },
            { name: 'الملف الشخصي', icon: '👤', href: route('profile.edit'), active: 'profile.edit' },
        ];
    }

    // خيارات المريض
    if (userRole.value === 'patient') {
        return [
            ...baseItems,
            { name: 'بحث عن دواء', icon: '🔍', href: route('medicine.search'), active: 'medicine.search' },
            // تم التعديل هنا ليرتبط بمسار 'patient.reservations' الموجود في web.php
            { name: 'حجوزاتي', icon: '📝', href: route('patient.reservations'), active: 'patient.reservations' },
            { name: 'الملف الشخصي', icon: '👤', href: route('profile.edit'), active: 'profile.edit' },
        ];
    }

    // خيارات المدير (إذا أردت تفعيلها لاحقاً بالأسماء الصحيحة)
    if (userRole.value === 'admin') {
        return [
            ...baseItems,
            { name: 'إدارة المستخدمين', icon: '👥', href: '#', active: '' },
            { name: 'التقارير', icon: '📊', href: '#', active: '' },
        ];
    }

    return baseItems;
});

// 3. نصوص ترحيبية ديناميكية
const welcomeTitle = computed(() => {
    if (userRole.value === 'pharmacist') return 'لوحة تحكم الصيدلي';
    if (userRole.value === 'patient') return 'بوابة المريض';
    if (userRole.value === 'admin') return 'الإدارة العامة';
    return 'مرحباً بك';
});
</script>

<template>
    <Head :title="welcomeTitle" />

    <div class="min-h-screen bg-gray-50 flex" dir="rtl">
        <aside class="w-64 bg-white border-l border-gray-100 p-6 flex flex-col shadow-sm fixed h-full">
            <div class="flex items-center gap-3 mb-10 pb-4 border-b border-gray-100">
                <span class="text-3xl">🏥</span>
                <h1 class="text-xl font-extrabold text-blue-900">نظام الصيدلية</h1>
            </div>

            <nav class="flex-grow space-y-3 overflow-y-auto">
                <Link v-for="item in menuItems" :key="item.name" :href="item.href"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-150 group"
                    :class="route().current(item.active) 
                        ? 'bg-blue-600 text-white shadow-md font-medium' 
                        : 'text-gray-700 hover:bg-blue-50 hover:text-blue-800'">
                    <span class="text-lg group-hover:scale-110 transition-transform">{{ item.icon }}</span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <div class="mt-auto pt-6 border-t border-gray-100">
                <Link :href="route('logout')" method="post" as="button"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 transition-colors font-medium">
                    <span>🔙</span>
                    تسجيل الخروج
                </Link>
            </div>
        </aside>

        <div class="flex-1 mr-64">
            <header class="bg-white border-b border-gray-100 py-4 px-10 flex items-center justify-between sticky top-0 z-10">
[07/04/2026 02:19 ص] ابراهيم خميس: <h2 class="text-lg font-semibold text-gray-800">{{ welcomeTitle }}</h2>
                
                <div class="flex items-center gap-4 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                    <div class="flex flex-col text-right">
                        <span class="font-bold text-gray-900 leading-none">{{ user?.name || 'مستخدم' }}</span>
                        <span class="text-[10px] text-gray-500 uppercase font-bold">{{ userRole || 'guest' }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-xl font-bold">
                        {{ (user?.name || 'U').charAt(0).toUpperCase() }}
                    </div>
                </div>
            </header>

            <main class="p-10 space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-6">
                    <span class="text-5xl">👋</span>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">أهلاً بك، {{ user?.name }}!</h3>
                        <p class="text-gray-600 mt-1">لقد قمت بتسجيل الدخول بنجاح بصلاحيات: 
                            <span class="font-medium text-blue-800 bg-blue-50 px-3 py-1 rounded-full text-sm">
                                {{ userRole === 'pharmacist' ? 'صيدلي' : (userRole === 'patient' ? 'مريض' : 'مدير') }}
                            </span>
                        </p>
                    </div>
                </div>

                <section>
                    <h4 class="text-lg font-semibold text-gray-800 mb-5">الإجراءات السريعة المتاحة لك:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link v-for="item in menuItems.filter(i => i.name !== 'الرئيسية')" :key="item.name + '_card'" :href="item.href"
                            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-100 hover:shadow-md transition-all flex items-start gap-4 group">
                            <div class="p-3.5 rounded-xl bg-blue-50 text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                {{ item.icon }}
                            </div>
                            <div class="flex-1">
                                <span class="font-bold text-gray-900 group-hover:text-blue-900 transition-colors">{{ item.name }}</span>
                                <p class="text-sm text-gray-500 mt-1">انتقل الآن لإدارة قسم {{ item.name }}.</p>
                            </div>
                            <span class="text-gray-400 group-hover:text-blue-600 transition-colors text-xl">←</span>
                        </Link>
                    </div>
                </section>
                
                <!-- مكان عرض محتوى الصفحات الأخرى -->
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');

:deep(html), :deep(body) {
    font-family: 'Cairo', sans-serif;
}
</style>