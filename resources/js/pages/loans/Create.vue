<script setup lang="ts">
import {
  BanknotesIcon,
  CreditCardIcon,
  CalendarDaysIcon,
  TagIcon,
  PercentBadgeIcon,
  CheckIcon,
  ChevronDownIcon
} from '@heroicons/vue/24/outline'

import { Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { ref, computed, watch } from 'vue'

import UserMobileLayout from '@/layouts/UserMobileLayout.vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue'
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue'
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue'
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue'
import InputGroup from '@/components/ui/input/InputGroup.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import { mobileToast } from '@/lib/swal'

/* ===============================
   STATIC DATA
================================= */

const loanTypes = [
  { id: 'credit_card', name: '💳 Kartu Kredit' },
  { id: 'personal', name: '👥 Pinjaman Personal' },
  { id: 'pinjol', name: '📱 Pinjol / Paylater' },
  { id: 'kasbon_kantor', name: '🏢 Kasbon Kantor' },
  { id: 'other', name: '📁 Lainnya' }
]

/* ===============================
   INERTIA FORM (ONLY RAW DATA)
================================= */

const form = useForm({
  name: '',
  loan_type: 'credit_card',
  credit_limit: 0,
  monthly_installment: 0,
  tenor: 0,
  due_date: 0
})

/* ===============================
   LOCAL STATE (NO LAG ZONE)
================================= */

const creditLimit = ref<number | ''>('')
const installment = ref<number | ''>('')
const tenor = ref<number | ''>('')

const noMonthlyInstallment = ref(false)
const lastEdited = ref<'installment' | 'tenor' | null>(null)

/* ===============================
   CALCULATION (LIGHTWEIGHT)
================================= */

const calculateFromInstallment = () => {
  if (!creditLimit.value || !installment.value) return
  if (installment.value <= 0) return

  tenor.value = Math.ceil(
    Number(creditLimit.value) / Number(installment.value)
  )
}

const calculateFromTenor = () => {
  if (!creditLimit.value || !tenor.value) return
  if (tenor.value <= 0) return

  installment.value = Math.round(
    Number(creditLimit.value) / Number(tenor.value)
  )
}

/* Only react when needed */
watch(creditLimit, () => {
  if (lastEdited.value === 'installment') calculateFromInstallment()
  if (lastEdited.value === 'tenor') calculateFromTenor()
})

watch(noMonthlyInstallment, (val) => {
  if (val) {
    installment.value = 0
    tenor.value = 0
  }
})

/* ===============================
   SUBMIT
================================= */

const submit = () => {
  form.credit_limit = Number(creditLimit.value) || 0
  form.monthly_installment = Number(installment.value) || 0
  form.tenor = Number(tenor.value) || 0

  form.post(route('loans.store'), {
    preserveScroll: true,
        onSuccess: () => {
            mobileToast('Pinjaman berhasil disimpan!');
            creditLimit.value = '';
            installment.value = '';
            tenor.value = '';
        },
        onError: (errors) => {
            console.error(errors);
            mobileToast('Gagal menyimpan pinjaman!', 'error');
        }
  })
}
</script>

<template>
    <Head title="Tambah Akun Kredit" />

    <UserMobileLayout title="Tambah Kredit" :back-route="route('loans.index')">
        <Card>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-4 px-1 pb-10">
                    <InputGroup
                        label="Account Name"
                        v-model="form.name"
                        placeholder="e.g. BCA Card"
                        :icon="TagIcon"
                        :error="form.errors.name"
                    />

                    <div class="space-y-1.5">
                        <Label class="text-[10px] font-black uppercase text-slate-400 ml-1 flex items-center gap-2">
                            <CreditCardIcon class="w-3 h-3" /> Type
                        </Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="w-full h-11 px-4 rounded-xl bg-slate-50/50 border border-slate-100 dark:bg-slate-800/50 flex items-center justify-between text-sm font-semibold active:scale-[0.98]">
                                    {{ loanTypes.find(t => t.id === form.loan_type)?.name || 'Select' }}
                                    <ChevronDownIcon class="w-4 h-4 text-slate-400" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-[90vw] rounded-xl p-1 shadow-xl border-slate-100 dark:bg-slate-900">
                                <DropdownMenuItem v-for="type in loanTypes" :key="type.id" @click="form.loan_type = type.id" class="rounded-lg py-2.5 px-3 font-semibold text-xs cursor-pointer">
                                    {{ type.name }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <InputGroup
                        label="Total Debt"
                        v-model="creditLimit"
                        type="number"
                        prefix="Rp"
                        :icon="BanknotesIcon"
                    />

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 transition-colors">
                        <label for="no-limit" class="text-[10px] font-black text-slate-500 uppercase tracking-tight">
                            Flexible Mode (No Installment)
                        </label>
                        <div class="relative flex items-center">
                            <input type="checkbox" id="no-limit" v-model="noMonthlyInstallment"
                                class="w-5 h-5 rounded-md border-2 border-slate-200 text-indigo-600 appearance-none checked:bg-indigo-600 transition-all cursor-pointer">
                            <CheckIcon v-if="noMonthlyInstallment" class="w-3 h-3 text-white absolute left-1 pointer-events-none" />
                        </div>
                    </div>

                    <Transition name="fade">
                        <div v-if="!noMonthlyInstallment" class="space-y-4">
                            <InputGroup
                                label="Installment / Month"
                                v-model="installment"
                                type="number"
                                prefix="Rp"
                                :icon="PercentBadgeIcon"
                                @update:modelValue="() => {
                                    lastEdited = 'installment'
                                    calculateFromInstallment()
                                }"
                            />

                            <InputGroup
                                label="Tenor (Months)"
                                v-model="tenor"
                                type="number"
                                prefix="Mo"
                                :icon="CalendarDaysIcon"
                                @update:modelValue="() => {
                                    lastEdited = 'tenor'
                                    calculateFromTenor()
                                }"
                            />
                        </div>
                    </Transition>

                    <InputGroup
                        label="Due Date Day (1-31)"
                        v-model.number="form.due_date"
                        type="number"
                        inputmode="numeric"
                        prefix="Day"
                        placeholder="15"
                        :icon="CalendarDaysIcon"
                        :error="form.errors.due_date"
                    />

                    <div class="pt-2">
                        <Button type="submit" :disabled="form.processing" variant="purple" 
                            class="w-full h-12 text-sm rounded-xl shadow-lg font-black uppercase tracking-widest">
                            {{ form.processing ? '...' : 'Save Loan Account' }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </UserMobileLayout>
</template>

<style scoped>
/* Hilangkan arrow up/down di input type number */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>