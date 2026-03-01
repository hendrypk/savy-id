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

// 1. Definisikan State Aktif
const isHome = computed(() => route().current('dashboard'));
const isBudget = computed(() => route().current('budget.*'));
const isWallet = computed(() => route().current('wallets.*'));
const isLoans = computed(() => route().current('loans.*'));
const isProfile = computed(() => route().current('settings.*') || route().current('profile.*'));
const showNav = computed(() => {
    return route().current('dashboard') || 
           route().current('budget.index') || 
           route().current('wallets.index') || 
           route().current('loans.index') || 
           route().current('settings.index');
});

// Helper class untuk animasi active
const activeClass = "text-indigo-600 dark:text-indigo-400 scale-110";
const inactiveClass = "text-slate-400 dark:text-slate-500 hover:text-slate-600";
</script>

<template>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex justify-center font-sans antialiased text-slate-900 dark:text-slate-100 transition-colors duration-300">
        
        <div class="w-full max-w-md bg-white dark:bg-slate-900 min-h-screen shadow-2xl shadow-slate-200 dark:shadow-none flex flex-col relative border-x border-slate-100 dark:border-slate-800">

            <MobileHeader 
                :title="title" 
                :firstName="firstName" 
                :isHome="isHome" 
                :showNav="showNav" 
                :backRoute="backRoute"
            />

            <main :class="['flex-1 px-5 pt-6 overflow-y-auto overflow-x-hidden', showNav ? 'pb-32' : 'pb-10']">
                <slot />
            </main>

            <MobileNav 
                v-if="showNav"
                :isHome="isHome" 
                :isBudget="isBudget" 
                :isWallet="isWallet" 
                :isLoans="isLoans"
                :isProfile="isProfile"
                :activeClass="activeClass" 
                :inactiveClass="inactiveClass"
            />

        </div>
    </div>
</template>

<style scoped>
main::-webkit-scrollbar {
    display: none;
}
main {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>