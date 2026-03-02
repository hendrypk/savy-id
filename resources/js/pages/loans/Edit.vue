<script setup lang="ts">
import { 
  BanknotesIcon, 
  InformationCircleIcon,
  ChevronDownIcon,
} from '@heroicons/vue/24/outline';
import { useForm, Head, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Button from '@/components/ui/button/Button.vue';
import { 
    DropdownMenu, 
    DropdownMenuTrigger, 
    DropdownMenuContent, 
    DropdownMenuItem 
} from '@/components/ui/dropdown-menu';
import InputGroup from '@/components/ui/input/InputGroup.vue';
import Label from '@/components/ui/label/Label.vue';
import UserMobileLayout from '@/layouts/UserMobileLayout.vue';
import { confirmDelete, mobileToast } from '@/lib/swal';

const props = defineProps<{
  loan: any;
}>();

const loanTypes = [
    { id: 'bank', name: 'Bank / KPR' },
    { id: 'credit_card', name: 'Kartu Kredit' },
    { id: 'pinjol', name: 'Pinjaman Online' },
    { id: 'personal', name: 'Personal / Teman' },
    { id: 'other', name: 'Lainnya' },
];

const form = useForm({
  name: props.loan.name,
  loan_type: props.loan.loan_type,
  credit_limit: props.loan.credit_limit,
  remaining_amount: props.loan.remaining_amount,
  monthly_installment: props.loan.monthly_installment,
  total_tenor: props.loan.total_tenor,
  due_date: props.loan.due_date,
  status: props.loan.status,
});

const submit = () => {
  form.put(route('loans.update', props.loan.uuid), {
    preserveScroll: true,
    onSuccess: () => mobileToast('Pinjaman berhasil diubah!'),
        onError: (errors) => {
            console.error(errors);
            mobileToast('Gagal mengubah pinjaman!', 'error');
        }
  });
};

const deleteLoan = async (uuid: string) => {
    const result = await confirmDelete('Hapus Pinjaman?');
    if (result.isConfirmed) {
    router.delete(route('loans.destroy', uuid), {
            onSuccess: () => {
                mobileToast('Pinjaman berhasil dihapus');
            },
            onFinish: () => {
            },
            preserveScroll: true 
        });
    }
};
</script>

<template>
  <Head :title="'Edit ' + loan.name" />

  <UserMobileLayout :title="'Edit Pinjaman'" :back-route="route('loans.show', loan.uuid)">
    <form @submit.prevent="submit" class="space-y-8 pb-32 pt-2">
      
      <div class="space-y-4">
        <label class="px-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 flex items-center gap-2">
          <InformationCircleIcon class="w-3 h-3" /> Informasi Dasar
        </label>
        
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 space-y-5 shadow-sm">
            <div class="space-y-1.5">
                <InputGroup 
                label="Nama Pinjaman"
                    v-model="form.name"
                    type="text" 
                    placeholder="ex: KPR, Kartu Kredit BCA"
                />
            </div>

            <div class="space-y-1.5">
                <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                    Type
                </Label>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button type="button" class="w-full h-14 px-5 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none flex items-center justify-between text-sm font-bold active:scale-[0.98] transition-all">
                            {{ loanTypes.find(t => t.id === form.loan_type)?.name || 'Select Type' }}
                            <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-[90vw] rounded-2xl p-2 shadow-2xl border-none dark:bg-slate-800">
                        <DropdownMenuItem 
                            v-for="type in loanTypes" 
                            :key="type.id" 
                            @click="form.loan_type = type.id" 
                            class="rounded-xl py-3 px-4 font-bold text-xs cursor-pointer focus:bg-indigo-50 dark:focus:bg-indigo-500/10 focus:text-indigo-600"
                        >
                            {{ type.name }}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>
      </div>

      <div class="space-y-4">
        <label class="px-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 flex items-center gap-2">
          <BanknotesIcon class="w-3 h-3" /> Detail Finansial
        </label>

        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 space-y-5 shadow-sm">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <InputGroup 
                label="Sisa Hutang"
                v-model="form.remaining_amount"
                type="number" 
              />
            </div>
            <div class="space-y-1.5">
              <InputGroup 
                label="Cicilan / Bln"
                v-model="form.monthly_installment"
                type="number" 
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <InputGroup 
                label="Tenor"
                v-model="form.total_tenor"
                type="number" 
              />
            </div>
            <div class="space-y-1.5">
              <InputGroup 
                label="Jatuh Tempo"
                v-model="form.due_date"
                type="number" 
                min="1" max="31"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="px-1 pt-4">
        <Button 
          type="submit"
          variant="purple"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Menyimpan...' : 'Update Pinjaman' }}
        </Button>
        
        <Button 
          type="button"
          variant="ghost_red"
          @click="deleteLoan(loan.uuid)"
        >
          Hapus Pinjaman Ini
        </Button>
      </div>

    </form>
  </UserMobileLayout>
</template>