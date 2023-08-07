<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import VueTailwindDatepicker from 'vue-tailwind-datepicker'
import ModalAddSales from "@/Components/ModalAddSales.vue";
import ModalAddDebt from "@/Components/ModalAddDebt.vue";


import axios from 'axios';

const laravelData = ref({});
const user_id = ref(0);
const searchTerm = ref('');
const showReceiveBtn = ref(0);
let showModalAddSales = ref(false);
let showModaldebtSales = ref(false);
const getResults = async (page = 1) => {
  searchTerm.value = '';
  const response = await fetch(`/getIndexAccounting?page=${page}&user_id=${user_id.value}`);
  laravelData.value = await response.json();
};
function openAddSales() {
  showModalAddSales.value = true;
}
function opendebtSales() {
  showModaldebtSales.value = true;
}
getResults();

const props = defineProps({
  url: String,
  users:Array,
  accounts:Array
});
const search = async (q) => {
  user_id.value=0;
  laravelData.value = [];
  const response = await fetch(`/livesearchAppointment?q=${q}`);
  laravelData.value = await response.json();
};
const form = useForm();

let showModal = ref(false);
const come = async (id) => {
  const response = await fetch(`/appointmentCome?id=${id}`);
      getResults();

};
const cancel = async (id) => {
  const response = await fetch(`/appointmentCancel?id=${id}`);
      getResults();

};

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
function sendToCourt(id) {
  showModal.value = id;
}
function method1(id) {
  form.get(route("sentToCourt", id));
  getResults();
  showModal.value = false;
}
const errors = ref(0);

const dateValue = ref({
    startDate: '',
    endDate: ''
})
const countComp = ref()
const formatter = ref({
  date: 'D/MM/YYYY',
  month: 'MM'
})
const options = ref({
  shortcuts: {
    today: 'اليوم',
    yesterday: 'البارحة',
    past: period => period + ' قبل يوم',
    currentMonth: 'الشهر الحالي',
    pastMonth: 'الشهر السابق'
  },
  footer: {
    apply: 'Terapkan',
    cancel: 'Batal'
  }
})
const dDate = (date) => {
  return date >= new Date() ;
}
const getcountComp = async () => {
    const response = await fetch(`getcount?start=${dateValue.value.startDate}&end=${dateValue.value.endDate}`);
    countComp.value = await response.json();
}
function confirm(V) {
  axios.post('/api/salesCard',V)
  .then(response => {
    showModalAddSales.value=false;
    console.log(response.data);
  })
  .catch(error => {

    errors.value = error.response.data.errors
  })
}
function confirmdebt(V) {
  axios.post('/api/salesDebt',V)
  .then(response => {
    showModaldebtSales.value=false;
    console.log(response.data);
  })
  .catch(error => {

    errors.value = error.response.data.errors
  })
}

getcountComp()
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
         المواعد المحجوزة
      </h2>
    </template>
    <ModalAddSales
            :show="showModalAddSales ? true : false"
            :data="users"
            :accounts="accounts"
            @a="confirm($event)"
            @close="showModalAddSales = false"
            >
          <template #header>
            <h3 class="text-center">المحاسبة</h3>
            
           </template>
      </ModalAddSales>
      <ModalAddDebt
            :show="showModaldebtSales ? true : false"
            :data="users"
            :accounts="accounts"
            @a="confirmdebt($event)"
            @close="showModaldebtSales = false"
            >
          <template #header>
            <h3 class="text-center">ادخال سلفة</h3>
            
           </template>
      </ModalAddDebt>
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
            <div class="flex flex-row">
                <div class="basis-1/2 px-4">

            <div className="flex items-center justify-between mb-6">
              <button  v-if="$page.props.auth.user.type_id==1 || $page.props.auth.user.type_id==2" className="px-6 py-2 text-white bg-rose-500 rounded-md focus:outline-none"
                                            @click="openAddSales()">
                                            مبيعات جديدة
              </button>
              <button  v-if="$page.props.auth.user.type_id==1 || $page.props.auth.user.type_id==2" className="px-6 py-2 text-white bg-rose-500 rounded-md focus:outline-none"
                                            @click="opendebtSales()">
                                             سلفة
              </button>
             </div>
            </div>
             </div>
            <div class="flex flex-row">
              <div class="basis-1/2 px-4">
                <select @change="getResults()" v-model="user_id" id="default" class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500">
                  <option value="0">الجميع</option>
                  <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.name }}</option>
                </select>
              </div>
              <div class="basis-1/2 px-4">
                      <div class="flex flex-row">
                                    <div class="basis-1/4">
                                      <button
                                        type="button"
                                        @click="getcountComp()"
                                        style="width: 70%;"
                                        className="px-6 mb-12 mx-2 py-2 font-bold text-white bg-rose-500 rounded"
                                      >
                                      فلترة
                                      </button>
                                    </div>
                                    <div class="basis-3/4" style="direction: ltr;">
                                      <vue-tailwind-datepicker overlay :options="options" :disable-date="dDate"  i18n="ar"  as-single use-range v-model="dateValue" />
                                    </div>
                  </div>
              </div>
            </div>

            <div class="overflow-x-auto shadow-md">
              <table class="w-full my-5">
                <thead
                  class="700 bg-rose-500 text-white text-center rounded-l-lg"
                >
                  <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                    <th className="px-4 py-2">التسلسل</th>
                    <th className="px-4 py-2">نوع الحركة</th>
                    <th className="px-4 py-2">التاريخ</th>
                    <th className="px-4 py-2">الوصف</th>
                    <th className="px-4 py-2">المبلغ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="user in laravelData.transactions"
                    :key="user.id"
                    class="hover:bg-gray-100 text-center"
                  >
                  <td className="border px-4 py-2">{{ user.id }}</td>
                  <td className="border px-4 py-2">{{ user?.type }}</td>
                  <td className="border px-4 py-2">{{ user?.created }}</td>
                  <th className="border px-4 py-2">{{ user.description }}</th>
                  <td className="border px-4 py-2">{{ user.amount }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mt-3 text-center" style="direction: ltr;">
              <TailwindPagination
                :data="laravelData"
                @pagination-change-page="getResults"
                :limit ="2"
              />
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