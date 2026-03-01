<script setup lang="ts">
import { ShieldCheckIcon } from '@heroicons/vue/24/outline';
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { store } from '@/routes/password/confirm';
</script>

<template>
    <Head title="Konfirmasi Keamanan" />

    <UserMobileLayout 
        title="Keamanan"
        back-route="settings.index"
    >
        <div class="space-y-6">

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex flex-col items-center mb-8">
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-3xl text-amber-500 mb-4">
                        <ShieldCheckIcon class="w-10 h-10" />
                    </div>
                    <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-400 px-4 leading-relaxed">
                        Anda akan mengakses area sensitif. Masukkan kata sandi akun Anda untuk melanjutkan.
                    </p>
                </div>

                <Form
                    v-bind="store.form()"
                    reset-on-success
                    v-slot="{ errors, processing }"
                >
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <Label for="password" class="text-[11px] font-black uppercase text-slate-400 ml-1">
                                Kata Sandi Saat Ini
                            </Label>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                autofocus
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <div class="pt-2">
                            <Button
                                class="w-full h-12 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-95"
                                :disabled="processing"
                                data-test="confirm-password-button"
                            >
                                <Spinner v-if="processing" class="mr-2" />
                                {{ processing ? 'Memproses...' : 'Konfirmasi & Lanjut' }}
                            </Button>
                        </div>
                    </div>
                </Form>
            </div>

            <div class="px-4">
                <p class="text-[10px] text-center text-slate-400 font-medium">
                    Sesi konfirmasi ini akan aktif selama beberapa menit.
                </p>
            </div>
        </div>
    </UserMobileLayout>
</template>