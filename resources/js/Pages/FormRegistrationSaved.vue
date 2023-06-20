<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
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

let timer = null;
const delay = 1000; // Delay in milliseconds

const handleInput = (v) => {
  clearTimeout(timer); // Clear the previous timer

  timer = setTimeout(() => {
    checkCard(v); // Call the function to make the Axios request after the delay
  }, delay);
};
const checkCard = (v) => {
  errors.value=0;
  userCard.value={}
  if(v){
    axios.get('/api/checkCard?card_id='+v)
    .then(response => {
      userCard.value=response.data;
    })
    .catch(error => {
      errors.value=1
      userCard.value={}
    })
  }

};

const props = defineProps({
  url: String,
  users:Array
});


const results = (id) => {
  if(id==0){
        return 'إنتظار تسليم الصندوق';
    }
    if(id==1){
        return 'تم التسليم';
    }
  if (id == 2) {
    return "مكتمل";
  }
};

</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      عرض سجل البطاقة  
      </h2>
    </template>
    <div v-if="userCard.card_number">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-green-300 rounded-lg dark:bg-green-300 text-center"
        role="alert"
      >
        <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">
          تم العثور على نتيجة البحث . شكرا لك
        </div>
      </div>
    </div>
    <div v-if="errors">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-pink-300 rounded-lg dark:bg-pink-300 text-center"
        role="alert"
      >
        <div class="ml-3 text-sm font-medium text-pink-700 dark:text-pink-800">
          لم يتم العثور على نتيجة يرجى التواصل مع الدعم الفني في حالة وجود مشكلة
        </div>
      </div>
    </div>
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
        <div class="bg-white overflow-hidden  sm:rounded-lg">
          <div class="p-6 bg-white">
            <div class="row m-auto">
              <div class=" px-4">
                <form class=" max-w-5xl m-auto">
                  <label for="simple-search" class="sr-only">بحث</label>
                  <div class="relative w-full">
                    <div
                      class="
                        absolute
                        inset-y-0
                        left-0
                        flex
                        items-center
                        pl-3
                        pointer-events-none
                      "
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
                      v-model="searchTerm"
                      @input="handleInput(searchTerm)"
                      type="number"
                      id="simple-search"
                      class="
                        bg-gray-50
                        border border-gray-300
                        text-gray-900 text-sm
                        rounded-lg
                        focus:ring-blue-500 focus:border-blue-500
                        block
                        w-full
                        pl-10
                        p-2.5
                        dark:bg-gray-700
                        dark:border-gray-600
                        dark:placeholder-gray-400
                        dark:text-white
                        dark:focus:ring-blue-500
                        dark:focus:border-blue-500
                      "
                      placeholder=" رقم البطاقة او اسم المشترك "
                      required
                    />
                  </div>
                </form>
              </div>
            </div>
          <div class="flex flex-row">
              <div class="grow">
                <div class="pb-3">
                  <div class="mx-auto mx-7">
                    <div class="bg-white overflow-hidden sm:rounded-lg">
                      <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-3">
                            <div className="mb-4 mx-5">
                              <InputLabel for="invoice_number" value="الأسم كامل" />
                              <TextInput
                                id="invoice_number"
                                type="text"
                                class="mt-1 block w-full"
                                :value="userCard?.name"
                                disabled
                              />
                            </div>
                 
                            <div className="mb-4 mx-5">
                              <InputLabel for="percentage" value="رقم الهاتف" />
                              <TextInput
                                id="percentage"
                                type="text"
                                class="mt-1 block w-full"
                                :value="userCard?.phone_number"
                                disabled
                              />
                            </div>
          
                            <div className="mb-4 mx-5">
                              <InputLabel for="percentage" value="المندوب" />
                              <TextInput
                                id="percentage"
                                type="text"
                                class="mt-1 block w-full"
                                :value="userCard.user?.name"
                                disabled
                              />
                  
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex flex-row">
              <div class="grow">
                <div class="pb-3">
                  <div class="mx-auto mx-7">
                    <div class="bg-white overflow-hidden  sm:rounded-lg">
                      <div class="px-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-3 lg:gap-3">
                            <div className="mb-4 mx-5">
                              <InputLabel for="invoice_number" value="أفراد العائلة" />
                              <TextInput
                                id="invoice_number"
                                type="text"
                                class="mt-1 block w-full"
                                :value="userCard?.family_name"
                                disabled
                              />
                            </div>
                 
             
          
                
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="overflow-x-auto " v-if="userCard.appointment">
              <h2 class="text-center text-xl py-4">الأستخدام</h2>
              <table class="w-full my-5">
                <thead
                  class="700 bg-rose-500 text-white text-center rounded-l-lg"
                >
                  <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                    <th className="px-4 py-2">التسلسل</th>
                    <th className="px-4 py-2">الطبيب</th>
                    <th className="px-4 py-2">رقم البطاقة</th>
                    <th className="px-4 py-2">التاريخ والساعة</th>
                    <th className="px-4 py-2">ملاحظة</th>
                    <th className="px-4 py-2">الحالة</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="user in userCard.appointment"
                    :key="user.id"
                    class="hover:bg-gray-100 text-center"
                  >
                  <td className="border px-4 py-2">{{ user.id }}</td>
                  <td className="border px-4 py-2">{{ user?.user?.name }}</td>
                  <td className="border px-4 py-2">{{ user.card_id }}</td>
                  <td className="border px-4 py-2">{{ user.start }}</td>
                  <td className="border px-4 py-2 td">{{ user.note }}</td>
                  <th className="border px-4 py-2">{{ user.is_come==2 ? 'تم التأكيد':user.is_come==0 ? 'تم الإلغاء' : 'في الانتظار' }}</th>
   
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