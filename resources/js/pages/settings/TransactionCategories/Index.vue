<script setup lang="ts">
import * as HeroIcons from '@heroicons/vue/24/outline';
import { 
    PencilSquareIcon, 
    TrashIcon, 
    TagIcon,
    ArrowDownCircleIcon, 
    ArrowUpCircleIcon 
} from '@heroicons/vue/24/outline';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import Fab from '@/components/ui/button/Fab.vue';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { confirmDelete, mobileToast } from '@/lib/swal';

// 1. Updated Interface to include uuid
interface Category {
    id: number;
    uuid: string;
    name: string;
    type: 'income' | 'expense';
    icon: string;
    color: string;
}

defineProps<{
    categories: Category[];
}>();

// 2. Dynamic Icon Helper
const getIcon = (iconName: string) => {
    return (HeroIcons as any)[iconName] || TagIcon;
};

// 3. Delete using UUID
const deleteCategory = async (uuid: string) => {
    const result = await confirmDelete('Hapus Kategori?');
    if (result.isConfirmed) {
    router.delete(route('transaction-categories.destroy', uuid), {
            onSuccess: () => {
                mobileToast('Kategori berhasil dihapus');
            },
            onFinish: () => {
            },
            preserveScroll: true 
        });
    }
};
</script>

<template>
    <Head title="Kategori Transaksi" />

    <UserMobileLayout
        title="Kategori"
        :back-route="route('settings.index')"
    >
        <div class="space-y-8 pb-20">
            <div class="px-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                    {{ categories.length }} Total Tersimpan
                </p>
            </div>

            <div class="space-y-4">
                <div v-for="category in categories" :key="category.uuid" 
                    class="relative overflow-hidden bg-white dark:bg-slate-900 p-5 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between group active:scale-[0.98] transition-all"
                >
                    <div class="absolute -left-4 -top-4 w-20 h-20 opacity-[0.05] rounded-full" :style="{ backgroundColor: category.color }"></div>

                    <div class="flex items-center gap-4 relative z-10">
                        <div 
                            :style="{ backgroundColor: category.color + '15', color: category.color }" 
                            class="w-14 h-14 rounded-3xl flex items-center justify-center shadow-inner"
                        >
                            <component :is="getIcon(category.icon)" class="w-7 h-7 stroke-[2px]" />
                        </div>
                        
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-base tracking-tight leading-none">
                                {{ category.name }}
                            </h4>
                            
                            <div class="flex items-center gap-1.5">
                                <component 
                                    :is="category.type === 'income' ? ArrowDownCircleIcon : ArrowUpCircleIcon" 
                                    class="w-3 h-3" 
                                    :class="category.type === 'income' ? 'text-emerald-500' : 'text-rose-500'"
                                />
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    {{ category.type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 relative z-10">
                        <Link 
                            :href="route('transaction-categories.edit', category.uuid)" 
                            class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 rounded-2xl transition-all"
                        >
                            <PencilSquareIcon class="w-5 h-5" />
                        </Link>

                        <button 
                            @click="deleteCategory(category.uuid)" 
                            class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-2xl transition-all"
                        >
                            <TrashIcon class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="categories.length === 0" class="py-20 flex flex-col items-center">
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-indigo-100 dark:bg-indigo-900/20 blur-3xl rounded-full"></div>
                    <div class="relative bg-white dark:bg-slate-800 w-24 h-24 rounded-[2.5rem] flex items-center justify-center shadow-xl border border-indigo-50 dark:border-slate-700">
                        <TagIcon class="w-10 h-10 text-indigo-500" />
                    </div>
                </div>
                <h3 class="font-black text-slate-800 dark:text-slate-100 text-lg">Belum Ada Kategori</h3>
                <p class="text-xs text-slate-400 mt-2 max-w-50 text-center font-bold uppercase tracking-wider leading-relaxed">
                    Atur kategori pengeluaranmu agar keuangan lebih tertata.
                </p>
                <Link :href="route('transaction-categories.create')" class="mt-8 block justify-center w-auto">
                    <Button variant="purple" class="rounded-full h-12 shadow-lg shadow-indigo-100 dark:shadow-none">
                        Buat Kategori Baru
                    </Button>
                </Link>
            </div>
        </div>
    <Fab :href="route('transaction-categories.create')" />
    </UserMobileLayout>
</template>