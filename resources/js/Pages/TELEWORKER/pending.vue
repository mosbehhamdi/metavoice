<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import { useToast, useModal } from "tailvue";
import moment from "moment";

const $toast = useToast();
const $modal = useModal();

const form = useForm({
  file: null,
  description: "",
});
const submit = () => {
  if (form.file === null) {
    isOpenMRespond.value = true;

    $toast.show({
      type: "warning",
      message: "Veuillez ajouter une fichier d'abord",
      timeout: 3,
    });
  } else {
    form.post(route("storeTaskResp", { taskid: taskToRespond.value }));

    isOpenMRespond.value = false;
    form.file = null;
    form.description = "";
    $toast.show({
      type: "success",
      message: "Réponse envoyé",
      timeout: 3,
    });
  }
};
const isOpenMShow = ref(false);
const taskToRespond = ref(0);
const isOpenMRespond = ref(false);
const showTaskPage = ref(false);
const taskName = ref("");
const taskTitle = ref("");
const taskDescription = ref("");
const taskId = ref(0);
const zoomFrameFile = ref(false);

var hasFile = ref(false);
var taskResponses = ref([]);
var seeTaskResponces = ref(false);

const props = defineProps({
  workerTasks: Array,
  id: Number,
});
function closeModalShow() {
  isOpenMShow.value = false;
}

function closeModalRespond() {
  isOpenMRespond.value = false;
}

function openModalRespond(taskId) {
  taskToRespond.value = taskId;
  isOpenMRespond.value = true;
}
function getFormattedDate(date) {
  return moment(date).format("HH:MM");
}
function openTaskResponses(id) {
  axios.get("http://127.0.0.1:8000/taskResponses/" + id).then((response) => {
    taskResponses.value = response.data;
  });
  seeTaskResponces.value = true;
}

let externalUserId = props.id; // You will supply the external user id to the OneSignal SDK

OneSignal.push(function () {
  OneSignal.setExternalUserId(externalUserId);
});

OneSignal.push(function () {
  OneSignal.getExternalUserId().then(function (externalUserId) {
    console.log("externalUserId: ", externalUserId);
  });
});

function showTask(name, title, description, HF) {
  this.taskName = name;
  this.taskTitle = title;
  this.taskDescription = description;
  this.hasFile = HF;
  if (HF) {
    showTaskPage.value = true;
  } else {
    isOpenMShow.value = true;
  }
}

function forceFileDownload(response, title) {
  console.log(title);
  const url = window.URL.createObjectURL(new Blob([response.data]));
  const link = document.createElement("a");
  link.href = url;
  link.setAttribute("download", title);
  document.body.appendChild(link);
  link.click();
}
function downloadWithAxios(url, title) {
  console.log("url : " + url);
  console.log("le titre : " + title);
  axios({
    method: "get",
    url,
    responseType: "arraybuffer",
  })
    .then((response) => {
      this.forceFileDownload(response, title);
    })
    .catch(() => console.log("error occured"));
}

/*	
hover tooltip
*/
var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new Tooltip(tooltipTriggerEl);
});
</script>

