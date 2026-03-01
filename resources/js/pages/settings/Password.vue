<script setup lang="ts">
import { KeyIcon } from '@heroicons/vue/24/outline';
import { useForm, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { mobileToast } from '@/lib/swal'; // Helper toast estetik kita

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const isFormValid = computed(() => {
    return form.current_password.length > 0 && 
           form.password.length >= 8 && 
           form.password_confirmation.length >= 8;
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            mobileToast('Kata sandi diperbarui!', 'success');
            form.reset();
        },

        onError: () => {
            mobileToast('Kata sandi Gagal diperbarui!', 'error');
        },
    });
};

</script>

<template>
    <Head title="Pengaturan Kata Sandi" />

    <UserMobileLayout 
        title="Keamanan" 
        :back-route="route('settings.index')"
    >
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex flex-col items-center mb-8">
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-4xl text-indigo-500">
                        <KeyIcon class="w-8 h-8" />
                    </div>
                </div>

                <form @submit.prevent="updatePassword" class="space-y-5">
                    <div class="space-y-2">
                        <Label for="current_password" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Sandi Saat Ini</Label>
                        <Input
                            id="current_password"
                            v-model="form.current_password"
                            type="password"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none"
                            placeholder="••••••••"
                        />
                        <InputError :message="form.errors.current_password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Sandi Baru</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none"
                            placeholder="Minimal 8 karakter"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation" class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Konfirmasi Sandi</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none"
                            placeholder="Ulangi sandi baru"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <div class="pt-4">
                        <Button
                            :disabled="form.processing || !isFormValid"
                            variant="purple"
                            class="w-full h-12 shadow-lg shadow-indigo-100 dark:shadow-none active:scale-95 transition-all disabled:opacity-50 disabled:grayscale-[0.5]"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </Button>
                    </div>
                </form>
            </div>

            <div class="px-4 text-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
                    Keamanan adalah prioritas. Gunakan kombinasi sandi yang kuat.
                </p>
            </div>
        </div>
    </UserMobileLayout>
</template>