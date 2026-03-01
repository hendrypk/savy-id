<script setup lang="ts">
import { ChevronDownIcon, LockClosedIcon } from '@heroicons/vue/24/outline';
import { useForm, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import { 
    DropdownMenu, 
    DropdownMenuTrigger, 
    DropdownMenuContent, 
    DropdownMenuItem 
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { mobileToast } from '@/lib/swal';

const props = defineProps<{ 
    wallet: any, // Wajib ada karena ini halaman edit
    types: { id: string, name: string }[] 
}>();

// 1. Form langsung mengambil data dari props wallet
const form = useForm({
    name: props.wallet.name,
    type: props.wallet.type,
    balance: Number(props.wallet.balance),
    color: props.wallet.color,
});

const activeTypeName = computed(() => {
    return props.types?.find(t => t.id === form.type)?.name || 'Pilih Tipe';
});

const submit = () => {
    form.put(route('wallets.update', props.wallet.uuid), {
        onSuccess: () => mobileToast('Dompet diperbarui! ✅'),
        onError: () => mobileToast('Gagal memperbarui data', 'error'),
    });
};
</script>

<template>
    <Head :title="`Edit ${wallet.name}`" />

    <UserMobileLayout 
        :title="`Edit Dompet ${wallet?.name || ''}`" 
        :back-route="route('wallets.index')"
    >
        <div class="p-6">
            <div class="p-8 bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div class="space-y-2">
                        <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 tracking-widest">Nama Dompet</Label>
                        <Input v-model="form.name" class="rounded-2xl h-12 bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 font-bold text-slate-800 dark:text-white" required />
                    </div>

                    <div class="space-y-2">
                        <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 tracking-widest">Tipe Akun</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="w-full h-12 px-4 rounded-2xl bg-slate-50/50 border border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 flex items-center justify-between text-sm active:scale-[0.98]">
                                    <span class="font-bold">{{ activeTypeName }}</span>
                                    <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-(--reka-dropdown-menu-trigger-width) rounded-2xl p-2 bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 shadow-xl">
                                <DropdownMenuItem v-for="type in types" :key="type.id" @click="form.type = type.id" class="rounded-xl font-bold">
                                    {{ type.name }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <Label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Saldo Saat Ini</Label>
                            <div class="flex items-center gap-1 text-[9px] font-bold text-amber-500 uppercase italic">
                                <LockClosedIcon class="w-3 h-3" /> Terkunci
                            </div>
                        </div>
                        <div class="relative opacity-60">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">Rp</div>
                            <Input 
                                type="number" 
                                v-model="form.balance" 
                                disabled 
                                class="rounded-2xl h-12 pl-10 bg-slate-100 dark:bg-slate-800 border-transparent font-black text-lg cursor-not-allowed text-slate-500" 
                            />
                        </div>
                        <p class="text-[9px] text-slate-400 px-1 italic">*Saldo hanya bisa diubah melalui transaksi.</p>
                    </div>

                    <div class="space-y-2">
                        <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 tracking-widest">Warna Tema</Label>
                        <div class="flex items-center gap-4 p-3 bg-slate-50/50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <div class="relative w-10 h-10 shrink-0">
                                <input type="color" v-model="form.color" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" />
                                <div class="w-full h-full rounded-xl border border-white/20 shadow-sm" :style="{ backgroundColor: form.color }"></div>
                            </div>
                            <span class="text-sm font-mono font-black text-slate-600 dark:text-slate-300 uppercase">{{ form.color }}</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <Button 
                            type="submit"
                            :disabled="form.processing" 
                            class="w-full h-14 rounded-2xl shadow-lg active:scale-95 transition-all font-black uppercase tracking-widest text-[11px] text-white"
                            :style="{ backgroundColor: form.color }"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </UserMobileLayout>
</template>