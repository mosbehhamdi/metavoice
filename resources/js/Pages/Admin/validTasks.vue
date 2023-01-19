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
import { useToast, useModal } from "tailvue";
import moment from "moment";
import { Link } from "@inertiajs/inertia-vue3";

const isOpenMShow = ref(false);
const isOpenRespValidate = ref(false);
const isOpenRespResolve = ref(false);

var seeTaskResponces = ref(false);
var taskResponses = ref([]);

function openTaskResponses(id) {
  axios.get("http://127.0.0.1:8000/taskResponsesAdmin/" + id).then((response) => {
    taskResponses.value = response.data;

    if (taskResponses.value.length == 0) {
      seeTaskResponces.value = false;
      $toast.show({
        type: "warning",
        message: "Aucune réponse",
        timeout: 3,
      });
    } else {
      seeTaskResponces.value = true;
    }
  });
}

function closeModalShow() {
  isOpenMShow.value = false;
}
function closeModalRespValidate() {
  isOpenRespValidate.value = false;
}

function openModalRespValidate(respId) {
  axios.put("http://127.0.0.1:8000/tagAsValidTask/" + respId);

  isOpenRespValidate.value = true;
}

function closeModalRespResolve() {
  isOpenRespResolve.value = false;
}

function openModalRespResolve() {
  isOpenRespResolve.value = true;
}
const $toast = useToast();
const $modal = useModal();

const props = defineProps({
  tasks: Array,
});

var teleworkers = ref([]);

const selectedSkill_id = ref(0);
const showEditPage = ref(false);

const ShowForm = ref(false);
const zoomFrameFile = ref(false);
const taskTitle = ref("");
const taskDescription = ref("");
const taskName = ref("");
const taskStatus = ref("");
const taskAdminPhoto = ref("");
const taskAdminName = ref("");
const taskWorkerPhoto = ref("");
const taskWorkerName = ref("");

var hasFile = ref(true);

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
  if (
    form.title === "" ||
    form.description === "" ||
    !Number.isInteger(selected.value["checked"])
  ) {
    ShowForm.value = true;
    $toast.show({
      type: "warning",
      message: "Veuillez remplir les champs obligatoires",
      timeout: 6,
    });
  } else {
    form.post(route("storeTask", { emailWorker: selected.value["email"] }));
    form.title = "";
    form.description = "";
    form.name = "";
    form.file = null;
    ShowForm.value = false;
    $toast.show({
      type: "success",
      message: "Envoi en cours à " + selected.value["name"],
      timeout: 3,
    });
  }
};

const submitUpdate = () => {
  showEditPage.value = !showEditPage.value;

  formUpdate.put("http://127.0.0.1:8000/updateTask", {
    title: formUpdate.title,
    description: formUpdate.description,
    id: formUpdate.id,
  });
  $toast.show({
    type: "success",
    message: "Modifications enregistrés",
    timeout: 3,
  });
};
function getFormattedDate(date) {
  return moment(date).format("HH:MM");
}

