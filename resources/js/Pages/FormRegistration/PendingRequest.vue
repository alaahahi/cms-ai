<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from 'vue'; // Import ref and watch from Vue
import InfiniteLoading from "v3-infinite-loading";
import axios from 'axios';
import debounce from 'lodash/debounce'; // Import debounce function from Lodash
import ModaDelete from "@/Components/ModaDelete.vue";
import ModaAcceptePendingRequest from "@/Components/ModaAcceptePendingRequest.vue";

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
    const response = await axios.get(`getIndexPendingRequest`, {
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
let showModalAcceptePendingRequest = ref(false);

function openModalAcceptePendingRequest(v){
  form.value = v
  showModalAcceptePendingRequest.value = true
}
function confirmAcceptePendingRequest(V) {
  showModalAcceptePendingRequest.value = false

  axios.post('AcceptePendingRequest',V)
  .then(response => {
    refresh();
    toast.success("تم القبول وتحويل البطاقة بنجاح", {
        timeout: 2000,
        position: "bottom-right",
        rtl: true,
      });
  })
  .catch(error => {
    toast.error("البطاقة موجودة سابقا يرجى التأكد من الاضافة", {
        timeout: 2000,
        position: "bottom-right",
        rtl: true,
      });
  })
}

let showModalDelete = ref(false);

function openModalDelete(v){
  form.value = v
  showModalDelete.value = true
}
function confirmDeletePendingRequest(V) {
  showModalDelete.value = false

  axios.post('deletePendingRequest',V)
  .then(response => {
    refresh();
    toast.success("تم الحذف بنجاح", {
        timeout: 2000,
        position: "bottom-right",
        rtl: true,
      });
  })
  .catch(error => {
    toast.error("لم يتم الحذف بنجاح", {
        timeout: 2000,
        position: "bottom-right",
        rtl: true,
      });
  })
}
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        إدارة الطلبات المعلقة
      </h2>
    </template>
    <ModaDelete
      :show="showModalDelete ? true : false"
      :data="form"
      @a="confirmDeletePendingRequest($event)"
      @close="showModalDelete = false"
    >
      <template #header>
        <h3 class="text-center fw-10">هل انت متأكد من الحذف الطلب {{ form?.card_number }}</h3>
      </template>
    </ModaDelete>
    <ModaAcceptePendingRequest
      :show="showModalAcceptePendingRequest ? true : false"
      :data="form"
      @a="confirmAcceptePendingRequest($event)"
      @close="showModalAcceptePendingRequest = false"
    >
      <template #header>
        <h3 class="text-center fw-10">هل انت متأكد من تأكيد البطاقة رقم {{ form?.card_number }}</h3>
      </template>
    </ModaAcceptePendingRequest>
    <div v-if="$page.props.success">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center"
        role="alert"
      >
        <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800">
          {{ $page.props.success }}
        </div>
      </div>
    </div>
    <div class="py-12">
      <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
     
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2 lg:gap-1">
              
                
                <div class="mb-4 mr-5 mt-1">
                  <label for="simple-search" class="sr-only">بحث</label>
                  <div class="relative w-full">
                    <div
                      class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                    >
                      <svg
                        aria-hidden="true"
                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </div>
                    <input
                      v-model="q"
                      @input="debouncedGetResultsCar"
                      type="text"
                      id="simple-search"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="رقم البطاقة او اسم المشترك"
                      required
                    />
                  </div>
                </div>
                
               
              <div>
                <div className="mb-4 mr-5">
                  <InputLabel for="from" value="من تاريخ" />
                  <TextInput
                    id="from"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="from"
                  />
                </div>
              </div>
              <div>
                <div className="mb-4 mr-5">
                  <InputLabel for="to" value="حتى تاريخ" />
                  <TextInput
                    id="to"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="to"
                  />
                </div>
              </div>
              <div className="mb-4  mr-5 print:hidden">
                <InputLabel for="pay" value="فلترة" />
                <button
                  @click.prevent="getResults()"
                  class="px-6 mb-12 py-2 mt-1 font-bold text-white bg-gray-500 rounded"
                  style="width: 100%"
                >
                  <span>فلترة</span>
                </button>
              </div>
              <div className="mb-4  mr-5 print:hidden">
                <InputLabel for="pay" value="طباعة" />
                <a
                  :href="`/getIndexFormRegistration?from=${from}&to=${to}&print=1`"
                  target="_blank"
                  class="px-6 mb-12 py-2 mt-1 font-bold text-white bg-orange-500 rounded block text-center"
                  style="width: 100%"
                >
                  <span>طباعة</span>
                </a>
              </div>
            </div>

            <div class="overflow-x-auto shadow-md">
              <table class="w-full my-5">
                <thead
                  class="700 bg-rose-500 text-white text-center rounded-l-lg"
                >
                  <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                    <th className="px-4 py-2 w-20">تسلسل</th>
                    <th className="px-4 py-2">رقم البطاقة</th>
                    <th className="px-4 py-2">الأسم كامل</th>
                    <th className="px-4 py-2">رقم الموبايل</th>
                    <th className="px-4 py-2">العنوان</th>
                    <th className="px-4 py-2">المندوب</th>
                    <th className="px-4 py-2">تاريخ التسجيل</th>
                    <th className="px-4 py-2">أفراد العائلة</th>
                    <th className="px-4 py-2">المصدر</th>
                    <th className="px-4 py-2">مرفق</th>
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
                      {{ user.card_number }}
                    </td>
                    <td className="border px-4 py-2 td">{{ user.name }}</td>
                    <td className="border px-4 py-2 td" style="direction: ltr;">
                      {{ user.phone }}
                    </td>
                    <td className="border px-4 py-2 td">{{ user.address }}</td>
                    <td className="border px-4 py-2 td">
                      {{ user.user?.name }}
                    </td>
                    <td className="border px-4 py-2">
                      {{ user.created_at.substring(0, 10) }}
                    </td>
                    <td className="border px-4 py-2 td">
                      {{ user.family_members_names }}
                    </td>
                    <td className="border px-4 py-2 td">
                      {{ user.source }}
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
                        :href="route('formRegistrationEdit', user.id)"
                        >
                        تعديل
                      </button>
                      <button
                        tabIndex="1"
                        className="px-2 py-1 text-sm text-white mx-1 bg-rose-500 rounded"
                        @click="openModalDelete(user)"
                      >
                       حذف
                      </button>
                      <button
                        tabIndex="1"
                        className="px-2 py-1 text-sm text-white mx-1 bg-green-500 rounded"
                          @click="openModalAcceptePendingRequest(user)"
                      >
                      قبول
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