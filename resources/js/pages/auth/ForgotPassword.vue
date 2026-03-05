<script setup lang="ts">
import { EnvelopeIcon } from '@heroicons/vue/24/outline'
import { Form, Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

import TextLink from '@/components/TextLink.vue'
import { Button } from '@/components/ui/button'
import InputGroup from '@/components/ui/input/InputGroup.vue'
import { Spinner } from '@/components/ui/spinner'
import AuthLayout from '@/layouts/AuthLayout.vue'

import { login } from '@/routes'

defineProps<{
  status?: string
}>()

const form = useForm({
  email: ''
})

const submit = () => {
  form.post(route('password.email'))
}
</script>

<template>
  <AuthLayout
    title="Forgot password"
    description="Enter your email to receive a password reset link"
    class="text-indigo-100"
  >
    <Head title="Forgot password" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-500">
      {{ status }}
    </div>

    <Form :form="form" @submit.prevent="submit" class="space-y-6 px-4 pb-10">
      <InputGroup
        label="Email address"
        v-model="form.email"
        type="email"
        placeholder="email@example.com"
        autocomplete="off"
        autofocus
        :icon="EnvelopeIcon"
        :error="form.errors.email"
      />

      <Button
        type="submit"
        variant="purple"
        :disabled="form.processing"
      >
        <Spinner v-if="form.processing" />
        Email password reset link
      </Button>

        <div class="flex items-center gap-2 my-2">
          <div class="flex-1 h-px bg-slate-600"></div>
          <span class="text-xs text-slate-300">OR</span>
          <div class="flex-1 h-px bg-slate-600"></div>
        </div>

      <div class="text-center text-sm text-indigo-200">
        <span>Return to </span>
        <TextLink :href="login()" class="text-indigo-300 underline underline-offset-4">Log in</TextLink>
      </div>
    </Form>
  </AuthLayout>
</template>
