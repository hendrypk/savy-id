<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';

// Kita tidak butuh breadcrumbItems lagi karena sudah pakai tombol Back manual
</script>

<template>
    <Head title="Pengaturan Tampilan" />

    <UserMobileLayout 
        title="Tampilan"
        :back-route="route('settings.index')"
    >
        <div class="space-y-6">

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="space-y-4">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 leading-relaxed">
                        Pilih tema yang paling sesuai untuk aktivitas keuangan Anda. Mode gelap dapat membantu menghemat baterai pada layar OLED.
                    </p>
                    
                    <AppearanceTabs />
                </div>
            </div>

            <div class="p-4 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl border border-indigo-100/50 dark:border-indigo-900/20">
                <p class="text-[11px] text-indigo-600 dark:text-indigo-400 font-medium leading-relaxed">
                    Tip: Pilih <strong>Sistem</strong> agar aplikasi otomatis mengikuti jadwal mode malam di perangkat Anda.
                </p>
            </div>
        </div>
    </UserMobileLayout>
</template>

<style scoped>
/* Transisi halus saat pindah mode */
.space-y-6 {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>