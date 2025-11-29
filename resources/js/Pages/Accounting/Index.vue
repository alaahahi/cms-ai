<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import ModalAddSales from "@/Components/ModalAddSales.vue";
import ModalAddDebt from "@/Components/ModalAddDebt.vue";
import ModalAddExpenses from "@/Components/ModalAddExpenses.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import axios from 'axios';

const laravelData = ref({});
const user_id = ref(0);
const searchTerm = ref('');
let card_id = ref(0);
let showModalAddSales = ref(false);
let showModaldebtSales = ref(false);
let showModalAddExpenses = ref(false);
let isLoading=ref(false);
let from = ref(0);
let to = ref(0);
const getResults = async (page = 1) => {
  searchTerm.value = '';
  const response = await fetch(`/getIndexAccounting?page=${page}&user_id=${props.boxes[0].id}&from=${from.value}&to=${to.value}&card_id=${card_id.value}`);
  laravelData.value = await response.json();
};
function openAddSales() {
  showModalAddSales.value = true;
}
function opendebtSales() {
  showModaldebtSales.value = true;
}
function openAddExpenses(){
  showModalAddExpenses.value = true;
}

const props = defineProps({
  url: String,
  users:Array,
  accounts:Array,
  boxes:Array,
  cards:Array
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
  axios.post('/api/salesCard?card_id='+card_id.value,V)
  .then(response => {
    showModalAddSales.value=false;
    getResults();
  })
  .catch(error => {

    errors.value = error.response.data.errors
  })
}
function confirmdebt(V) {
  axios.post('/api/salesDebt',V)
  .then(response => {
    getResults();
    showModaldebtSales.value=false;
    showModalAddExpenses.value = false;
  })
  .catch(error => {

    errors.value = error.response.data.errors
  })
}

getcountComp()

function getTodayDate() {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}
function delTransactions(id){
  axios.post(`/api/delTransactions?id=${id}`)
  .then(response => {
    getResults();
    showModaldebtSales.value=false;
    showModalAddExpenses.value = false;
  })
  .catch(error => {

    errors.value = error.response.data.errors
  })
}
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white  leading-tight">
        المحاسبة
      </h2>
    </template>
    <ModalAddSales
            :show="showModalAddSales ? true : false"
            :data="users"
            :card_id="card_id"
            :cards="cards"
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
      <ModalAddExpenses 
            :show="showModalAddExpenses ? true : false"
            :boxes="boxes"
            @a="confirmdebt($event)"
            @close="showModalAddExpenses = false"
            >
          <template #header>
            <h3 class="text-center">ادخال مصاريف اليومية</h3>
            
           </template>
      </ModalAddExpenses>
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
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
          <div class="p-6">
                <div className="mb-6">
                      <InputLabel for="sales_id" value="اختر نوع البطاقة" class="mb-2 font-semibold text-gray-700 dark:text-gray-300" />
                      <select
                         @change="getResults()"
                        v-model="card_id"
                        id="userType"
                        class="pr-8 bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all hover:border-blue-400"
                      >
                        <option value="" selected disabled>-- اختر البطاقة --</option>
                        <option
                          v-for="(type ,index) in cards"
                          :key="index"
                          :value="type.id"
                        >
                          {{ type.name }}
                        </option>
                      </select>
                      <div v-if="errors?.saler_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        البطاقة حقل مطلوب
                      </div>
                    </div>
          </div>
        </div>
      </div>
    </div>
    <div class="py-12" v-if="card_id">
      <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
          
          <div class="p-6 bg-white dark:bg-gray-900">
            <!-- صف الأزرار الرئيسية -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-4 mb-6">
              <!-- زر مبيعات جديدة -->
              <div class="print:hidden">
                <InputLabel for="new_sales" value="إضافة مبيعات" class="mb-2 font-semibold text-gray-700 dark:text-gray-300" />
                <button  
                  v-if="$page.props.auth.user.type_id==1 || $page.props.auth.user.type_id==2 || $page.props.auth.user.type_id==5" 
                  className="w-full px-6 py-3 text-white bg-rose-500 hover:bg-rose-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg font-medium"
                  @click="openAddSales()">
                  <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    مبيعات جديدة
                  </span>
                </button>
              </div>
              
              <!-- تاريخ من -->
              <div>
                <InputLabel for="from" value="من تاريخ" class="mb-2 font-semibold text-gray-700 dark:text-gray-300" />
                <TextInput
                  id="from"
                  type="date"
                  class="mt-1 block w-full"
                  v-model="from"
                />
              </div>
              
              <!-- تاريخ إلى -->
              <div>
                <InputLabel for="to" value="حتى تاريخ" class="mb-2 font-semibold text-gray-700 dark:text-gray-300" />
                <TextInput
                  id="to"
                  type="date"
                  class="mt-1 block w-full"
                  v-model="to"
                />
              </div>
              
              <!-- زر فلترة -->
              <div className="print:hidden">
                <InputLabel for="filter" value="فلترة" class="mb-2 font-semibold text-gray-700 dark:text-gray-300" />
                <button
                  @click.prevent="getResults()"
                  :disabled="isLoading"
                  class="w-full px-6 py-3 mt-1 font-bold text-white bg-gray-500 hover:bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                  <span v-if="!isLoading" class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    فلترة
                  </span>
                  <span v-else class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    جاري التحميل...
                  </span>
                </button>
              </div>
              
              <!-- زر طباعة -->
              <div className="print:hidden">
                <InputLabel for="print" value="طباعة" class="mb-2 font-semibold text-gray-700 dark:text-gray-300" />
                <a
                  class="w-full px-6 py-3 mt-1 font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2"
                  :href="`/getIndexAccounting?user_id=${laravelData?.user?.id}&from=${from}&to=${to}&print=1`"
                  target="_blank"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                  </svg>
                  طباعة
                </a>
              </div>
            </div>
          
            <!-- <div class="flex flex-row">
              <div class="basis-1/2 ">
                <select @change="getResults()" v-model="user_id" id="default" class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500">
                  <option value="0">الجميع</option>
                  <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.name }}</option>
                </select>
              </div>
            </div> -->

            <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
              <table class="w-full my-5">
                <thead
                  class="bg-rose-500 text-white text-center"
                >
                  <tr class="bg-rose-500">
                    <th className="px-6 py-4 font-semibold text-sm">رقم الوصل</th>
                    <th className="px-6 py-4 font-semibold text-sm">التاريخ</th>
                    <th className="px-6 py-4 font-semibold text-sm">الوصف</th>
                    <th className="px-6 py-4 font-semibold text-sm">المبلغ</th>
                    <th className="px-6 py-4 font-semibold text-sm print:hidden">تنفيذ</th>

                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                  <tr
                    v-for="user in laravelData.transactions"
                    :key="user.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 text-center border-b border-gray-200 dark:border-gray-700 transition-colors"
                  >
                  <td className="px-6 py-4 text-gray-900 dark:text-gray-100 font-medium">{{ user.id }}</td>
                  <td className="px-6 py-4 text-gray-700 dark:text-gray-300">{{ user?.created }}</td>
                  <td className="px-6 py-4 text-gray-700 dark:text-gray-300">{{ user.description }}</td>
                  <td className="px-6 py-4 text-gray-900 dark:text-gray-100 font-semibold">{{ new Intl.NumberFormat('en-US').format(user.amount) }} د.ع</td>
                  <td className="px-6 py-4 print:hidden">
                    <button class="px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all text-sm font-medium" @click="delTransactions(user.id)" >
                            <span class="flex items-center gap-1">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                              </svg>
                              حذف
                            </span>
                    </button>
                  </td>
                  </tr>
                  <tr v-if="!laravelData.transactions || laravelData.transactions.length === 0" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                      لا توجد معاملات لعرضها
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- ملخص المبالغ -->
            <div v-if="laravelData.transactions && laravelData.transactions.length > 0" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-1">إجمالي المعاملات</div>
                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                  {{ laravelData.transactions?.length || 0 }}
                </div>
              </div>
              <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="text-sm text-green-600 dark:text-green-400 font-medium mb-1">إجمالي المبالغ</div>
                <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                  {{ new Intl.NumberFormat('ar-IQ').format(laravelData.transactions?.reduce((sum, t) => sum + (parseFloat(t.amount) || 0), 0) || 0) }} د.ع
                </div>
              </div>
              <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                <div class="text-sm text-purple-600 dark:text-purple-400 font-medium mb-1">متوسط المبلغ</div>
                <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                  {{ laravelData.transactions?.length > 0 ? new Intl.NumberFormat('ar-IQ').format(Math.round((laravelData.transactions.reduce((sum, t) => sum + (parseFloat(t.amount) || 0), 0) || 0) / laravelData.transactions.length)) : 0 }} د.ع
                </div>
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