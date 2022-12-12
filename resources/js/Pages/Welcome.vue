<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />

  <div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
  >
    <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
      <Link
        v-if="$page.props.auth.user"
        :href="route('dashboard')"
        class="text-sm text-gray-700 dark:text-gray-500 underline"
        >Dashboard</Link
      >

      <template v-else>
        <div class="flex items-center justify-end mt-4">
          <Link
            :href="route('login')"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-1 text-base font-medium text-white hover:bg-indigo-700"
            >Log in</Link
          >

          <Link
            v-if="canRegister"
            :href="route('register')"
            class="ml-4 inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-1 text-base font-medium text-white hover:bg-indigo-700"
            >Register</Link
          >
        </div>
      </template>
    </div>

    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
      <span class="block">Ready to dive in?</span>
      <span class="block text-indigo-600">Start today.</span>
    </h2>
  </div>
</template>
