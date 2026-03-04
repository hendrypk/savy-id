<script setup lang="ts">
import { 
    BanknotesIcon, CalendarDaysIcon, 
    WalletIcon, Squares2X2Icon, ArrowUpIcon, 
    ArrowDownIcon, DocumentTextIcon, 
} from '@heroicons/vue/24/outline';
import { useForm, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

import { Button } from '@/components/ui/button';
import InputGroup from '@/components/ui/input/InputGroup.vue';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { confirmDelete, mobileToast } from '@/lib/swal';

interface Props {
    transaction: {
        uuid: string;
        id: number;
        wallet_id: number;
        transaction_category_id: number;
        amount: number;
        description: string;
        type: string;
        transaction_date: string;
    };
    wallets: { id: number; name: string; balance: number }[];
    categories: { id: number; name: string; type: string; }[];
}

const props = defineProps<Props>();

/**
 * Inertia Form State
 * Only binding amount and description as editable fields
 */
const form = useForm({
    amount: props.transaction.amount.toString(),
    description: props.transaction.description || '',
});

// --- COMPUTED HELPERS ---

const isExpense = computed(() => props.transaction.type === 'expense' || props.transaction.type === 'outflow');

const activeWallet = computed(() => 
    props.wallets.find(w => w.id === props.transaction.wallet_id)?.name || 'Unknown Wallet'
);

const activeCategory = computed(() => {
    const found = props.categories.find(c => c.id === props.transaction.transaction_category_id);
    return found ? found.name : 'Unknown Category';
});

/** Update Transaction */
const submit = () => {
    form.put(route('api.transactions.update', props.transaction.id), {
        onSuccess: () => mobileToast('Transaksi diperbarui! ✨'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            mobileToast(firstError || 'Gagal memperbarui transaksi', 'error');
        },
    });
};

const deleteTrx = async (uuid: string) => {
    const result = await confirmDelete('Hapus Transaksi?');
    if (result.isConfirmed) {
    router.delete(route('api.transactions.destroy', props.transaction.uuid), {
            onSuccess: () => {
                mobileToast('Transaksi berhasil dihapus');
            },
            onFinish: () => {
            },
            preserveScroll: true 
        });
    }
};

</script>

<template>
    <UserMobileLayout title="Edit Transaksi">
        <div class="max-w-md mx-auto pb-10 px-4">
            
            <div class="mt-4 mb-8 text-center">
                <div class="inline-flex flex-col items-center gap-1">
                    <div :class="isExpense ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'" 
                         class="p-3 rounded-2xl mb-2">
                        <component :is="isExpense ? ArrowUpIcon : ArrowDownIcon" class="w-6 h-6 stroke-2" />
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Transaction ID #{{ transaction.id }}
                    </span>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                
                <div class="space-y-2">
                    <InputGroup 
                        label="Nominal Transaksi" 
                        v-model="form.amount" 
                        type="number" 
                        prefix="Rp" 
                        :icon="BanknotesIcon" 
                        :error="form.errors.amount" 
                    />
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center gap-4">
                        <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm">
                            <CalendarDaysIcon class="w-5 h-5 text-slate-400" />
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">Tanggal</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ transaction.transaction_date }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center gap-3">
                            <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm shrink-0">
                                <WalletIcon class="w-4 h-4 text-slate-400" />
                            </div>
                            <div class="truncate">
                                <p class="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">Dompet</p>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate">{{ activeWallet }}</p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center gap-3">
                            <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm shrink-0">
                                <Squares2X2Icon class="w-4 h-4 text-slate-400" />
                            </div>
                            <div class="truncate">
                                <p class="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">Kategori</p>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate">{{ activeCategory }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <InputGroup 
                        label="Keterangan" 
                        v-model="form.description" 
                        :icon="DocumentTextIcon" 
                        placeholder="Makan siang..." 
                        :error="form.errors.description"
                    />
                </div>

                <div class="pt-6 space-y-3">
                    <Button 
                        variant="purple"
                        type="submit"
                        :disabled="form.processing" 
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </Button>
                    <Button
                        variant="soft_rose" 
                        type="button"
                        @click="deleteTrx"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Mohon Tunggu...' : 'Hapus Transaksi' }}
                    </button>
                </div>
            </form>
        </div>
    </UserMobileLayout>
</template>