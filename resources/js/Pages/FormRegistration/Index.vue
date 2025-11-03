<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";

const laravelData = ref({});
const userLocation = ref({});
let from = ref('')
let to = ref('')
let card_id = ref(0)
const getResults = async (page = 1) => {
  const response = await fetch(`/getIndexFormRegistration?page=${page}&from=${from.value}&to=${to.value}&card_id=${card_id.value}`);
  laravelData.value = await response.json();
};
const searchTerm = ref("");

 
const props = defineProps({
  url: String,
  cards: Array,
});
const search = async (q) => {
  laravelData.value = [];
  const response = await fetch(`/livesearch?q=${q}&card_id=${card_id.value}`);
  laravelData.value = await response.json();
};
const form = useForm();

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
let showModal = ref(false);
</script>

<template>
  <Head title="إدارة العقود الإلكترونية" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        إدارة العقود الإلكترونية
      </h2>
    </template>

    <div v-if="$page.props.success">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-green-50 border-r-4 border-green-400 rounded-lg mx-4"
        role="alert"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="mr-3">
            <p class="text-sm font-medium text-green-800">{{ $page.props.success }}</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="py-6">
      <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white dark:bg-gray-900">
            <!-- Filter Section -->
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">فلترة البيانات</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                <div className="mb-4">
                  <label for="card_id" class="block text-sm font-medium text-gray-700 mb-1">نوع البطاقة</label>
                  <select
                    @change="getResults()"
                    v-model="card_id"
                    id="card_id"
                    class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  >
                    <option :value="0" selected disabled>تحديد البطاقة</option>
                    <option v-for="(card, index) in cards" :key="index" :value="card.id">{{ card.name }}</option>
                  </select>
                </div>
                
                <div class="mb-4" v-if="card_id">
                  <label for="simple-search" class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
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
                      v-model="searchTerm"
                      @input="search(searchTerm)"
                      type="text"
                      id="simple-search"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="رقم البطاقة أو اسم المشترك"
                    />
                  </div>
                </div>
                
                <div class="mb-4" v-if="card_id">
                  <label for="from" class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                  <TextInput
                    id="from"
                    type="date"
                    class="block w-full"
                    v-model="from"
                  />
                </div>
                
                <div class="mb-4" v-if="card_id">
                  <label for="to" class="block text-sm font-medium text-gray-700 mb-1">حتى تاريخ</label>
                  <TextInput
                    id="to"
                    type="date"
                    class="block w-full"
                    v-model="to"
                  />
                </div>
                
                <div class="mb-4 flex items-end" v-if="card_id">
                  <button
                    @click.prevent="getResults()"
                    class="px-6 py-2.5 font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg w-full"
                  >
                    <span class="flex items-center justify-center">
                      <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                      </svg>
                      فلترة
                    </span>
                  </button>
                </div>
                
                <div class="mb-4 flex items-end" v-if="card_id">
                  <a
                    :href="`/getIndexFormRegistration?from=${from}&to=${to}&print=1&card_id=${card_id}`"
                    target="_blank"
                    class="px-6 py-2.5 font-semibold text-white bg-orange-500 hover:bg-orange-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg w-full text-center"
                  >
                    <span class="flex items-center justify-center">
                      <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                      </svg>
                      طباعة
                    </span>
                  </a>
                </div>
              </div>
              
              <div class="mt-4" v-if="card_id">
                <Link
                  className="inline-flex items-center px-6 py-2.5 font-semibold text-white bg-rose-500 hover:bg-rose-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg"
                  :href="route('تسجيل-الاستمارة')"
                >
                  <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  إنشاء بطاقة جديدة
                </Link>
              </div>
            </div>

            <!-- Table Section -->
            <div v-if="card_id && laravelData.data && laravelData.data.length > 0" class="overflow-x-auto shadow-md rounded-lg">
              <table class="w-full my-5 bg-white">
                <thead class="bg-gradient-to-r from-rose-500 to-rose-600 text-white">
                  <tr>
                    <th className="px-4 py-3 text-right font-semibold">تسلسل</th>
                    <th className="px-4 py-3 text-right font-semibold">رقم البطاقة</th>
                    <th className="px-4 py-3 text-right font-semibold">الأسم كامل</th>
                    <th className="px-4 py-3 text-right font-semibold">رقم الموبايل</th>
                    <th className="px-4 py-3 text-right font-semibold">العنوان</th>
                    <th className="px-4 py-3 text-right font-semibold">المندوب</th>
                    <th className="px-4 py-3 text-right font-semibold">تاريخ التسجيل</th>
                    <th className="px-4 py-3 text-right font-semibold">أفراد العائلة</th>
                    <th className="px-4 py-3 text-right font-semibold">المصدر</th>
                    <th className="px-4 py-3 text-right font-semibold">الحالة</th>
                    <th className="px-4 py-3 text-center font-semibold">تنفيذ</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr
                    v-for="user in laravelData.data"
                    :key="user.id"
                    class="hover:bg-gray-50 transition-colors duration-150"
                  >
                    <td className="px-4 py-3 text-center text-gray-700">{{ user.no }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 font-medium">{{ user.card_number }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 td">{{ user.name }}</td>
                    <td className="px-4 py-3 text-right text-gray-700" dir="ltr">{{ user.phone_number }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 td">{{ user.address || '-' }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 td">{{ user.user?.name || '-' }}</td>
                    <td className="px-4 py-3 text-center text-gray-700">{{ user.created_at.substring(0, 10) }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 td">{{ user.family_name || '-' }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 td">{{ user.source || '-' }}</td>
                    <td className="px-4 py-3 text-center">
                      <span 
                        :class="{
                          'px-3 py-1 text-xs font-semibold rounded-full': true,
                          'bg-yellow-100 text-yellow-800': user.results == 0,
                          'bg-green-100 text-green-800': user.results == 1,
                          'bg-blue-100 text-blue-800': user.results == 2,
                        }"
                      >
                        {{ results(user.results) }}
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <div class="flex items-center justify-center gap-2">
                        <a
                          tabIndex="-1"
                          className="px-3 py-1.5 text-sm text-white bg-gray-500 hover:bg-gray-600 rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center"
                          :href="route('document', user.id)"
                          target="_self"
                          title="طباعة"
                        >
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                          </svg>
                          طباعة
                        </a>
                        <Link
                          tabIndex="1"
                          className="px-3 py-1.5 text-sm text-white bg-slate-500 hover:bg-slate-600 rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center"
                          :href="route('formRegistrationEdit', user.id)"
                          title="تعديل"
                        >
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                          تعديل
                        </Link>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Empty State -->
            <div v-else-if="card_id && (!laravelData.data || laravelData.data.length === 0)" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد بطاقات</h3>
              <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء بطاقة جديدة.</p>
              <div class="mt-6">
                <Link
                  :href="route('تسجيل-الاستمارة')"
                  className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-500 hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500"
                >
                  <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  إنشاء بطاقة جديدة
                </Link>
              </div>
            </div>
            
            <!-- Select Card First -->
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">اختر نوع البطاقة</h3>
              <p class="mt-1 text-sm text-gray-500">يرجى تحديد نوع البطاقة لعرض البيانات.</p>
            </div>
            
            <!-- Pagination -->
            <div v-if="laravelData.data && laravelData.data.length > 0" class="mt-6 flex justify-center">
              <TailwindPagination
                :data="laravelData"
                @pagination-change-page="getResults"
                :limit="2"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.td {
  max-width: 200px;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}

/* Improve table appearance */
table {
  border-collapse: separate;
  border-spacing: 0;
}

thead th:first-child {
  border-top-right-radius: 0.5rem;
}

thead th:last-child {
  border-top-left-radius: 0.5rem;
}
</style>