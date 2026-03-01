<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { mobileToast } from '@/lib/swal';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();
    

interface User {
    name: string;
    email: string;
    phone?: string;
    email_verified_at?: string;
}

const page = usePage<{ auth: { user: User } }>();
const user = computed(() => page.props.auth.user);

// Menggunakan form helper dari Inertia
const form = useForm({
    name: user.value?.name || '',
    email: user.value?.email || '',
    phone: user.value?.phone || '',
});

// Logic Submit menggunakan Inertia
const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            mobileToast('Profile berhasil diubah', 'success');
            form.reset();
        },

        onError: () => {
            mobileToast('Gagal mengubah Profile', 'error');
        },
    });
};
</script>

<template>
    <Head title="Pengaturan Profil" />

    <UserMobileLayout
        title="Profil"
        :back-route="route('settings.index')"
    >
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    
                    <div class="space-y-2">
                        <Label for="name" class="text-[11px] font-black uppercase text-slate-400 ml-1">Nama Lengkap</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            required
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="phone" class="text-[11px] font-black uppercase text-slate-400 ml-1">WhatsApp</Label>
                        <Input
                            id="phone"
                            v-model="form.phone"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            required
                        />
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div class="space-y-2">
                        <Label for="email" class="text-[11px] font-black uppercase text-slate-400 ml-1">Alamat Email</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            required
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-3xl border border-amber-100 dark:border-amber-900/30">
                        <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">
                            Email belum diverifikasi. 
                            <Link :href="route('verification.send')" method="post" as="button" class="underline font-bold">Kirim ulang.</Link>
                        </p>
                    </div>

                    <div class="pt-4">
                        <Button
                            type="submit"
                            variant="purple"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Sedang Menyimpan...' : 'Simpan Perubahan' }}
                        </Button>
                    </div>
                </form>
            </div>

            <div class="pt-4 pb-10 px-2">
                <DeleteUser />
            </div>
        </div>
    </UserMobileLayout>
</template>