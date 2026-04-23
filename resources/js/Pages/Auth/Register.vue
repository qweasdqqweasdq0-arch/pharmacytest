<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '', // أضفنا الحقل هنا
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="إنشاء حساب جديد" />

        <form @submit.prevent="submit">
            <!-- الاسم -->
            <div>
                <InputLabel for="name" value="الاسم الكامل" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- البريد -->
            <div class="mt-4">
                <InputLabel for="email" value="البريد الإلكتروني" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- نوع الحساب (مهم جداً) -->
            <div class="mt-4">
                <InputLabel for="role" value="نوع الحساب" />
                <select 
                    id="role" 
                    v-model="form.role" 
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required
                >
                    <option value="" disabled>اختر نوع الحساب...</option>
                    <option value="patient">مريض (للبحث والحجز)</option>
                    <option value="pharmacist">صيدلي (لإدارة الأدوية)</option>
                </select>
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <!-- كلمة المرور -->
            <div class="mt-4">
                <InputLabel for="password" value="كلمة المرور" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- تأكيد كلمة المرور -->
            <div class="mt-4">
                <InputLabel for="password_confirmation" value="تأكيد كلمة المرور" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link :href="route('login')" class="text-sm text-gray-600 underline hover:text-gray-900">
                    لديك حساب بالفعل؟
                </Link>
                <PrimaryButton class="ms-4" :disabled="form.processing">
                    تسجيل الحساب
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>