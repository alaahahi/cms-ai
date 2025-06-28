<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import { ref } from "vue";
import axios from 'axios'

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
    <input type="file" multiple @change="uploadImages" />
    
    <div v-if="uploading">
      <p>جارٍ المعالجة... {{ progress }}%</p>
      <progress :value="progress" max="100"></progress>
    </div>

    <ul>
      <li v-for="(phone, index) in phones" :key="index">{{ phone }}</li>
    </ul>
  </div>

  </AuthenticatedLayout>
</template>
