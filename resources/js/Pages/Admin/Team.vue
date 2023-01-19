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
import { useToast, useModal } from "tailvue";

const $toastDeletedWorker = useToast();
const $modalDeleteWorker = useModal();

const props = defineProps({
  teleworkers: Array,
  msg: String,
});

const isOpen = ref(false);
const workerSkills = ref(null);

function openDeleteModal(id) {
  this.$modalDeleteWorker.show({
    type: "danger",
    title: "Etes vous sur de vouloir supprimer",
    body: "Vous etes sur le point de supprimer ce teleworker ",
    primary: {
      label: "Confirmé",
      theme: "red",
      action: () => {
        axios.delete(`http://127.0.0.1:8000/destroyTeleworker/${id}`),
          $toastDeletedWorker.show({
            type: "success",
            message: "Teleworker supprimé",
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
                  Status
                </DialogTitle>
                <div class="mt-2">
                  <form @submit.prevent="submitUpdate">
                    <input
                      id="status"
                      class="block rounded w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-purple-600 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                      placeholder="Saisir le status ici"
                      v-model="formUpdate.status"
                    />
                    <div class="mt-4 space-x-3">
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
      <div class="w-full overflow-x-auto">
        <table
          class="table text-gray-400 border-separate space-y-6 text-sm w-full"
          v-if="$page.props.teleworkers.length !== 0"
        >
          <thead class="bg-gray-800 text-gray-500">
            <tr>
              <th class="p-3 rounded">Teleworker</th>
              <th class="p-3 text-left rounded">Status</th>
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
                    v-bind:src="'/uploads/' + worker.profilePicture"
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
              <td class="p-3 rounded" v-else>Aucun status</td>
              <td class="p-3 rounded">
                <span
                  class="bg-green-400 text-gray-50 rounded-md px-2"
                  v-if="worker.available"
                  >Disponible</span
                >
                <span class="bg-red-400 text-gray-50 rounded-md px-2" v-else>Occupé</span>
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
                  <i
                    class="material-icons mx-2"
                    style="font-size: 30px"
                    v-if="worker.profile !== null"
                  >
                    edit</i
                  >

                  <i v-else class="material-icons mx-2" style="font-size: 30px">
                    add_circle</i
                  >
                </button>

                <button
                  @click="openDeleteModal(worker.id)"
                  class="text-gray-400 hover:text-gray-100 mr-2"
                >
                  <i class="material-icons mx-2" style="font-size: 30px">
                    delete_forever</i
                  >
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else class="container px-6 mx-auto grid">
          <!-- Big section cards -->
          <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Aucun teleworker
          </h4>
          <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              les teleworkers doivent s'inscrirent pour qu'ils peuvent recevoir les taches
              à réaliser
            </p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
