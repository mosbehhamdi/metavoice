<script setup>
import { Link, useForm, usePage } from "@inertiajs/inertia-vue3";

import { ref } from "vue";

const props = defineProps({
  mustVerifyEmail: Boolean,
  status: String,
});
const user = usePage().props.value.auth.user;

let externalUserId = user.id; // You will supply the external user id to the OneSignal SDK

OneSignal.push(function () {
  OneSignal.setExternalUserId(externalUserId);
});
OneSignal.push(function () {
  OneSignal.getExternalUserId().then(function (externalUserId) {
    console.log("externalUserId: ", externalUserId);
  });
});
const form = useForm({
  name: user.name,
  email: user.email,
  id: user.id,
  current_password: "",
  password: "",
  password_confirmation: "",
});

const dropdownOpen = ref(false);

const isOpen = ref(false);
const dropdown1 = ref(false);
const dropdown2 = ref(false);
const dropdown3 = ref(false);
const dropdownTeam = ref(false);
const dropdownOpenSkills = ref(false);
</script>

<template>
  <div class="flex h-screen bg-white font-roboto">
    <div class="flex">
      <!-- Backdrop -->
      <div
        :class="isOpen ? 'block' : 'hidden'"
        @click="isOpen = false"
        class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"
      ></div>
      <!-- End Backdrop -->

      <div
        :class="isOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="fixed inset-y-0 center z-30 w-64 overflow-y-auto transition duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0"
      >
        <div class="flex justify-center">
          <div class="flex justify-center">
            <div>
              <div class="text-white">
                <div class="flex p-2"></div>
                <div class="flex justify-center">
                  <div class="">
                    <img
                      class="hidden h-full w-24 rounded-full sm:block object-cover mr-2 border-4 border-white"
                      src="appLogo.jpg"
                      alt=""
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <nav class="mt-10 text-slate-500" v-if="user.type === 'admin'">
          <ul class="mt-12">
            <li class="relative px-6 py-1">
              <a
                href="/"
                class="router-link-active bg-purple-600 text-white inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-800 hover:text-white py-2 px-2 rounded-lg"
                aria-current="page"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  aria-hidden="true"
                  class="flex-shrink-0 h-6 w-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  ></path></svg
                ><span class="ml-4">METAVOICE</span></a
              >
            </li>
            <li class="relative px-6 py-1">
              <button
                class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                @click="dropdown1 = !dropdown1"
                aria-haspopup="true"
              >
                <span class="inline-flex items-center">
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
                      d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"
                    />
                  </svg>

                  <span class="ml-4">Gestion des taches</span>
                </span>
              </button>

              <div v-show="dropdown1">
                <div class="w-48 bg-white rounded-lg border">
                  <Link
                    :href="route('Tasks')"
                    type="button"
                    class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="mr-2 w-4 h-4 fill-current"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    ></svg>
                    Envoyés
                  </Link>
                </div>
                <div class="w-48 bg-white rounded-lg border">
                  <Link
                    :href="route('indexAdminRespondedTask')"
                    type="button"
                    class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="mr-2 w-4 h-4 fill-current"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    ></svg>
                    Repondu
                  </Link>
                  <div v-show="dropdown1">
                    <div class="w-48 bg-white rounded-lg border">
                      <Link
                        :href="route('indexAdminToResolveTask')"
                        type="button"
                        class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                      >
                        <svg
                          aria-hidden="true"
                          class="mr-2 w-4 h-4 fill-current"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                          xmlns="http://www.w3.org/2000/svg"
                        ></svg>
                        A corriger
                      </Link>
                    </div>
                  </div>
                  <div v-show="dropdown1">
                    <div class="w-48 bg-white rounded-lg border">
                      <Link
                        :href="route('indexAdminValidTask')"
                        type="button"
                        class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                      >
                        <svg
                          aria-hidden="true"
                          class="mr-2 w-4 h-4 fill-current"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                          xmlns="http://www.w3.org/2000/svg"
                        ></svg>
                        Validé
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </nav>

        <nav class="mt-10 text-slate-500" v-if="user.type === 'user'">
          <ul class="mt-12">
            <li class="relative px-6 py-1">
              <a
                href="/"
                class="router-link-active bg-purple-600 text-white inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-800 hover:text-white py-2 px-2 rounded-lg"
                aria-current="page"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  aria-hidden="true"
                  class="flex-shrink-0 h-6 w-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  ></path></svg
                ><span class="ml-4">METAVOICE</span></a
              >
            </li>
            <li class="relative px-6 py-1">
              <button
                class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                @click="dropdown1 = !dropdown1"
                aria-haspopup="true"
              >
                <span class="inline-flex items-center">
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
                      d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"
                    />
                  </svg>

                  <span class="ml-4">Missions</span>
                </span>
              </button>

              <div v-show="dropdown1">
                <div class="w-48 bg-white rounded-lg border">
                  <Link
                    :href="route('indexWorkerTasks')"
                    type="button"
                    class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="mr-2 w-4 h-4 fill-current"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    ></svg>
                    En attente
                  </Link>
                </div>
              </div>

              <div v-show="dropdown1">
                <div class="w-48 bg-white rounded-lg border">
                  <Link
                    :href="route('indexWorkerRespondedTask')"
                    type="button"
                    class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                  >
                    <svg
                      aria-hidden="true"
                      class="mr-2 w-4 h-4 fill-current"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg"
                    ></svg>
                    Repondu
                  </Link>
                  <div v-show="dropdown1">
                    <div class="w-48 bg-white rounded-lg border">
                      <Link
                        :href="route('indexWorkerToResolveTask')"
                        type="button"
                        class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                      >
                        <svg
                          aria-hidden="true"
                          class="mr-2 w-4 h-4 fill-current"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                          xmlns="http://www.w3.org/2000/svg"
                        ></svg>
                        A corriger
                      </Link>
                    </div>
                  </div>
                  <div v-show="dropdown1">
                    <div class="w-48 bg-white rounded-lg border">
                      <Link
                        :href="route('indexWorkerValidTask')"
                        type="button"
                        class="inline-flex relative items-center py-2 px-4 w-full text-sm font-medium hover:bg-gray-100 hover:text-purple-700 focus:z-10 focus:text-purple-700"
                      >
                        <svg
                          aria-hidden="true"
                          class="mr-2 w-4 h-4 fill-current"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                          xmlns="http://www.w3.org/2000/svg"
                        ></svg>
                        Validé
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
      <header
        class="z-10 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
      >
        <div
          class="container flex items-center justify-between h-full px-6 mx-auto text-gray-600 dark:text-purple-300"
        >
          <button
            class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
            aria-label="Menu"
            @click="isOpen = true"
          >
            <svg
              class="w-6 h-6"
              aria-hidden="true"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"
              ></path>
            </svg>
          </button>
          <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl mr-6 focus-within:text-purple-500">
              <div class="absolute inset-y-0 flex items-center pl-2">
                <svg
                  class="w-4 h-4"
                  aria-hidden="true"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
              <form>
                <input
                  class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                  type="text"
                  placeholder="recherche"
                  aria-label="Search"
                />
              </form>
            </div>
          </div>
          <ul class="flex items-center flex-shrink-0 space-x-6">
            <li class="flex">
              <div class="relative" v-if="user.type === 'admin'">
                <button
                  @click="dropdownTeam = !dropdownTeam"
                  class="relative z-10 block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none"
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
                      d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                    />
                  </svg>
                </button>

                <transition
                  enter-active-class="transition duration-150 ease-out transform"
                  enter-from-class="scale-95 opacity-0"
                  enter-to-class="scale-100 opacity-100"
                  leave-active-class="transition duration-150 ease-in transform"
                  leave-from-class="scale-100 opacity-100"
                  leave-to-class="scale-95 opacity-0"
                  @click="dropdownTeam = false"
                >
                  <div
                    v-show="dropdownTeam"
                    class="absolute right-0 z-20 w-48 py-2 mt-2 bg-white rounded-md shadow-xl"
                  >
                    <Link
                      :href="route('adminTeam')"
                      class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                      >Equipe
                    </Link>
                  </div>
                </transition>
              </div>
            </li>
            <li class="relative" v-if="user.type === 'user'">
              <div class="relative">
                <button
                  @click="
                    dropdownOpenSkills = !dropdownOpenSkills;
                    dropdownOpen = false;
                  "
                  class="relative bg-white-600 text-purple z-10 block overflow-hidden rounded-full shadow focus:outline-none"
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
                      d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"
                    />
                  </svg>
                </button>

                <div
                  v-if="dropdownOpenSkills"
                  @click="dropdownOpenSkills = !dropdownOpenSkills"
                  class="fixed inset-0 z-10 w-full h-full"
                ></div>

                <transition
                  enter-active-class="transition duration-150 ease-out transform"
                  enter-from-class="scale-95 opacity-0"
                  enter-to-class="scale-100 opacity-100"
                  leave-active-class="transition duration-150 ease-in transform"
                  leave-from-class="scale-100 opacity-100"
                  leave-to-class="scale-95 opacity-0"
                  @click="dropdownOpenSkills = false"
                >
                  <div
                    v-show="dropdownOpenSkills"
                    class="absolute right-0 z-20 w-48 py-2 mt-2 bg-white rounded-md shadow-xl"
                  >
                    <Link
                      :href="route('skillAdd')"
                      class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                      >Gere vos competences</Link
                    >

                    <Link
                      :href="route('skills')"
                      class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                      >Competences liste</Link
                    >
                  </div>
                </transition>
              </div>
            </li>
            <li class="relative">
              <div class="relative">
                <button
                  @click="
                    dropdownOpen = !dropdownOpen;
                    dropdownOpenSkills = false;
                  "
                  class="relative z-10 block w-12 h-12 border-4 border-purple-600 overflow-hidden rounded-full shadow focus:outline-none"
                >
                  <img
                    class="inline object-cover w-12 h-12 mr-2 rounded-full"
                    v-bind:src="'/uploads/' + user.profilePicture"
                    alt="Profile image"
                  />
                </button>

                <div
                  v-if="dropdownOpen && user.type === 'admin'"
                  @click="dropdownOpen = !dropdownOpen"
                  class="fixed inset-0 z-10 w-full h-full"
                ></div>

                <transition
                  enter-active-class="transition duration-150 ease-out transform"
                  enter-from-class="scale-95 opacity-0"
                  enter-to-class="scale-100 opacity-100"
                  leave-active-class="transition duration-150 ease-in transform"
                  leave-from-class="scale-100 opacity-100"
                  leave-to-class="scale-95 opacity-0"
                  @click="dropdownOpen = false"
                >
                  <div
                    v-show="dropdownOpen"
                    class="absolute right-0 z-20 w-48 py-2 mt-2 bg-white rounded-md shadow-xl"
                  >
                    <Link
                      :href="route('profile.edit')"
                      class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                      >{{ user.name }}</Link
                    >
                    <a
                      href="#"
                      class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                      >Products</a
                    >

                    <Link
                      :href="route('logout')"
                      class="inline-flex items-center w-full text-sm font-semibold transition duration-200 ease-in hover:bg-purple-500 hover:text-white py-2 px-2 rounded-lg"
                      >Déconnecter</Link
                    >
                  </div>
                </transition>
              </div>
            </li>
          </ul>
        </div>
      </header>

      <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
          <!-- CTA -->
          <button
            class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                ></path>
              </svg>
              <span>Gerer votre teletravail</span>
            </div>
          </button>

          <slot />
        </div>
      </main>
    </div>
  </div>
</template>
