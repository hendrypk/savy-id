<script setup lang="ts">
import { ChevronLeftIcon, UserCircleIcon } from '@heroicons/vue/24/outline';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

const page = usePage();
// Gunakan optional chaining agar tidak crash jika session expired
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <Head title="Pengaturan Profil" />

    <UserMobileLayout title="Profil">
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <Link 
                    :href="route('settings.index')" 
                    class="p-2 -ml-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 active:scale-90 transition-transform shadow-sm"
                >
                    <ChevronLeftIcon class="w-5 h-5 stroke-[2.5]" />
                </Link>
                <div>
                    <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 tracking-tight">Informasi Profil</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Perbarui detail identitas Anda</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex flex-col items-center mb-8">
                    <div class="p-1 bg-indigo-50 dark:bg-indigo-900/20 rounded-full border-2 border-dashed border-indigo-200 dark:border-indigo-800">
                        <UserCircleIcon class="w-16 h-16 text-indigo-500" />
                    </div>
                </div>

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-5"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="space-y-2">
                        <Label for="name" class="text-[11px] font-black uppercase text-slate-400 ml-1">Nama Lengkap</Label>
                        <Input
                            id="name"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Contoh: Budi Santoso"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="email" class="text-[11px] font-black uppercase text-slate-400 ml-1">Alamat Email</Label>
                        <Input
                            id="email"
                            type="email"
                            class="h-12 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 focus:ring-indigo-500 shadow-none transition-all"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            placeholder="email@contoh.com"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at" class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                        <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed font-medium">
                            Email Anda belum diverifikasi. 
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline font-bold decoration-amber-300 dark:decoration-amber-800"
                            >
                                Kirim ulang email verifikasi.
                            </Link>
                        </p>
                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-[10px] font-bold text-green-600">
                            Link verifikasi baru telah dikirim!
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            class="w-full h-12 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-95"
                        >
                            {{ processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </Button>
                    </div>

                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                    >
                        <p v-show="recentlySuccessful" class="text-center text-xs font-bold text-green-600">
                            Berhasil disimpan! ✨
                        </p>
                    </Transition>
                </Form>
            </div>

            <div class="pt-4 pb-10">
                <DeleteUser />
            </div>
        </div>
    </UserMobileLayout>
</template>