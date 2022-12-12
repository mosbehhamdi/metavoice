<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head,useForm } from '@inertiajs/inertia-vue3';

/*let externalUserId = "2"; // You will supply the external user id to the OneSignal SDK

OneSignal.push(function() {
  OneSignal.setExternalUserId(externalUserId);
});

axios.post('http://127.0.0.1:8000/sendPush')
  .then(function (response) {
    console.log('responce  : ' + response.data); 
  })
  .catch(function (error) {
    console.log(error);
  }); 
      
OneSignal.isPushNotificationsEnabled(function(isEnabled) {
  if (isEnabled) {
      // user has subscribed
      OneSignal.getUserId( function(userId) {
          console.log('player_id of the subscribed user is : '+userId);
          // Make a POST call to your server with the user ID 
       

      });
  }
});

*/


 
const props = defineProps({
    files: Array,
    msg: String,
});

const form = useForm({
    title: '',
    file: null,
    name:'',
    description:'',
});


function  forceFileDownload(response, title) {
      console.log(title)
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', title)
      document.body.appendChild(link)
      link.click()
    };
function downloadWithAxios(url, title) {
    console.log('url : '+url);
          console.log('le titre : '+title);
      axios({
        method: 'get',
        url,
        responseType: 'arraybuffer',
      })
        .then((response) => {
          this.forceFileDownload(response, title);
          
        })
        .catch(() => console.log('error occured'))
    };


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1>Task List</h1>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
            <tr>
                <th scope="col" class="py-3 px-6">
                    Image
                </th>
               
                <th scope="col" class="py-3 px-6">
                    Title
                </th>
               
                <th scope="col" class="py-3 px-6">
                    Action
                </th>
            </tr>
        </thead> 
        <tbody>
            <tr v-for="file in files" class="bg-white border-b  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="p-4 w-32">
                    
                    <img
                    class="inline object-cover w-12 h-12 mr-2 rounded"
                          :src="file.name"
                          alt="profile pic"
                        />

                </td>
             
                <td class="py-4 px-6 font-semibold text-gray-900 dark:text-white ">
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
    <button  class="font-medium text-blue-600 dark:text-blue-500 hover:underline" @click="downloadWithAxios(file.name, file.title)">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
    </AuthenticatedLayout>
</template>
