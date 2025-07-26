<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
 import InputLabel from "@/Components/InputLabel.vue";
 import TextInput from "@/Components/TextInput.vue";
 import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import axios from 'axios'

const form = useForm({
  name: props.data.name,
  birthdate:  props.data.birthdate,
  certification:  props.data.certification,
  job:  props.data.job,
  address:  props.data.address,
  phone_number:  props.data.phone_number,
  invoice_number:  props.data.invoice_number,
  relatives:  props.data.relatives,
  saler_id:  props.data.user_id,
  card_number:props.data.card_number,
  family_name: props.data.family_name,
  created: props.data.created,
  image: props.data.image,
});
const props = defineProps({
  data: Array,
  url: String,
  sales:Array
});


const submit = () => {
  form.post(route("formRegistrationstoreEdit", props.data.id));
};


const handleImage = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('image', file)
   if (props.data.image) {
    formData.append('old_image', props.data.image)
    formData.append('profile_id', props.data.id)
  }

  try {
    const response = await axios.post('/api/update-image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

     window.location.reload()
  } catch (error) {
    console.error('فشل رفع الصورة:', error)
    alert('حدث خطأ أثناء رفع الصورة')
  }
}
 
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white  leading-tight">
         العقد الإلكتروني
      </h2>
    </template>
    <div class="flex flex-row">
        <div class="grow">
          <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white  dark:bg-gray-900">

              <div className="mb-4">
                <InputLabel for="name" value="الصورة المرفق" />
                <input
                  @change="handleImage"
                  type="file"
                  accept="image/*"
                  class="px-2 mt-3 py-1 font-bold text-white bg-rose-500 rounded"
                />
              </div>
              </div>
              </div>
              </div>
              </div>
              </div>
            </div>
  

    <form name="createForm" @submit.prevent="submit">
      <div class="flex flex-row">
        <div class="grow">
          <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white  dark:bg-gray-900">
                  <h2 class="text-center text-xl py-2">معلومات البطاقة</h2>
                  <div className="flex flex-col">
             
                    <div className="mb-4">
                      <InputLabel for="card_number" value="رقم البطاقة" />

                      <TextInput
                        id="card_number"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.card_number"
                      />

                      <span
                        className="text-red-600"
                        v-if="form.errors.card_number"
                      >
                        رقم البطاقة حقل مطلوب
                      </span>
                    </div>
                    <div className="mb-4">
                      <InputLabel for="name" value="الأسم" />

                      <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                      />

                      <span className="text-red-600" v-if="form.errors.name">
                        الأسم حقل مطلوب
                      </span>
                    </div>
                    <div className="mb-4">
                      <InputLabel for="sales_id" value="المندوب" />
                      <select
                        v-model="form.saler_id"
                        id="userType"
                        class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      >
                        <option selected disabled>المندوب</option>
                        <option
                          v-for="(type ,index) in sales"
                          :key="index"
                          :value="type.id"
                        >
                          {{ type.name }}
                        </option>
                      </select>
                      <span
                        className="text-red-600"
                        v-if="form.errors.saler_id"
                      >
                         اسم المندوب حقل مطلوب
                      </span>
                    </div>


                    <div className="mb-4">
                      <InputLabel for="address" value="العنوان" />

                      <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                      />

                      <span className="text-red-600" v-if="form.errors.address">
                        العنوان حقل مطلوب
                      </span>
                    </div>

                    <div className="mb-4">
                      <InputLabel for="family_name" value="أفراد العائلة" />

                      <TextInput
                        id="family_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.family_name"
                      />

                      <span
                        className="text-red-600"
                        v-if="form.errors.family_name"
                      >
                        أفراد العائلة حقل مطلوب
                      </span>
                    </div>
                    <div className="mb-4">
                      <InputLabel for="created" value="تاريخ البيع" />

                      <TextInput
                        id="created"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.created"
                      />

                    </div>
                    <div className="mb-4 mx-5">
                        <InputLabel for="phone_number" value="رقم الهاتف" />
                        <TextInput
                          id="phone_number"
                          type="text"
                          class="mt-1 block w-full"
                          v-model="form.phone_number"
                        />

                        <span
                          className="text-red-600"
                          v-if="form.errors.phone_number"
                        >
                          رقم الهاتف حقل مطلوب
                        </span>
                      </div>
                  </div>
                  <img :src="'/public/'+form.image" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="flex items-center justify-center my-6 ">
        <Link
          className="px-6 mx-2 py-2 mb-12 text-white bg-gray-500 rounded-md focus:outline-none rounded"
          :href="route('formRegistration')"
        >
          العودة
        </Link>

        <button
          type="submit"
          className="px-6 mb-12 mx-2 py-2 font-bold text-white bg-rose-500 rounded"
        >
          حفظ التعديلات
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>