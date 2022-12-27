<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Listbox, ListboxButton } from "@headlessui/vue";
import { ListboxLabel, ListboxOptions, ListboxOption } from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";

const props = defineProps({
  tasks: Array,
  msg: String,
  skills: Array,
});

const isOpen = ref(false);
var teleworkers = ref([]);

function filterTeleworkers() {
  axios
    .get("http://127.0.0.1:8000/skillWorkers/" + selectedSkill_id.value)
    .then((response) => {
      teleworkers.value = response.data;
      selected.value = ref(teleworkers);
    });

  ShowForm.value = true;

  isOpen.value = false;
}

function openModal() {
  isOpen.value = true;
}
function closeModal() {
  isOpen.value = false;
}

const selectedSkill_id = ref(0);
const showEditPage = ref(false);

const ShowForm = ref(false);
const zoomFrameFile = ref(false);
const taskTitle = ref("");
const taskDescription = ref("");
const taskName = ref("");
const showTaskPage = ref(false);
var selected = ref([]);
const form = useForm({
  title: "",
  file: null,
  name: "",
  description: "",
});
const formUpdate = useForm({
  title: "",
  description: "",
  id: 0,
});

const submit = () => {
  if (form.title && form.description !== "") {
    ShowForm.value = false;
  }
  form.post(route("storeTask", { emailWorker: selected.value["email"] }));
  form.title = "";
  form.description = "";
  form.name = "";
  form.file = null;
};

const submitUpdate = () => {
  showEditPage.value = !showEditPage.value;

  formUpdate.put("http://127.0.0.1:8000/updateTask", {
    title: formUpdate.title,
    description: formUpdate.description,
    id: formUpdate.id,
  });
};

function showTask(name, title, description) {
  showEditPage.value = false;
  showTaskPage.value = true;
  this.taskName = name;
  this.taskTitle = title;
  this.taskDescription = description;
}
function deleteTask(id) {
  form.delete(`http://127.0.0.1:8000/deleteTask/${id}`);
}

