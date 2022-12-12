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
  msg: String,
});

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
  
  form.post(route("file.upload.store"));
  form.title = "";
  form.description = "";
  form.name = "";
  form.file = null;
  
};

const submitUpdate = () => {
  showEditPage.value = !showEditPage.value;

  formUpdate
    .put("http://127.0.0.1:8000/updateImage", {
      title: formUpdate.title,
      description: formUpdate.description,
      id: formUpdate.id,
    })
    .then(function (response) {
      console.log("responce  : " + response.data);
    })
    .catch(function (error) {
      console.log(error);
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

                <form @submit.prevent="submit">
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
                    <textarea
                      name="imageDescription"
                      id="description"
                      v-model="form.description"
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

                  <div class="space-x-4">
                    <label
                      v-if="form.file === null"
                      class="bg-transparent hover:bg-indigo-600 hover:text-white text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                      for="file"
                    >
                      <input
                        id="file"
                        type="file"
                        hidden
                        @input="form.file = $event.target.files[0]"
                        autofocus
                      />
                       
                      Upload image
                    </label>
                    <button
                    
                    
                      type="submit"
                      class="bg-transparent hover:bg-indigo-600 hover:text-white text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                    >
                      Confirm
                    </button>
                  </div>
                </form>
              </div>
              <!-- ... -->
              <div class="max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl ...">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg flex">
                  <div>
                    <table
                      class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                      <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                      >
                        <tr>
                          <th scope="col" class="py-3 px-6">Image</th>

                          <th scope="col" class="py-3 px-6">Title</th>

                          <th scope="col" class="py-3 px-6">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="file in files"
                          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                        >
                          <td class="p-4 w-32">
                            <img
                              class="inline object-cover w-12 h-12 mr-2 rounded"
                              :src="file.name"
                              alt="profile pic"
                            />
                          </td>

                          <td
                            class="py-4 px-6 font-semibold text-gray-900 dark:text-white"
                          >
                            {{ file.title }}
                          </td>

                          <td class="py-4 px-6">
                            <div class="flex space-x-4">
                              <div>
                                <!-- 
       <Link :href="route('showFile',{id:file.id})" class=" inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-1 text-base font-medium text-white hover:bg-indigo-700">show</Link>

       -->
                              </div>
                              <div>
                                <button
                                  @click="
                                    showImage(file.name, file.title, file.description)
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
                              </div>

                              <div>
                                <button
                                  @click="editForm(file.id, file.title, file.description)"
                                  class="font-medium text-blue-600 dark:text-green-500 hover:underline"
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
                                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                    />
                                  </svg>
                                </button>
                              </div>
                              <div>
                                <button
                                  @click="deleteImage(file.id)"
                                  class="font-medium text-red-600 dark:text-red-500 hover:underline"
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
                                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                    />
                                  </svg>
                                </button>
                              </div>
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
                <input type="text" id="id" hidden v-model="formUpdate.id" />
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
                    <svg v-if="true==false" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
</svg>

                    <div class="text-sm">
                      <p class="leading-none text-gray-900">task pending</p>
                      <p v-if="true==false" class="text-gray-600">Aug 18</p>
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
