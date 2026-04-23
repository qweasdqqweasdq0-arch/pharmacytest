<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    pharmacies: Array,
});
</script>

<template>
    <Head title="الصيدليات المتاحة" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">قائمة الصيدليات</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="pharmacy in pharmacies" :key="pharmacy.id" 
                         class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-blue-600">{{ pharmacy.name }}</h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                ⭐ {{ pharmacy.rating }}
                            </span>
                        </div>
                        <p class="text-gray-600 mt-2"><i class="fas fa-map-marker-alt"></i> {{ pharmacy.address }}</p>
                        <p class="text-gray-600"><i class="fas fa-phone"></i> {{ pharmacy.phone || 'لا يوجد رقم' }}</p>
                        
                        <div class="mt-4">
                            <!-- رابط ينقلك لصفحة تفاصيل الصيدلية وأدويتها -->
                            <Link :href="route('patient.pharmacies.show', pharmacy.id)" 
                                  class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue transition ease-in-out duration-150">
                                عرض الأدوية والمعلومات
                            </Link>
                        </div>
                    </div>
                </div>
                
                <div v-if="pharmacies.length === 0" class="text-center text-gray-500 mt-10">
                    لا توجد صيدليات مسجلة حالياً.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>