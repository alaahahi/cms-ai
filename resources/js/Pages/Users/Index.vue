
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted } from 'vue'
import { DataTable } from 'simple-datatables'
import ModalUsersCustom from "@/Components/ModalUsersCustom.vue";
import ModalUsersCustomEdit from "@/Components/ModalUsersCustomEdit.vue";
import axios from 'axios'
import { ref } from 'vue'
import { useToast } from "vue-toastification";
const toast = useToast();

let show = ref(false)
let ids = 0;
let names = '';
let percentages = 0;
let showModalUsers = ref(false)
let showModalUsersEdit = ref(false)
const showModalAssign = (id, name,percentage,email    ) => {
    ids= id
    names = name
    percentages = percentage
   showModalUsersEdit.value = true
}

const props = defineProps({  users: Array});


onMounted(() => {
  const el = document.querySelector("#default-table")
  if (el) {
    const dataTable = new DataTable(el, {
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

    // إضافة listener على الزر "تنفيذ"
    el.addEventListener('click', (e) => {
        if (e.target.matches('.btn-edit')) {
            const id = e.target.getAttribute('data-id')
            const name = e.target.getAttribute('data-name')
            const percentage = e.target.getAttribute('data-percentage')
            const email = e.target.getAttribute('data-email')

            showModalAssign(id, name,percentage,email)
        }
    })
  }
})

const addUser = (form) => {
  axios.post('/api/add-user-custom', form) 
  .then(response => {
    showModalUsers.value = false
    toast.success("تم اضافة المندوب بنجاح")
    window.location.reload()
  })
  .catch(error => {
     if(error.response.status == 422) {
      toast.error("حدث خطأ ما او الايميل مستخدم من قبل يرجى كتابة ايميل جديد")
      }

  })  
}

const editUser = (form) => {
  axios.post('/api/edit-user-custom', form) 
  .then(response => {
    showModalUsersEdit.value = false
    toast.success("تم التعديل على المندوب بنجاح")
    window.location.reload()
  })
  .catch(error => {
     if(error.response.status == 422) {
      toast.error("حدث خطأ ما او الايميل مستخدم من قبل يرجى كتابة ايميل جديد")
      }

  })  
}
</script>

<template>
  <Head title="Dashboard" />
    <ModalUsersCustom :show="showModalUsers" @close="showModalUsers = false" @a="addUser"  ></ModalUsersCustom>
    <ModalUsersCustomEdit :show="showModalUsersEdit" @close="showModalUsersEdit = false" @a="editUser" :percentage="percentages"  :id="ids" :name="names"   ></ModalUsersCustomEdit>
  <AuthenticatedLayout>
    <div class="p-6" >
 
    <div class="overflow-x-auto">
      <div class="overflow-x-auto rounded-lg">
        <button @click="showModalUsers = true" class="px-6 py-2 mb-4 font-bold text-white bg-blue-500 rounded">اضافة مندوب</button>
      <table id="default-table" class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="px-4 py-3 text-center">#</th>
            <th class="px-4 py-3 text-center">المندوب</th>
            <th class="px-4 py-3 text-center">نسبة المبيعات بالدينار لكل بطاقة</th>
             <th class="px-4 py-3 text-center">تنفيذ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, i) in users" :key="user.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-2 text-center">{{ i + 1 }}</td>
            <td class="px-4 py-2 text-center">{{ user.name }}</td>
            <td class="px-4 py-2 text-center">{{ user.percentage }}</td>
             <td class="px-4 py-2 text-center">
              <button
                    tabIndex="1"
                     :data-id="user.id"
                     :data-name="user.name"
                      
                     :data-percentage="user.percentage"
                     :className="'px-3 btn-edit py-1 text-white mx-1 bg-rose-600 rounded user-' + user.id  "
                     >تعديل</button>
            </td>
          </tr>
        </tbody>
      </table>
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
