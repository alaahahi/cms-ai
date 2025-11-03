<script setup>
import Modal from "@/Components/Modal.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import axios from 'axios';

const userCard = ref({});
const searchTerm = ref('');
const errors = ref(0);
const isLoading = ref(false);

let timer = null;
const delay = 1000; // Delay in milliseconds

const handleInput = (v) => {
  clearTimeout(timer); // Clear the previous timer

  timer = setTimeout(() => {
    checkCard(v); // Call the function to make the Axios request after the delay
  }, delay);
};

const checkCard = (v) => {
  errors.value = 0;
  userCard.value = {};
  
  if (v) {
    isLoading.value = true;
    // البحث في جميع أنواع البطاقات باستخدام search parameter
    axios.get('/api/checkCard?search=' + encodeURIComponent(v))
      .then(response => {
        userCard.value = response.data;
        errors.value = 0;
        isLoading.value = false;
      })
      .catch(error => {
        errors.value = 1;
        userCard.value = {};
        isLoading.value = false;
      });
  } else {
    userCard.value = {};
    errors.value = 0;
  }
};

const props = defineProps({
  url: String,
  users: Array,
  config: Object
});

const results = (id) => {
  if (id == 0) {
    return 'إنتظار تسليم الصندوق';
  }
  if (id == 1) {
    return 'تم التسليم';
  }
  if (id == 2) {
    return "مكتمل";
  }
};
</script>

