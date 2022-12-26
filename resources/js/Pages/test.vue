<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const showEditPage = ref(false);
const showImagePage = ref(false);

const imgTitle = ref("");
const imgDescription = ref("");
const imgName = ref("");

const props = defineProps({
  files: Array,
  user_id: Array,
  msg: String,
});

const form = useForm({
  title: "",
  file: null,
  name: "",
  description: "",
  user_id: props.user_id,
});
const formUpdate = useForm({
  title: "",
  description: "",
  id: 0,
});

const submit = () => {
  form.post(route("file.upload.store"));
  form.title = "";
  form.description = "";
  form.name = "";
  form.file = null;
  form.user_id;
};

const submitUpdate = () => {
  showEditPage.value = !showEditPage.value;

  formUpdate.put("http://127.0.0.1:8000/updateImage", {
    title: formUpdate.title,
    description: formUpdate.description,
    id: formUpdate.id,
  });
};

function showImage(name, title, description) {
  showEditPage.value = false;
  showImagePage.value = true;
  this.imgName = name;
  this.imgTitle = title;
  this.imgDescription = description;
}
function deleteImage(id) {
  form.delete(`http://127.0.0.1:8000/deleteImage/${id}`);
}

function editForm(id, title, description) {
  showEditPage.value = !showEditPage.value;
  formUpdate.title = title;
  formUpdate.description = description;
  formUpdate.id = id;
}
function back() {
  showImagePage.value = false;
}
</script>
<template>
  <Head title="Profile" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8 space-y-3">
        <div
          v-if="!showEditPage && !showImagePage"
          class="p-4 sm:p-8 bg-white shadow sm:rounded-lg"
        >
          <section>
            <div class="grid grid-flow-col space-x-6">
              <div class="space-y-4">
                <header>
                  <h2 class="text-lg font-medium text-gray-900">Upload Image</h2>
                </header>

                <form @submit.prevent="submit" class="space-y-4">
                  <div class="relative z-0 mb-6 w-full group">
                    <input
                      v-model="form.user_id"
                      type="text"
                      id="user_id"
                      placeholder=" "
                      hidden
                    />
                  </div>
                  <div class="relative z-0 mb-6 w-full group">
                    <input
                      v-model="form.title"
                      type="text"
                      id="title"
                      class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                      placeholder=" "
                    />
                    <label
                      for="floating_email"
                      class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                      >Image title</label
                    >
                  </div>

                  <div class="relative z-0 mb-6 w-full group items-center justify-center">
                    <div
                      class="relative z-0 mb-6 w-full group items-center justify-center"
                    >
                   
                      <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Write your thoughts here..."
                      ></textarea>
                    </div>
                  </div>

                  <div>
                    <label
                      v-if="form.file === null"
                      class="bg-transparent hover:bg-purple-600 hover:text-white text-purple-700 font-semibold hover:text-white py-2 px-4 border border-purple-500 hover:border-transparent rounded"
                      for="file"
                    >
                      <input
                        id="file"
                        type="file"
                        hidden
                        @input="form.file = $event.target.files[0]"
                        autofocus
                        accept=".png, .jpg, .jpeg"
                      />

                      Upload 
                    </label>
                  </div>
                  <div>
                    <button
                      type="submit"
                      class="bg-transparent hover:bg-purple-600 hover:text-white text-purple-700 font-semibold hover:text-white py-2 px-4 border border-purple-500 hover:border-transparent rounded"
                    >
                      Confirm
                    </button>
                  </div>
                </form>
              </div>
              <!-- ... -->
              <div class="max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl ...">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg flex">
                  <div v-if="$page.props.files.length!==0">
                    <h4
                      class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
                    >
                      Table with actions 
                    </h4>
                    <div class="w-full overflow-hidden rounded-lg shadow-xs">
                      <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                          <thead>
                            <tr
                              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                            >
                              <th class="px-4 py-3">Image</th>
                              <th class="px-4 py-3">Title</th>
                              <th class="px-4 py-3">Status</th>
                              <th class="px-4 py-3">Date</th>
                              <th class="px-4 py-3">Actions</th>
                            </tr>
                          </thead>
                          <tbody
                            class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                          >
                            <tr class="text-gray-700 dark:text-gray-400"  v-for="file in files">
                              <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                  <!-- Avatar with inset shadow -->
                                  <div
                                    class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                  >
                                    <img
                                      class="object-cover w-full h-full"
                                      :src="file.name"
                                      alt=""
                                      loading="lazy"
                                    />
                                    <div
                                      class="absolute inset-0 rounded-full shadow-inner"
                                      aria-hidden="true"
                                    ></div>
                                  </div>
                                  
                                </div>
                              </td>
                              <td class="px-4 py-3 text-sm">{{file.title}}</td>
                              <td class="px-4 py-3 text-xs"  >
                                <span v-if="file.accepted===true"
                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                                >
                                  Approved
                                </span>
                                <span v-else
                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                                >
                                  waiting
                                </span>
                              </td>
                              <td class="px-4 py-3 text-sm">6/10/2020</td>
                              <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                  <button  @click="editForm(file.id, file.title, file.description)"
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
                                  <button    @click="deleteImage(file.id)"
                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
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
                            <tr class="text-gray-700 dark:text-gray-400"  v-for="file in files">
                              <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                  <!-- Avatar with inset shadow -->
                                  <div
                                    class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                  >
                                    <img
                                      class="object-cover w-full h-full"
                                      :src="file.name"
                                      alt=""
                                      loading="lazy"
                                    />
                                    <div
                                      class="absolute inset-0 rounded-full shadow-inner"
                                      aria-hidden="true"
                                    ></div>
                                  </div>
                                  
                                </div>
                              </td>
                              <td class="px-4 py-3 text-sm">{{file.title}}</td>
                              <td class="px-4 py-3 text-xs"  >
                                <span v-if="file.accepted===true"
                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                                >
                                  Approved
                                </span>
                                <span v-else
                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                                >
                                  waiting
                                </span>
                              </td>
                              <td class="px-4 py-3 text-sm">6/10/2020</td>
                              <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                  <button  @click="editForm(file.id, file.title, file.description)"
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
                                  <button    @click="deleteImage(file.id)"
                                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
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
            </div>
          </section>
        </div>
        <div
          v-else-if="showEditPage && !showImagePage"
          class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-4"
        >
          <header>
            <h2 class="text-lg font-medium text-gray-900">Update image data</h2>
          </header>
          <div>
            <form @submit.prevent="submitUpdate">
              <div class="relative z-0 mb-6 w-full group">
                <input
                  v-model="formUpdate.title"
                  type="text"
                  name="imageTitle"
                  id="imageTitle"
                  class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                  placeholder=" "
                  required
                />
                <label
                  for="floating_email"
                  class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                  >Image title</label
                >
              </div>

              <div class="relative z-0 mb-6 w-full group">
                <textarea
                  v-model="formUpdate.description"
                  name="imageDescription"
                  id="imageDescription"
                  class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                  placeholder=" "
                  required
                ></textarea>
                <label
                  for="floating_email"
                  class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                  >Image description</label
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
        <div v-else class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-4">
          <header></header>
          <div>
            <div class="mt-5" v-if="showImagePage">
              <button
                @click="back()"
                class="bg-transparent hover:bg-indigo-600 hover:text-white text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
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
              <div class="w-full max-w-sm mt-6 lg:max-w-full lg:flex">
                <div
                  class="flex-none h-48 overflow-hidden text-center bg-cover rounded-t lg:h-auto lg:w-48 lg:rounded-t-none lg:rounded-l"
                  v-bind:style="{ backgroundImage: 'url(' + imgName + ')' }"
                ></div>
                <div
                  class="flex flex-col justify-between p-4 leading-normal bg-white border-b border-l border-r border-gray-200 rounded-b lg:border-l-0 lg:border-t lg:border-gray-200 lg:rounded-b-none lg:rounded-r"
                >
                  <div class="mb-8">
                    <p class="flex items-center text-sm text-gray-600">
                      <svg
                        class="w-3 h-3 mr-2 text-gray-500 fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z"
                        />
                      </svg>
                      Members only
                    </p>
                    <div class="mb-2 text-xl font-bold text-gray-900">
                      {{ imgTitle }}
                    </div>
                    <p class="text-base text-gray-700">
                      {{ imgDescription }}
                    </p>
                  </div>
                  <div class="flex items-center">
                    <svg
                      v-if="true == false"
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
                      <p class="leading-none text-gray-900">task pending</p>
                      <p v-if="true == false" class="text-gray-600">Aug 18</p>
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
