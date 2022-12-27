<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
  skills: Array,
  user_id:Number,
});

let externalUserId = props.user_id; 

OneSignal.push(function () {
  OneSignal.setExternalUserId(externalUserId);
});

OneSignal.push(function () {
  OneSignal.getExternalUserId().then(function (externalUserId) {
    console.log("externalUserId: ", externalUserId);
  });
});





function changeSkill(e, id) {
  if (e.target.checked) {
    axios.post("http://127.0.0.1:8000/enableSkill/", {
      skillId: id,
    });
  } else {
    axios.post("http://127.0.0.1:8000/disableSkill/", {
      skillId: id,
    });
  }
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
        <h6 class="font-medium leading-tight text-base mt-0 mb-2 text-gray-800">
          Activation des compétences /
          <Link
            :href="route('skillAdd')"
            class="underline underline-offset-2 font-medium leading-tight text-base mt-0 mb-2 text-blue-600"
            >Ajout des nouvelles</Link
          >
        </h6>
        <div>
          <form action="" class="space-y-3 space-x-5">
            <ul>
              <li class="space-y-3 space-x-3" v-for="skill in skills">
                <label class="inline-flex relative items-center mb-5 cursor-pointer">
                  <input
                    type="checkbox"
                    :id="skill.id"
                    class="sr-only peer"
                    v-on:change="changeSkill($event, skill.id)"
                    v-if="skill.activated"
                    checked
                  />
                  <input
                    type="checkbox"
                    :id="skill.id"
                    class="sr-only peer"
                    v-on:change="changeSkill($event, skill.id)"
                    v-else
                  />

                  <div
                    class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                  ></div>
                  <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                    >{{ skill.label }}
                  </span>
                </label>
              </li>
            </ul>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
