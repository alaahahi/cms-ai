<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

const laravelData = ref({});
const user_id = ref(0);
const isLoading = ref(0);
let card_id = ref(0);

const showReceiveBtn = ref(0);
const getResults = async (page = 1) => {
  const response = await fetch(
    `/getIndexAccountsSelas?page=${page}&user_id=${user_id.value}&card_id=${card_id.value}`
  );
  laravelData.value = await response.json();
};


const props = defineProps({
  url: String,
  users:Array,
  cards:Array

});

const form = useForm();

let showModal = ref(false);
const pay = async (id) => {
  const response = await fetch(`/paySelse/${id}?card_id=${card_id.value}`);
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
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white  leading-tight">
           الهدف المباشر
      </h2>
    </template>
    <modal
      :show="showModal ? true : false"
      :data="showModal.toString()"
      @a="method1($event, arg1)"
      @close="showModal = false"
    >
      <template #header>
        <h3 class="text-center">إدارة الاستمارات</h3>
      </template>
    </modal>
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
    <div class="py-4">
      <h2 class="text-center pb-2">فاتورة مبيعات</h2>
      <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white  border-gray-200">
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

            <div class="flex flex-row">
              <div class="basis-1/2 px-4">
                <InputLabel class="mb-1" for="invoice_number" value="حساب" />
                <select @change="getResults()" v-model="user_id" id="default" class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500">
                  <option value="0" disabled>اختار المندوب</option>
                  <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.name }}</option>
                </select>
              </div>
              <div class="basis-1/2">
                            <div className="mb-4 mx-5">
                              <InputLabel for="totalAmount" value="المجموع المطلوب بالدينار العراقي" />
                              <TextInput
                                id="totalAmount"
                                type="text"
                                class="mt-1 block w-full"
                                :value="laravelData.sales?.wallet?.balance"
                                disabled
                              />
                            </div>
                </div>
            </div>
            <div class="flex flex-row">
              <div class="grow">
                <div class="pb-3">
                  <div class="mx-auto">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                      <div class=" bg-white  border-gray-200">
                        <div class="flex flex-row">
                          <div class="basis-1/2">
                            <div className="mb-4 mx-5">
                              <InputLabel for="percentage" value="نسبة البيع" />
                              <TextInput
                                id="percentage"
                                type="text"
                                class="mt-1 block w-full"
                                :value="laravelData.sales?.percentage"
                                disabled
                              />
                            </div>
                          </div>
                          <div class="basis-1/2">
                            <div className="mb-4 mx-5">
                              <InputLabel for="date" value="بتاريخ" />
                              <TextInput
                                id="date"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="laravelData.date"
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
            </div>
            <div class="flex flex-row">
              <div class="grow">
                <div class="pb-3">
                  <div class="mx-auto">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                      <div class="bg-white">
                        <div class="flex flex-row">
                          <div class="basis-1/3">
                            <div className="mb-4 mx-5">
                              <InputLabel for="percentage" value="عدد البطاقة تم بيعها" />
                              <TextInput
                                id="percentage"
                                type="text"
                                class="mt-1 block w-full"
                                :value="laravelData.sales?.wallet?.card"
                                disabled
                              />
                            </div>
                          </div>
                          <div class="basis-1/3">
                            <div className="mb-4 mx-5">
                              <InputLabel for="totalAmount" value="مجموع المبيعات بالدينار العراقي" />
                              <TextInput
                                id="totalAmount"
                                type="text"
                                class="mt-1 block w-full"
                                :value="laravelData.totalAmount"
                                disabled
                              />
                            </div>
                          </div>
                          <div class="basis-1/3">
                            <div className="mb-4 mx-5">
                              <InputLabel for="debt" value="مجموع السلف" />
                              <TextInput
                                id="debt"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="laravelData.debt"
                                disabled
                              />
                            </div>
                          </div>
                        </div>
                        <div class="flex flex-row">
                          <div class="basis-1/3">
                            <div className="mb-4  print:hidden">
                              <InputLabel for="pay" value="تأكيد الدفع" />
                              <button
                              @click.prevent="pay(laravelData.sales?.id)"
                              :disabled="isLoading || !parseInt(laravelData.totalAmount)"
                              class="px-6 mb-12 mx-2 py-2 mt-1 font-bold text-white bg-green-500 rounded" style="width: 100%"
                            >
                              <span v-if="!isLoading">دفع</span>
                              <span v-else>جاري الحفظ...</span>
                            </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="overflow-x-auto shadow-md">
              <table class="w-full my-5">
                <thead
                  class="700 bg-rose-500 text-white text-center rounded-l-lg">
                  <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                    <th className="px-2 py-2" style="width: 70px;">تم الدفع</th>
                    <th className="px-2 py-2" style="width: 90px;">التاريخ</th>
                    <th className="px-4 py-2" style="min-width: 320px;">الوصف</th>
                    <th className="px-2 py-2">المبلغ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="user in laravelData.data"
                    :key="user.id"  class="hover:bg-gray-100 text-center">
                  <td className="border px-2 py-2">{{ user.is_pay ? 'نعم' :'لا' }}</td>
                  <td className="border px-2 py-2 td">{{ user.created }}</td>
                  <td className="border px-4 py-2 td">{{ user.description }}</td>
                  <td className="border px-2 py-2 td">{{ user.amount  }}</td>
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
    <div class="max-w-7xl mx-auto  px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-row">
                      <div class="basis-1/2">
                            توقيع صاحب الحساب
                            <br>
                            {{ laravelData.sales?.name }}
                        </div>
                        <div class="basis-1/2  text-center">
                           توقيع قسم المحاسبة
                        </div>
                        <div class="basis-1/2 text-end">
                            توقيع المدير
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