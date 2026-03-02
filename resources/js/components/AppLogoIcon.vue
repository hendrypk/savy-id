<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img 
        src="/landscape-dark.png" 
        alt="Savy Logo" 
        :class="className"
    >
</template>
