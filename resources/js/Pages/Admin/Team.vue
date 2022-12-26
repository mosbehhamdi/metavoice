<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";

const props = defineProps({
  teleworkers: Array,
  msg: String,
});

const isOpen = ref(false);

const workerSkills = ref(null);

function validateSkills(skillId, bool) {
  axios.put("http://127.0.0.1:8000/validateSkills", {
    skill_id: skillId,
    checked: bool,
  });
}

function closeModal() {
  isOpen.value = false;
}

function openModalSkills(workerId) {
  axios.get("http://127.0.0.1:8000/workerSkills/" + workerId).then((response) => {
    workerSkills.value = response.data;

    isOpen.value = true;
  });
}

const isOpenUpdate = ref(false);

const formUpdate = useForm({
  status: "",
  id: 0,
});
const submitUpdate = () => {
  isOpenUpdate.value = false;
  formUpdate.put("http://127.0.0.1:8000/workerUpdate", {
    id: formUpdate.id,
    status: formUpdate.status,
  });
};

function closeModalUpdate() {
  isOpenUpdate.value = false;
}

function openModalUpdate(workerId) {
  isOpenUpdate.value = true;
  formUpdate.id = workerId;
}

function deleteSkillForever(id) {
  axios.delete(`http://127.0.0.1:8000/deleteSkillForever/${id}`);

  workerSkills.value = workerSkills.value.filter((skill) => skill.id != id);
}
</script>
<template>
  <Head title="Profile" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Team</h2>
    </template>

    <!-- modal Update-->

    <TransitionRoot appear :show="isOpenUpdate" as="template">
      <Dialog as="div" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Modifier
                </DialogTitle>
                <div class="mt-2">
                  <form @submit.prevent="submitUpdate">
                    <select
                      v-model="formUpdate.status"
                      id="underline_select"
                      class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                    >
                      <option value="graphist">Graphist</option>
                      <option value="comptable">Comptable</option>
                      <option value="developpeur">Developpeur</option>
                    </select>
                    <div class="mt-4">
                      <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      >
                        Confirmer
                      </button>
                      <button
                        @click="closeModalUpdate"
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      >
                        Annuler
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- modal -->

    <TransitionRoot appear :show="isOpen" as="template">
      <Dialog as="div" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full space-y-3 max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Liste de compétences
                </DialogTitle>
                <div class="mt-2">
                  <form>
                    <ul>
                      <li v-for="skill in workerSkills" class="space-y-3 space-x-3">
                        <label
                          class="inline-flex relative items-center mb-5 cursor-pointer"
                        >
                          <input
                            v-if="skill.checked"
                            type="checkbox"
                            id="skill"
                            checked
                            class="sr-only peer"
                            v-on:change="validateSkills(skill.id, false)"
                          />
                          <input
                            v-else
                            type="checkbox"
                            id="skill"
                            class="sr-only peer"
                            v-on:change="validateSkills(skill.id, true)"
                          />

                          <div
                            class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                          ></div>
                          <span
                            class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >{{ skill.label }}</span
                          >
                        </label>
                        <button @click="deleteSkillForever(skill.id)" type="button">
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
                      </li>
                    </ul>

                    <div class="mt-4">
                      <button
                        @click="closeModal"
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      >
                        Fermer
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
    <div class="space-y-4">
      <div class="overflow-auto lg:overflow-visible">
        <table
          class="table text-gray-400 border-separate space-y-6 text-sm"
          v-if="$page.props.teleworkers.length !== 0"
        >
          <thead class="bg-gray-800 text-gray-500">
            <tr>
              <th class="p-3 rounded">Teleworker</th>
              <th class="p-3 text-left rounded">Status</th>
              <th class="p-3 text-left rounded">NB Taches</th>
              <th class="p-3 text-left rounded">Status</th>
              <th class="p-3 text-left rounded">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-gray-800 rounded" v-for="worker in teleworkers">
              <td class="p-3 rounded">
                <div class="flex align-items-center">
                  <img
                    class="rounded-full h-12 w-12 object-cover"
                    v-bind:src="'/uploads/user/' + worker.profilePicture"
                    alt="unsplash image"
                  />
                  <div class="ml-3">
                    <div class="">{{ worker.name }}</div>
                    <div class="text-gray-500">{{ worker.email }}</div>
                  </div>
                </div>
              </td>
              <td
                class="p-3 rounded"
                v-if="worker.profile !== null && worker.profile !== ''"
              >
                <div>
                  <p>{{ worker.profile }}</p>
                </div>
              </td>
              <td class="p-3 rounded" v-else>aucun status</td>
              <td class="p-3 font-bold rounded">200.00$</td>
              <td class="p-3 rounded">
                <span class="bg-green-400 text-gray-50 rounded-md px-2">available</span>
              </td>
              <td class="p-3 rounded">
                <button
                  @click="openModalSkills(worker.id)"
                  class="text-gray-400 hover:text-gray-100 mr-2"
                >
                  <i class="material-icons mx-2" style="font-size: 30px">
                    assignment_turned_in</i
                  >
                </button>

                <button
                  @click="openModalUpdate(worker.id)"
                  class="text-gray-400 hover:text-gray-100 mr-2"
                >
                  <i class="material-icons mx-2" style="font-size: 30px"> edit</i>
                </button>
                <a href="#" class="text-gray-400 hover:text-gray-100 mr-2">
                  <i class="material-icons mx-2" style="font-size: 30px">
                    delete_forever</i
                  >
                </a>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else class="container px-6 mx-auto grid">
          <!-- Big section cards -->
          <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Big section cards
          </h4>
          <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Large, full width sections goes here
            </p>
          </div>

          <!-- Responsive cards -->
          <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Responsive cards
          </h4>
          <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <!-- Card -->
            <div
              class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
            >
              <div
                class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                  ></path>
                </svg>
              </div>
              <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                  Total clients
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">6389</p>
              </div>
            </div>
            <!-- Card -->
            <div
              class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
            >
              <div
                class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    fill-rule="evenodd"
                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
              <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                  Account balance
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                  $ 46,760.89
                </p>
              </div>
            </div>
            <!-- Card -->
            <div
              class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
            >
              <div
                class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                  ></path>
                </svg>
              </div>
              <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                  New sales
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">376</p>
              </div>
            </div>
            <!-- Card -->
            <div
              class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
            >
              <div
                class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    fill-rule="evenodd"
                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
              <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                  Pending contacts
                </p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">35</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
