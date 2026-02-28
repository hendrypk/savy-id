<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { 
    UserIcon, 
    KeyIcon, 
    ShieldCheckIcon, 
    PaintBrushIcon,
    ChevronRightIcon,
    ArrowLeftOnRectangleIcon
} from '@heroicons/vue/24/outline';
import { route } from 'ziggy-js';

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
];
</script>

<template>
    <UserMobileLayout title="Pengaturan">
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

            <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] pt-4">
                Versi 1.0.2 • AnggaranKu
            </p>
        </div>
    </UserMobileLayout>
</template>