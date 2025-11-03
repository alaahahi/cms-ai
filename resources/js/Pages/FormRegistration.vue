<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { WebCamUI } from "vue-camera-lib";
import axios from 'axios';

const form = ref({
  created: ref(getTodayDate()), // Set the initial value to today's date
});

function getTodayDate() {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}
const props =  defineProps({
  usersType: Array,
  sales : Array,
  cards: Array,
  apiKey: String,
  third_title_ar: String,
  phone: String,
});


const userCard = ref(0);

const showHusband = ref(false);

const profileAdded = ref(0);
const errors = ref(0);

const isLoading = ref(false);
const whatsappBatchId = ref(null);
const whatsappProgress = ref(null);
const progressInterval = ref(null);

const submit = () => {
  isLoading.value = true;
  // Remove direct WhatsApp sending - now handled by Job
  // sendWhatsAppMessageArray([form.value.phone_number])

  axios.post('/api/formRegistration', form.value)
  .then(response => {

    profileAdded.value = response.data.profile;
    whatsappBatchId.value = response.data.batch_id;
    
    // Start tracking WhatsApp progress
    if (whatsappBatchId.value) {
      startProgressTracking(whatsappBatchId.value);
    }
    
    form.value={
      created: ref(getTodayDate()), // Set the initial value to today's date
    };
    isLoading.value = false;


  })
  .catch(error => {
    profileAdded.value = false;
    if (error.response && error.response.data && error.response.data) {
      errors.value = error.response.data;
    } else {
      errors.value = { general: ['حدث خطأ غير متوقع'] };
    }
    isLoading.value = false;

    
  })
};

const startProgressTracking = (batchId) => {
  const checkProgress = () => {
    axios.get(`/api/whatsapp-progress?batch_id=${batchId}`)
      .then(response => {
        whatsappProgress.value = response.data;
        
        // Stop tracking if completed
        if (response.data.status === 'completed' || response.data.status === 'failed') {
          if (progressInterval.value) {
            clearInterval(progressInterval.value);
            progressInterval.value = null;
          }
        }
      })
      .catch(error => {
        console.error('Error checking progress:', error);
        // Stop tracking on error
        if (progressInterval.value) {
          clearInterval(progressInterval.value);
          progressInterval.value = null;
        }
      });
  };
  
  // Check immediately
  checkProgress();
  
  // Check every 2 seconds
  progressInterval.value = setInterval(checkProgress, 2000);
  
  // Stop after 5 minutes max
  setTimeout(() => {
    if (progressInterval.value) {
      clearInterval(progressInterval.value);
      progressInterval.value = null;
    }
  }, 300000);
};

const photoHusband = (data) => {
  form.image = data.image_data_url;
  showHusband.value = false;
};
const photoWife = (data) => {
  form.wife_image = data.image_data_url;
  // showWife.value = false;
};
const handleImage = (e) => {
  const selectedImage = e.target.files[0]; // get first file
  createBase64Image(selectedImage);
};
const createBase64Image = (fileObject) => {
  const reader = new FileReader();

  reader.onload = (e) => {
   form.value.image = e.target.result;
  };
  reader.readAsDataURL(fileObject);
};
const handleImageWife = (e) => {
  const selectedImage = e.target.files[0]; // get first file
  createBase64ImageWife(selectedImage);
};
const createBase64ImageWife = (fileObject) => {
  const reader = new FileReader();

  reader.onload = (e) => {
    //form.wife_image = e.target.result;
    //this.uploadImage();
  };
  reader.readAsDataURL(fileObject);
};
let timer = null;
const delay = 1000; // Delay in milliseconds

const handleInput = (v,id) => {
  clearTimeout(timer); // Clear the previous timer

  timer = setTimeout(() => {
    checkCard(v,id); // Call the function to make the Axios request after the delay
  }, delay);
};
const checkCard = (v,id) => {
  axios.get('/api/checkCard?card_number='+v+'&card_id='+id)
  .then(response => {
    userCard.value=response.data;
  })
  .catch(error => {
    userCard.value=0;
  })
};
// Removed sendWhatsAppMessageArray - now handled by Job queue

