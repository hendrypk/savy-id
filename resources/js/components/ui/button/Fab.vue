<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '.';
import { PlusIcon } from '@heroicons/vue/24/outline';
import { cn } from '@/lib/utils';

interface Props {
  href?: string;
  icon?: any;
  variant?: "purple" | "default" | "destructive" | "secondary";
  class?: string;
}

const props = withDefaults(defineProps<Props>(), {
  variant: "purple",
  icon: PlusIcon,
});
</script>

<template>
  <div :class="cn('fixed bottom-8 right-6 z-50', props.class)">
    <Link v-if="href" :href="href">
      <div class="relative group">
        <div 
          :class="cn(
            'absolute inset-0 blur-2xl opacity-40 group-active:opacity-20 transition-opacity rounded-full',
            variant === 'purple' ? 'bg-indigo-500' : 'bg-slate-400'
          )"
        ></div>
        
        <Button
          :variant="variant"
          class="relative h-16 w-16 p-0 rounded-full shadow-2xl flex items-center justify-center active:scale-90 transition-all duration-200"
        >
          <component :is="icon" class="w-8 h-8 stroke-[3px]" />
        </Button>
      </div>
    </Link>

    <div v-else class="relative group">
       <div :class="cn('absolute inset-0 blur-2xl opacity-40 rounded-full', variant === 'purple' ? 'bg-indigo-500' : 'bg-slate-400')"></div>
       <Button
          :variant="variant"
          class="relative h-16 w-16 p-0 rounded-full shadow-2xl flex items-center justify-center active:scale-90 transition-all duration-200"
        >
          <component :is="icon" class="w-8 h-8 stroke-[3px]" />
        </Button>
    </div>
  </div>
</template>