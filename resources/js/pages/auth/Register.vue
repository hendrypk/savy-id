<script setup lang="ts">
import { Form, Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

import TextLink from '@/components/TextLink.vue'
import { Button } from '@/components/ui/button'
import { Spinner } from '@/components/ui/spinner'
import AuthBase from '@/layouts/AuthLayout.vue';
import InputGroup from '@/components/ui/input/InputGroup.vue'

import { TagIcon, PhoneIcon, EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/outline'
import { login } from '@/routes'

const form = useForm({
  name: '',
  phone: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const submit = () => {
  form.post(route('register'))
}

const registerGoogle = () => {
  window.location.href = '/auth/google'
}
</script>

<template>
  <AuthBase 
        title="Create an account"
        description="Enter your details below to create your account"
        class="text-slate-200"
         :back-route="route('login')">

    <Head title="Register" />

    <Form :form="form" @submit.prevent="submit" class="space-y-2 px-4 pb-5">
      <InputGroup
        label="Name"
        v-model="form.name"
        placeholder="Full name"
        :icon="TagIcon"
        :error="form.errors.name"
      />

      <InputGroup
        label="Phone"
        v-model="form.phone"
        placeholder="Phone Number"
        :icon="PhoneIcon"
        :error="form.errors.phone"
      />

      <InputGroup
        label="Email"
        v-model="form.email"
        type="email"
        placeholder="email@example.com"
        autocomplete="email"
        :icon="EnvelopeIcon"
        :error="form.errors.email"
      />

      <InputGroup
        label="Password"
        v-model="form.password"
        type="password"
        placeholder="Password"
        autocomplete="new-password"
        :icon="LockClosedIcon"
        :error="form.errors.password"
      />

      <InputGroup
        label="Confirm Password"
        v-model="form.password_confirmation"
        type="password"
        placeholder="Confirm password"
        autocomplete="new-password"
        :icon="LockClosedIcon"
        :error="form.errors.password_confirmation"
      />

      <Button
        type="submit"
        class="w-full h-12 text-sm rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold"
        :disabled="form.processing"
      >
        <Spinner v-if="form.processing" />
        Create account
      </Button>

      <!-- Divider -->
      <div class="flex items-center gap-2 my-2">
        <div class="flex-1 h-px bg-indigo-400"></div>
        <span class="text-xs text-indigo-300">OR</span>
        <div class="flex-1 h-px bg-indigo-400"></div>
      </div>

      <!-- Register with Google -->
      <Button
        type="button"
        class="w-full h-12 bg-white text-slate-800 border border-indigo-400 hover:bg-indigo-100 flex items-center justify-center rounded-xl"
        @click="registerGoogle"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5 mr-2">
          <path fill="#4285F4" d="M24 9.5c3.5 0 6.6 1.2 9 3.6l6.6-6.6C35.9 2.7 30.3 0 24 0 14.6 0 6.4 5.4 2.5 13.3l7.7 6c2.1-6.1 7.9-9.8 13.8-9.8z"/>
          <path fill="#34A853" d="M46.1 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.6c-.5 2.6-2 4.8-4.2 6.3l6.6 5.1c3.9-3.6 6.1-8.9 6.1-15.9z"/>
          <path fill="#FBBC05" d="M10.2 28.7c-1.1-3.2-1.1-6.6 0-9.8l-7.7-6C.9 16.2 0 20 0 24s.9 7.8 2.5 11.1l7.7-6.4z"/>
          <path fill="#EA4335" d="M24 48c6.3 0 11.6-2.1 15.5-5.7l-6.6-5.1c-2 1.4-4.6 2.3-8.9 2.3-5.9 0-11.7-3.7-13.8-9.8l-7.7 6c3.9 7.9 12.1 13.3 21.5 13.3z"/>
        </svg>
        Register with Google
      </Button>

      <div class="text-center text-xs text-indigo-200">
        Already have an account?
        <TextLink :href="login()" class="text-indigo-300 underline underline-offset-4">Log in</TextLink>
      </div>
    </Form>
  </AuthBase>
</template>
