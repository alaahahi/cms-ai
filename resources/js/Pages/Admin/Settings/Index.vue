<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// الوصول إلى props مباشرة عند استخدام script setup
const props = defineProps({
  settings: Array,
});

// تهيئة النموذج باستخدام useForm
const form = useForm({
  id:"",
  key: "",
  value: "",
  description:'',
  image: null,
});

// دالة لمعالجة رفع الملفات
const handleFileUpload = (event) => {
  form.image = event.target.files[0];
};

// دالة لحفظ الإعدادات
const saveSetting = () => {
  form.post(route("settings.update"));
};

const editSetting = (v) => {
    form.id = v.id
    form.key = v.key
    form.value = v.value
    form.description = v.description 
    form.image= v.image
};
const resutSetting = () => {
    form.id =''
    form.key =''
    form.value = ''
    form.description =''
    form.image=''
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="إدارة إعدادات التطبيق" />

    <div class="max-w-7xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">إدارة إعدادات التطبيق</h1>

      <!-- عرض رسالة النجاح -->
      <div
        v-if="$page.props.flash.success"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"
      >
        {{ $page.props.flash.success }}
      </div>

      <!-- نموذج إضافة الإعدادات -->
      <form
        @submit.prevent="saveSetting"
        enctype="multipart/form-data"
        class="space-y-6 bg-white p-6 rounded shadow-md">
        <div class="my-2">
          <label for="key" class="block text-sm font-medium text-gray-700">
            مفتاح Key
          </label>
          <input
            type="text"
            v-model="form.key"
            id="key"
            :disabled="!form.id==''"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <div class="my-2">
          <label for="value" class="block text-sm font-medium text-gray-700">
            القيمة
          </label>
          <textarea
            v-model="form.value"
            id="value"
            rows="4"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          ></textarea>
        </div>
        
        <div class="my-2">
          <label for="description" class="block text-sm font-medium text-gray-700">
            الوصف
          </label>
          <textarea
            v-model="form.description"
            id="description"
            rows="4"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          ></textarea>
        </div>

        <div class="my-2">
          <label for="image" class="block text-sm font-medium text-gray-700">
            تحميل صور
          </label>
          <input 
            type="file"
            @change="handleFileUpload"
            id="image"
            class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>
      
        <div class="my-2">
          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            حفظ الاعدادت
          </button>
        </div>
      </form>
      <button
          tabIndex="1"
          className="px-2 py-1 text-sm text-white mx-1 bg-rose-500 rounded"
          @click="resutSetting()"
          >
          تهيئة
      </button>
      <hr class="my-8" />

      <!-- عرض الإعدادات الحالية -->
      <h2 class="text-xl font-semibold mb-4">الاعدادت الحالية</h2>
      <div class="overflow-x-auto bg-white rounded shadow-md">
        <div class="overflow-x-auto shadow-md">
              <table class="w-full my-5">
                <thead class="700 bg-rose-500 text-white text-center rounded-l-lg" >
                <tr class="bg-rose-500 rounded-l-lg mb-2 sm:mb-0">
                <th class="px-4 py-2">
                    مفتاح Key
                </th>
                <th class="px-4 py-2">
                    القيمة
                </th>
                <th class="px-4 py-2">
                    نوع
                </th>
                <th class="px-4 py-2">
                    الوصف
                </th>
                <th class="px-4 py-2">
                    العمليات
                </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="setting in props.settings"
              :key="setting.id"
              class="mb-2 sm:mb-0 hover:bg-gray-100 text-center"
            >
              <td class="border px-4 py-2 td">
                {{ setting.key }}
              </td>
              <td class="border px-4 py-2 td">
                <img
                  v-if="setting.type === 'image'"
                  :src="`/public/storage/${setting.value}`"
                  alt="Setting Image"
                  style="width:200px;"
                />
                <span v-else>{{ setting.value }}</span>
              </td>
              <td class="border px-4 py-2 td">
                {{ setting.type }}
              </td>
              <td class="border px-4 py-2 td">
                {{ setting.description }}
              </td>
              <td className="border px-2 py-2">
                      <button
                        tabIndex="1"
                        className="px-2 py-1 text-sm text-white mx-1 bg-slate-500 rounded"
                        @click="editSetting(setting)"
                       >
                        تعديل
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

<style></style>
