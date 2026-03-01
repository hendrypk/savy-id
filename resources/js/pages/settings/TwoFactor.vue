<script setup lang="ts">
import { ShieldCheckIcon, ShieldExclamationIcon, LockClosedIcon } from '@heroicons/vue/24/outline';
import { Form, Head } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';
import { route } from 'ziggy-js';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { disable, enable } from '@/routes/two-factor';

type Props = {
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => {
    clearTwoFactorAuthData();
});
</script>

<template>
    <Head title="Keamanan 2FA" />

    <UserMobileLayout 
        title="Keamanan 2FA"
        :back-route="route('settings.index')"
    >
        <div class="space-y-6">

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm overflow-hidden relative">
                <LockClosedIcon class="absolute -right-4 -top-4 w-24 h-24 text-slate-50 dark:text-slate-800/50 z-0" />

                <div class="relative z-10 space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-400">Status Keamanan</span>
                        <Badge 
                            :variant="twoFactorEnabled ? 'default' : 'destructive'"
                            class="rounded-lg px-3 py-1 uppercase text-[10px] font-black tracking-tighter"
                            :class="twoFactorEnabled ? 'bg-green-500 hover:bg-green-500' : 'bg-red-500 hover:bg-red-500'"
                        >
                            {{ twoFactorEnabled ? 'Aktif' : 'Nonaktif' }}
                        </Badge>
                    </div>

                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                        Saat 2FA aktif, Anda akan diminta memasukkan PIN keamanan dari aplikasi autentikator (seperti Google Authenticator) setiap kali masuk.
                    </p>

                    <div v-if="!twoFactorEnabled" class="pt-2">
                        <Button
                            v-if="hasSetupData"
                            @click="showSetupModal = true"
                            class="w-full h-12 rounded-2xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-100 dark:shadow-none"
                        >
                            <ShieldCheckIcon class="w-5 h-5 mr-2" /> Lanjutkan Setup
                        </Button>
                        
                        <Form
                            v-else
                            v-bind="enable.form()"
                            @success="showSetupModal = true"
                            #default="{ processing }"
                        >
                            <Button 
                                type="submit" 
                                :disabled="processing"
                                class="w-full h-12 rounded-2xl bg-slate-900 dark:bg-indigo-600 text-white font-bold transition-all active:scale-95 shadow-lg"
                            >
                                <ShieldCheckIcon class="w-5 h-5 mr-2 text-indigo-400" /> Aktifkan 2FA
                            </Button>
                        </Form>
                    </div>

                    <div v-else class="space-y-6 pt-2">
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                            <TwoFactorRecoveryCodes />
                        </div>

                        <Form v-bind="disable.form()" #default="{ processing }">
                            <Button
                                variant="destructive"
                                type="submit"
                                :disabled="processing"
                                class="w-full h-12 rounded-2xl font-bold bg-red-50 dark:bg-red-900/10 text-red-600 border border-red-100 dark:border-red-900/30 shadow-none hover:bg-red-100 transition-all active:scale-95"
                            >
                                <ShieldExclamationIcon class="w-5 h-5 mr-2" />
                                Matikan Otentikasi 2FA
                            </Button>
                        </Form>
                    </div>
                </div>
            </div>

            <div class="px-2">
                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                    Pastikan Anda menyimpan kode pemulihan (recovery codes) di tempat yang aman jika sewaktu-waktu kehilangan akses ke perangkat autentikator Anda.
                </p>
            </div>
        </div>

        <TwoFactorSetupModal
            v-model:isOpen="showSetupModal"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />
    </UserMobileLayout>
</template>