<template>
  <Head title="عرض البطاقات" />
  
  <!-- Title Section -->
  <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 px-6 shadow-md mb-6">
    <h2 class="text-center text-xl font-bold">
      <span v-if="userCard.card">
        {{ userCard.card?.name_ar || userCard.card?.name_en || 'معلومات البطاقة' }}
      </span>
      <span v-else>
        معلومات البطاقة
      </span>
    </h2>
  </div>

  <!-- Success Message -->
  <div v-if="userCard.card_number" class="mb-4 mx-4">
    <div
      id="alert-2"
      class="p-4 bg-green-50 border-r-4 border-green-400 rounded-lg print:hidden"
      role="alert"
    >
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-green-800">تم العثور على نتيجة البحث. شكراً لك</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Message -->
  <div v-if="errors && searchTerm" class="mb-4 mx-4">
    <div
      id="alert-2"
      class="p-4 bg-pink-50 border-r-4 border-pink-400 rounded-lg print:hidden"
      role="alert"
    >
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-pink-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-pink-800">
            لم يتم العثور على نتيجة. يرجى التواصل مع الدعم الفني في حالة وجود مشكلة
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Message from Page Props -->
  <div v-if="$page.props.success" class="mb-4 mx-4">
    <div
      id="alert-2"
      class="p-4 bg-red-50 border-r-4 border-red-400 rounded-lg print:hidden"
      role="alert"
    >
      <div class="mr-3">
        <p class="text-sm font-medium text-red-800">{{ $page.props.success }}</p>
      </div>
    </div>
  </div>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Search Section -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">البحث عن بطاقة</h3>
          <form @submit.prevent="checkCard(searchTerm)">
            <label for="simple-search" class="sr-only">بحث</label>
            <div class="relative w-full">
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
                @input="handleInput(searchTerm)"
                type="text"
                id="simple-search"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="رقم البطاقة أو اسم المشترك"
                :disabled="isLoading"
              />
              <div v-if="isLoading" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Card Information Section -->
      <div v-if="userCard.card_number" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b">معلومات البطاقة</h3>
          
          <!-- Basic Information Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div className="mb-4">
              <InputLabel for="name" value="الأسم الكامل" class="mb-2 font-semibold" />
              <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg">
                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-gray-700">{{ userCard?.name || '-' }}</span>
              </div>
            </div>

            <div className="mb-4">
              <InputLabel for="card_number" value="رقم البطاقة" class="mb-2 font-semibold" />
              <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg">
                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-gray-700">{{ userCard?.card_number || '-' }}</span>
              </div>
            </div>

            <div className="mb-4">
              <InputLabel for="phone" value="رقم الهاتف" class="mb-2 font-semibold" />
              <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg" dir="ltr">
                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="text-gray-700">{{ userCard?.phone_number || '-' }}</span>
              </div>
            </div>

            <div className="mb-4">
              <InputLabel for="saler" value="المندوب" class="mb-2 font-semibold" />
              <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg">
                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-gray-700">{{ userCard.user?.name || '-' }}</span>
              </div>
            </div>

            <div className="mb-4" v-if="userCard.card">
              <InputLabel for="card_type" value="نوع البطاقة" class="mb-2 font-semibold" />
              <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg">
                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-gray-700">{{ userCard.card?.name_ar || userCard.card?.name_en || '-' }}</span>
              </div>
            </div>

            <div className="mb-4 print:hidden" v-if="userCard.id">
              <InputLabel for="print" value="إجراءات" class="mb-2 font-semibold" />
              <a
                :href="route('document', userCard.id)"
                target="_blank"
                class="inline-flex items-center justify-center px-4 py-3 w-full font-semibold text-white bg-green-500 hover:bg-green-600 rounded-lg transition duration-200 shadow-md hover:shadow-lg"
              >
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                طباعة الوصل
              </a>
            </div>
          </div>

          <!-- Family Members -->
          <div v-if="userCard?.family_name" class="mb-6">
            <InputLabel for="family_name" value="أفراد العائلة" class="mb-2 font-semibold" />
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
              <p class="text-gray-700">{{ userCard.family_name }}</p>
            </div>
          </div>

          <!-- Image Section -->
          <div v-if="userCard?.image" class="mb-6">
            <InputLabel for="image" value="الصورة الشخصية" class="mb-2 font-semibold" />
            <div class="flex justify-center">
              <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50 max-w-md">
                <img 
                  :src="'/public/' + userCard.image" 
                  alt="صورة البطاقة"
                  class="max-w-full h-auto rounded-lg shadow-md"
                />
              </div>
            </div>
          </div>

          <!-- Appointments Table -->
          <div v-if="userCard.appointment && userCard.appointment.length > 0" class="mt-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">الاستخدام</h3>
            <div class="overflow-x-auto shadow-md rounded-lg">
              <table class="w-full">
                <thead class="bg-gradient-to-r from-rose-500 to-rose-600 text-white">
                  <tr>
                    <th className="px-4 py-3 text-right font-semibold">التسلسل</th>
                    <th className="px-4 py-3 text-right font-semibold">الطبيب</th>
                    <th className="px-4 py-3 text-right font-semibold">رقم البطاقة</th>
                    <th className="px-4 py-3 text-right font-semibold">التاريخ والساعة</th>
                    <th className="px-4 py-3 text-right font-semibold">ملاحظة</th>
                    <th className="px-4 py-3 text-center font-semibold">الحالة</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr
                    v-for="appointment in userCard.appointment"
                    :key="appointment.id"
                    class="hover:bg-gray-50 transition-colors"
                  >
                    <td className="px-4 py-3 text-center text-gray-700">{{ appointment.id }}</td>
                    <td className="px-4 py-3 text-right text-gray-700">{{ appointment?.user?.name || '-' }}</td>
                    <td className="px-4 py-3 text-center text-gray-700">{{ appointment.card_id || '-' }}</td>
                    <td className="px-4 py-3 text-center text-gray-700">{{ appointment.start || '-' }}</td>
                    <td className="px-4 py-3 text-right text-gray-700 td">{{ appointment.note || '-' }}</td>
                    <td className="px-4 py-3 text-center">
                      <span
                        :class="{
                          'px-3 py-1 text-xs font-semibold rounded-full': true,
                          'bg-green-100 text-green-800': appointment.is_come == 2,
                          'bg-red-100 text-red-800': appointment.is_come == 0,
                          'bg-yellow-100 text-yellow-800': appointment.is_come == 1 || !appointment.is_come
                        }"
                      >
                        {{ appointment.is_come == 2 ? 'تم التأكيد' : appointment.is_come == 0 ? 'تم الإلغاء' : 'في الانتظار' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Empty State for Appointments -->
          <div v-else-if="userCard.card_number" class="mt-6 text-center py-8 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">لا توجد مواعيد مسجلة</p>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!searchTerm" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">ابدأ البحث</h3>
          <p class="mt-1 text-sm text-gray-500">أدخل رقم البطاقة أو اسم المشترك للبحث</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.td {
  max-width: 200px;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
</style>