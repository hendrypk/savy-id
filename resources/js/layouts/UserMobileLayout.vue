<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import MobileHeader from './app/MobileHeader.vue';
import MobileNav from './app/MobileNav.vue';

defineProps<{
    title?: string;
    backRoute?: string;
}>();

const page = usePage();
const firstName = computed(() => page.props.auth?.user?.name?.split(' ')[0] || 'User');

// 1. Definisikan State Aktif sesuai menu baru
const isHome = computed(() => route().current('dashboard'));
const isBudget = computed(() => route().current('budget.*'));
const isWallet = computed(() => route().current('wallets.*'));
const isSavings = computed(() => route().current('savings.*') || route().current('goals.*')); // Tombol Tengah
const isLoans = computed(() => route().current('loans.*')); // Pengganti Setting

// Logika kapan navigasi bawah muncul
const showNav = computed(() => {
    return route().current('dashboard') || 
           route().current('budget.index') || 
           route().current('wallets.index') || 
           route().current('loans.index') || 
           route().current('savings.index') ||
           route().current('wealth.index');
});

// Helper class untuk animasi active
const activeClass = "text-indigo-600 dark:text-indigo-400 scale-110";
const inactiveClass = "text-slate-400 dark:text-slate-500 hover:text-slate-600";
</script>

<template>
    <div class="fixed inset-0 bg-white dark:bg-slate-900 -z-10"></div>
    <div class="min-h-screen w-full font-sans antialiased text-slate-900 dark:text-slate-100 relative">
        <div class="flex flex-col min-h-screen w-full relative">
            <MobileHeader 
                :title="title" 
                :firstName="firstName" 
                :isHome="isHome" 
                :showNav="showNav" 
                :backRoute="backRoute"
                class="w-full"
            />

            <main :class="['flex-1 px-5 pt-6 overflow-y-auto overflow-x-hidden w-full', showNav ? 'pb-32' : 'pb-10']">
                <slot />
            </main>

            <MobileNav 
                v-if="showNav"
                :isHome="isHome" 
                :isBudget="isBudget" 
                :isWallet="isWallet" 
                :isSavings="isSavings"
                :isLoans="isLoans"
                :activeClass="activeClass" 
                :inactiveClass="inactiveClass"
                class="w-full fixed bottom-0 left-0"
            />

        </div>
    </div>
</template>