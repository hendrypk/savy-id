<script setup lang="ts">
import { 
  HomeIcon as HomeOutline, 
  ChartBarIcon as ChartOutline, 
  CreditCardIcon as CreditOutline, 
  UserIcon as UserOutline,
  BellIcon, 
  BanknotesIcon
} from '@heroicons/vue/24/outline';
import { 
  HomeIcon as HomeSolid, 
  ChartBarIcon as ChartSolid, 
  CreditCardIcon as CreditSolid, 
  UserIcon as UserSolid,
} from '@heroicons/vue/24/solid';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    title?: string;
}>();

const page = usePage();
const currentRoute = computed(() => page.url);

// Anti-crash check untuk user name (seperti yang kita bahas sebelumnya)
const firstName = computed(() => {
    return page.props.auth?.user?.name?.split(' ')[0] || 'User';
});

const isHome = computed(() => currentRoute.value === '/dashboard');
const isBudget = computed(() => currentRoute.value.startsWith('/budget'));
const isLoans = computed(() => currentRoute.value.startsWith('/loans'));
const isSaving = computed(() => currentRoute.value.startsWith('/saving'));
const isProfile = computed(() => currentRoute.value.startsWith('/settings') || currentRoute.value.startsWith('/profile'));

</script>

<template>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex justify-center font-sans antialiased text-slate-900 dark:text-slate-100 transition-colors duration-300">
        
        <div class="w-full max-w-md bg-white dark:bg-slate-900 min-h-screen shadow-2xl shadow-slate-200 dark:shadow-none flex flex-col relative border-x border-slate-100 dark:border-slate-800">
            
            <header class="sticky top-0 z-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-6 py-4 flex justify-between items-center border-b border-slate-50 dark:border-slate-800">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-extrabold">Halo, {{ firstName }}</p>
                    <h1 class="text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ title || 'Ringkasan' }}</h1>
                </div>
                <button class="relative p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-full transition-colors">
                    <BellIcon class="w-6 h-6" />
                    <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                </button>
            </header>

            <main class="flex-1 pb-32 px-5 pt-6 overflow-y-auto overflow-x-hidden">
                <slot />
            </main>

            <nav class="fixed bottom-0 w-full max-w-md bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 px-4 py-3 pb-6 flex justify-between items-end z-30 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] dark:shadow-none">
                
                <Link href="/dashboard" class="flex flex-col items-center flex-1 transition-all"
                    :class="isHome ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">
                    <HomeSolid v-if="isHome" class="w-6 h-6" />
                    <HomeOutline v-else class="w-6 h-6" />
                    <span class="text-[10px] mt-1 font-bold">Home</span>
                </Link>

                <Link href="/budget" class="flex flex-col items-center flex-1 transition-all"
                    :class="isBudget ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">
                    <ChartSolid v-if="isBudget" class="w-6 h-6" />
                    <ChartOutline v-else class="w-6 h-6" />
                    <span class="text-[10px] mt-1 font-bold">Budget</span>
                </Link>


                <Link href="/saving" class="flex flex-col items-center flex-1 transition-all"
                    :class="isSaving ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">
                    <BanknotesIcon v-if="isSaving" class="w-6 h-6" />
                    <BankNotesOutline v-else class="w-6 h-6" />
                    <span class="text-[10px] mt-1 font-bold">Budget</span>
                </Link>

                <Link href="/loans" class="flex flex-col items-center flex-1 transition-all"
                    :class="isLoans ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">
                    <CreditSolid v-if="isLoans" class="w-6 h-6" />
                    <CreditOutline v-else class="w-6 h-6" />
                    <span class="text-[10px] mt-1 font-bold">Loan</span>
                </Link>

                <Link href="/settings" class="flex flex-col items-center flex-1 transition-all"
                    :class="isProfile ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500'">
                    <UserSolid v-if="isProfile" class="w-6 h-6" />
                    <UserOutline v-else class="w-6 h-6" />
                    <span class="text-[10px] mt-1 font-bold">Profil</span>
                </Link>
            </nav>
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