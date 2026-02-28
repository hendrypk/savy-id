<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';

// Kita tidak butuh breadcrumbItems lagi karena sudah pakai tombol Back manual
</script>

<template>
    <Head title="Pengaturan Tampilan" />

    <UserMobileLayout title="Tampilan">
        <div class="space-y-6">
            <div class="flex items-center gap-4 mb-2">
                <Link 
                    :href="route('settings.index')" 
                    class="p-2 -ml-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 active:scale-90 transition-transform shadow-sm"
                >
                    <ChevronLeftIcon class="w-5 h-5 stroke-[2.5]" />
                </Link>
                <div>
                    <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 tracking-tight">Mode Tampilan</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sesuaikan kenyamanan mata Anda</p>
                </div>
            </div>

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