</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white  leading-tight">
        العقد الإلكتروني
      </h2>
      <!-- <WebCamUI @photoTaken="photoHusband" v-if="showHusband" />
      <WebCamUI @photoTaken="photoWife" v-if="showWife" /> -->
    </template>
    <div v-if="profileAdded">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-green-300 rounded-lg dark:bg-green-300 text-center"
        role="alert"
      >
        <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">
          تم ادخال البطاقة بنجاح رقم 
          {{ profileAdded.card_number }}
          بأسم الزبون
          {{ profileAdded.name }}
        </div>
      </div>
      
      <!-- WhatsApp Progress Indicator -->
      <div v-if="whatsappProgress" class="p-4 mb-4 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-sm font-medium text-blue-800">حالة إرسال رسالة الواتساب</h3>
          <span class="text-xs text-blue-600">{{ whatsappProgress.status === 'completed' ? 'مكتمل' : whatsappProgress.status === 'queued' ? 'في الانتظار' : 'قيد المعالجة' }}</span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
          <div 
            class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
            :style="{ width: whatsappProgress.total > 0 ? (whatsappProgress.completed / whatsappProgress.total * 100) + '%' : '0%' }"
          ></div>
        </div>
        
        <div class="text-xs text-blue-700">
          <span v-if="whatsappProgress.status === 'queued'">جاري إعداد الرسالة...</span>
          <span v-else-if="whatsappProgress.status === 'processing'">
            جاري الإرسال... ({{ whatsappProgress.completed }} / {{ whatsappProgress.total }})
          </span>
          <span v-else-if="whatsappProgress.status === 'completed'">
            ✅ تم إرسال الرسالة بنجاح
          </span>
          <span v-else-if="whatsappProgress.status === 'failed'">
            ❌ فشل إرسال الرسالة
          </span>
        </div>
      </div>
    </div>
    <div v-if="errors && !profileAdded">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-red-300 rounded-lg dark:bg-red-300 text-center"
        role="alert"
      >
        <div
          v-for="(messages, field) in errors"
          :key="field"
          class="ml-3 text-sm font-medium text-red-700 dark:text-red-800"
        >
        {{ errors }}
        </div>
      </div>
    </div>
    <form name="createForm" @submit.prevent="submit">
      <div class="flex flex-row">
        <div class="grow">
          <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="mb-4">
                      <InputLabel for="sales_id" value="البطاقة" />
                      <select
                        v-model="form.card_id"
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="form.card_id">
                <div class="p-6 bg-white  dark:bg-gray-900">
                  <h2 class="text-center text-xl py-2">معلومات البطاقة</h2>
                  <div className="flex flex-col">
                  <div className="mb-4">
                      <InputLabel for="name" value="الصورة الشخصية" />
                      <img :src="form.image" />
                      <input
                        @change="handleImage"
                        type="file"
                        accept="image/*"
                        class="px-2 mt-3 py-1 font-bold text-white bg-rose-500 rounded"
                      />
           
                    </div>
                    <div className="mb-4">
                      <InputLabel for="card_number" value="رقم البطاقة" />

                      <TextInput
                        id="card_number"
                        type="number"
                        class="mt-1 block w-full"
                        autofocus
                        @input="handleInput(form.card_number,form.card_id)"
                        v-model="form.card_number"
                      />

                   
                      <span
                        v-if="userCard"
                      >
                      البطاقة تم تسجيلها قبل بأسم 
                      <span  className="text-red-600">
                        {{userCard.name}}
                      </span>
                        للمندوب
                      <span  className="text-red-600">
                        {{userCard.user?.name}}
                      </span>
                      أفراد العائلة
                      <span  className="text-red-600">
                        {{userCard.family_name}}
                      </span>
                      </span>
                      <div v-if="errors?.card_number">
                        البطاقةالبطاقة حقل مطلوب
                      </div>
                    </div>
                    <div className="mb-4">
                      <InputLabel for="name" value="الأسم" />

                      <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                      />
                      <div v-if="errors?.name">
                        البطاقةالبطاقة حقل مطلوب
                      </div>
         
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
                      <div v-if="errors?.saler_id">
                        المندوب حقل مطلوب
                      </div>
                    </div>
                    <div className="mb-4">
                      <InputLabel for="address" value="العنوان" />

                      <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                      />

           
                    </div>

                    <div className="mb-4">
                      <InputLabel for="family_name" value="أفراد العائلة" />

                      <TextInput
                        id="family_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.family_name"
                      />

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

                    <div className="mb-4">
                        <InputLabel for="phone_number" value="رقم الهاتف" />
                        <TextInput
                          id="phone_number"
                          type="text"
                          class="mt-1 block w-full"
                          v-model="form.phone_number"
                        />
                        <div v-if="errors?.phone_number">
                        رقم الهاتف حقل مطلوب
                        </div>
       
                      </div>
                  </div>
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
          @click.prevent="submit"
          @keyup.enter="submit" 
          :disabled="isLoading"
          class="px-6 mb-12 mx-2 py-2 font-bold text-white bg-rose-500 rounded"
        >
          <span v-if="!isLoading">حفظ</span>
          <span v-else>جاري الحفظ...</span>
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>