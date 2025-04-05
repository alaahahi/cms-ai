<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from 'vue'; // Import ref and watch from Vue
import InfiniteLoading from "v3-infinite-loading";
import axios from 'axios';
import debounce from 'lodash/debounce'; // Import debounce function from Lodash
import ModaEditCategoryCardsMobile from "@/Components/ModaEditCategoryCardsMobile.vue";
import ModaAddCategoryCardsMobile from "@/Components/ModaAddCategoryCardsMobile.vue";


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
    const response = await axios.get(`getIndexCategoryCardMobile?card_id=`+card_id.value, {
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
  parents:Array
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

function confirmAddCategoryCardsMobile(V) {
  showModalAddCategoryCardsMobile.value = false;
  let formData = new FormData();

  for (const key in V) {
    formData.append(key, V[key]);
  }

  // إذا في editMode معناها تعديل، لازم نرسل ID ونغير الرابط
  const isEdit = V.id !== undefined && V.id !== null;
  const url = isEdit ? `UpdateCategoryCardsMobile/${V.id}` : 'AddCategoryCardsMobile' + '?card_id=' + card_id.value;

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
        إدارة تصنيفات  البطاقات  في التطبيق
      </h2>
    </template>
 
    <ModaAddCategoryCardsMobile
      :show="showModalAddCategoryCardsMobile ? true : false"
      :data="form"
      :parents="parents"
      @a="confirmAddCategoryCardsMobile($event)"
      @close="showModalAddCategoryCardsMobile = false"
    >
      <template #header>
        <h3 class="text-center fw-10">اضافة بطاقة جديدة للتطبيق - الخطوة الاولى</h3>
      </template>
    </ModaAddCategoryCardsMobile>
    

    <ModaEditCategoryCardsMobile
      :show="showModalEditCategoryCardsMobile ? true : false"
      :data="form"
      :parents="parents"
      @a="confirmAddCategoryCardsMobile($event)"
      @close="showModalEditCategoryCardsMobile = false"
    >
      <template #header>
        <h3 class="text-center fw-10">هل انت متأكد من تأكيد البطاقة رقم {{ form?.card_number }}</h3>
      </template>
    </ModaEditCategoryCardsMobile>

<div class="py-12"> 
  <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 bg-white border-b border-gray-200">
        <!-- زر لإنشاء تصنيف جديد -->
        <button
          v-if="card_id"
          style="width: 100%; text-align: center; display: block;"
          class="px-6 mb-12 py-2 mt-1 font-bold text-white bg-rose-500 rounded"
          @click="openModalAddCategoryCardsMobile()"
        >
          إنشاء تصنيف جديد
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
        <!-- عرض التصنيفات -->
        <h3 class="text-xl font-semibold mb-4">التصنيفات</h3>
        <div class="overflow-x-auto shadow-md">
          <table class="w-full my-5">
            <thead class="700 bg-rose-500 text-white text-center rounded-l-lg">
              <tr>
                <th class="px-4 py-2 w-20">تسلسل</th>
                <th class="px-4 py-2">اسم التصنيف بالعربية</th>
                <th class="px-4 py-2">اسم التصنيف بالإنكليزية</th>
                <th class="px-4 py-2">الايقونة</th>
                <th class="px-4 py-2">الخصم</th>
                <th class="px-4 py-2">التصنيف الرئيسي</th>
                <th class="px-4 py-2"> البطاقة</th>

                <th class="px-4 py-2">تنفيذ</th>
              </tr>
            </thead>
            <tbody class="flex-1 sm:flex-none">
              <tr
                v-for="(category, index) in laravelData"
                :key="category.id"
                class="mb-2 sm:mb-0 hover:bg-gray-100 text-center"
              >
                <td class="border px-4 py-2">{{ index + 1 }}</td>
                <td class="border px-4 py-2">{{ category.name_ar }}</td>
                <td class="border px-4 py-2">{{ category.name_en }}</td>
                <td class="border px-4 py-2">
                  <img v-if="category.icon" :src="`/public/storage/${category.icon}`" alt="Icon" style="width:40px;" />
                </td>
                <td class="border px-4 py-2">{{ category.discount }}%</td>
                <td class="border px-4 py-2">
                  {{ category?.parent ? category.parent.name_ar : 'تصنيف رئيسي' }}
                </td>
                <td class="border px-4 py-2">
                  {{ category?.card ? category.card.name_ar : 'تصنيف رئيسي' }}
                </td>
                <td class="border px-2 py-2">
                  <button
                    tabIndex="1"
                    class="px-2 py-1 text-sm text-white mx-1 bg-slate-500 rounded"
                    @click="openModalEditCategoryCardsMobile(category)"
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