<script setup lang="ts">
import { ChevronLeftIcon, KeyIcon } from '@heroicons/vue/24/outline';
import { Form, Head, Link } from '@inertiajs/vue3';
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

// Menghapus breadcrumbItems karena sudah menggunakan navigasi Back manual
</script>

<template>
    <Head title="Pengaturan Kata Sandi" />

    <UserMobileLayout title="Keamanan">
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <Link 
                    :href="route('settings.index')" 
                    class="p-2 -ml-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 active:scale-90 transition-transform shadow-sm"
                >
                    <ChevronLeftIcon class="w-5 h-5 stroke-[2.5]" />
                </Link>
                <div>
                    <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 tracking-tight">Ganti Kata Sandi</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jaga keamanan akun Anda tetap kuat</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex flex-col items-center mb-6">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl text-amber-500">
                        <KeyIcon class="w-8 h-8" />
                    </div>
                </div>

                <Form
                    v-bind="PasswordController.update.form()"
                    :options="{
                        preserveScroll: true,
                    }"
                    reset-on-success
                    :reset-on-error="[
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]"
                    class="space-y-5"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="space-y-2">
                        <Label for="current_password" class="text-[11px] font-black uppercase text-slate-400 ml-1">Kata Sandi Saat Ini</Label>
                        <Input
                            id="current_password"
                            name="current_password"
                            type="password"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password" class="text-[11px] font-black uppercase text-slate-400 ml-1">Kata Sandi Baru</Label>
                        <Input
                            id="password"
                            name="password"
                            type="password"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation" class="text-[11px] font-black uppercase text-slate-400 ml-1">Konfirmasi Kata Sandi Baru</Label>
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="pt-4 flex flex-col gap-4">
                        <Button
                            :disabled="processing"
                            class="w-full h-12 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-95"
                        >
                            {{ processing ? 'Memproses...' : 'Simpan Kata Sandi' }}
                        </Button>

                        <Transition
                            enter-active-class="transition ease-out duration-300"
                            enter-from-class="opacity-0 translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                        >
                            <p v-show="recentlySuccessful" class="text-center text-xs font-bold text-green-600">
                                Kata sandi berhasil diperbarui! 🔒
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <div class="px-2">
                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                    Gunakan kombinasi huruf, angka, dan simbol untuk memastikan keamanan akun Anda tetap maksimal.
                </p>
            </div>
        </div>
    </UserMobileLayout>
</template>