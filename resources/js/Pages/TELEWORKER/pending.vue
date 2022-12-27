<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const showTaskPage  = ref(false);
const taskName = ref("");
const taskTitle = ref("");
const taskDescription = ref("");
const zoomFrameFile = ref(false);

const props = defineProps({
  workerTasks: Array,
  id: Number,
});

let externalUserId = props.id; // You will supply the external user id to the OneSignal SDK

OneSignal.push(function () {
  OneSignal.setExternalUserId(externalUserId);
});

OneSignal.push(function () {
  OneSignal.getExternalUserId().then(function (externalUserId) {
    console.log("externalUserId: ", externalUserId);
  });
});

function showTask(name, title, description) {
  showTaskPage.value = true;
  this.taskName = name;
  this.taskTitle = title;
  this.taskDescription = description;
}
</script>

<template>
  <Head title="TABLEAU DE BORD" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">TABLEAU DE BORD</h2>
    </template>

    <div class="flex flex-col space-y-4 ...">
      <div class="flex flex-col space-y-8 ...">
                     <button         class="router-link-active bg-blue-600 text-white inline-flex w-12 text-sm font-semibold transition duration-200 ease-in hover:bg-blue-800 hover:text-white py-2 px-2 rounded-lg"
 v-if="showTaskPage && zoomFrameFile" @click="zoomFrameFile = false" type="button">
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
 <table class="w-full" v-if="!showTaskPage">
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
                          v-for="file in workerTasks"
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
                       
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>



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
        <div v-if="taskName.split('.').pop() === 'pdf' && showTaskPage">
          <div
            v-if="
              taskName.split('.').pop() === 'jpg' || taskName.split('.').pop() === 'pdf'
            "
          >
            <div class="mt-5 space-y-2" v-if="showTaskPage">
            <div v-if="zoomFrameFile"> 

            </div>
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
        <div   v-if="
            taskName.split('.').pop() !== 'jpg' &&
            taskName.split('.').pop() !== 'jpg' &&
            taskName.split('.').pop() !== 'png' &&
            taskName.split('.').pop() !== 'psd' &&
            taskName.split('.').pop() !== 'ai' &&
            taskName.split('.').pop() !== 'jpg' && 
            showTaskPage
          ">
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
