<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from 'vue'; // Import ref and watch from Vue
import InfiniteLoading from "v3-infinite-loading";
import axios from 'axios';
import debounce from 'lodash/debounce'; // Import debounce function from Lodash
import ModaAddServicesCardsMobile from "@/Components/ModaAddServicesCardsMobile.vue";
import ModaEditServicesCardsMobile from "@/Components/ModaAddServicesCardsMobile.vue";


import { useToast } from "vue-toastification";
const toast = useToast();

let laravelData = ref([]);
const userLocation = ref({});
let from = ref('')
let to = ref('')
let resetData = ref(false);
let page = 1;
let json = ref({});
let  controller = new AbortController(); // Create a new AbortController
const isLoading = ref(0);
const q = ref('');
let card_id =  ref(0);
const refresh = () => {
  page = 1;
  laravelData.value.length = 0;
  resetData.value = !resetData.value;
};
const changeGetResults = () => {
  refresh()
  getResults()
}

const getResults = async ($state) => {
  try {
    const response = await axios.get(`getIndexServicesCardMobile?card_id=`+card_id.value, {
      params: {
        limit: 25,
        page: page,
        q: q.value,
        from: from.value,
        to: to.value
      },
      signal: controller.signal // Pass the signal to abort the request if needed
    });
    json.value = response.data;
    if (json.value.data.length < 25) {

      laravelData.value.push(...json.value.data);
      $state.complete();
    } else {
      laravelData.value.push(...json.value.data);
      $state.loaded();
    }

    page++;
  } catch (error) {
    console.error(error);
  }
};

// Function to abort the ongoing request
const abortRequest = () => {
  if (controller) {
    controller.abort(); // Abort previous request if it exists
  }
  controller = new AbortController(); // Create a new AbortController
};

watch([q, from, to], () => {
  abortRequest(); // Abort previous request
  debouncedGetResultsCar(); // Call debounced function to fetch new results
});

// Watch for changes in isLoading
watch(isLoading, (newVal) => {
  if (newVal === 1) {
    // Handle loading state
    console.log('Loading data...');
  } else {
    // Handle loaded state
    console.log('Data loaded');
  }
});

const debouncedGetResultsCar = debounce(() => {
  isLoading.value = 1; // Set isLoading to 1 to indicate loading
  refresh(); // 
}, 500);

const props = defineProps({
  url: String,
  card: Array,
  category:Array
});
const search = async (q) => {
  laravelData.value = [];
  const response = await fetch(`/livesearch?q=${q}`);
  laravelData.value = await response.json();
};
let form = ref({});

const results = (id) => {
  if (id == 0) {
    return "إنتظار تسليم الصندوق";
  }
  if (id == 1) {
    return "تم التسليم";
  }
  if (id == 2) {
    return "مكتمل";
  }
};




let showModalEditCategoryCardsMobile = ref(false);
let showModalAddCategoryCardsMobile = ref(false);


function openModalEditCategoryCardsMobile(v){
  form.value = v
  showModalEditCategoryCardsMobile.value = true
}
function openModalAddCategoryCardsMobile(){
  showModalAddCategoryCardsMobile.value = true
}

function confirmAddServicesCardsMobile(V) {
  showModalAddCategoryCardsMobile.value = false;
  let formData = new FormData();

  for (const key in V) {
    formData.append(key, V[key]);
  }

  // إذا في editMode معناها تعديل، لازم نرسل ID ونغير الرابط
  const isEdit = V.id !== undefined && V.id !== null;
  const url = isEdit ? `UpdateAddCardService/${V.id}` : 'AddCardService' + '?card_id=' + card_id.value;

  axios.post(url, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    showModalAddCategoryCardsMobile.value = false;
    showModalEditCategoryCardsMobile.value = false;

    toast.success(isEdit ? "تم تعديل البطاقة بنجاح" : "تم إضافة البطاقة بنجاح", {
      timeout: 2000,
      position: "bottom-right",
      rtl: true,
    });
  })
  .catch(error => {
    showModalAddCategoryCardsMobile.value = false;
    showModalEditCategoryCardsMobile.value = false;
    toast.error("يرجى التأكد من تعبئة البيانات بشكل صحيح", {
      timeout: 5000,
      position: "bottom-right",
      rtl: true,
    });
  });
}



</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        إدارة خدمةات  البطاقات  في التطبيق
      </h2>
    </template>
    <ModaAddServicesCardsMobile
      :show="showModalAddCategoryCardsMobile ? true : false"
      :data="form"
      :categories="category"
      :cards="card"
      @submit="confirmAddServicesCardsMobile($event)"
      @close="showModalAddCategoryCardsMobile = false"
    >
      <template #header>
        <h3 class="text-center fw-10">اضافة بطاقة جديدة للتطبيق - الخطوة الاولى</h3>
      </template>
    </ModaAddServicesCardsMobile>
    

    <ModaEditServicesCardsMobile
      :show="showModalEditCategoryCardsMobile ? true : false"
      :data="form"
      :cards="card"
      :categories="category"
      @submit="confirmAddServicesCardsMobile($event)"
      @close="showModalEditCategoryCardsMobile = false"
    >
      <template #header>
        <h3 class="text-center fw-10">هل انت متأكد من تأكيد البطاقة رقم {{ form?.card_number }}</h3>
      </template>
    </ModaEditServicesCardsMobile>

