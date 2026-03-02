<script setup lang="ts">
import { 
    UserIcon, 
    KeyIcon, 
    ShieldCheckIcon, 
    PaintBrushIcon,
    ChevronRightIcon,
    ArrowLeftOnRectangleIcon,
    TagIcon,
} from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const menuItems = [
    {
        title: 'Profil',
        description: 'Ubah nama, email, dan foto profil',
        href: route('profile.edit'), 
        icon: UserIcon,
        color: 'text-blue-600',
        bg: 'bg-blue-50'
    },
    {
        title: 'Kata Sandi',
        description: 'Perbarui keamanan akun Anda',
        href: route('user-password.edit'),
        icon: KeyIcon,
        color: 'text-amber-600',
        bg: 'bg-amber-50'
    },
    {
        title: 'Keamanan (2FA)',
        description: 'Proteksi ganda untuk akun Anda',
        href: route('two-factor.show'),
        icon: ShieldCheckIcon,
        color: 'text-green-600',
        bg: 'bg-green-50'
    },
    {
        title: 'Tampilan',
        description: 'Atur mode terang atau gelap',
        href: route('appearance.edit'),
        icon: PaintBrushIcon,
        color: 'text-purple-600',
        bg: 'bg-purple-50'
    },
    {
        title: 'Transaction Category',
        href: route('transaction-categories.index'),
        icon: TagIcon,
        color: 'text-emerald-600',
        bg: 'bg-emerald-50'
    },
];
</script>

<template>
    <UserMobileLayout 
        title="Pengaturan"
        :back-route="route('dashboard')"
    >
        <div class="space-y-6">
            <div>
                <h3 class="px-1 text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Akun & Keamanan</h3>
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm">
                    <div v-for="(item, index) in menuItems" :key="item.title">
                        <Link 
                            :href="item.href"
                            class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors active:bg-slate-100 dark:active:bg-slate-800"
                            :class="{ 'border-b border-slate-50 dark:border-slate-800': index !== menuItems.length - 1 }"
                        >
                            <div class="flex items-center gap-4">
                                <div :class="['p-2.5 rounded-2xl', item.bg, item.color, 'dark:bg-slate-800']">
                                    <component :is="item.icon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 dark:text-slate-200 text-sm">{{ item.title }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ item.description }}</p>
                                </div>
                            </div>
                            <ChevronRightIcon class="w-4 h-4 text-slate-300 dark:text-slate-600" />
                        </Link>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="px-1 text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Aplikasi</h3>
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm">
                    <Link 
                        href="/logout" 
                        method="post" 
                        as="button"
                        class="w-full flex items-center justify-between p-4 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors group"
                    >
                        <div class="flex items-center gap-4">
                            <div class="p-2.5 rounded-2xl bg-red-50 dark:bg-red-900/20 text-red-600">
                                <ArrowLeftOnRectangleIcon class="w-5 h-5" />
                            </div>
                            <span class="font-bold text-red-600 text-sm">Keluar Aplikasi</span>
                        </div>
                    </Link>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center space-y-6 pt-12 pb-10">
                <div class="flex items-center gap-4">
                    <div class="flex h-15 w-15 items-center justify-center">
                            <img 
                                src="/logo-light.png" 
                                alt="Savy Logo" 
                                class="text-indigo-600 dark:text-indigo-400"
                            >
                    </div>     
                    
                    <div class="h-5 w-[1.5px] bg-slate-200 dark:bg-slate-800"></div>
                    
                    <div class="flex flex-col">
                        <span class="text-[11px] font-black text-slate-800 dark:text-slate-200 uppercase tracking-[0.25em] leading-none">
                            Savy
                        </span>
                        <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">
                            Smart Finance Manager
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.07)]">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                            System Active
                        </span>
                    </div>
                    
                    <div class="w-px h-3 bg-slate-200 dark:bg-slate-700"></div>
                    
                    <span class="text-[10px] font-mono font-black text-indigo-600 dark:text-indigo-400">
                        v{{ $page.props.app_version }}
                    </span>
                </div>

                <p class="text-[9px] font-bold text-slate-300 dark:text-slate-700 uppercase tracking-widest">
                    &copy; 2026 Smart Finance Intelligence
                </p>
            </div>
        </div>
    </UserMobileLayout>
</template>