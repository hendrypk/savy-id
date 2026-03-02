<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'
import { computed } from 'vue'

interface Props {
  label: string
  modelValue: string | number | null
  type?: string
  placeholder?: string
  icon?: any
  error?: string
  prefix?: string
  class?: string
  inputClass?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
})

const emit = defineEmits(['update:modelValue'])

const internalValue = computed({
  get: () => props.modelValue ?? '',
  set: (val: string) => {
    if (props.type === 'number') {
      const parsed = val === '' ? '' : Number(val)
      emit('update:modelValue', parsed)
    } else {
      emit('update:modelValue', val)
    }
  }
})
</script>

<template>
  <div :class="cn('w-full', props.class)">
    <Label class="text-[9px] font-black uppercase text-slate-400 mb-1.5 ml-1 flex items-center gap-1.5 tracking-widest">
      <component :is="icon" v-if="icon" class="w-3 h-3 stroke-[2.5px]" />
      {{ label }}
    </Label>

    <div class="relative flex items-center group">
      <span
        v-if="prefix"
        class="absolute left-4 text-[11px] font-black text-slate-400 group-focus-within:text-indigo-500 transition-colors pointer-events-none uppercase"
      >
        {{ prefix }}
      </span>

      <Input
        :type="type"
        v-model="internalValue"
        :placeholder="placeholder"
        :class="cn(
          'w-full rounded-xl h-11 bg-slate-50/50 dark:bg-slate-900/50 border-slate-100 dark:border-slate-800 font-bold text-sm transition-colors focus:ring-0 focus:border-indigo-500 shadow-none appearance-none',
          prefix ? 'pl-12' : 'pl-4',
          error ? 'border-rose-300 bg-rose-50/20' : '',
          props.inputClass
        )"
      />
    </div>

    <div class="h-4 mt-0.5">
      <Transition name="fade">
        <p v-if="error" class="text-[9px] text-rose-500 font-bold ml-2 italic">
          {{ error }}
        </p>
      </Transition>
    </div>
  </div>
</template>