function showTask(
  name,
  title,
  description,
  HF,
  status,
  adminName,
  adminPhoto,
  workerName,
  workerPhoto
) {
  this.taskName = name;
  this.taskTitle = title;
  this.taskDescription = description;
  this.hasFile = HF;
  this.taskStatus = status;
  this.taskAdminName = adminName;
  this.taskAdminPhoto = adminPhoto;
  this.taskWorkerName = workerName;
  this.taskWorkerPhoto = workerPhoto;

  if (HF) {
    showEditPage.value = false;
    showTaskPage.value = true;
  } else {
    isOpenMShow.value = true;
  }
}
function deleteTask(id) {
  this.$modal.show({
    type: "danger",
    title: "Etes vous sur de vouloir supprimer",
    body: "Vous etes sur le point de supprimer le tache ",
    primary: {
      label: "Confirmé",
      theme: "red",
      action: () => {
        form.delete(`http://127.0.0.1:8000/deleteTask/${id}`),
          $toast.show({
            type: "success",
            message: "Tache supprimé",
            timeout: 1,
          });
      },
    },
    secondary: {
      label: "Annulé",
      theme: "white",
    },
  });
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
</script>
<template>
  <Head title="Profile" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </template>

    <!-- 0 tasks -->
    <TransitionRoot appear :show="$page.props.tasks.length === 0" as="template">
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
                  class="text-lg font-medium leading-6 text-gray-900 break-words"
                >
                  Aucun tache validé
                </DialogTitle>

                <div class="mt-4 space-x-2">
                  <Link
                    :href="route('indexAdminToResolveTask')"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  >
                    Precedent
                  </Link>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- show text Task -->
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
                <DialogTitle
                  as="h3"
                  class="text-lg font-medium leading-6 text-gray-900 break-words uppercase"
                >
                  {{ taskTitle }}
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 break-words">{{ taskDescription }}</p>
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
    <!-- show resp resolve -->
    <TransitionRoot appear :show="isOpenRespResolve" as="template">
      <Dialog as="div" @close="closeModalRespResolve" class="relative z-10">
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
                  class="text-lg font-medium leading-6 text-gray-900 break-words uppercase"
                >
                </DialogTitle>
                <div class="mt-2">
                  <form>
                    <textarea
                      id="description"
                      class="block rounded w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-purple-600 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                      placeholder="Ajouter des instructions"
                    />
                  </form>
                </div>
                <div class="mt-4 space-x-2">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModalRespResolve"
                  >
                    Confirmer
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModalRespResolve"
                  >
                    Annuler
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- show resp validate -->
    <TransitionRoot appear :show="isOpenRespValidate" as="template">
      <Dialog as="div" @close="closeModalRespValidate" class="relative z-10">
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
                  class="text-lg font-medium leading-6 text-gray-900 break-words"
                >
                  Etes vous sur de vouloir valider ce tache
                </DialogTitle>

                <div class="mt-4 space-x-2">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-green-100 px-4 py-2 text-sm font-medium text-green-900 hover:bg-green-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
                    @click="closeModalRespValidate"
                  >
                    Confirmer
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModalRespValidate"
                  >
                    Annuler
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <div class="space-y-4">
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

      <div v-if="!showEditPage && !showTaskPage && !seeTaskResponces">
        <section>
          <!-- ... -->

          <div class="overflow-x-auto relative shadow-md sm:rounded-lg flex">
            <div v-if="$page.props.tasks.length !== 0">
              <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                  <h6 class="font-medium leading-tight text-base mt-0 mb-2 text-blue-600">
                    Validés
                  </h6>
                  <table class="w-screen">
                    <thead>
                      <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                      >
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Reponses</th>

                        <th class="px-4 py-3">Actions</th>
                      </tr>
                    </thead>
                    <tbody
                      class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                    >
                      <tr class="text-gray-700 dark:text-gray-400" v-for="task in tasks">
                        <td class="px-4 py-3 text-sm">{{ task.title }}</td>

                        <td class="px-4 py-3 text-sm">
                          {{ task.created_at.substring(0, 10) }}
                        </td>

                        <td class="px-4 py-3">
                          <div class="flex items-center space-x-4 text-sm">
                            &nbsp &nbsp &nbsp
                            <button
                              @click="openTaskResponses(task.id)"
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
                                  d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"
                                />
                              </svg>
                            </button>
                          </div>
                        </td>

                        <td class="px-4 py-3">
                          <div class="flex items-center space-x-4 text-sm">
                            <button
                              @click="
                                showTask(
                                  task.name,
                                  task.title,
                                  task.description,
                                  task.hasFile,
                                  task.status,
                                  task.adminName,
                                  task.adminPhoto,
                                  task.workerName,
                                  task.workerPhoto
                                )
                              "
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
                              @click="editForm(task.id, task.title, task.description)"
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
                              @click="deleteTask(task.id)"
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
        </section>
      </div>
      <div v-else-if="showEditPage && !showTaskPage && !seeTaskResponces">
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

      <div v-else-if="!showEditPage && !showTaskPage && seeTaskResponces">
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
                class="my-1 px-1 w-48 md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3"
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

                  <header
                    class="flex items-center justify-between leading-tight p-2 md:p-4"
                  >
                    <p class="text-grey-darker text-sm">
                      <span class="text-slate-500">Repondu le </span>

                      {{ resp.created_at.substring(0, 10) }}
                    </p>
                    <p class="text-grey-darker text-sm">
                      <span class="text-slate-500">à </span>
                      {{ getFormattedDate(resp.created_at) }}
                    </p>
                  </header>

                  <footer
                    class="flex items-center justify-between leading-none p-2 md:p-4"
                  >
                    <a
                      class="flex items-center no-underline hover:underline text-black"
                      href="#"
                    >
                      <img
                        alt="Placeholder"
                        class="block rounded-full w-6 h-6"
                        v-bind:src="resp.workerPhoto"
                      />
                      <p class="ml-2 text-sm">De {{ resp.workerName }}</p>
                    </a>
                    <a
                      class="no-underline space-x-1 text-grey-darker hover:text-red-dark"
                      href="#"
                    >
                      <button
                        @click="openModalRespResolve()"
                        class="text-white rounded-full"
                        type="button"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-6 h-6 bg-blue-500 rounded-full"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                          />
                        </svg>
                      </button>
                    </a>
                  </footer>
                </article>
                <!-- END Article -->
              </div>
              <!-- END Column -->
            </div>
          </div>
        </div>
      </div>
      <div v-else>
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
                  <a
                    class="flex items-center no-underline hover:underline text-black"
                    href="#"
                  >
                    <img
                      alt="Placeholder"
                      class="block rounded-full w-6 h-6"
                      v-bind:src="'/uploads/' + taskWorkerPhoto"
                    />
                    <p class="ml-2 text-sm">A {{ taskWorkerName }}</p>
                  </a>
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
                    <a
                      class="flex items-center no-underline hover:underline text-black"
                      href="#"
                    >
                      <img
                        alt="Placeholder"
                        class="block rounded-full w-6 h-6"
                        v-bind:src="'/uploads/' + taskWorkerPhoto"
                      />
                      <p class="ml-2 text-sm">A {{ taskWorkerName }}</p>
                    </a>
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
                  <div class="flex items-center space-x-3" v-else>
                    <a
                      class="flex items-center no-underline hover:underline text-black"
                      href="#"
                    >
                      <img
                        alt="Placeholder"
                        class="block rounded-full w-6 h-6"
                        v-bind:src="'/uploads/' + taskWorkerPhoto"
                      />
                      <p class="ml-2 text-sm">A {{ taskWorkerName }}</p>
                    </a>

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
    </div>
  </AuthenticatedLayout>
</template>
