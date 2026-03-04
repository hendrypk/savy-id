<script setup lang="ts">
import { 
    ChevronDownIcon, BanknotesIcon, CalendarDaysIcon, 
    WalletIcon, Squares2X2Icon, ArrowUpIcon, 
    ArrowDownIcon, LinkIcon, DocumentTextIcon, 
    LockClosedIcon
} from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { route } from 'ziggy-js';

import { Button } from '@/components/ui/button';
import {
    DropdownMenu, DropdownMenuContent,
    DropdownMenuItem, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import InputGroup from '@/components/ui/input/InputGroup.vue';
import Label from '@/components/ui/label/Label.vue';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { mobileToast } from '@/lib/swal';

/**
 * Interface for Component Props
 * Standardizing data structures received from Laravel/Inertia
 */
interface Props {
    wallets: { id: number; name: string; balance: number }[];
    categories: { 
        id: number; 
        name: string;
        type: string;
    }[];
    budgets: { 
        id: number; 
        transaction_category_id: number; 
        name: string; 
        month_year: string;
        remaining: number;
    }[];
    loans: { id: number; name: string; remaining_amount: number }[];
    selectedType: string;
}

const props = defineProps<Props>();

/**
 * Inertia Form State
 */
const form = useForm({
    wallet_id: '',
    transaction_category_id: '',
    amount: '',
    description: '',
    type: props.selectedType,
    transaction_date: new Date().toISOString().split('T')[0],
    ref_category: '',
    budget_allocation_id: '',
    reference_id: '',
});

/**
 * Reset budget connection and unlock category selection
 */
const disconnectBudget = () => {
    form.budget_allocation_id = '';
    form.transaction_category_id = ''; 
    form.description = '';
};

/** * Handles interaction with the locked category field.
 * Triggers an error toast to guide the user on how to unlock it.
 */
const handleLockedCategoryClick = () => {
if (form.budget_allocation_id) {
        // Use the function directly with two arguments
        mobileToast("Disconnect budget to change.", "error");
    }
};
// --- COMPUTED HELPERS ---

/** Returns true if the transaction is an expense */
const isExpense = computed(() => form.type === 'expense');

/** Returns all available budgets without filtering (as requested) */
const allBudgets = computed(() => {
    console.log('Budgets from props:', props.budgets); // Cek console browser Anda
    return props.budgets;
});
/** Resolves the name of the selected wallet */
const activeWallet = computed(() => 
    props.wallets.find(w => w.id === Number(form.wallet_id))?.name || 'Pilih Dompet'
);

/** * Resolves the name of the selected category.
 * Logic uses Number() to ensure type-safe comparison.
 */
const filteredCategories = computed(() => {
    // 1. Tentukan string yang dicari di kolom 'type' pada table categories
    const targetType = isExpense.value ? 'expense' : 'income'; 
    
    // 2. Filter data categories dari props
    return props.categories.filter(cat => {
        // Gunakan toLowerCase() untuk menghindari error case-sensitive
        return cat.type?.toLowerCase() === targetType;
    });
});

const activeCategory = computed(() => {
    const found = props.categories.find(c => Number(c.id) === Number(form.transaction_category_id));
    return found ? found.name : 'Pilih Kategori';
});

// Tambahkan ini agar ketika tipe berubah, kategori yang tidak relevan dihapus
watch(() => form.type, (newType) => {
    form.transaction_category_id = '';
    form.budget_allocation_id = '';
    form.reference_id = '';
    form.ref_category = '';
});
console.log('Semua Kategori:', props.categories);
console.log('Tipe yang dicari:', isExpense.value ? 'expense' : 'income');
/** Resolves label for Budget or Loan reference buttons */
const activeReferenceLabel = computed(() => {
    if (form.ref_category === 'budget') {
        return props.budgets.find(b => b.id === Number(form.budget_allocation_id))?.name || 'Pilih Budget';
    }
    if (form.ref_category === 'loan') {
        return props.loans.find(l => l.id === Number(form.reference_id))?.name || 'Pilih Pinjaman';
    }
    return 'Pilih Referensi';
});

/** Formats numbers to Indonesian Rupiah currency string */
const formatIDR = (val: number) => 
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

// --- WATCHERS ---

/**
 * Auto-selects Category when a Budget is picked.
 * It maps the budget's pre-defined category to the transaction form.
 */
watch(() => form.budget_allocation_id, (newId) => {
    if (newId) {
        const selectedBudget = props.budgets.find(b => b.id === Number(newId));
        if (selectedBudget?.transaction_category_id) {
            form.transaction_category_id = String(selectedBudget.transaction_category_id);
            if (!form.description) form.description = selectedBudget.name;
        }
    }
});

/** Handles form submission */
const submit = () => {
    form.post(route('api.transactions.store'), {
        onSuccess: () => mobileToast('Anggaran berhasil dibuat! 🎯'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            mobileToast(firstError || 'Gagal menyimpan anggaran', 'error');
        },
    });
};
</script>

<template>
    <UserMobileLayout :title="isExpense ? 'Pengeluaran' : 'Pemasukan'">
        <div class="max-w-md mx-auto">
            
            <div class="mt-2 mb-10 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-full border border-slate-100 dark:border-slate-700">
                    <component :is="isExpense ? ArrowUpIcon : ArrowDownIcon" 
                            :class="isExpense ? 'text-rose-500' : 'text-emerald-500'" 
                            class="w-4 h-4 stroke-3" />
                    
                    <span class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight">
                        {{ isExpense ? 'New Expense' : 'New Income' }}
                    </span>
                    
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-3">
                    <InputGroup 
                        label="Nominal Transaksi" 
                        v-model="form.amount" 
                        type="number" 
                        prefix="Rp" 
                        :icon="BanknotesIcon" 
                        :error="form.errors.amount" 
                        
                    />
                </div>

                <div v-if="isExpense" class="p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl border border-slate-100 dark:border-slate-800 space-y-3">
                    <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                        <LinkIcon class="w-3 h-3" /> Hubungkan Ke Budget/Hutang
                    </Label>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <button 
                            v-for="cat in ['budget', 'loan']" :key="cat" 
                            type="button" 
                            @click="form.ref_category = form.ref_category === cat ? '' : cat; form.reference_id = ''; form.budget_allocation_id = ''"
                            :class="form.ref_category === cat ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-md border-transparent' : 'bg-white text-slate-500 dark:bg-slate-800 border-slate-200'"
                            class="h-9 rounded-xl text-[10px] font-black uppercase transition-all flex items-center justify-center border"
                        >
                            {{ cat }}
                        </button>
                    </div>

                    <div v-if="form.ref_category" class="animate-in fade-in zoom-in-95 duration-200">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="w-full h-11 px-4 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-700 flex items-center justify-between text-xs font-bold bg-white dark:bg-slate-900 transition-all active:scale-[0.98]">
                                    <span class="truncate pr-4" :class="form.budget_allocation_id || form.reference_id ? 'text-slate-900 dark:text-white' : 'text-slate-500'">
                                        {{ activeReferenceLabel }}
                                    </span>
                                    
                                    <div v-if="form.budget_allocation_id || form.reference_id" @click.stop="disconnectBudget" class="ml-2 px-2 py-1 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-lg text-[9px] font-black uppercase hover:bg-rose-100 transition-colors border border-rose-100 dark:border-rose-500/20">
                                        Disconnect
                                    </div>
                                    <ChevronDownIcon v-else class="w-4 h-4 text-slate-400 shrink-0" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-[85vw] max-w-100 rounded-xl z-999 p-1 shadow-2xl border-slate-100 dark:border-slate-800">
                                <template v-if="form.ref_category === 'budget'">
                                    <div v-if="allBudgets.length === 0" class="p-6 text-center">
                                        <p class="text-[10px] font-black uppercase text-rose-400">Belum ada budget dibuat</p>
                                    </div>
                                    <DropdownMenuItem 
                                        v-for="item in allBudgets" :key="'b-' + item.id" 
                                        @select="form.budget_allocation_id = item.id.toString()"
                                        class="rounded-lg py-3 px-3 cursor-pointer flex flex-col items-start"
                                    >
                                        <div class="flex justify-between w-full font-bold text-xs">
                                            <span>{{ item.name }}</span>
                                            <span :class="item.remaining < 0 ? 'text-rose-500' : 'text-emerald-600'">{{ formatIDR(item.remaining) }}</span>
                                        </div>
                                        <span class="text-[9px] text-slate-400 uppercase font-black">{{ item.month_year }}</span>
                                    </DropdownMenuItem>
                                </template>

                                <template v-else-if="form.ref_category === 'loan'">
                                    <div v-if="loans.length === 0" class="p-6 text-center">
                                        <p class="text-[10px] font-black uppercase text-rose-400">Tidak ada pinjaman aktif</p>
                                    </div>
                                    <DropdownMenuItem 
                                        v-for="item in loans" :key="'l-' + item.id" 
                                        @select="form.reference_id = item.id.toString()"
                                        class="rounded-lg py-3 px-3 cursor-pointer flex flex-col items-start border-b last:border-0 focus:bg-slate-50 dark:focus:bg-slate-800"
                                    >
                                        <span class="font-bold text-xs dark:text-white">{{ item.name }}</span>
                                        <span class="text-[10px] text-rose-500 font-semibold italic">Sisa: {{ formatIDR(item.remaining_amount) }}</span>
                                    </DropdownMenuItem>
                                </template>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    <div class="space-y-3">
                        <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                            <Squares2X2Icon class="w-3 h-3" /> Kategori
                            <span v-if="form.budget_allocation_id" class="text-[8px] bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-500 px-1.5 py-0.5 rounded font-bold border border-amber-200 dark:border-amber-500/20">
                                LOCKED BY BUDGET
                            </span>
                        </Label>
                        <div @click="handleLockedCategoryClick">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child :disabled="!!form.budget_allocation_id">
                                <button 
                                    type="button" 
                                    :class="[
                                        'w-full h-11 px-4 rounded-xl border flex items-center justify-between text-sm font-semibold transition-all',
                                        'bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700',
                                        form.budget_allocation_id ? 'cursor-not-allowed opacity-80 pointer-events-none' : 'active:scale-[0.98]'
                                    ]"
                                >
                                    <span :class="form.transaction_category_id ? 'text-slate-900 dark:text-white' : 'text-slate-400'">
                                        {{ activeCategory }}
                                    </span>
                                    <ChevronDownIcon v-if="!form.budget_allocation_id" class="w-4 h-4 text-slate-400 shrink-0" />
                                    <LockClosedIcon v-else class="w-4 h-4 text-emerald-500 shrink-0" /> 
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-[85vw] max-w-100 rounded-xl z-999 p-1 shadow-xl border-slate-100 dark:border-slate-800">
                                <DropdownMenuItem 
                                    v-for="cat in filteredCategories" :key="cat.id" 
                                    @select="form.transaction_category_id = cat.id.toString()"
                                    class="rounded-lg py-2.5 px-3 font-semibold text-xs cursor-pointer focus:bg-slate-100 dark:focus:bg-slate-800 dark:text-slate-200"
                                >
                                    {{ cat.name }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <p v-if="form.errors.transaction_category_id" class="text-[10px] text-rose-500 font-bold ml-1 italic">{{ form.errors.transaction_category_id }}</p>
                    </div>
                        </div>


                    <div class="space-y-3">
                        <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                            <WalletIcon class="w-3 h-3" /> Sumber Dana
                        </Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="w-full h-11 px-4 rounded-xl border bg-slate-50/50 border-slate-100 dark:bg-slate-800/50 dark:border-slate-700 flex items-center justify-between text-sm font-semibold active:scale-[0.98] transition-all">
                                    <span :class="form.wallet_id ? 'text-slate-900 dark:text-white' : 'text-slate-400'">{{ activeWallet }}</span>
                                    <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-[85vw] max-w-100 rounded-xl z-999 p-1 shadow-xl border-slate-100 dark:border-slate-800">
                                <DropdownMenuItem 
                                    v-for="w in wallets" :key="w.id" 
                                    @select="form.wallet_id = w.id.toString()"
                                    class="rounded-lg py-2.5 px-3 font-semibold text-xs cursor-pointer focus:bg-slate-100 dark:focus:bg-slate-800"
                                >
                                    <div class="flex justify-between w-full items-center">
                                        <span class="dark:text-slate-200">{{ w.name }}</span>
                                        <span class="text-[10px] opacity-60 italic">{{ formatIDR(w.balance) }}</span>
                                    </div>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>

                <InputGroup label="Keterangan" v-model="form.description" :icon="DocumentTextIcon" placeholder="Makan siang bakso..." />
                <InputGroup label="Tanggal" v-model="form.transaction_date" type="date" :icon="CalendarDaysIcon" />

                <div class="pt-4">
                    <Button 
                        :disabled="form.processing" 
                        :variant="isExpense ? 'destructive' : 'emerald'"
                        class="text-bold"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Transaction' }}
                    </Button>
                </div>
            </form>
        </div>
    </UserMobileLayout>
</template>