<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Switch } from "@headlessui/vue";
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/inertia-vue3";

import { Head, useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
  skills: Array,
  allSkills: Array,
  msg: String,
});

const skill = useForm({
  label: "",
});

const submit = () => {
  skill.post(route("skill.store"));
  skill.label = "";
};

function deleteSkill(id) {
  skill.delete(`http://127.0.0.1:8000/deleteSkill/${id}`);
}
</script>

<template>
  <Head title="TABLEAU DE BORD" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de bord</h2>
    </template>
    <div class="flex flex-col space-y-8 ...">
      <div class="flex flex-col space-y-8 ...">
        <h6 class="font-medium leading-tight text-base mt-0 mb-2 text-gray-800">
          Ajout des compétences /
          <Link
            :href="route('skills')"
            class="underline underline-offset-2 font-medium leading-tight text-base mt-0 mb-2 text-blue-600"
            >Activation parmis la liste</Link
          >
        </h6>

        <div>
          <form action="" class="space-y-3 space-x-5" @submit.prevent="submit">
            <input
              required
              v-model="skill.label"
              id="label"
              class="block rounded w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
              placeholder="Ajouter nouvelle compétence"
            />
            <button
              type="submit"
              class="router-link-active bg-blue-600 text-white inline-flex items-center w-19.5 text-sm font-semibold transition duration-200 ease-in hover:bg-blue-800 hover:text-white py-2 px-2 rounded-lg"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </button>
          </form>
        </div>
      </div>

      <div>
        <div class="grid grid-cols-3 gap-3">
          <div v-for="skill in skills">
            <button type="button">
              <span class="bg-green-500 text-white font-bold py-2 px-4 rounded-full"
                >{{ skill.label }}
              </span>
              <span>
                <button @click="deleteSkill(skill.id)" type="button">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4 bg-red-500 text-white rounded-full"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
