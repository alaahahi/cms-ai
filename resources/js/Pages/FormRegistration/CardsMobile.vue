<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from 'vue'; // Import ref and watch from Vue
import InfiniteLoading from "v3-infinite-loading";
import axios from 'axios';
import debounce from 'lodash/debounce'; // Import debounce function from Lodash
import ModaEditCardsMobile from "@/Components/ModaEditCardsMobile.vue";
import ModaAddCardsMobile from "@/Components/ModaAddCardsMobile.vue";


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

const refresh = () => {
  page = 1;
  laravelData.value.length = 0;
  resetData.value = !resetData.value;
};
const getResults = async ($state) => {
  try {
    const response = await axios.get(`getIndexCardsMobile`, {
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
  card: String,
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




let showModalEditCardsMobile = ref(false);
let showModalAddCardsMobile = ref(false);


function openModalEditCardsMobile(v){
  form.value = v
  showModalEditCardsMobile.value = true
}
function openModalAddCardsMobile(){
  showModalAddCardsMobile.value = true
}

function confirmAddCardsMobile(V) {
  showModalAddCardsMobile.value = false;
  let formData = new FormData();

  for (const key in V) {
    formData.append(key, V[key]);
  }

  // إذا في editMode معناها تعديل، لازم نرسل ID ونغير الرابط
  const isEdit = V.id !== undefined && V.id !== null;
  const url = isEdit ? `UpdateCardsMobile/${V.id}` : 'AddCardsMobile';

  axios.post(url, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    showModalAddCardsMobile.value = false;
    showModalEditCardsMobile.value = false;

    refresh();
    toast.success(isEdit ? "تم تعديل البطاقة بنجاح" : "تم إضافة البطاقة بنجاح", {
      timeout: 2000,
      position: "bottom-right",
      rtl: true,
    });
  })
  .catch(error => {
    showModalAddCardsMobile.value = false;
    showModalEditCardsMobile.value = false;
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
        إدارة معلومات  البطاقات  في التطبيق
      </h2>
    </template>
 
    <ModaAddCardsMobile
      :show="showModalAddCardsMobile ? true : false"
      :data="form"
      @a="confirmAddCardsMobile($event)"
      @close="showModalAddCardsMobile = false"
    >
      <template #header>
        <h3 class="text-center fw-10">اضافة بطاقة جديدة للتطبيق - الخطوة الاولى</h3>
      </template>
    </ModaAddCardsMobile>
    

    <ModaEditCardsMobile
      :show="showModalEditCardsMobile ? true : false"
      :data="form"
      @a="confirmAddCardsMobile($event)"
      @close="showModalEditCardsMobile = false"
    >
      <template #header>
        <h3 class="text-center fw-10">هل انت متأكد من تأكيد البطاقة رقم {{ form?.card_number }}</h3>
      </template>
    </ModaEditCardsMobile>

    <div class="py-12">
      <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <button
                    style="width: 100%;text-align: center;    display: block;"
                    className="px-6 mb-12 py-2 mt-1 font-bold text-white bg-rose-500 rounded"
                    @click="openModalAddCardsMobile()"

                    >
                    إنشاء بطاقة جديدة
                </button>
      

            <div class="overflow-x-auto shadow-md">
              <table class="w-full my-5">
                <thead
                  class="700 bg-rose-500 text-white text-center rounded-l-lg"
                >
                  <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                    <th className="px-4 py-2 w-20">تسلسل</th>
                    <th className="px-4 py-2">اسم البطاقة بالعربية</th>
                    <th className="px-4 py-2">اسم البطاقة بالانكليزية</th>
                    <th className="px-4 py-2">عدد الايام</th>
                    <th className="px-4 py-2">تاريخ انتهاءالعقد</th>
                    <th className="px-4 py-2">متاحة  في التطبيق</th>
                    <th className="px-4 py-2">تاريخ التسجيل</th>
                    <th className="px-4 py-2">السعر بالدينار</th>
                    <th className="px-4 py-2">الصورة</th>
                    <th className="px-4 py-2">تنفيذ</th>
                  </tr>
                </thead>
                <tbody class="flex-1 sm:flex-none">
                  <tr
                    v-for="(user,index) in laravelData"
                    :key="user.id"
                    class="mb-2 sm:mb-0 hover:bg-gray-100 text-center"
                  >
                    <td className="border px-4 py-2">{{ index+1 }}</td>
                    <td className="border px-4 py-2 td">
                      {{ user.name_ar }}
                    </td>
                    <td className="border px-4 py-2 td">{{ user.name_en }}</td>
                    <td className="border px-4 py-2 td" style="direction: ltr;">
                      {{ user.day }}
                    </td>
                    <td className="border px-4 py-2">
                      {{ user.expir_date.substring(0, 10) }}
                    </td>
                    <td className="border px-4 py-2 td">
                      {{ user.show_on_app ? 'متاحة للطلب من التطبيق' : 'غير متاحة للطلب في التطبيق'}}
                    </td>
                    <td className="border px-4 py-2">
                      {{ user.created_at.substring(0, 10) }}
                    </td>
                    <td className="border px-4 py-2 td">
                      {{ user.price }}
                    </td>
                
                    <td className="border px-4 py-2 td">
                      <a
                        :href="`/public/storage/${user.image}`"
                        style="cursor: pointer"
                        target="_blank">
                        <img
                        v-if="user.image"
                        :src="`/public/storage/${user.image}`"
                        alt="Setting Image"
                        style="width:200px;"
                        />
                      </a>
                    </td>
          
          
                    <td className="border px-2 py-2">
                      <button
                        tabIndex="1"
                        className="px-2 py-1 text-sm text-white mx-1 bg-slate-500 rounded"
                         @click="openModalEditCardsMobile(user)"
                        >
                        تعديل
                      </button>
              
                     
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <div class="mt-3 text-center" style="direction: ltr;">
                <div class="spaner">
                <InfiniteLoading :laravelData="laravelData" @infinite="getResults" :identifier="resetData" />
                </div>
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