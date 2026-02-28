<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { 
    UserIcon, 
    KeyIcon, 
    ShieldCheckIcon, 
    PaintBrushIcon,
    ChevronRightIcon 
} from '@heroicons/vue/24/outline';
import { route } from 'ziggy-js';

// Kita gunakan hardcoded URL atau helper yang sudah kamu punya
// sesuai keinginanmu untuk menghindari crash Ziggy
const sidebarNavItems = [
    {
        title: 'Profile',
        href: route('profile.edit'), 
        icon: UserIcon,
        color: 'text-blue-600',
        bg: 'bg-blue-50'
    },
    {
        title: 'Password',
        href: route('user-password.edit'),
        icon: KeyIcon,
        color: 'text-amber-600',
        bg: 'bg-amber-50'
    },
    {
        title: 'Two-factor auth',
        href: route('two-factor.show'),
        icon: ShieldCheckIcon,
        color: 'text-green-600',
        bg: 'bg-green-50'
    },
    {
        title: 'Appearance',
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
            <div class="px-1">
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Akun & Keamanan</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Kelola preferensi profil Anda</p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                <div v-for="(item, index) in sidebarNavItems" :key="item.title">
                    <Link 
                        :href="item.href"
                        class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors active:bg-slate-100"
                        :class="{ 'border-b border-slate-50': index !== sidebarNavItems.length - 1 }"
                    >
                        <div class="flex items-center gap-4">
                            <div :class="['p-2.5 rounded-xl', item.bg, item.color]">
                                <component :is="item.icon" class="w-5 h-5" />
                            </div>
                            
                            <span class="font-bold text-slate-700 text-sm">{{ item.title }}</span>
                        </div>
                        
                        <ChevronRightIcon class="w-4 h-4 text-slate-300" />
                    </Link>
                </div>
            </div>

            <div v-if="$slots.default" class="mt-8 pt-6 border-t border-dashed border-slate-200">
                <slot />
            </div>

            <div class="px-4 py-2">
                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                    Beberapa perubahan mungkin memerlukan verifikasi ulang demi keamanan akun Anda.
                </p>
            </div>
        </div>
    </UserMobileLayout>
</template>

<style scoped>
/* Tambahkan sedikit efek transisi saat ditekan */
.active\:scale-95:active {
    transform: scale(0.98);
}
</style>