<div class="py-12"> 
  <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 bg-white border-b border-gray-200">
        <!-- زر لإنشاء خدمة جديد -->
        <button
          v-if="card_id"
          style="width: 100%; text-align: center; display: block;"
          class="px-6 mb-12 py-2 mt-1 font-bold text-white bg-rose-500 rounded"
          @click="openModalAddCategoryCardsMobile()"
        >
          إنشاء خدمة جديد
        </button>
        <div className="mb-4 mx-5">
          <label for="card_id" > نوع البطاقة</label>
          <select
            @change="changeGetResults()"
            v-model="card_id"
            id="card_id"
            class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected disabled>تحديد البطاقة</option>
            <option v-for="(card, index) in card" :key="index" :value="card.id">{{ card.name }}</option>
          </select>
        </div>
        <!-- عرض الخدمةات -->
        <h3 class="text-xl font-semibold mb-4">الخدمات</h3>
        <div class="overflow-x-auto shadow-md">
          <table  class="w-full my-5">
            <thead class="700 bg-rose-500 text-white text-center rounded-l-lg">
              <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">اسم الخدمة (ع)</th>
                <th class="px-4 py-2">اسم الخدمة (En)</th>
                <th class="px-4 py-2">الوصف (ع)</th>
                <th class="px-4 py-2">الوصف (En)</th>
                <th class="px-4 py-2">السعر</th>
                <th class="px-4 py-2"> الأيام</th>
                <th class="px-4 py-2">عدد الساعات</th>
                <th class="px-4 py-2">المواعيد باليوم</th>
                <th class="px-4 py-2">تاريخ الانتهاء</th>
                <th class="px-4 py-2">العملة</th>
                <th class="px-4 py-2">شائع؟</th>
                <th class="px-4 py-2">التصنيف</th>
                <th class="px-4 py-2">البطاقة</th>
                <th class="px-4 py-2">التقييم</th>
                <th class="px-4 py-2">سنة الخبرة</th>
                <th class="px-4 py-2">عرض بالتطبيق؟</th>
                <th class="px-4 py-2">الاختصاص (ع)</th>
                <th class="px-4 py-2">الاختصاص (En)</th>
                <th class="px-4 py-2">التنفيذ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(service, index) in laravelData" :key="service.id" class="text-center hover:bg-gray-100">
                <td class="border px-2 py-2">{{ index + 1 }}</td>
                <td class="border px-2 py-2">{{ service.service_name_ar }}</td>
                <td class="border px-2 py-2">{{ service.service_name_en }}</td>
                <td class="border px-2 py-2">{{ service.description_ar }}</td>
                <td class="border px-2 py-2">{{ service.description_en }}</td>
                <td class="border px-2 py-2">{{ service.price }}</td>
                <td class="border px-2 py-2">{{ service.working_days }}</td>
                <td class="border px-2 py-2">{{ service.working_hours }}</td>
                <td class="border px-2 py-2">{{ service.appointments_per_day }}</td>
                <td class="border px-2 py-2">{{ service.expir_date?.substring(0, 10) }}</td>
                <td class="border px-2 py-2">{{ service.currency }}</td>
                <td class="border px-2 py-2">{{ service.is_popular ? 'نعم' : 'لا' }}</td>
                <td class="border px-2 py-2">{{ service.category?.name_ar || 'غير محدد' }}</td>
                <td class="border px-2 py-2">{{ service.card?.name_ar || 'غير محدد' }}</td>
                <td class="border px-2 py-2">{{ service.review_rate }}</td>
                <td class="border px-2 py-2">{{ service.ex_year }}</td>
                <td class="border px-2 py-2">{{ service.show_on_app ? 'نعم' : 'لا' }}</td>
                <td class="border px-2 py-2">{{ service.specialty_ar }}</td>
                <td class="border px-2 py-2">{{ service.specialty_en }}</td>
                <td class="border px-2 py-2">
                  <button
                    tabIndex="1"
                    class="px-2 py-1 text-sm text-white mx-1 bg-slate-500 rounded"
                    @click="openModalEditCategoryCardsMobile(service)"
                  >
                    تعديل
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

    
  </AuthenticatedLayout>
</template>
<style>
.td {
  max-width: 200px; /* can be 100% ellipsis will happen when contents exceed it */
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
</style>