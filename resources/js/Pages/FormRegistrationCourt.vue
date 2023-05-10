<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { TailwindPagination } from 'laravel-vue-pagination';

const laravelData = ref({});
const userLocation = ref({});
const getResults = async (page = 1) => {
    const response = await fetch(`/getIndexFormRegistrationCourt?page=${page}`);
    laravelData.value = await response.json();
}

getResults();


const props = defineProps({
    url:String
});

const form = useForm();

let showModal =  ref(false);
const  results = (id) => {
    if(id==0){
        return 'انتظار التحليل';
    }
    if(id==1){
        return 'انتظار الطبيب';
    }
    if(id==2){
        return 'مرفوض';
    }
}
function sendToCourt(id) {
    showModal.value = id;
    
}
function method1 (id)  {
    form.get(route('sentToCourt', id))
    getResults()
    showModal.value = false
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 البطاقات - المحكمة
            </h2>
        </template>
        <modal :show="showModal ? true : false" :data="showModal.toString()" @a="method1($event, arg1)"  @close="showModal = false">
        <template #header>
            <h3 class="text-center"> إدارة الاستمارات</h3>
        </template>
        </modal>
        <div v-if="$page.props.success">
            <div id="alert-2" class=" p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center" role="alert" >
            <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800">
                {{ $page.props.success }}
            </div>
            </div>
        </div>
        <div class="py-12">
      
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200"> 
                        <div class="overflow-x-auto shadow-md ">
                        <table  class="w-full my-5">      
                            <thead class="700 bg-rose-500 text-white text-center rounded-l-lg">
                                <tr  class="bg-rose-500  rounded-l-lg  mb-2 sm:mb-0">
                                    <th className="px-4 py-2 w-20">الرقم</th>
                                    <th className="px-4 py-2">اسم الزوج</th>
                                    <th className="px-4 py-2">اسم الزوجة</th>
                                    <th className="px-4 py-2">رقم الموبايل</th>
                                    <th className="px-4 py-2">عنوان الزوج</th>
                                    <th className="px-4 py-2">عنوان الزوجة</th>   
                                    <th className="px-4 py-2">تاريخ الإدخال</th>
                                    <th className="px-4 py-2">تنفيذ</th>          
                                </tr>
                            </thead>
                            <tbody >
                              
                                <tr v-for="user in laravelData.data" :key="user.id"  class=" hover:bg-gray-100 text-center"  >
                                    <td className="border px-4 py-2">{{ user.no }}</td>
                                    <td className="border px-4 py-2 td">{{ user.husband_name }}</td>
                                    <td className="border px-4 py-2 td">{{ user.wife_name }}</td>
                                    <td className="border px-4 py-2 td">{{ user.phone_number  }}</td>
                                    <td className="border px-4 py-2 td">{{ user.husband_address }}</td>
                                    <td className="border px-4 py-2 td">{{ user.wife_address }}</td>
                                    <td className="border px-4 py-2">{{ (user.created_at).substring(0, 10) }}</td>
                                    <td className="border px-2 py-2" >                                       
                                        <a 
                                            tabIndex="-1"
                                            className="mx-1 px-2 py-1 text-sm text-white bg-gray-400 rounded"
                                            :href="route('document', user.id)"
                                            target="_self"
                                        >
                                             طباعة
                                        </a>
                                        <a 
                                            tabIndex="-1"
                                            className="mx-1 px-2 py-1 text-sm text-white bg-blue-400 rounded"
                                            :href="route('show', user.id)"
                                            target="_blank"
                                        >
                                             عرض
                                        </a>               
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        <div class="mt-3 text-center"  style="direction: ltr;">
                            <TailwindPagination
                                :data="laravelData"
                                @pagination-change-page="getResults"
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