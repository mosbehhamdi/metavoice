<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
  workerTasks: Array,
  id: Number,
});

let externalUserId = "10"; // You will supply the external user id to the OneSignal SDK

OneSignal.push(function () {
  OneSignal.setExternalUserId(externalUserId);
});

OneSignal.push(function () {
  OneSignal.getExternalUserId().then(function (externalUserId) {
    console.log("externalUserId: ", externalUserId);
  });
});
</script>

<template>
  <Head title="TABLEAU DE BORD" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">TABLEAU DE BORD</h2>
    </template>

    <div class="flex flex-col space-y-4 ...">
      <div class="flex flex-col space-y-8 ...">
        <div>pending tasks</div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
