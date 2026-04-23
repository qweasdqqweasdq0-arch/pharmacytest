<script setup>
import { computed } from 'vue';
import { usePage, Head, Link } from '@inertiajs/vue3';

const page = usePage();

// استقبال الإحصائيات من الكنترولر (أضفنا stats للمدير)
const props = defineProps({
    ratingStats: Object,
    stats: Object // إحصائيات المدير
});

const user = computed(() => page.props.auth?.user);
const userRole = computed(() => user.value?.role);

const menuItems = computed(() => {
    const baseItems = [
        { name: 'الرئيسية', icon: '🏠', href: '/dashboard' },
    ];

    if (userRole.value === 'pharmacist') {
        return [
            ...baseItems,
            { name: 'إدارة الأدوية', icon: '💊', href: '/pharmacist/medicines' },
            { name: 'إدارة الحجوزات', icon: '📅', href: '/pharmacist/reservations' },
            { name: 'الملف الشخصي', icon: '👤', href: '/profile' },
        ];
    }

    if (userRole.value === 'patient') {
        return [
            ...baseItems,
            { name: 'عرض الصيدليات', icon: '🏥', href: '/patient/pharmacies' },
            { name: 'بحث عن دواء', icon: '🔍', href: '/patient/search' },
            { name: 'حجوزاتي', icon: '📝', href: '/patient/my-reservations' },
            { name: 'الملف الشخصي', icon: '👤', href: '/profile' },
        ];
    }

    if (userRole.value === 'admin') {
        return [
            ...baseItems,
            { name: 'إدارة المستخدمين', icon: '👥', href: '/admin/users' },
            { name: 'إدارة الصيدليات', icon: '🏢', href: '/admin/pharmacies' }, // تمت الإضافة
            { name: 'التقارير', icon: '📊', href: '/admin/reports' },
            { name: 'الملف الشخصي', icon: '👤', href: '/profile' },
        ];
    }

    return baseItems;
});

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
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-l border-gray-100 p-6 flex flex-col shadow-sm">
            <div class="flex items-center gap-3 mb-10 pb-4 border-b border-gray-100">
                <span class="text-3xl">🏥</span>
                <h1 class="text-xl font-extrabold text-blue-900">نظام الصيدلية</h1>
            </div>

            <nav class="flex-grow space-y-3">
                <Link v-for="item in menuItems" :key="item.name" :href="item.href"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-800 transition-all duration-150 group"
                    :class="{ 'bg-blue-100 text-blue-900 font-medium': $page.url === item.href }">
                    <span class="text-lg group-hover:scale-110 transition-transform">{{ item.icon }}</span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <div class="mt-auto pt-6 border-t border-gray-100">
                <Link href="/logout" method="post" as="button"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 transition-colors font-medium">
                    <span>🔙</span>
                    تسجيل الخروج
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <header class="bg-white border-b border-gray-100 py-4 px-10 flex items-center justify-between shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800">{{ welcomeTitle }}</h2>
                
                <div class="flex items-center gap-4 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                    <div class="flex flex-col text-right">
                        <span class="font-bold text-gray-900 leading-none">{{ user?.name || 'مستخدم' }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">{{ userRole || 'guest' }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xl font-bold border-2 border-blue-200 shadow-sm">
                        {{ (user?.name || 'U').charAt(0).toUpperCase() }}
                    </div>
                </div>
            </header>

            <main class="p-10 space-y-8">
                <!-- Welcome Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-6 transform hover:scale-[1.01] transition-transform duration-300">
                    <span class="text-5xl animate-bounce">👋</span>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">أهلاً بك، {{ user?.name }}!</h3>
                        <p class="text-gray-600 mt-1">لقد قمت بتسجيل الدخول بنجاح بصلاحيات: 
                            <span class="font-medium text-blue-800 bg-blue-50 px-3 py-1 rounded-full text-sm mr-2 border border-blue-100">
                                {{ userRole === 'pharmacist' ? 'صيدلي' : (userRole === 'patient' ? 'مريض' : 'مدير النظام') }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- قسم إحصائيات التقييم للصيدلي فقط -->
                <div v-if="userRole === 'pharmacist'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-8 rounded-3xl shadow-lg text-white flex items-center justify-between">
                        <div>
                            <p class="text-lg opacity-90 font-medium">متوسط التقييم العام</p>
                            <h3 class="text-5xl font-black mt-2">{{ ratingStats?.average || '0.0' }}</h3>
                            <div class="mt-4 flex gap-1 text-2xl">
                                <span v-for="i in 5" :key="i" :class="i <= Math.round(ratingStats?.average) ? 'text-white' : 'text-yellow-600 opacity-40'">★</span>
                            </div>
                        </div>
                        <span class="text-7xl opacity-30">⭐️</span>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border-2 border-blue-50 flex items-center gap-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center text-4xl shadow-inner">👥</div>
                        <div>
                            <p class="text-gray-500 font-bold uppercase tracking-widest text-sm">إجمالي المقيمين</p>
                            <p class="text-4xl font-black text-gray-900 mt-1">{{ ratingStats?.count || 0 }} <span class="text-lg font-normal text-gray-400">مريض</span></p>
                        </div>
                    </div>
                </div>

                <!-- قسم إحصائيات المدير فقط -->
                <div v-if="userRole === 'admin'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-600 p-8 rounded-3xl shadow-lg text-white">
                        <p class="opacity-80 font-bold italic">إجمالي المستخدمين</p>
                        <h3 class="text-4xl font-black mt-2">{{ stats?.users_count || 0 }}</h3>
                    </div>
                    <div class="bg-emerald-600 p-8 rounded-3xl shadow-lg text-white">
                        <p class="opacity-80 font-bold italic">إجمالي الصيدليات</p>
                        <h3 class="text-4xl font-black mt-2">{{ stats?.pharmacies_count || 0 }}</h3>
                    </div>
                    <div class="bg-purple-600 p-8 rounded-3xl shadow-lg text-white">
                        <p class="opacity-80 font-bold italic">طلبات معلقة</p>
                        <h3 class="text-4xl font-black mt-2">{{ stats?.pending_reservations || 0 }}</h3>
                    </div>
                </div>

                <!-- Quick Actions Grid -->
                <section>
                    <h4 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        الإجراءات السريعة المتاحة لك:
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link v-for="item in menuItems.filter(i => i.name !== 'الرئيسية')" :key="item.name + '_card'" :href="item.href"
                            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-start gap-4 group">
                            <div class="p-3.5 rounded-xl bg-blue-50 text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                {{ item.icon }}
                            </div>
                            <div class="flex-1">
                                <span class="font-bold text-gray-900 group-hover:text-blue-900 transition-colors block">{{ item.name }}</span>
                                <p class="text-sm text-gray-500 mt-1">انتقل الآن لإدارة قسم {{ item.name }} الخاص بك.</p>
                            </div>
                            <span class="text-gray-300 group-hover:text-blue-600 transition-colors text-2xl font-light">←</span>
                        </Link>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>