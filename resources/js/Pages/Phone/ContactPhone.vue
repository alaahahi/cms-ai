
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted } from 'vue'
import { DataTable } from 'simple-datatables'
import ModalAsinPhone from "@/Components/ModalAsinPhone.vue";
import ModalSure from "@/Components/ModalSure.vue";
import { ref } from 'vue'
let show = ref(false)
let ids = 0;
let showSure = ref(false)
let status = 0;
function getStatusHtml(status) {
  const map = {
    0: ['لم يتم الإسناد', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100 '],
    1: ['تم الإسناد', 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100'],
    2: ['تم قبول العرض', 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'],
    3: ['رفض العرض', 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100'],
    3: ['معاودة مره اخرى', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'],
    4: ['مشغول', 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100'],
  }

  const [label, classes] = map[status] || ['غير معروف', 'bg-gray-100 text-gray-800']

  return `<span class="text-xs text-white font-medium me-2 px-2.5 py-0.5 rounded-full ${classes}">${label}</span>`
}
const showModalAssign = (id) => {
   ids= id
  show.value = true
}
const showModalSure = (id, status) => {
   ids= id
   status = status
   console.log(status)
      showSure.value = true
}
const props = defineProps({  numbers: Array});


onMounted(() => {
  const el = document.querySelector("#default-table")
  if (el) {
    new DataTable(el, {
      perPage: 10,
      perPageSelect: [5, 10, 25, 50],
      searchable: true,
      fixedHeight: true,
      pagination: true,
      perPageDropdown: [5, 10, 25, 50],
      
      labels: {
        placeholder: "بحث...",
        perPage: "عرض كل صفحة",
        noRows: "لا توجد بيانات",
        info: "عرض {start} إلى {end} من أصل {rows} سجل"
      }
    })
  }
})
const surePhone = () => {
  console.log(ids)
  console.log(status)
}
  </script>

<template>
  <Head title="Dashboard" />
  <ModalAsinPhone :show="show" :userId="ids" @close="show = false" @a="assignPhone" >
    </ModalAsinPhone>
    <ModalSure :show="showSure" :userId="ids" :status="status" @close="showSure = false" @a="surePhone" >
      </ModalSure>


  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
         ارقام تم التواصل معها 
      </h2>
    </template>
    <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">قائمة المندوبين</h1>

    <div class="overflow-x-auto">
      <div class="overflow-x-auto rounded-lg border">
      <table id="default-table" class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="px-4 py-3 text-center">#</th>
            <th class="px-4 py-3 text-center">رقم واتساب</th>
            <th class="px-4 py-3 text-center">رقم الهاتف</th>
            <th class="px-4 py-3 text-center">الصورة</th>
            <th class="px-4 py-3 text-center">الاسم</th>
            <th class="px-4 py-3 text-center">الملاحظات</th>
            <th class="px-4 py-3 text-center">الحالة</th>
            <th class="px-4 py-3 text-center">تنفيذ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, i) in numbers" :key="user.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2 text-center">{{ i + 1 }}</td>
            <td class="px-4 py-2 text-center">
              <a :href="`https://wa.me/${user.phone}`" target="_blank">
              {{ user.phone }}
              </a>
            </td>
            <td class="px-4 py-2 text-center">
              <a :href="`tel:${user.phone}`" target="_blank">
              {{ user.phone }}
              </a>
            </td>
            <td class="px-4 py-2 text-center">{{ user.image_name }}</td>
            <td class="px-4 py-2 text-center">{{ user.name }}</td>
            <td class="px-4 py-2 text-center">{{ user.note }}</td>
            <td class="px-4 py-2 text-center"  v-html="getStatusHtml(user.status)"></td>

            <td class="px-4 py-2 text-center">
              <button
                    tabIndex="1"
                    :className="'px-2 py-1 text-white mx-1 bg-slate-500 rounded user-' + user.id  "
                     @click="showModalSure(user.id, 'OfferAccepted')">
                     قبول العرض
                  </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    </div>
  </div>
  </AuthenticatedLayout>
</template>
