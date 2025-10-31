
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/inertia-vue3";
import { onMounted } from 'vue'
import ModalSure from "@/Components/ModalSure.vue";
import { ref, computed } from 'vue'
import { TailwindPagination } from "laravel-vue-pagination";

const props = defineProps({  
    numbers: Object  // Laravel pagination object
});

// استخراج البيانات من pagination object
const numbersList = computed(() => {
    return props.numbers?.data || [];
});

let showSure = ref(false)
let phoneSelected = ref();
let status = 0;
function getStatusHtml(status) {
  const map = {
    0: ['لم يتم الإسناد', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100 '],
    1: ['تم الإسناد', 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100'],
    2: ['تم قبول العرض', 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'],
    3: ['رفض العرض', 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100'],
    4: ['معاودة مره اخرى', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'],
    5: ['مشغول', 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100'],
  }

  const [label, classes] = map[status] || ['غير معروف', 'bg-gray-100 text-gray-800']

  return `<span class="text-xs text-white font-medium me-2 px-2.5 py-0.5 rounded-full ${classes}">${label}</span>`
}

const showModalSure = (phone, status) => {
   phoneSelected.value= phone
   console.log(phoneSelected.value)
  showSure.value = true
  status = status
}
  

// دالة للتنقل بين الصفحات
const getResults = (page) => {
    if (page && props.numbers?.links && props.numbers.links[page]?.url) {
        const url = props.numbers.links[page].url;
        if (url) {
            // استخدام window.location للتنقل (أبسط وأكثر موثوقية)
            window.location.href = url;
        }
    }
}

const surePhone = () => {
  console.log(ids)
}
  </script>

<template>
  <Head title="Dashboard" />
 
  <ModalSure :show="showSure" :phone="phoneSelected" :status="status" @close="showSure = false" @a="surePhone" >
  </ModalSure>


  <AuthenticatedLayout>
    <div class="p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">عرض الارقام</h1>

    <div class="overflow-x-auto">
      <div class="overflow-x-auto rounded-lg">
      <table id="default-table" class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="px-4 py-3 text-center">#</th>
            <th class="px-4 py-3 text-center">رقم واتساب</th>
            <th class="px-4 py-3 text-center">رقم الهاتف</th>
            <th class="px-4 py-3 text-center">الصورة</th>
            <th class="px-4 py-3 text-center">تاريخ</th>
            <th class="px-4 py-3 text-center">الاسم</th>
            <th class="px-4 py-3 text-center">الملاحظات</th>
            <th class="px-4 py-3 text-center">الحالة</th>
            <th class="px-4 py-3 text-center">تنفيذ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, i) in numbersList" :key="user.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-2 text-center">{{ numbers ? (numbers.current_page - 1) * numbers.per_page + i + 1 : i + 1 }}</td>
            <td class="px-4 py-2 text-center">
              <a :href="`https://wa.me/${user.phone}`" target="_blank" class="text-blue-600 hover:underline">
              {{ user.phone }}
              </a>
            </td>
            <td class="px-4 py-2 text-center">
              <a :href="`tel:${user.phone}`" target="_blank" class="text-green-600 hover:underline">
              {{ user.phone }}
              </a>
            </td>
            <td class="px-4 py-2 text-center">{{ user.image_name || '-' }}</td>
            <td class="px-4 py-2 text-center">{{ user.created_at ? (user.created_at.split('T') ? user.created_at.split('T')[0] : user.created_at) : '-' }}</td>
            <td class="px-4 py-2 text-center">{{ user.name || '-' }}</td>
            <td class="px-4 py-2 text-center">{{ user.note || '-' }}</td>
            <td class="px-4 py-2 text-center" style="min-width: 140px;"  v-html="getStatusHtml(user.status)"></td>

            <td class="px-4 py-2 text-center">
               <button
                     class="px-3 py-1 text-white mx-1 bg-rose-600 rounded hover:bg-rose-700 transition"
                     @click="showModalSure(user, 'OfferAccepted')">
                     تنفيذ
            </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Pagination من السيرفر -->
    <div v-if="numbers && numbers.last_page > 1" class="mt-6 flex justify-center" style="direction: ltr;">
      <TailwindPagination 
        :data="numbers" 
        @pagination-change-page="getResults"
        :limit="5"
      />
    </div>
    
    <!-- معلومات Pagination -->
    <div v-if="numbers" class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
      عرض {{ numbers.from }} إلى {{ numbers.to }} من {{ numbers.total }} سجل
    </div>
    
    </div>
  </div>
  </AuthenticatedLayout>
</template>

<style>
/* الحاوية العامة للـ pagination */
.datatable-pagination {
  @apply flex justify-center mt-4 rtl:flex-row-reverse;
  direction: ltr;
}
.datatable-pagination-list-item-link {
    border-radius: 0.25rem !important;
    border-width: 1px !important;
    --tw-border-opacity: 1;
    border-color: rgb(209 213 219 / var(--tw-border-opacity)) !important;
    --tw-bg-opacity: 1;
    background-color: rgb(255 255 255 / var(--tw-bg-opacity)) !important;
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
    padding-top: 0.375rem !important;
    padding-bottom: 0.375rem !important;
    font-size: 0.875rem !important;
    line-height: 1.25rem !important;
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, -webkit-backdrop-filter;
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter, -webkit-backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
    transition-duration: 150ms !important;
}
/* قائمة الصفحات */
.datatable-pagination-list {
  @apply inline-flex items-center space-x-1 rtl:space-x-reverse;
  font-family: 'Tahoma', sans-serif; /* خط واضح وداعم للعربية */
}

/* العنصر الواحد في القائمة */
.datatable-pagination-list-item {
  @apply inline-block;
}

/* الزر داخل كل عنصر */
.datatable-pagination-list-item-link {
  @apply px-3 py-1.5 text-sm border border-gray-300 rounded bg-white hover:bg-gray-100 transition;

  /* لون الخط الافتراضي */
  color: #1f2937; /* Tailwind: text-gray-800 */
  font-weight: 500;
}

/* العنصر النشط (الصفحة الحالية) */
.datatable-pagination-list-item.datatable-active .datatable-pagination-list-item-link {
  @apply bg-blue-600 text-white border-blue-600;
}

/* العناصر المعطلة مثل (…) */
.datatable-pagination-list-item.datatable-disabled .datatable-pagination-list-item-link {
  @apply cursor-not-allowed bg-gray-100 text-gray-400;
}

.datatable-active > button {
 background-color: #007bff !important;
 color: #fff !important;
}
</style>
