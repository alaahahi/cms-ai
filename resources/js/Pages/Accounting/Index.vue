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
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="mb-4">
                      <InputLabel for="sales_id" value="البطاقة" />
                      <select
                         @change="getResults()"

                        v-model="card_id"
                        id="userType"
                        class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      >
                        <option selected disabled>البطاقة</option>
                        <option
                          v-for="(type ,index) in cards"
                          :key="index"
                          :value="type.id"
                        >
                          {{ type.name }}
                        </option>
                      </select>
                      <div v-if="errors?.saler_id">
                        البطاقة حقل مطلوب
                      </div>
                    </div>
                </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="card_id">
          
          <div class="p-6 bg-white  dark:bg-gray-900">
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 lg:gap-3">
              <div class="pt-5  print:hidden">
              <button  v-if="$page.props.auth.user.type_id==1 || $page.props.auth.user.type_id==2 || $page.props.auth.user.type_id==5" className="px-4 py-2 text-white bg-rose-500 rounded-md focus:outline-none"
                                            @click="openAddSales()">
                                            مبيعات جديدة
              </button>
              </div>
              <!-- <div class="pt-5  print:hidden">
              <button  v-if="$page.props.auth.user.type_id==1 || $page.props.auth.user.type_id==2|| $page.props.auth.user.type_id==5" className="px-4 py-2 text-white bg-yellow-500 rounded-md focus:outline-none"
                                            @click="opendebtSales()">
                                            سلفة مندوبين 
              </button>
              </div>
              <div class="pt-5  print:hidden">
              <button  v-if="$page.props.auth.user.type_id==1 || $page.props.auth.user.type_id==2|| $page.props.auth.user.type_id==5" className="px-4 py-2 text-white bg-blue-500 rounded-md focus:outline-none"
                                            @click="openAddExpenses()">
                                             اضافة مصاريف
              </button>
              </div> -->
              <div class=" px-4">
                          <div >
                              <InputLabel for="from" value="من تاريخ" />
                              <TextInput
                                id="from"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="from"
                                
                              />
                            </div>
              </div>
              <div class=" px-4">
                            <div >
                              <InputLabel for="to" value="حتى تاريخ" />
                              <TextInput
                                id="to"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="to"
                              />
                            </div>
              </div>
              <div className=" mr-5 print:hidden">
                            <InputLabel for="pay" value="فلترة" />
                            <button
                            @click.prevent="getResults()"
                            class="px-6 mb-12 py-2 mt-1 font-bold text-white bg-gray-500 rounded" style="width: 100%">
                            <span v-if="!isLoading">فلترة</span>
                            <span v-else>جاري الحفظ...</span>
                          </button>
              </div>
              <div className=" mr-5 print:hidden" >
                            <InputLabel for="pay" value="طباعة" />
                            <a
                            class="px-6 mb-12 py-2 mt-1 font-bold text-white bg-orange-500 rounded" style="display: block;text-align: center;"
                            :href="`/getIndexAccounting?user_id=${laravelData?.user?.id}&from=${from}&to=${to}&print=1`"
                            target="_blank"
                            >
                            
                            <span v-if="!isLoading">طباعة</span>
                            <span v-else>جاري الحفظ...</span>
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

            <div class="overflow-x-auto shadow-md">
              <table class="w-full my-5">
                <thead
                  class="700 bg-rose-500 text-white text-center rounded-l-lg"
                >
                  <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                    <th className="px-4 py-2">رقم الوصل</th>
                    <th className="px-4 py-2">التاريخ</th>
                    <th className="px-4 py-2">الوصف</th>
                    <th className="px-4 py-2">المبلغ</th>
                    <th className="px-4 py-2">تنفيذ</th>

                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="user in laravelData.transactions"
                    :key="user.id"
                    class="hover:bg-gray-100 text-center"
                  >
                  <td className="border px-4 py-2">{{ user.id }}</td>
                  <td className="border px-4 py-2">{{ user?.created }}</td>
                  <th className="border px-4 py-2">{{ user.description }}</th>
                  <td className="border px-4 py-2">{{ user.amount }}</td>
                  <td className="border px-4 py-2">
                    <button class="px-6 py-2 text-white bg-pink-500 rounded-md focus:outline-none" @click="delTransactions(user.id)" >
                            حذف
                    </button>
                  </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- <div class="mt-3 text-center" style="direction: ltr;">
              <TailwindPagination
                :data="laravelData"
                @pagination-change-page="getResults"
                :limit ="2"
              />
            </div> -->
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