<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { useClipboard } from '@vueuse/core';
import { CheckIcon, ClipboardDocumentIcon, QrCodeIcon } from '@heroicons/vue/24/outline';
import { computed, nextTick, ref, useTemplateRef, watch } from 'vue';
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import { useAppearance } from '@/composables/useAppearance';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { confirm } from '@/routes/two-factor';
import type { TwoFactorConfigContent } from '@/types';

type Props = {
    requiresConfirmation: boolean;
    twoFactorEnabled: boolean;
};

const { resolvedAppearance } = useAppearance();
const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');

const { copy, copied } = useClipboard();
const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } = useTwoFactorAuth();

const showVerificationStep = ref(false);
const code = ref<string>('');
const pinInputContainerRef = useTemplateRef('pinInputContainerRef');

const modalConfig = computed<TwoFactorConfigContent>(() => {
    if (props.twoFactorEnabled) {
        return {
            title: '2FA Berhasil Aktif!',
            description: 'Otentikasi dua faktor sekarang sudah aktif. Akun Anda jauh lebih aman sekarang.',
            buttonText: 'Selesai',
        };
    }
    if (showVerificationStep.value) {
        return {
            title: 'Verifikasi Kode',
            description: 'Masukkan 6 digit kode dari aplikasi autentikator Anda.',
            buttonText: 'Lanjutkan',
        };
    }
    return {
        title: 'Aktifkan 2FA',
        description: 'Scan QR Code di bawah menggunakan Google Authenticator atau sejenisnya.',
        buttonText: 'Lanjutkan',
    };
});

const handleModalNextStep = () => {
    if (props.requiresConfirmation && !props.twoFactorEnabled) {
        showVerificationStep.value = true;
        nextTick(() => {
            pinInputContainerRef.value?.querySelector('input')?.focus();
        });
        return;
    }
    clearSetupData();
    isOpen.value = false;
};

const resetModalState = () => {
    if (props.twoFactorEnabled) clearSetupData();
    showVerificationStep.value = false;
    code.value = '';
};

watch(() => isOpen.value, async (open) => {
    if (!open) { resetModalState(); return; }
    if (!qrCodeSvg.value) await fetchSetupData();
});
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="max-w-[90vw] sm:max-w-md rounded-[2rem] border-none bg-white dark:bg-slate-900 p-6 shadow-2xl">
            
            <DialogHeader class="flex flex-col items-center">
                <div class="mb-4 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl text-indigo-600">
                    <QrCodeIcon class="w-8 h-8" />
                </div>
                <DialogTitle class="text-xl font-black text-slate-800 dark:text-slate-100 text-center">
                    {{ modalConfig.title }}
                </DialogTitle>
                <DialogDescription class="text-center text-xs font-medium text-slate-400 dark:text-slate-500 px-2 mt-2">
                    {{ modalConfig.description }}
                </DialogDescription>
            </DialogHeader>

            <div class="mt-6 flex flex-col items-center space-y-6">
                <template v-if="!showVerificationStep">
                    <AlertError v-if="errors?.length" :errors="errors" />
                    
                    <template v-else>
                        <div class="relative p-4 bg-white rounded-3xl border-2 border-dashed border-slate-100 dark:border-slate-800">
                            <div v-if="!qrCodeSvg" class="w-48 h-48 flex items-center justify-center bg-slate-50 dark:bg-slate-800 rounded-2xl animate-pulse">
                                <Spinner class="w-6 h-6 text-indigo-500" />
                            </div>
                            <div v-else 
                                 v-html="qrCodeSvg" 
                                 class="w-48 h-48 flex items-center justify-center rounded-xl overflow-hidden"
                                 :style="{ filter: resolvedAppearance === 'dark' ? 'invert(1) brightness(1.2)' : 'none' }"
                            />
                        </div>

                        <div class="w-full space-y-3">
                            <div class="relative flex items-center justify-center">
                                <span class="relative z-10 px-3 bg-white dark:bg-slate-900 text-[10px] font-black uppercase text-slate-300 tracking-widest">Atau input manual</span>
                                <div class="absolute inset-0 top-1/2 h-px bg-slate-100 dark:bg-slate-800"></div>
                            </div>

                            <div class="flex items-center gap-2 p-1.5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <code class="flex-1 px-3 py-2 text-xs font-mono font-bold text-slate-600 dark:text-slate-300 truncate">
                                    {{ manualSetupKey || 'Memuat kunci...' }}
                                </code>
                                <button 
                                    @click="copy(manualSetupKey || '')"
                                    class="p-2.5 rounded-xl bg-white dark:bg-slate-700 text-slate-500 shadow-sm active:scale-90 transition-transform"
                                >
                                    <CheckIcon v-if="copied" class="w-4 h-4 text-green-500" />
                                    <ClipboardDocumentIcon v-else class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <Button class="w-full h-12 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition-all active:scale-95" @click="handleModalNextStep">
                            {{ modalConfig.buttonText }}
                        </Button>
                    </template>
                </template>

                <template v-else>
                    <Form
                        v-bind="confirm.form()"
                        class="w-full space-y-6"
                        @success="isOpen = false"
                        v-slot="{ errors, processing }"
                    >
                        <input type="hidden" name="code" :value="code" />
                        
                        <div ref="pinInputContainerRef" class="flex flex-col items-center space-y-6">
                            <InputOTP v-model="code" :maxlength="6" :disabled="processing">
                                <InputOTPGroup class="gap-2">
                                    <InputOTPSlot 
                                        v-for="index in 6" 
                                        :key="index" 
                                        :index="index - 1" 
                                        class="w-10 h-14 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-lg font-black focus:ring-2 focus:ring-indigo-500 shadow-none transition-all"
                                    />
                                </InputOTPGroup>
                            </InputOTP>
                            
                            <InputError :message="errors?.code" class="text-center font-bold" />

                            <div class="flex w-full gap-3">
                                <Button
                                    type="button"
                                    variant="outline"
                                    class="flex-1 h-12 rounded-2xl border-slate-100 dark:border-slate-800 font-bold"
                                    @click="showVerificationStep = false"
                                    :disabled="processing"
                                >
                                    Kembali
                                </Button>
                                <Button
                                    type="submit"
                                    class="flex-1 h-12 rounded-2xl bg-indigo-600 text-white font-bold active:scale-95 transition-all shadow-lg shadow-indigo-100 dark:shadow-none"
                                    :disabled="processing || code.length < 6"
                                >
                                    {{ processing ? '...' : 'Konfirmasi' }}
                                </Button>
                            </div>
                        </div>
                    </Form>
                </template>
            </div>
        </DialogContent>
    </Dialog>
</template>