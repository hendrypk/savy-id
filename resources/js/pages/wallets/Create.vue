<script setup lang="ts">
import { watch, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { mobileToast } from '@/lib/swal';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { route } from 'ziggy-js';

import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';

// 1. Definisikan Props duluan
const props = defineProps<{ 
    wallet?: any,
    types: { id: string, name: string }[] 
}>();

// 2. Definisikan Form setelah Props, tapi sebelum Computed
const form = useForm({
    name: props.wallet?.name ?? '',
    type: props.wallet?.type ?? 'cash',
    balance: props.wallet ? Number(props.wallet.balance) : 0,
    color: props.wallet?.color ?? '#4f46e5',
});

// 3. Computed sekarang aman mengakses 'form' dan 'props.types'
const activeTypeName = computed(() => {
    // Tambahkan optional chaining ?. agar tidak error jika types kosong
    return props.types?.find(t => t.id === form.type)?.name || 'Pilih Tipe';
});

// 4. Sinkronisasi data saat edit (Gunakan form.setData agar reaktifitas terjaga)
watch(() => props.wallet, (newWallet) => {
    if (newWallet) {
        form.name = newWallet.name;
        form.type = newWallet.type;
        form.balance = Number(newWallet.balance);
        form.color = newWallet.color;
    }
}, { immediate: true });

const submit = () => {
    // Cek mode EDIT atau CREATE secara dinamis
    if (props.wallet) {
        form.put(route('wallets.update', props.wallet.uuid), {
            onSuccess: () => mobileToast('Dompet diperbarui! ✅'),
        });
    } else {
        form.post(route('wallets.store'), {
            onSuccess: () => mobileToast('Dompet dibuat! ✨'),
            onError: (errors) => {
                console.error(errors);
                mobileToast('Gagal menyimpan dompet', 'error');
            }
        });
    }
};
</script>

<template>
    <Head :title="wallet ? 'Edit Dompet' : 'Dompet Baru'" />

    <UserMobileLayout 
        :title="wallet ? 'Edit Dompet' : 'Dompet Baru'" 
        :back-route="route('wallets.index')"
    >
        <div class="p-8 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
            <form @submit.prevent="submit" class="space-y-6">
                
                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1">Nama Dompet</Label>
                    <Input v-model="form.name" placeholder="Misal: BCA Tabungan" class="rounded-2xl h-12 bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700" required />
                </div>

                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1">Tipe Akun</Label>
                    
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button 
                                type="button"
                                class="w-full h-12 px-4 rounded-2xl bg-slate-50/50 border border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 flex items-center justify-between text-sm transition-all active:scale-[0.98]"
                            >
                                <span :class="form.type ? 'text-slate-900 dark:text-white font-semibold' : 'text-slate-400'">
                                    {{ activeTypeName }}
                                </span>
                                <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent align="start" class="w-[var(--reka-dropdown-menu-trigger-width)] rounded-2xl p-2 shadow-xl border-slate-100 dark:border-slate-800">
                            <DropdownMenuItem 
                                v-for="type in types" 
                                :key="type.id"
                                :active="form.type === type.id"
                                @click="form.type = type.id"
                            >
                                {{ type.name }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1">Saldo Awal</Label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">Rp</div>
                        <Input type="number" v-model="form.balance" class="rounded-2xl h-12 pl-10 bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700" />
                    </div>
                </div>

                <div class="space-y-2">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1">Warna Tema</Label>
                    <div class="flex items-center gap-4 p-3 bg-slate-50/50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <input type="color" v-model="form.color" class="w-10 h-10 rounded-xl border-none bg-transparent cursor-pointer" />
                        <span class="text-sm font-mono font-bold text-slate-600 dark:text-slate-300 uppercase">{{ form.color }}</span>
                    </div>
                </div>

                <div class="pt-4">
                    <Button :disabled="form.processing" variant="purple" class="w-full h-14 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none active:scale-95 transition-all font-bold">
                        {{ form.processing ? 'Menyimpan...' : (wallet ? 'Simpan Perubahan' : 'Buat Dompet') }}
                    </Button>
                </div>
            </form>
        </div>
    </UserMobileLayout>
</template> 