<template>
  <Head title="TABLEAU DE BORD" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">TABLEAU DE BORD</h2>
    </template>

    <button
      class="router-link-active bg-blue-600 text-white inline-flex w-12 text-sm font-semibold transition duration-200 ease-in hover:bg-blue-800 hover:text-white py-2 px-2 rounded-lg"
      v-if="showTaskPage && zoomFrameFile"
      @click="zoomFrameFile = false"
      type="button"
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
      name="backFromDisplay"
      v-if="showTaskPage && !zoomFrameFile"
      @click="showTaskPage = false"
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

    <div v-if="seeTaskResponces">
      <button
        name="backFromDisplay"
        @click="seeTaskResponces = false"
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
      <div class="container mx-auto px-4 md:px-12">
        <div class="flex flex-wrap -mx-1 lg:-mx-4">
          <!-- Column -->
          <div
            data-bs-toggle="tooltip"
            v-bind:title="resp.taskTitle + ' : ' + resp.description"
            class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3"
            v-for="resp in taskResponses"
          >
            <!-- Article -->
            <article
              class="overflow-hidden rounded-lg shadow-lg hover:-translate-y-1 hover:scale-110 duration-300"
            >
              <a href="#">
                <iframe
                  v-if="resp.name.split('.').pop() === 'pdf'"
                  alt="Placeholder"
                  class="block h-72 w-full"
                  v-bind:src="resp.name"
                ></iframe>

                <img
                  v-if="
                    resp.name.split('.').pop() === 'jpg' ||
                    resp.name.split('.').pop() === 'jpg' ||
                    resp.name.split('.').pop() === 'png' ||
                    resp.name.split('.').pop() === 'psd' ||
                    resp.name.split('.').pop() === 'ai' ||
                    resp.name.split('.').pop() === 'jpg'
                  "
                  alt="Placeholder"
                  class="block h-72 w-full"
                  v-bind:src="resp.name"
                />
              </a>

              <header class="flex items-center justify-between leading-tight p-2 md:p-4">
                <p class="text-grey-darker text-sm">
                  <span class="text-slate-500">Rependu le </span>

                  {{ resp.created_at.substring(0, 10) }}
                </p>
                <p class="text-grey-darker text-sm">
                  <span class="text-slate-500">à </span>
                  {{ getFormattedDate(resp.created_at) }}
                </p>
              </header>

              <footer class="flex items-center justify-between leading-none p-2 md:p-4">
                <a
                  class="flex items-center no-underline hover:underline text-black"
                  href="#"
                >
                  <img
                    alt="Placeholder"
                    class="block rounded-full w-6 h-6"
                    v-bind:src="resp.andminPhoto"
                  />
                  <p class="ml-2 text-sm">De {{ resp.andminName }}</p>
                </a>
                <a class="no-underline text-grey-darker hover:text-red-dark" href="#">
                  <span class="hidden">Like</span>
                </a>
              </footer>
            </article>
            <!-- END Article -->
          </div>
          <!-- END Column -->
        </div>
      </div>
    </div>
    <TransitionRoot appear :show="isOpenMRespond" as="template">
      <Dialog as="div" @close="closeModalRespond" class="relative z-10">
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
                  Tache accomplie ! Envoyer votre essai
                </DialogTitle>
                <div class="mt-2">
                  <form @submit.prevent="submit" class="space-y-4">
                    <label class="block text-sm">
                      <textarea
                        id="description"
                        class="block rounded w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-purple-600 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        placeholder="Si vous avez quelques choses a dire (optionnel)"
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
                    </div>
                    <div class="space-x-2">
                      <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      >
                        Envoyer
                      </button>

                      <button
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        type="button"
                        @click="closeModalRespond"
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

    <TransitionRoot appear :show="isOpenMShow" as="template">
      <Dialog as="div" @close="closeModalShow" class="relative z-10">
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
                  {{ taskTitle }}
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">{{ taskDescription }}</p>
                </div>

                <div class="mt-4">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModalShow"
                  >
                    Je l'ai Merci !
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <table class="w-full" v-if="!showTaskPage && !seeTaskResponces">
      <thead>
        <tr
          class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
        >
          <th class="px-4 py-3">tache</th>
          <th class="px-4 py-3">Reçu le</th>
          <th class="px-4 py-3">Heure</th>

          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="task in workerTasks">
          <td class="px-4 py-3 text-sm">{{ task.title }}</td>
          <td class="px-4 py-3 text-xs">
            <span>
              {{ task.created_at.substring(0, 10) }}
            </span>
          </td>
          <td class="px-4 py-3 text-xs">
            <span>
              {{ getFormattedDate(task.created_at) }}
            </span>
          </td>

          <td class="px-4 py-3">
            <div class="flex items-center space-x-4 text-sm">
              <button
                @click="showTask(task.name, task.title, task.description, task.hasFile)"
                class="font-medium text-green-600 dark:text-green-500 hover:underline"
              >
                Afficher
              </button>

              <button
                @click="openModalRespond(task.id)"
                type="button"
                name="repondre"
                class="font-medium text-blue-600 dark:text-green-500 hover:underline"
              >
                repondre
              </button>

              <button
                @click="openTaskResponses(task.id)"
                type="button"
                name="repondre"
                class="font-medium text-blue-900 dark:text-green-500 hover:underline"
              >
                voir réponses
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="mt-5 space-y-2" v-if="showTaskPage">
      <div v-if="hasFile">
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
                <button
                  class="router-link-active bg-purple-600 text-white inline-flex w-10 text-sm font-semibold transition duration-200 ease-in hover:bg-purple-800 hover:text-white py-2 px-2 rounded-lg"
                  @click="downloadWithAxios(taskName, taskTitle)"
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
                      d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </button>
                <div class="text-gray-900 font-bold text-xl mb-2 w-screen">
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
        <div v-else-if="taskName.split('.').pop() === 'pdf' && showTaskPage">
          <div class="mt-5 space-y-2" v-if="showTaskPage">
            <div v-if="zoomFrameFile"></div>
            <div v-if="!zoomFrameFile" class="max-w-sm w-full lg:max-w-full lg:flex">
              <iframe :src="taskName"></iframe>

              <div
                class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal"
              >
                <div class="mb-8">
                  <p class="text-sm text-gray-600 flex items-center space-x-2">
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

                    <button
                      class="router-link-active bg-purple-600 text-white inline-flex w-10 text-sm font-semibold transition duration-200 ease-in hover:bg-purple-800 hover:text-white py-2 px-2 rounded-lg"
                      @click="downloadWithAxios(taskName, taskTitle)"
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
                          d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </button>
                  </p>
                  <div class="text-gray-900 font-bold text-xl mb-2 w-screen">
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
              <embed :src="taskName" type="application/pdf" width="100%" height="600px" />
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
                  <div class="text-sm">
                    <button
                      class="router-link-active bg-purple-600 text-white inline-flex w-10 text-sm font-semibold transition duration-200 ease-in hover:bg-purple-800 hover:text-white py-2 px-2 rounded-lg"
                      type="button"
                      @click="downloadWithAxios(taskName, taskTitle)"
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
                          d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </button>
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
