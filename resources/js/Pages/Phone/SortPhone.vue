
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { onMounted } from 'vue'
import { DataTable } from 'simple-datatables'
import ModalAsinPhone from "@/Components/ModalAsinPhone.vue";
import { ref } from 'vue'
let show = ref(false)
let ids = 0;

const showModalAssign = (id) => {
   ids= id
  show.value = true
}

const props = defineProps({  users: Array});


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
</script>

<template>
  <Head title="Dashboard" />
  <ModalAsinPhone :show="show" :userId="ids" @close="show = false" @a="assignPhone" >
    </ModalAsinPhone>


  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
         فرز الارقام
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
            <th class="px-4 py-3 text-center">المندوب</th>
            <th class="px-4 py-3 text-center">البريد الالكتروني</th>
            <th class="px-4 py-3 text-center">تنفيذ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, i) in users" :key="user.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2 text-center">{{ i + 1 }}</td>
            <td class="px-4 py-2 text-center">{{ user.name }}</td>
            <td class="px-4 py-2 text-center">{{ user.email }}</td>
            <td class="px-4 py-2 text-center">
              <button
                    tabIndex="1"
                    :className="'px-2 py-1 text-white mx-1 bg-slate-500 rounded user-' + user.id  "
                     @click="showModalAssign(user.id)">
                     اسناد
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
