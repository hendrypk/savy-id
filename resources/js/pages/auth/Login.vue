<script setup lang="ts">
import { Form, Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import InputGroup from '@/components/ui/input/InputGroup.vue'
import Label from '@/components/ui/label/Label.vue'
import { Spinner } from '@/components/ui/spinner'
import AuthBase from '@/layouts/AuthLayout.vue'
import { register } from '@/routes'
import { request } from '@/routes/password'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'))
}

defineProps<{
  status?: string
  canResetPassword: boolean
  canRegister: boolean
}>()

const loginGoogle = () => {
  window.location.href = '/auth/google'
}
</script>

<template>
  <AuthBase
    title="Login to Your Finance Manager"
    description="Enter your credentials or use Google"
    class="text-indigo-100"
  >
    <Head title="Login" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-500">
      {{ status }}
    </div>

    <Form :form="form" @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">
        <!-- Email -->
        <InputGroup
          label="Email"
          v-model="form.email"
          type="email"
          name="email"
          required
          autofocus
          autocomplete="email"
          placeholder="you@example.com"
          :error="form.errors.email"
        />

        <!-- Password -->
        <div class="grid gap-2">
            <div class="flex items-center justify-between">
            <Label for="password" class="text-slate-300">Password</Label>
            <TextLink v-if="canResetPassword" :href="request()" class="text-sm text-slate-300">
                Forgot password?
            </TextLink>
            </div>

            <InputGroup
            label=""
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            :error="form.errors.password"
            />

        </div>

        <!-- Remember me -->
        <div class="flex items-center justify-between">
          <label for="remember" class="flex items-center space-x-2 text-indigo-200">
            <Checkbox id="remember" name="remember" v-model="form.remember" />
            <span>Remember me</span>
          </label>
        </div>

        <!-- Submit -->
        <Button
          type="submit"
          variant="purple"
          :disabled="form.processing"
        >
          <Spinner v-if="form.processing" />
          Log in
        </Button>

        <!-- Divider -->
        <div class="flex items-center gap-2 my-2">
          <div class="flex-1 h-px bg-slate-600"></div>
          <span class="text-xs text-slate-300">OR</span>
          <div class="flex-1 h-px bg-slate-600"></div>
        </div>

        <!-- Google Login -->
        <Button
          type="button"
          class="w-full bg-white text-slate-800 border flex items-center justify-center"
          @click="loginGoogle"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5 mr-2">
            <path fill="#4285F4" d="M24 9.5c3.5 0 6.6 1.2 9 3.6l6.6-6.6C35.9 2.7 30.3 0 24 0 14.6 0 6.4 5.4 2.5 13.3l7.7 6c2.1-6.1 7.9-9.8 13.8-9.8z"/>
            <path fill="#34A853" d="M46.1 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.6c-.5 2.6-2 4.8-4.2 6.3l6.6 5.1c3.9-3.6 6.1-8.9 6.1-15.9z"/>
            <path fill="#FBBC05" d="M10.2 28.7c-1.1-3.2-1.1-6.6 0-9.8l-7.7-6C.9 16.2 0 20 0 24s.9 7.8 2.5 11.1l7.7-6.4z"/>
            <path fill="#EA4335" d="M24 48c6.3 0 11.6-2.1 15.5-5.7l-6.6-5.1c-2 1.4-4.6 2.3-8.9 2.3-5.9 0-11.7-3.7-13.8-9.8l-7.7 6c3.9 7.9 12.1 13.3 21.5 13.3z"/>
          </svg>
          Continue with Google
        </Button>
      </div>

      <!-- Register -->
      <div class="text-center text-sm text-slate-399" v-if="canRegister">
        Don't have an account?
        <TextLink :href="register()" class="text-indigo-400">Sign up</TextLink>
        <span> or </span>
        <Button
          type="button"
          variant="link"
          class="text-indigo-400"
          @click="loginGoogle"
        >
          Register with Google
        </Button>
      </div>
    </Form>
  </AuthBase>
</template>
