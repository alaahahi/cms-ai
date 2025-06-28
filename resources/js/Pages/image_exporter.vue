<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import { ref } from "vue";
import axios from 'axios'
let text = ref('')
const phones = ref([])
const uploading = ref(false)
const progress = ref(0)

function uploadImages(event) {
  const formData = new FormData()
  for (let file of event.target.files) {
    formData.append('images[]', file)
  }

  uploading.value = true
  progress.value = 0

  axios.post('/extract-phones', formData, {
    onUploadProgress: (e) => {
      progress.value = Math.round((e.loaded * 100) / e.total)
    }
  })
  .then(response => {
    // استخراج الأرقام من كل صورة
    phones.value = response.data.results.flatMap(r => r.phones)
  })
  .catch(error => {
    console.error('حدث خطأ', error)
  })
  .finally(() => {
    uploading.value = false
  })
}


const dateValue = ref({
  startDate: "",
  endDate: "",
});
const countComp = ref();
const formatter = ref({
  date: "D/MM/YYYY",
  month: "MM",
});
const options = ref({
  shortcuts: {
    today: "اليوم",
    yesterday: "البارحة",
    past: (period) => period + " قبل يوم",
    currentMonth: "الشهر الحالي",
    pastMonth: "الشهر السابق",
  },
  footer: {
    apply: "Terapkan",
    cancel: "Batal",
  },
});
const dDate = (date) => {
  return date >= new Date();
};
const getcountComp = async () => {
  const response = await fetch(
    `getcount?start=${dateValue.value.startDate}&end=${dateValue.value.endDate}`
  );
  countComp.value = await response.json();
};
getcountComp();
const props = defineProps({
  url: String,
  user: String,
  profile: String,
  comp: String,
  working: String,
  cardCompany: String,
  cardRegister: String,
  balance: String,
});
const sendText = () => {
  axios.post('api/send-text-phone', { text: text.value })
  .then(response => {
    phones.value = response.data.phones
  })
  .catch(error => {
    console.error('حدث خطأ', error)
  })
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white  leading-tight">
        استخراج الصور
      </h2>
    </template>

   <div>
     


       <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
        <button @click="sendText()"    class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600">
              <svg class="w-5 h-5 rotate-45  " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z"/>
              </svg>
              <span class="sr-only">Send message</span>
          </button>
          <textarea    rows="1" v-model="text" class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  style="height: 300px;font: 1.5em sans-serif;"></textarea>  


        <input   multiple @change="uploadImages" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help"   type="file">
  
 
       
            
      </div>
      <div class="mt-2 px-5 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
         <ul class="w-64 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <li
          v-for="(phone, index) in phones"
          :key="index"
          class="w-full px-4 py-2 border-b border-gray-200 last:border-b-0 dark:border-gray-600"
          :class="{
            'rounded-t-lg': index === 0,
            'rounded-b-lg': index === phones.length - 1
          }"
        >
          {{ phone }}
        </li>
      </ul>
      </div>
      <div v-if="uploading" class="px-5 py-2">
  

        <!-- شريط Tailwind مخصص --> 
        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 mt-2">
          <div
            class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
            :style="{ width: progress + '%' }"
          >
            {{ progress }}%
          </div>
        </div>
      </div>

  </div>

  </AuthenticatedLayout>
</template>