function editForm(id, title, description) {
  showEditPage.value = !showEditPage.value;
  formUpdate.title = title;
  formUpdate.description = description;
  formUpdate.id = id;
}
function back() {
  showTaskPage.value = false;
  zoomFrameFile.value = false;
}
</script>
<template>
  <Head title="Profile" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </template>
    <div class="space-y-4">
      <button
        v-if="!showTaskPage && !ShowForm && !showEditPage && !showTaskPage"
        @click="openModal()"
        class="router-link-active bg-green-600 text-white inline-flex items-center w-18 text-sm font-semibold transition duration-200 ease-in hover:bg-green-800 hover:text-white py-2 px-2 rounded-lg"
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
      <button
        name="backFromDisplay"
        v-if="showTaskPage"
        @click="back()"
        class="router-link-active bg-blue-600 text-white inline-flex w-12 text-sm font-semibold transition duration-200 ease-in hover:bg-blue-800 hover:text-white py-2 px-2 rounded-lg"
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
            d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
      </button>
      <button
        name="backFromAdd"
        v-if="ShowForm && !showEditPage && !showTaskPage"
        @click="ShowForm = false"
        class="router-link-active bg-blue-600 text-white inline-flex w-10 text-sm font-semibold transition duration-200 ease-in hover:bg-blue-800 hover:text-white py-2 px-2 rounded-lg"
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
            d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
      </button>
      <button
        name="backFromUpdate"
        v-if="!ShowForm && showEditPage && !showTaskPage"
        @click="showEditPage = false"
        class="router-link-active bg-blue-600 text-white inline-flex w-10 text-sm font-semibold transition duration-200 ease-in hover:bg-blue-800 hover:text-white py-2 px-2 rounded-lg"
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
            d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
      </button>

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
                  class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                >
                  <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900"
                  >
                    Choisir le tache a assigner d'abord
                  </DialogTitle>
                  <div class="mt-2">
                    <div class="mt-4 text-sm space-y-4">
                      <span class="text-gray-700 dark:text-gray-400"> </span>
                      <ul>
                        <li v-for="skill in skills" class="space-y-4">
                          <div class="mt-2">
                            <label
                              class="inline-flex items-center text-gray-600 dark:text-gray-400"
                            >
                              <input
                                type="radio"
                                class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple-600 dark:focus:shadow-outline-gray"
                                name="skillLabel"
                                v-bind:value="skill.id"
                                v-model="selectedSkill_id"
                              />
                              <span class="ml-2">{{ skill.label }}</span>
                            </label>
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="space-y-3 space-x-3">
                      <button
                        @click="filterTeleworkers()"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-500 px-5 py-1 text-base font-medium text-white hover:bg-blue-700"
                      >
                        Suivant
                      </button>
                      <button
                        @click="closeModal()"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-500 px-5 py-1 text-base font-medium text-white hover:bg-blue-700"
                      >
                        Annuler
                      </button>
                    </div>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>

      <div v-if="!showEditPage && !showTaskPage">
        <section>
          <div class="space-y-4" v-if="ShowForm">
            <form @submit.prevent="submit" class="space-y-4">
              <label class="block text-sm space-y-2">
                <!--start selecty !-->
                <Listbox as="div" v-model="selected">
                  <div class="relative mt-1">
                    <input hidden />
                    <ListboxButton
                      class="rounded-full relative w-full cursor-default rounded-md border border-purple-600 bg-purple py-2 pl-3 pr-10 text-left shadow-sm focus:border-purple focus:outline-none focus:ring-1 focus:ring-purple sm:text-sm"
                    >
                      <span class="flex items-center">
                        <svg
                          v-if="selected.checked == null"
                          class="h-6 w-6 border-2 border-purple flex-shrink-0 rounded-full"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"
                          ></path>
                        </svg>

                        <img
                          v-else
                          v-bind:src="'/uploads/user/' + selected.profilePicture"
                          alt=""
                          class="h-6 w-6 border-2 border-purple-600 flex-shrink-0 rounded-full"
                        />
                        <span class="ml-3 block truncate">{{ selected.name }}</span>
                      </span>
                      <span
                        class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                      >
                        <ChevronUpDownIcon
                          class="h-5 w-5 text-purple"
                          aria-hidden="true"
                        />
                      </span>
                    </ListboxButton>

                    <transition
                      leave-active-class="transition ease-in duration-100"
                      leave-from-class="opacity-100"
                      leave-to-class="opacity-0"
                    >
                      <ListboxOptions
                        class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                      >
                        <ListboxOption
                          as="template"
                          v-for="worker in teleworkers"
                          :key="worker.id"
                          :value="worker"
                          v-slot="{ active, selected }"
                        >
                          <li
                            :class="[
                              active
                                ? 'text-white bg-purple-600 rounded-full'
                                : 'text-gray-900',
                              'relative cursor-default select-none py-2 pl-3 pr-9',
                            ]"
                          >
                            <div class="flex items-center">
                              <img
                                v-bind:src="'/uploads/user/' + worker.profilePicture"
                                alt=""
                                class="h-6 w-6 border-2 border-purple-600 flex-shrink-0 rounded-full"
                              />
                              <span
                                :class="[
                                  selected ? 'font-semibold' : 'font-normal',
                                  'ml-3 block truncate',
                                ]"
                                >{{ worker.name }}</span
                              >
                            </div>

                            <span
                              v-if="selected"
                              :class="[
                                active ? 'text-white' : 'text-indigo-600',
                                'absolute inset-y-0 right-0 flex items-center pr-4',
                              ]"
                            >
                              <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                          </li>
                        </ListboxOption>
                      </ListboxOptions>
                    </transition>
                  </div>
                </Listbox>
                <input
                  id="title"
                  class="block rounded w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-purple-600 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Saisir le titre ici"
                  v-model="form.title"
                />
              </label>
              <label class="block text-sm">
                <textarea
                  id="description"
                  class="block rounded w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-purple-600 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Decrire votre demande"
                  v-model="form.description"
                />
              </label>

              <div class="space-x-3">
                <label
                  v-if="form.file === null"
                  class="underline underline-offset-2 font-medium leading-tight text-base mt-0 mb-2 text-blue-600"
                  for="file"
                >
                  <input
                    id="file"
                    type="file"
                    hidden
                    @input="form.file = $event.target.files[0]"
                    autofocus
                  />

                  Joindre une fichier
                </label>
                <label
                  v-else
                  class="underline underline-offset-2 font-medium leading-tight text-base mt-0 mb-2 text-blue-600"
                  for="file"
                >
                  <input
                    id="file"
                    type="file"
                    hidden
                    @input="form.file = $event.target.files[0]"
                    autofocus
                  />

                  {{ form.file.name }}
                </label>
                <span class="text-slate-500">optionnel</span>
              </div>
              <div>
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
                      d="M4.5 12.75l6 6 9-13.5"
                    />
                  </svg>
                </button>
              </div>
            </form>
          </div>
          <!-- ... -->
          <div v-else>
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg flex">
              <div v-if="$page.props.tasks.length !== 0">
                <div class="w-full overflow-hidden rounded-lg shadow-xs">
                  <div class="w-full overflow-x-auto">
                    <table class="w-screen">
                      <thead>
                        <tr
                          class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                          <th class="px-4 py-3">Title</th>
                          <th class="px-4 py-3">Status</th>
                          <th class="px-4 py-3">Date</th>
                          <th class="px-4 py-3">Actions</th>
                        </tr>
                      </thead>
                      <tbody
                        class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                      >
                        <tr
                          class="text-gray-700 dark:text-gray-400"
                          v-for="file in tasks"
                        >
                          <td class="px-4 py-3 text-sm">{{ file.title }}</td>
                          <td class="px-4 py-3 text-xs">
                            <span
                              v-if="file.completed === true"
                              class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                            >
                              Approved
                            </span>
                            <span
                              v-else
                              class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                            >
                              waiting
                            </span>
                          </td>
                          <td class="px-4 py-3 text-sm">
                            {{ file.created_at.substring(0, 10) }}
                          </td>
                          <td class="px-4 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                              <button
                                @click="showTask(file.name, file.title, file.description)"
                                class="font-medium text-green-600 dark:text-green-500 hover:underline"
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
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                  />
                                  <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                  />
                                </svg>
                              </button>
                              <button
                                @click="editForm(file.id, file.title, file.description)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                aria-label="Edit"
                              >
                                <svg
                                  class="w-5 h-5"
                                  aria-hidden="true"
                                  fill="currentColor"
                                  viewBox="0 0 20 20"
                                >
                                  <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                  ></path>
                                </svg>
                              </button>
                              <button
                                @click="deleteTask(file.id)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                aria-label="Delete"
                              >
                                <svg
                                  class="w-5 h-5"
                                  aria-hidden="true"
                                  fill="currentColor"
                                  viewBox="0 0 20 20"
                                >
                                  <path
                                    fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                  ></path>
                                </svg>
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <div v-else-if="showEditPage && !showTaskPage">
        <div>
          <form @submit.prevent="submitUpdate">
            <div class="relative z-0 mb-6 w-full group">
              <input
                v-model="formUpdate.title"
                type="text"
                name="taskTitle"
                id="taskTitle"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
                required
              />
              <label
                for="floating_email"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >titre</label
              >
            </div>

            <div class="relative z-0 mb-6 w-full group">
              <textarea
                v-model="formUpdate.description"
                name="taskDescription"
                id="taskDescription"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
                required
              ></textarea>
              <label
                for="floating_email"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >description</label
              >
            </div>

            <button
              type="submit"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
              Update
            </button>
          </form>
        </div>
      </div>
      <div v-else>
        <div
          v-if="
            taskName.split('.').pop() === 'jpg' ||
            taskName.split('.').pop() === 'jpg' ||
            taskName.split('.').pop() === 'png' ||
            taskName.split('.').pop() === 'psd' ||
            taskName.split('.').pop() === 'ai' ||
            taskName.split('.').pop() === 'jpg'
          "
        >
          <div class="mt-5 space-y-2" v-if="showTaskPage">
            <div class="max-w-sm w-full lg:max-w-full lg:flex">
              <div
                class="h-48 lg:h-auto lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden"
                v-bind:style="{ backgroundImage: 'url(' + taskName + ')' }"
                title="Woman holding a mug"
              ></div>
              <div
                class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal"
              >
                <div class="mb-8">
                  <div class="text-gray-900 font-bold text-xl mb-2">
                    {{ taskTitle }}
                  </div>
                  <p class="text-gray-700 text-base">
                    {{ taskDescription }}
                  </p>
                </div>
                <div class="flex items-center" v-if="tasktaken">
                  <img
                    class="w-10 h-10 rounded-full mr-4"
                    src="/img/jonathan.jpg"
                    alt="Avatar of Jonathan Reinink"
                  />
                  <div class="text-sm">
                    <p class="text-gray-900 leading-none">Jonathan Reinink</p>
                    <p class="text-gray-600">Aug 18</p>
                  </div>
                </div>
                <div class="flex items-center" v-else>
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
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                    />
                  </svg>

                  <div class="text-sm">
                    <span
                      class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600"
                    >
                      Pending Request
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else-if="taskName.split('.').pop() === 'pdf'">
          <div
            v-if="
              taskName.split('.').pop() === 'jpg' || taskName.split('.').pop() === 'pdf'
            "
          >
            <div class="mt-5 space-y-2" v-if="showTaskPage">
              <div v-if="!zoomFrameFile" class="max-w-sm w-full lg:max-w-full lg:flex">
                <iframe :src="taskName"></iframe>

                <div
                  class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal"
                >
                  <div class="mb-8">
                    <p class="text-sm text-gray-600 flex items-center">
                      <button
                        @click="zoomFrameFile = true"
                        name="zoom"
                        class="router-link-active bg-green-600 text-white inline-flex w-10 text-sm font-semibold transition duration-200 ease-in hover:bg-green-800 hover:text-white py-2 px-2 rounded-lg"
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
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"
                          />
                        </svg>
                      </button>
                    </p>
                    <div class="text-gray-900 font-bold text-xl mb-2">
                      {{ taskTitle }}
                    </div>
                    <p class="text-gray-700 text-base">
                      {{ taskDescription }}
                    </p>
                  </div>
                  <div class="flex items-center" v-if="tasktaken">
                    <img
                      class="w-10 h-10 rounded-full mr-4"
                      src="/img/jonathan.jpg"
                      alt="Avatar of Jonathan Reinink"
                    />
                    <div class="text-sm">
                      <p class="text-gray-900 leading-none">Jonathan Reinink</p>
                      <p class="text-gray-600">Aug 18</p>
                    </div>
                  </div>
                  <div class="flex items-center" v-else>
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
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                      />
                    </svg>

                    <div class="text-sm">
                      <span
                        class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600"
                      >
                        Pending Request
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="max-w-sm w-full lg:max-w-full lg:flex">
                <embed
                  :src="taskName"
                  type="application/pdf"
                  width="100%"
                  height="600px"
                />
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <div class="mt-5 space-y-2">
            <div class="max-w-sm w-full lg:max-w-full lg:flex">
              <div
                class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal"
              >
                <div class="mb-8">
                  <p class="text-sm text-gray-600 flex items-center">
                    <svg
                      class="fill-current text-gray-500 w-3 h-3 mr-2"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z"
                      />
                    </svg>
                    Members only
                  </p>
                  <div class="text-gray-900 font-bold text-xl mb-2">
                    Can coffee make you a better developer?
                  </div>
                  <p class="text-gray-700 text-base">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus
                    quia, nulla! Maiores et perferendis eaque, exercitationem praesentium
                    nihil.
                  </p>
                </div>
                <div class="flex items-center" v-if="tasktaken">
                  <img
                    class="w-10 h-10 rounded-full mr-4"
                    src="/img/jonathan.jpg"
                    alt="Avatar of Jonathan Reinink"
                  />
                  <div class="text-sm">
                    <p class="text-gray-900 leading-none">Jonathan Reinink</p>
                    <p class="text-gray-600">Aug 18</p>
                  </div>
                </div>
                <div class="flex items-center" v-else>
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
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                    />
                  </svg>

                  <div class="text-sm">
                    <span
                      class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600"
                    >
                      Pending Request
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
