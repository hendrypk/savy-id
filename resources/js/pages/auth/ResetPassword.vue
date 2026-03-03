<script setup lang="ts">
import { Form, Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { readonly, ref } from 'vue'

import { Button } from '@/components/ui/button'
import { Spinner } from '@/components/ui/spinner'
import AuthLayout from '@/layouts/AuthLayout.vue'
import InputGroup from '@/components/ui/input/InputGroup.vue'
import InputError from '@/components/InputError.vue'

import { EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/outline'
import { update } from '@/routes/password'

const props = defineProps<{
  token: string
  email: string
}>()

const inputEmail = ref(props.email)

const form = useForm({
  email: props.email,
  password: '',
  password_confirmation: '',
  token: props.token
})

const submit = () => {
  form.post(route('password.update'))
}
</script>

<template>
  <AuthLayout
    title="Reset password"
    description="Please enter your new password below"
    class="text-indigo-100"
  >
    <Head title="Reset password" />

    <Form :form="form" @submit.prevent="submit" class="space-y-6 px-4 pb-10">
      <!-- Email (readonly) -->
      <InputGroup
        label="Email"
        v-model="inputEmail"
        type="email"
        :readonly="true"
        :icon="EnvelopeIcon"
        :error="form.errors.email"
      />

      <!-- Password -->
      <InputGroup
        label="Password"
        v-model="form.password"
        type="password"
        placeholder="New password"
        autocomplete="new-password"
        autofocus
        :icon="LockClosedIcon"
        :error="form.errors.password"
      />

      <!-- Confirm Password -->
      <InputGroup
        label="Confirm Password"
        v-model="form.password_confirmation"
        type="password"
        placeholder="Confirm new password"
        autocomplete="new-password"
        :icon="LockClosedIcon"
        :error="form.errors.password_confirmation"
      />

      <!-- Submit -->
      <Button
        type="submit"
        variant="purple"
        :disabled="form.processing"
      >
        <Spinner v-if="form.processing" />
        Reset password
      </Button>
    </Form>
  </AuthLayout>
</template>
